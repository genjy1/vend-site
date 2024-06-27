<?php
class ControllerCommonMail extends Controller {
    // fetch('https://vend-shop.com/index.php?route=common/mail&email=wewewe@mail.de').then(response => { window.console.log(response) });
    public function index() {

        $json = array();

        $data = array();

        // error_log("serialize post:" . serialize($this->request->post));

        if(isset($this->request->post['email'])){

            $this->load->model("extension/mail");
            $this->model_extension_mail->addMail($this->request->post['email']);
            $json['success'] = true;
        } elseif(isset($this->request->post['Email'])){
        	error_log("got: " . $this->request->post['Email']);
            $this->load->model("extension/mail");
            $this->model_extension_mail->addMail($this->request->post['Email']);
            $json['success'] = true;
        }
         else {
            $json['success'] = false;
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
