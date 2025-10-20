class ControllerExtensionModuleRecaptcha extends Controller {
    public function validateCaptcha() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $token = isset($data['token']) ? $data['token'] : '';

        $secret_key = RECAPTCHA_SECRET_KEY;

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_response = file_get_contents($recaptcha_url . '?secret=' . $secret_key . '&response=' . $token);
        $recaptcha_data = json_decode($recaptcha_response, true);

        // Отладка: проверьте содержимое ответа от reCAPTCHA
        if (empty($recaptcha_response)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['success' => false, 'error' => 'Empty response from reCAPTCHA']));
            return;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['success' => false, 'error' => 'Invalid JSON response from reCAPTCHA']));
            return;
        }

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