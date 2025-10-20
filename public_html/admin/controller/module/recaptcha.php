<?php
class ControllerModuleRecaptcha extends Controller {
    public function validateCaptcha() {
        // Получение токена из POST-запроса
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $token = isset($data['token']) ? $data['token'] : '';

        // Настройки reCAPTCHA
        $secret_key = RECAPTCHA_SECRET_KEY;

        // Отправка запроса для проверки токена
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_response = file_get_contents($recaptcha_url . '?secret=' . $secret_key . '&response=' . $token);
        $recaptcha_data = json_decode($recaptcha_response, true);

        // Проверка и сохранение результата в сессии
        if ($recaptcha_data['success']) {
            $this->session->data['captcha_valid'] = true;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['success' => true]));
        } else {
            $this->session->data['captcha_valid'] = false;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['success' => false]));
        }
    }
}
?>