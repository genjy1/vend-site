<?php
class Mail {
    protected $to;
    protected $from;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $attachments = array();
    public $protocol = 'mail';
    public $smtp_hostname;
    public $smtp_username;
    public $smtp_password;
    public $smtp_port = 25;
    public $smtp_timeout = 5;
    public $verp = false;
    public $parameter = '';

    private $log;
    private $logEnabled = true;
    private $logFile = 'mail.log';

    /**
     * Конструктор класса Mail
     *
     * @param array $config Конфигурация для инициализации
     */
    public function __construct($config = array()) {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        // Инициализация логирования
        $this->initializeLogging();
    }

    /**
     * Инициализация системы логирования
     */
    private function initializeLogging() {
        // Проверяем наличие глобального объекта логирования OpenCart
        if (defined('DIR_LOGS')) {
            $this->logFile = DIR_LOGS . 'mail.log';
        }

        $this->logInfo('Mail class initialized', [
            'protocol' => $this->protocol,
            'smtp_hostname' => $this->smtp_hostname ?? 'not set',
            'smtp_port' => $this->smtp_port
        ]);
    }

    public function setTo($to) {
        $this->to = $to;
        $toLog = is_array($to) ? implode(', ', $to) : $to;
        $this->logDebug('Recipient set', ['to' => $toLog]);
    }

    public function setFrom($from) {
        $this->from = $from;
        $this->logDebug('Sender address set', ['from' => $from]);
    }

    public function setSender($sender) {
        $this->sender = $sender;
        $this->logDebug('Sender name set', ['sender' => $sender]);
    }

    public function setReplyTo($reply_to) {
        $this->reply_to = $reply_to;
        $this->logDebug('Reply-to address set', ['reply_to' => $reply_to]);
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        $this->logDebug('Subject set', ['subject' => $subject]);
    }

    public function setText($text) {
        $this->text = $text;
        $this->logDebug('Text content set', ['length' => strlen($text)]);
    }

    public function setHtml($html) {
        $this->html = $html;
        $this->logDebug('HTML content set', ['length' => strlen($html)]);
    }

    public function addAttachment($filename) {
        if (file_exists($filename)) {
            $this->attachments[] = $filename;
            $this->logInfo('Attachment added', [
                'filename' => basename($filename),
                'size' => filesize($filename)
            ]);
        } else {
            $this->logWarning('Attachment file not found', ['filename' => $filename]);
        }
    }

    /**
     * Отправка email
     *
     * @throws Exception при ошибках валидации или отправки
     */
    public function send() {
        $startTime = microtime(true);

        try {
            // Валидация обязательных полей
            $this->validateRequiredFields();

            $to = is_array($this->to) ? implode(',', $this->to) : $this->to;

            $this->logInfo('Starting email send', [
                'protocol' => $this->protocol,
                'to' => $to,
                'from' => $this->from,
                'subject' => $this->subject,
                'has_html' => !empty($this->html),
                'has_text' => !empty($this->text),
                'attachments_count' => count($this->attachments)
            ]);

            // Подготовка сообщения
            $boundary = '----=_NextPart_' . md5(time());
            $header = $this->buildHeaders($to, $boundary);
            $message = $this->buildMessage($boundary);

            // Отправка в зависимости от протокола
            if ($this->protocol == 'mail') {
                $this->sendViaMail($to, $header, $message);
            } elseif ($this->protocol == 'smtp') {
                $this->sendViaSMTP($to, $header, $message);
            } else {
                throw new \Exception('Error: Unknown mail protocol: ' . $this->protocol);
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logInfo('Email sent successfully', [
                'to' => $to,
                'subject' => $this->subject,
                'duration_ms' => $duration,
                'protocol' => $this->protocol
            ]);

            return true;

        } catch (\Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Email send failed', [
                'error' => $e->getMessage(),
                'to' => $this->to ?? 'not set',
                'subject' => $this->subject ?? 'not set',
                'duration_ms' => $duration,
                'protocol' => $this->protocol
            ]);

            throw $e;
        }
    }

    /**
     * Валидация обязательных полей
     *
     * @throws Exception при отсутствии обязательных полей
     */
    private function validateRequiredFields() {
        if (!$this->to) {
            throw new \Exception('Error: E-Mail to required!');
        }

        if (!$this->from) {
            throw new \Exception('Error: E-Mail from required!');
        }

        if (!$this->sender) {
            throw new \Exception('Error: E-Mail sender required!');
        }

        if (!$this->subject) {
            throw new \Exception('Error: E-Mail subject required!');
        }

        if ((!$this->text) && (!$this->html)) {
            throw new \Exception('Error: E-Mail message required!');
        }

        $this->logDebug('Required fields validation passed');
    }

    /**
     * Построение заголовков email
     *
     * @param string $to Получатель
     * @param string $boundary Граница для multipart
     * @return string Заголовки
     */
    private function buildHeaders($to, $boundary) {
        $header = 'MIME-Version: 1.0' . PHP_EOL;

        if ($this->protocol != 'mail') {
            $header .= 'To: <' . $to . '>' . PHP_EOL;
            $header .= 'Subject: =?UTF-8?B?' . base64_encode($this->subject) . '?=' . PHP_EOL;
        }

        $header .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
        $header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;

        if (!$this->reply_to) {
            $header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
        } else {
            $header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . PHP_EOL;
        }

        $header .= 'Return-Path: ' . $this->from . PHP_EOL;
        $header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;
        $header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;

        $this->logDebug('Email headers built', ['boundary' => $boundary]);

        return $header;
    }

    /**
     * Построение тела сообщения
     *
     * @param string $boundary Граница для multipart
     * @return string Тело сообщения
     */
    private function buildMessage($boundary) {
        $message = '';

        if (!$this->html) {
            $message  = '--' . $boundary . PHP_EOL;
            $message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $message .= $this->text . PHP_EOL;
        } else {
            $message  = '--' . $boundary . PHP_EOL;
            $message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
            $message .= '--' . $boundary . '_alt' . PHP_EOL;
            $message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

            if ($this->text) {
                $message .= $this->text . PHP_EOL;
            } else {
                $message .= 'This is a HTML email and your email client software does not support HTML email!' . PHP_EOL;
            }

            $message .= '--' . $boundary . '_alt' . PHP_EOL;
            $message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $message .= $this->html . PHP_EOL;
            $message .= '--' . $boundary . '_alt--' . PHP_EOL;
        }

        // Добавление вложений
        $attachmentCount = 0;
        foreach ($this->attachments as $attachment) {
            if (file_exists($attachment)) {
                $handle = fopen($attachment, 'r');
                $content = fread($handle, filesize($attachment));
                fclose($handle);

                $message .= '--' . $boundary . PHP_EOL;
                $message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . PHP_EOL;
                $message .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
                $message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . PHP_EOL;
                $message .= 'Content-ID: <' . basename(urlencode($attachment)) . '>' . PHP_EOL;
                $message .= 'X-Attachment-Id: ' . basename(urlencode($attachment)) . PHP_EOL . PHP_EOL;
                $message .= chunk_split(base64_encode($content));

                $attachmentCount++;
                $this->logDebug('Attachment processed', [
                    'filename' => basename($attachment),
                    'size' => filesize($attachment)
                ]);
            } else {
                $this->logWarning('Attachment file not found during processing', [
                    'filename' => $attachment
                ]);
            }
        }

        $message .= '--' . $boundary . '--' . PHP_EOL;

        $this->logDebug('Message body built', [
            'total_size' => strlen($message),
            'attachments_added' => $attachmentCount
        ]);

        return $message;
    }

    /**
     * Отправка через PHP mail()
     *
     * @param string $to Получатель
     * @param string $header Заголовки
     * @param string $message Сообщение
     */
    private function sendViaMail($to, $header, $message) {
        $this->logInfo('Sending via PHP mail() function');

        ini_set('sendmail_from', $this->from);

        if ($this->parameter) {
            $result = mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
            $this->logDebug('mail() called with additional parameters', [
                'parameter' => $this->parameter
            ]);
        } else {
            $result = mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
            $this->logDebug('mail() called without additional parameters');
        }

        if (!$result) {
            $this->logError('PHP mail() function returned false');
            throw new \Exception('Error: PHP mail() function failed');
        }
    }

    /**
     * Отправка через SMTP
     *
     * @param string $to Получатель
     * @param string $header Заголовки
     * @param string $message Сообщение
     * @throws Exception при ошибках SMTP
     */
    private function sendViaSMTP($to, $header, $message) {
        $this->logInfo('Sending via SMTP', [
            'hostname' => $this->smtp_hostname,
            'port' => $this->smtp_port,
            'username' => $this->smtp_username ?? 'not set',
            'timeout' => $this->smtp_timeout
        ]);

        // Определение хоста
        if (substr($this->smtp_hostname, 0, 3) == 'tls') {
            $hostname = substr($this->smtp_hostname, 6);
            $useTLS = true;
        } else {
            $hostname = $this->smtp_hostname;
            $useTLS = false;
        }

        $this->logDebug('Connecting to SMTP server', [
            'hostname' => $hostname,
            'port' => $this->smtp_port,
            'tls' => $useTLS
        ]);

        $handle = fsockopen($hostname, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);

        if (!$handle) {
            $this->logError('Failed to connect to SMTP server', [
                'errno' => $errno,
                'errstr' => $errstr,
                'hostname' => $hostname,
                'port' => $this->smtp_port
            ]);
            throw new \Exception('Error: ' . $errstr . ' (' . $errno . ')');
        }

        $this->logInfo('Connected to SMTP server');

        if (substr(PHP_OS, 0, 3) != 'WIN') {
            socket_set_timeout($handle, $this->smtp_timeout, 0);
        }

        // Чтение приветствия сервера
        $reply = $this->readSMTPResponse($handle, 'Server greeting');

        // EHLO
        $this->sendSMTPCommand($handle, 'EHLO ' . getenv('SERVER_NAME'));
        $reply = $this->readSMTPResponse($handle, 'EHLO');

        if (substr($reply, 0, 3) != 250) {
            $this->logError('EHLO command rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: EHLO not accepted from server!');
        }

        // STARTTLS если требуется
        if ($useTLS) {
            $this->sendSMTPCommand($handle, 'STARTTLS');
            $reply = $this->readSMTPResponse($handle, 'STARTTLS');

            if (substr($reply, 0, 3) != 220) {
                $this->logError('STARTTLS command rejected', ['reply' => trim($reply)]);
                throw new \Exception('Error: STARTTLS not accepted from server!');
            }

            stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->logInfo('TLS encryption enabled');
        }

        // Аутентификация
        if (!empty($this->smtp_username) && !empty($this->smtp_password)) {
            $this->authenticateSMTP($handle);
        } else {
            $this->sendSMTPCommand($handle, 'HELO ' . getenv('SERVER_NAME'));
            $reply = $this->readSMTPResponse($handle, 'HELO');

            if (substr($reply, 0, 3) != 250) {
                $this->logError('HELO command rejected', ['reply' => trim($reply)]);
                throw new \Exception('Error: HELO not accepted from server!');
            }
        }

        // MAIL FROM
        if ($this->verp) {
            $this->sendSMTPCommand($handle, 'MAIL FROM: <' . $this->from . '>XVERP');
        } else {
            $this->sendSMTPCommand($handle, 'MAIL FROM: <' . $this->from . '>');
        }

        $reply = $this->readSMTPResponse($handle, 'MAIL FROM');

        if (substr($reply, 0, 3) != 250) {
            $this->logError('MAIL FROM command rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: MAIL FROM not accepted from server!');
        }

        // RCPT TO
        if (!is_array($this->to)) {
            $this->sendRCPTTO($handle, $this->to);
        } else {
            foreach ($this->to as $recipient) {
                $this->sendRCPTTO($handle, $recipient);
            }
        }

        // DATA
        $this->sendSMTPCommand($handle, 'DATA');
        $reply = $this->readSMTPResponse($handle, 'DATA');

        if (substr($reply, 0, 3) != 354) {
            $this->logError('DATA command rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: DATA not accepted from server!');
        }

        // Отправка сообщения
        $this->sendMessageData($handle, $header . $message);

        $reply = $this->readSMTPResponse($handle, 'Message data');

        if (substr($reply, 0, 3) != 250) {
            $this->logError('Message data rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: DATA not accepted from server!');
        }

        // QUIT
        $this->sendSMTPCommand($handle, 'QUIT');
        $reply = $this->readSMTPResponse($handle, 'QUIT');

        if (substr($reply, 0, 3) != 221) {
            $this->logWarning('QUIT command not acknowledged properly', ['reply' => trim($reply)]);
        }

        fclose($handle);
        $this->logInfo('SMTP connection closed');
    }

    /**
     * Аутентификация на SMTP сервере
     *
     * @param resource $handle Соединение с сервером
     * @throws Exception при ошибках аутентификации
     */
    private function authenticateSMTP($handle) {
        $this->logInfo('Starting SMTP authentication', [
            'username' => $this->smtp_username
        ]);

        $this->sendSMTPCommand($handle, 'EHLO ' . getenv('SERVER_NAME'));
        $reply = $this->readSMTPResponse($handle, 'EHLO (auth)');

        if (substr($reply, 0, 3) != 250) {
            $this->logError('EHLO command rejected during auth', ['reply' => trim($reply)]);
            throw new \Exception('Error: EHLO not accepted from server!');
        }

        $this->sendSMTPCommand($handle, 'AUTH LOGIN');
        $reply = $this->readSMTPResponse($handle, 'AUTH LOGIN');

        if (substr($reply, 0, 3) != 334) {
            $this->logError('AUTH LOGIN command rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: AUTH LOGIN not accepted from server!');
        }

        $this->sendSMTPCommand($handle, base64_encode($this->smtp_username));
        $reply = $this->readSMTPResponse($handle, 'Username');

        if (substr($reply, 0, 3) != 334) {
            $this->logError('Username rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: Username not accepted from server!');
        }

        $this->sendSMTPCommand($handle, base64_encode($this->smtp_password));
        $reply = $this->readSMTPResponse($handle, 'Password');

        if (substr($reply, 0, 3) != 235) {
            $this->logError('Password rejected', ['reply' => trim($reply)]);
            throw new \Exception('Error: Password not accepted from server!');
        }

        $this->logInfo('SMTP authentication successful');
    }

    /**
     * Отправка RCPT TO команды
     *
     * @param resource $handle Соединение с сервером
     * @param string $recipient Получатель
     * @throws Exception если получатель отклонен
     */
    private function sendRCPTTO($handle, $recipient) {
        $this->sendSMTPCommand($handle, 'RCPT TO: <' . $recipient . '>');
        $reply = $this->readSMTPResponse($handle, 'RCPT TO');

        if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
            $this->logError('RCPT TO command rejected', [
                'recipient' => $recipient,
                'reply' => trim($reply)
            ]);
            throw new \Exception('Error: RCPT TO not accepted from server!');
        }

        $this->logDebug('Recipient accepted', ['recipient' => $recipient]);
    }

    /**
     * Отправка данных сообщения
     *
     * @param resource $handle Соединение с сервером
     * @param string $message Сообщение
     */
    private function sendMessageData($handle, $message) {
        // According to rfc 821 we should not send more than 1000 including the CRLF
        $message = str_replace("\r\n", "\n", $message);
        $message = str_replace("\r", "\n", $message);

        $lines = explode("\n", $message);
        $lineCount = count($lines);

        $this->logDebug('Sending message data', [
            'total_lines' => $lineCount,
            'total_bytes' => strlen($message)
        ]);

        foreach ($lines as $line) {
            $results = str_split($line, 998);

            foreach ($results as $result) {
                if (substr(PHP_OS, 0, 3) != 'WIN') {
                    fputs($handle, $result . "\r\n");
                } else {
                    fputs($handle, str_replace("\n", "\r\n", $result) . "\r\n");
                }
            }
        }

        fputs($handle, '.' . "\r\n");
        $this->logDebug('Message data transmission completed');
    }

    /**
     * Отправка SMTP команды
     *
     * @param resource $handle Соединение с сервером
     * @param string $command Команда
     */
    private function sendSMTPCommand($handle, $command) {
        // Скрываем пароль в логах
        $logCommand = $command;
        if (strpos($command, base64_encode($this->smtp_password)) !== false) {
            $logCommand = '[PASSWORD HIDDEN]';
        }

        $this->logDebug('SMTP Command sent', ['command' => $logCommand]);
        fputs($handle, $command . "\r\n");
    }

    /**
     * Чтение ответа от SMTP сервера
     *
     * @param resource $handle Соединение с сервером
     * @param string $context Контекст для логирования
     * @return string Ответ сервера
     */
    private function readSMTPResponse($handle, $context = '') {
        $reply = '';

        while ($line = fgets($handle, 515)) {
            $reply .= $line;

            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }

        $this->logDebug('SMTP Response received', [
            'context' => $context,
            'response' => trim($reply)
        ]);

        return $reply;
    }

    /**
     * Логирование debug сообщений
     *
     * @param string $message Сообщение
     * @param array $context Контекст
     */
    private function logDebug($message, $context = []) {
        if (!$this->logEnabled) return;
        $this->writeLog('DEBUG', $message, $context);
    }

    /**
     * Логирование информационных сообщений
     *
     * @param string $message Сообщение
     * @param array $context Контекст
     */
    private function logInfo($message, $context = []) {
        if (!$this->logEnabled) return;
        $this->writeLog('INFO', $message, $context);
    }

    /**
     * Логирование предупреждений
     *
     * @param string $message Сообщение
     * @param array $context Контекст
     */
    private function logWarning($message, $context = []) {
        if (!$this->logEnabled) return;
        $this->writeLog('WARNING', $message, $context);
    }

	/**
     * Логирование ошибок
     *
     * @param string $message Сообщение
     * @param array $context Контекст
     */

    private function logError($message, $context = []) {
        if (!$this->logEnabled) return;
        $this->writeLog('ERROR', $message, $context);
    }

    /**
     * Запись в лог файл
     *
     * @param string $level Уровень логирования
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     */
    private function writeLog($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $microtime = sprintf('%06d', (microtime(true) - floor(microtime(true))) * 1000000);

        $logEntry = sprintf(
            "[%s.%s] [%s] %s",
            $timestamp,
            $microtime,
            $level,
            $message
        );

        if (!empty($context)) {
            // Фильтруем чувствительные данные
            $safeContext = $this->filterSensitiveData($context);
            $logEntry .= ' | ' . json_encode($safeContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $logEntry .= PHP_EOL;

        // Запись в файл с блокировкой
        @file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Фильтрация чувствительных данных из контекста
     *
     * @param array $context Контекст
     * @return array Отфильтрованный контекст
     */
    private function filterSensitiveData($context) {
        $sensitiveKeys = ['password', 'smtp_password', 'pass', 'secret'];

        foreach ($context as $key => $value) {
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (stripos($key, $sensitiveKey) !== false) {
                    $context[$key] = '[FILTERED]';
                }
            }

            // Рекурсивная фильтрация для вложенных массивов
            if (is_array($value)) {
                $context[$key] = $this->filterSensitiveData($value);
            }
        }

        return $context;
    }

    /**
     * Включение/выключение логирования
     *
     * @param bool $enabled
     */
    public function setLogging($enabled) {
        $this->logEnabled = (bool)$enabled;
    }

    /**
     * Установка пути к лог файлу
     *
     * @param string $logFile Путь к файлу
     */
    public function setLogFile($logFile) {
        $this->logFile = $logFile;
    }
}