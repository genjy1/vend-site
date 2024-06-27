<?php
class ControllerCommonFeedback extends Controller {
	public function index() {

		$template = "";

		$json = array();

		$data = array();
    
		//file_put_contents("/var/www/vend-shop.com/public_html/debug", print_r($json, false), FILE_APPEND);

		if(isset($this->request->post['data'])){
			$data = html_entity_decode($this->request->post['data']);
			$data = json_decode($data, true);
			$json['data'] = $data;
		} else {
			$json['error'] = 1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			exit;
		}

		$data['date'] = date('d-m-Y');

		$f = fopen($_SERVER['DOCUMENT_ROOT'].'/mailslog','a');
		fwrite($f, print_r($data, TRUE));
		fclose($f);	

		//$ddd = file_put_contents($_SERVER['DOCUMENT_ROOT']."/debug", $this->config->get('config_mail_smtp_username'), FILE_APPEND);

    	file_put_contents($_SERVER['DOCUMENT_ROOT']."/mails", $data, FILE_APPEND);

			//print_r($_SERVER['DOCUMENT_ROOT']);
			

// Проверяем была ли отправлена форма
if (!empty($data['recaptcha_response'])) {
 	
     // Создаем POST запрос
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lcn7DgpAAAAAD_JnNF74xcvgnxfBDC7aF_7-yhL';
    $recaptcha_response = $data['recaptcha_response'];
 
    // Отправляем POST запрос и декодируем результаты ответа
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);
 
 	file_put_contents($_SERVER['DOCUMENT_ROOT']."/debugm", print_r($recaptcha, true), FILE_APPEND);
 
    // Принимаем меры в зависимости от полученного результата
    if ($recaptcha->score >= 0.5) { 
    	//print_r(111);
        // Проверка пройдена - отправляем сообщение.*/

		$template = $data['template'];

		if(isset($data["subject"]) && $data["subject"] != "" && $data["subject"] !="undefined"){
			$subject = $data["subject"];
		} else {
			$subject = "Новое уведомление";
		}

    if(isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL))
    {
      $this->load->model("extension/mail");
      $this->model_extension_mail->addMail($data['email']);
    }
    
    	$maildata = $this->load->view('common/feedback/'.$template, $data) . "\n\n\n";
    	
    	
    	
    	
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_mail_smtp_username'));
		$mail->setSender(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($this->load->view('common/feedback/'.$template, $data));
		$mail->setText("");
		$mail->send();

		$json['success'] = true;

		if ($template == "callback") {
			$json['callback'] = true;
		}

		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

} else {
        // Проверка не пройдена. Показываем ошибку.
        //print_r(222);
        $json['error'] = 1;
        
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/debugm", print_r($json, true), FILE_APPEND);
        
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			exit;
    }
 
}


	}

}
