<?php
class ControllerPushPush extends Controller {
  public function index(){
    $this->load->model('extension/push');

    $data['config'] = html_entity_decode($this->config->get("notification_config"));

    return $this->load->view('push/push', $data);
  }

  public function save() 
  {
    $json = array();

    $this->load->model('extension/push');

    if (isset($this->request->get['token'])) {
      $token = $this->request->get['token'];

      $this->model_extension_push->addToken($token);

      $json['success'] = true;
    }


    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}
