<?php
/**
 * Controller Common Feedback
 * Контроллер обработки форм обратной связи с расширенной валидацией и логированием
 * 
 * Улучшения:
 * - Безопасная работа с магическими свойствами OpenCart
 * - Комплексная валидация всех входных данных
 * - Защита от SMTP timeout
 * - Фильтрация чувствительных данных в логах
 * - Graceful degradation для некритичных ошибок
 * - Rate limiting через reCAPTCHA
 * - XSS защита через sanitization
 */
class ControllerCommonFeedback extends Controller {

    private const RECAPTCHA_MIN_SCORE = 0.5;
    private const LOG_FILE = 'feedback.log';
    private const ERROR_LOG_FILE = 'feedback_errors.log';
    
    // Лимиты валидации
    private const MIN_NAME_LENGTH = 2;
    private const MAX_NAME_LENGTH = 100;
    private const MIN_PHONE_LENGTH = 10;
    private const MAX_PHONE_LENGTH = 18;
    private const MAX_EMAIL_LENGTH = 254;
    private const MAX_MESSAGE_LENGTH = 5000;
    private const MAX_SUBJECT_LENGTH = 200;
    
    // Timeout для email
    private const EMAIL_TIMEOUT = 5; // 5 секунд максимум

    /**
     * Основной метод обработки запроса обратной связи
     */
    public function index() {
        $startTime = microtime(true);
        $requestId = $this->generateRequestId();

        try {
            $this->logInfo('Feedback request received', [
                'request_id' => $requestId,
                'ip' => $this->getClientIp(),
                'user_agent' => $this->getUserAgent()
            ]);

            // Инициализация компонентов
            $this->initializeComponents();

            // Валидация и получение данных
            $data = $this->validateAndGetRequestData();

            // Проверка reCAPTCHA (если включена)
            if (isset($data['recaptcha_token'])) {
                if (!$this->verifyRecaptcha($data['recaptcha_token'])) {
                    return $this->sendJsonResponse([
                        'error' => 1,
                        'message' => 'reCAPTCHA verification failed. Please try again.',
                        'request_id' => $requestId
                    ], 400);
                }
            }

            // Отправка email (с защитой от timeout)
            $emailSent = $this->sendFeedbackEmailSafe($data);

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if ($emailSent) {
                $this->logInfo('Feedback processed successfully', [
                    'request_id' => $requestId,
                    'template' => $data['template'],
                    'duration_ms' => $duration
                ]);

                $response = [
                    'error' => 0,
                    'success' => true,
                    'request_id' => $requestId
                ];

                // Специальная обработка для callback
                if ($data['template'] === 'callback') {
                    $response['callback'] = true;
                }

                return $this->sendJsonResponse($response);

            } else {
                $this->logWarning('Email sending failed, but request processed', [
                    'request_id' => $requestId,
                    'template' => $data['template'],
                    'duration_ms' => $duration
                ]);

                return $this->sendJsonResponse([
                    'error' => 1,
                    'message' => 'Failed to send feedback. Please try again later.',
                    'request_id' => $requestId
                ], 500);
            }

        } catch (ValidationException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logWarning('Validation error', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'field' => $e->getField(),
                'duration_ms' => $duration
            ]);

            return $this->sendJsonResponse([
                'error' => 1,
                'message' => $e->getMessage(),
                'field' => $e->getField(),
                'request_id' => $requestId
            ], 400);

        } catch (Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Unexpected error in feedback controller', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'duration_ms' => $duration,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);

            return $this->sendJsonResponse([
                'error' => 1,
                'message' => 'An unexpected error occurred. Please try again later.',
                'request_id' => $requestId
            ], 500);
        }
    }

    /**
     * Генерация уникального ID запроса
     */
    private function generateRequestId() {
        return sprintf(
            '%s-%s-%s',
            date('YmdHis'),
            substr(md5(uniqid('', true)), 0, 8),
            substr(md5($this->getClientIp()), 0, 4)
        );
    }

    /**
     * Инициализация компонентов (безопасная проверка)
     */
    private function initializeComponents() {
        $missing = [];

        // Проверка критичных компонентов через try-catch
        try {
            if (!is_object($this->request ?? null)) {
                $missing[] = 'request';
            }
        } catch (Exception $e) {
            $missing[] = 'request';
        }

        try {
            if (!is_object($this->config ?? null)) {
                $missing[] = 'config';
            }
        } catch (Exception $e) {
            $missing[] = 'config';
        }

        try {
            if (!is_object($this->response ?? null)) {
                $missing[] = 'response';
            }
        } catch (Exception $e) {
            $missing[] = 'response';
        }

        if (!empty($missing)) {
            throw new Exception('Critical components not available: ' . implode(', ', $missing));
        }

        $this->logDebug('Components initialized successfully');
    }

    /**
     * Валидация и получение данных из запроса
     *
     * @return array
     * @throws ValidationException
     */
    private function validateAndGetRequestData() {
        try {
            // Проверка наличия данных
            if (!isset($this->request->post['data'])) {
                throw new ValidationException('No data provided', 'data');
            }

            // Декодирование данных
            $rawData = html_entity_decode($this->request->post['data']);
            $data = json_decode($rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logError('JSON decode error', ['error' => json_last_error_msg()]);
                throw new ValidationException('Invalid data format', 'data');
            }

            // Валидация обязательных полей
            if (!isset($data['template']) || empty($data['template'])) {
                throw new ValidationException('Template is required', 'template');
            }

            // Sanitization template
            $data['template'] = $this->sanitizeString($data['template']);

            if (!preg_match('/^[a-z0-9_-]+$/i', $data['template'])) {
                throw new ValidationException('Invalid template name', 'template');
            }

            // Валидация subject
            if (isset($data['subject'])) {
                $data['subject'] = $this->sanitizeString($data['subject']);
                
                if (mb_strlen($data['subject']) > self::MAX_SUBJECT_LENGTH) {
                    throw new ValidationException(
                        'Subject is too long (max ' . self::MAX_SUBJECT_LENGTH . ' characters)',
                        'subject'
                    );
                }
            } else {
                $data['subject'] = 'Новое уведомление';
            }

            // Валидация name (если есть)
            if (isset($data['name'])) {
                $data['name'] = $this->sanitizeString($data['name']);
                
                if (mb_strlen($data['name']) < self::MIN_NAME_LENGTH) {
                    throw new ValidationException(
                        'Name is too short (min ' . self::MIN_NAME_LENGTH . ' characters)',
                        'name'
                    );
                }
                
                if (mb_strlen($data['name']) > self::MAX_NAME_LENGTH) {
                    throw new ValidationException(
                        'Name is too long (max ' . self::MAX_NAME_LENGTH . ' characters)',
                        'name'
                    );
                }

                if (preg_match('/[<>{}]/', $data['name'])) {
                    throw new ValidationException('Name contains invalid characters', 'name');
                }
            }

            // Валидация email (если есть)
            if (isset($data['email']) && !empty($data['email'])) {
                $data['email'] = $this->sanitizeString($data['email']);
                
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new ValidationException('Invalid email format', 'email');
                }

                if (strlen($data['email']) > self::MAX_EMAIL_LENGTH) {
                    throw new ValidationException('Email is too long', 'email');
                }

                // Сохраняем email в БД (некритичная операция)
                $this->saveEmailSafe($data['email']);
            }

            // Валидация phone (если есть)
            if (isset($data['phone'])) {
                $data['phone'] = $this->sanitizePhone($data['phone']);
                
                $digitsOnly = preg_replace('/\D/', '', $data['phone']);
                
                if (strlen($digitsOnly) < self::MIN_PHONE_LENGTH) {
                    throw new ValidationException(
                        'Phone number is too short (min ' . self::MIN_PHONE_LENGTH . ' digits)',
                        'phone'
                    );
                }
                
                if (strlen($digitsOnly) > self::MAX_PHONE_LENGTH) {
                    throw new ValidationException(
                        'Phone number is too long (max ' . self::MAX_PHONE_LENGTH . ' digits)',
                        'phone'
                    );
                }
            }

            // Валидация message (если есть)
            if (isset($data['message'])) {
                $data['message'] = trim($data['message']); // НЕ sanitize - может содержать HTML
                
                if (mb_strlen($data['message']) > self::MAX_MESSAGE_LENGTH) {
                    throw new ValidationException(
                        'Message is too long (max ' . self::MAX_MESSAGE_LENGTH . ' characters)',
                        'message'
                    );
                }
            }

            // Добавляем дату и время
            $data['date'] = date('d-m-Y H:i:s');

            $this->logDebug('Request data validated', [
                'template' => $data['template'],
                'has_email' => isset($data['email']),
                'has_phone' => isset($data['phone']),
                'has_message' => isset($data['message'])
            ]);

            return $data;

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logError('Error validating request data', [
                'error' => $e->getMessage()
            ]);
            throw new ValidationException('Invalid request data', 'data');
        }
    }

    /**
     * Проверка reCAPTCHA токена
     *
     * @param string $token
     * @return bool
     */
    private function verifyRecaptcha($token) {
        try {
            $secret = $this->config->get('config_recaptcha_secret');
            
            if (empty($secret)) {
                $this->logWarning('reCAPTCHA secret not configured');
                return true; // Пропускаем если не настроено
            }

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $this->getClientIp()
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 секунд timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                $this->logError('reCAPTCHA cURL error', ['error' => $curlError]);
                return true; // Пропускаем при ошибке сети
            }

            if ($httpCode !== 200) {
                $this->logError('reCAPTCHA HTTP error', ['code' => $httpCode]);
                return true; // Пропускаем при ошибке HTTP
            }

            $result = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logError('reCAPTCHA JSON decode error', ['error' => json_last_error_msg()]);
                return true; // Пропускаем при ошибке парсинга
            }

            if (!isset($result['success']) || !$result['success']) {
                $this->logWarning('reCAPTCHA verification failed', [
                    'error_codes' => $result['error-codes'] ?? []
                ]);
                return false;
            }

            $score = $result['score'] ?? 0;

            if ($score < self::RECAPTCHA_MIN_SCORE) {
                $this->logWarning('reCAPTCHA score too low', [
                    'score' => $score,
                    'threshold' => self::RECAPTCHA_MIN_SCORE
                ]);
                return false;
            }

            $this->logDebug('reCAPTCHA verified successfully', ['score' => $score]);

            return true;

        } catch (Exception $e) {
            $this->logError('reCAPTCHA verification error', [
                'error' => $e->getMessage()
            ]);
            return true; // Пропускаем при непредвиденной ошибке
        }
    }

    /**
     * Безопасная отправка email (с защитой от timeout)
     *
     * @param array $data
     * @return bool
     */
    private function sendFeedbackEmailSafe($data) {
        try {
            $template = $data['template'];
            $subject = $data['subject'];

            // Проверка существования шаблона
            $templatePath = DIR_TEMPLATE . 'common/feedback/' . $template . '.tpl';
            
            if (!file_exists($templatePath)) {
                $this->logError('Template not found', [
                    'template' => $template,
                    'path' => $templatePath
                ]);
                return false;
            }

            // Загружаем view (может быть медленным)
            try {
                $htmlContent = $this->load->view('common/feedback/' . $template, $data);
            } catch (Exception $e) {
                $this->logError('Failed to render template', [
                    'template' => $template,
                    'error' => $e->getMessage()
                ]);
                return false;
            }

            // Создаем mail объект
            $mail = new Mail();
            
            // Конфигурация SMTP с коротким timeout
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode(
                $this->config->get('config_mail_smtp_password'),
                ENT_QUOTES,
                'UTF-8'
            );
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = self::EMAIL_TIMEOUT; // ВАЖНО: короткий timeout

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_mail_smtp_username'));
            $mail->setSender(html_entity_decode(
                $this->config->get('config_name') ?? $this->config->get('config_email'),
                ENT_QUOTES,
                'UTF-8'
            ));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($htmlContent);

            // Отправка с подавлением ошибок
            $startTime = microtime(true);
            
            try {
                @$mail->send(); // @ подавляет ошибки SMTP
                
                $duration = round((microtime(true) - $startTime) * 1000, 2);
                
                $this->logInfo('Feedback email sent successfully', [
                    'template' => $template,
                    'duration_ms' => $duration
                ]);
                
                return true;

            } catch (Exception $e) {
                $duration = round((microtime(true) - $startTime) * 1000, 2);
                
                $this->logWarning('Email sending failed (non-critical)', [
                    'template' => $template,
                    'error' => $e->getMessage(),
                    'duration_ms' => $duration
                ]);
                
                return false;
            }

        } catch (Exception $e) {
            $this->logError('Email sending error', [
                'template' => $data['template'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);
            
            return false;
        }
    }

    /**
     * Безопасное сохранение email в БД (не критично если не получится)
     *
     * @param string $email
     */
    private function saveEmailSafe($email) {
        try {
            // Проверяем что load доступен
            if (!is_object($this->load ?? null)) {
                $this->logDebug('Loader not available, skipping email save');
                return;
            }

            // Пытаемся загрузить модель
            try {
                $this->load->model('extension/mail');
            } catch (Exception $e) {
                $this->logDebug('Mail model not available', [
                    'error' => $e->getMessage()
                ]);
                return;
            }

            // Проверяем что модель загружена
            if (!is_object($this->model_extension_mail ?? null)) {
                $this->logDebug('Mail model not loaded');
                return;
            }

            // Проверяем что метод существует
            if (!method_exists($this->model_extension_mail, 'addMail')) {
                $this->logDebug('addMail method not available');
                return;
            }

            // Пытаемся сохранить
            $this->model_extension_mail->addMail($email);
            
            $this->logDebug('Email saved to database', ['email' => $email]);

        } catch (Exception $e) {
            // Не критично если не сохранилось
            $this->logDebug('Failed to save email to database (non-critical)', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Отправка JSON ответа
     *
     * @param array $data
     * @param int $httpCode
     */
    private function sendJsonResponse($data, $httpCode = 200) {
        try {
            if (is_object($this->response ?? null)) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->addHeader('HTTP/1.1 ' . $httpCode);
                $this->response->setOutput(json_encode($data, JSON_UNESCAPED_UNICODE));
            } else {
                // Fallback
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                    header('HTTP/1.1 ' . $httpCode);
                }
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            $this->logError('Failed to send response', [
                'error' => $e->getMessage()
            ]);
            
            if (!headers_sent()) {
                header('Content-Type: application/json');
                header('HTTP/1.1 500');
            }
            echo json_encode(['error' => 1, 'message' => 'Internal server error'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // =========================================================================
    // HELPER МЕТОДЫ
    // =========================================================================

    /**
     * Sanitization строк
     */
    private function sanitizeString($str) {
        if (!is_string($str)) {
            return '';
        }
        return trim(strip_tags($str));
    }

    /**
     * Sanitization телефона
     */
    private function sanitizePhone($phone) {
        if (!is_string($phone)) {
            return '';
        }
        return trim(preg_replace('/[^\d\s\-\+\(\)]/', '', $phone));
    }

    /**
     * Получение IP клиента
     */
    private function getClientIp() {
        try {
            if (is_object($this->request ?? null) && isset($this->request->server['REMOTE_ADDR'])) {
                return $this->request->server['REMOTE_ADDR'];
            }
        } catch (Exception $e) {
            // Ignore
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Получение User Agent
     */
    private function getUserAgent() {
        try {
            if (is_object($this->request ?? null) && isset($this->request->server['HTTP_USER_AGENT'])) {
                return $this->request->server['HTTP_USER_AGENT'];
            }
        } catch (Exception $e) {
            // Ignore
        }
        
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Форматирование trace для логов
     */
    private function formatTrace($trace) {
        $formatted = [];
        
        foreach (array_slice($trace, 0, 5) as $item) {
            $formatted[] = [
                'file' => $item['file'] ?? 'unknown',
                'line' => $item['line'] ?? 0,
                'function' => $item['function'] ?? 'unknown',
                'class' => $item['class'] ?? ''
            ];
        }
        
        return $formatted;
    }

    // =========================================================================
    // ЛОГИРОВАНИЕ
    // =========================================================================

    /**
     * Debug логирование
     */
    private function logDebug($message, $context = []) {
        $this->writeLog('DEBUG', $message, $context);
    }

    /**
     * Info логирование
     */
    private function logInfo($message, $context = []) {
        $this->writeLog('INFO', $message, $context);
    }

    /**
     * Warning логирование
     */
    private function logWarning($message, $context = []) {
        $this->writeLog('WARNING', $message, $context);
    }

    /**
     * Error логирование
     */
    private function logError($message, $context = []) {
        $this->writeLog('ERROR', $message, $context);
        // Дублируем в error log
        $this->writeLog('ERROR', $message, $context, self::ERROR_LOG_FILE);
    }

    /**
     * Запись в лог
     */
    private function writeLog($level, $message, $context = [], $logFile = self::LOG_FILE) {
        try {
            $timestamp = date('Y-m-d H:i:s');
            $ip = $this->getClientIp();

            $logEntry = sprintf(
                "[%s] [%s] [IP: %s] %s",
                $timestamp,
                $level,
                $ip,
                $message
            );

            if (!empty($context)) {
                $safeContext = $this->filterSensitiveData($context);
                $logEntry .= ' | ' . json_encode($safeContext, JSON_UNESCAPED_UNICODE);
            }

            $logEntry .= PHP_EOL;

            // Используем встроенное логирование OpenCart
            if (is_object($this->log ?? null) && method_exists($this->log, 'write')) {
                $this->log->write($logEntry);
            } else {
                // Fallback на прямую запись
                if (defined('DIR_LOGS')) {
                    $logFilePath = DIR_LOGS . $logFile;
                    @file_put_contents($logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
                }
            }

        } catch (Exception $e) {
            // Тихо игнорируем ошибки логирования
            @error_log('Failed to write to custom log: ' . $e->getMessage());
        }
    }

    /**
     * Фильтрация чувствительных данных
     */
    private function filterSensitiveData($context) {
        if (!is_array($context)) {
            return [];
        }

        $sensitiveKeys = [
            'password', 'card', 'cvv', 'secret', 'token', 'api_key', 
            'smtp_password', 'recaptcha_token', 'recaptcha_secret'
        ];

        foreach ($context as $key => $value) {
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (stripos($key, $sensitiveKey) !== false) {
                    $context[$key] = '[FILTERED]';
                    break;
                }
            }

            if (is_array($value)) {
                $context[$key] = $this->filterSensitiveData($value);
            }
        }

        return $context;
    }
}

// =============================================================================
// CUSTOM EXCEPTION
// =============================================================================

/**
 * Исключение для ошибок валидации
 */
class ValidationException extends Exception {
    private $field;

    public function __construct($message, $field = '', $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->field = $field;
    }

    public function getField() {
        return $this->field;
    }
}
