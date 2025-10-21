<?php
class ControllerCommonFeedback extends Controller {
    public function index() {
        $json = [];
        $ok = false;

        // Читаем данные из php://input с ограничением размера
        $input = file_get_contents("php://input");
        if (strlen($input) > 1_000_000) { // 1MB лимит
            $json['error'] = 'Payload too large';
            return $this->sendResponse($json);
        }

        if (!empty($input)) {
            $data = json_decode($input, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $ok = true;
            } else {
                $json['error'] = 'Invalid JSON format';
                return $this->sendResponse($json);
            }
        } else {
            $json['error'] = 'Empty request';
            return $this->sendResponse($json);
        }

        if ($ok) {
            // Добавляем дату
            $data['date'] = date('d-m-Y');

            // Логируем в system/storage/logs
            $this->log->write('Feedback: ' . print_r($data, true));

            // Безопасный список шаблонов
            $allowed = ['callback', 'feedback', 'support', 'request'];
            if (!isset($data['template']) || !in_array($data['template'], $allowed)) {
                $json['error'] = 'Invalid template';
                return $this->sendResponse($json);
            }
            $template = $data['template'];

            // Тема письма
            $subject = (!empty($data['subject'])) ? $data['subject'] : 'Новое уведомление';

            // Сохраняем email, если валиден
            if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                if (file_exists(DIR_APPLICATION . 'model/extension/mail.php')) {
                    $this->load->model("extension/mail");
                    $this->model_extension_mail->addMail($data['email']);
                }
            }

            // Формируем письмо
            $messageHtml = $this->load->view('common/feedback/' . $template, $data);

            try {
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($this->config->get('config_email'));
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml($messageHtml);
                $mail->send();

                $json['success'] = true;
            } catch (Exception $e) {
                $this->log->write('Mail send error: ' . $e->getMessage());
                $json['error'] = 'Mail send failed';
            }

            if ($template === 'callback') {
                $json['callback'] = true;
            }

            return $this->sendResponse($json);
        }
    }

    /**
     * Хелпер для JSON-ответов
     */
    private function sendResponse(array $json) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return;
    }
}
