<?php
class ControllerCommonFeedback extends Controller {

    private const RECAPTCHA_MIN_SCORE = 0.5;
    private const LOG_FILE = 'feedback.log';

    public function index() {
        try {
            // Инициализация ответа
            $json = ['error' => 0, 'success' => false];

            // Валидация и получение данных
            $data = $this->getRequestData();
            if (!$data) {
                $this->logError('Invalid request data');
                $json['error'] = 1;
                $json['message'] = 'Invalid request data';
                return $this->sendJsonResponse($json);
            }

            $this->logInfo('Feedback request received', [
                'template' => $data['template'] ?? 'unknown',
                'email' => $data['email'] ?? 'not provided'
            ]);


            // Отправка email
            $emailSent = $this->sendFeedbackEmail($data);

            if ($emailSent) {
                $this->logInfo('Feedback email sent successfully', [
                    'template' => $data['template'] ?? 'unknown'
                ]);

                $json['success'] = true;

                // Специальная обработка для callback
                if (isset($data['template']) && $data['template'] === 'callback') {
                    $json['callback'] = true;
                }
            } else {
                $this->logError('Failed to send feedback email');
                $json['error'] = 1;
                $json['message'] = 'Failed to send email';
            }

            return $this->sendJsonResponse($json);

        } catch (Exception $e) {
            $this->logError('Unexpected error in feedback controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendJsonResponse([
                'error' => 1,
                'message' => 'An unexpected error occurred'
            ]);
        }
    }

    /**
     * Получение и валидация данных из запроса
     */
    private function getRequestData() {
        if (!isset($this->request->post['data'])) {
            return false;
        }

        $rawData = html_entity_decode($this->request->post['data']);
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logError('JSON decode error', ['error' => json_last_error_msg()]);
            return false;
        }

        // Добавляем дату
        $data['date'] = date('d-m-Y H:i:s');

        // Валидация обязательных полей
        if (!isset($data['template']) || empty($data['template'])) {
            $this->logError('Missing required field: template');
            return false;
        }

        // Валидация email если присутствует
        if (isset($data['email']) && !empty($data['email'])) {
            if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->saveEmail($data['email']);
            } else {
                $this->logWarning('Invalid email format', ['email' => $data['email']]);
            }
        }

        return $data;
    }

    /**
     * Отправка email с обратной связью
     */
    private function sendFeedbackEmail($data) {
        try {
            $template = $data['template'];
            $subject = $data['subject'] ?? 'Новое уведомление';

            // Проверка существования шаблона
            $templatePath = DIR_TEMPLATE . 'common/feedback/' . $template . '.tpl';
            if (!file_exists($templatePath)) {
                $this->logError('Template not found', ['template' => $template, 'path' => $templatePath]);
                return false;
            }

            $mail = new Mail();
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
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_mail_smtp_username'));
            $mail->setSender(html_entity_decode(
                $this->config->get('config_email'),
                ENT_QUOTES,
                'UTF-8'
            ));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($this->load->view('common/feedback/' . $template, $data));
            $mail->setText('');

            $mail->send();

            return true;

        } catch (Exception $e) {
            $this->logError('Email sending failed', [
                'error' => $e->getMessage(),
                'template' => $template ?? 'unknown'
            ]);
            return false;
        }
    }

    /**
     * Сохранение email в базу данных
     */
    private function saveEmail($email) {
        try {
            $this->load->model('extension/mail');
            $this->model_extension_mail->addMail($email);
            $this->logInfo('Email saved to database', ['email' => $email]);
        } catch (Exception $e) {
            $this->logError('Failed to save email to database', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Отправка JSON ответа
     */
    private function sendJsonResponse($data) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    /**
     * Логирование информационных сообщений
     */
    private function logInfo($message, $context = []) {
        $this->writeLog('INFO', $message, $context);
    }

    /**
     * Логирование предупреждений
     */
    private function logWarning($message, $context = []) {
        $this->writeLog('WARNING', $message, $context);
    }

    /**
     * Логирование ошибок
     */
    private function logError($message, $context = []) {
        $this->writeLog('ERROR', $message, $context);
    }

    /**
     * Запись в лог файл
     */
    private function writeLog($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $this->request->server['REMOTE_ADDR'] ?? 'unknown';

        $logEntry = sprintf(
            "[%s] [%s] [IP: %s] %s",
            $timestamp,
            $level,
            $ip,
            $message
        );

        if (!empty($context)) {
            $logEntry .= ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $logEntry .= PHP_EOL;

        // Используем встроенный механизм логирования OpenCart если доступен
        if (method_exists($this, 'log')) {
            $this->log->write($logEntry);
        } else {
            // Fallback на запись в файл
            $logFile = DIR_LOGS . self::LOG_FILE;
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        }
    }
}