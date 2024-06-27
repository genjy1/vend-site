<?php
class ControllerExtensionOnlinevendshop extends Controller {
    private $error = array();

    public function index() {
        $this->document->setTitle("Online vendhsop ADS");

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }


        $this->load->model('extension/onlinevendshop');

        $data['action'] = $this->url->link('extension/onlinevendshop', 'token=' . $this->session->data['token'], 'SSL');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_extension_onlinevendshop->edit($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

        }

        $data['image'] = $this->model_extension_onlinevendshop->getImage();
        $data['link'] = $this->model_extension_onlinevendshop->getLink();

        $this->load->model('tool/image');

        if ($data['image'] && is_file(DIR_IMAGE . $data['image'])) {
          $data['thumb'] = $this->model_tool_image->resize($data['image'], 100, 100);
        } else {
          $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
          $data['image'] = 'no_image.png';
        }

        $data['error'] = $this->error;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/onlinevendshop', $data));

    }


    public function delete() {
        $this->load->language('extension/youtube');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/post');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_extension_youtube->deletePost($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
    }

    private function validateForm(){
      if(!file_exists(DIR_IMAGE . $this->request->post['image'])){
        $this->error[] = "File does not exists";
        return false;
      }

      $size = getimagesize(DIR_IMAGE . $this->request->post['image']);

      if($size[0] != 330 || $size[1] != 120){
        $this->error[] = "Размер файла должен быть 330px х 120px";
        return false;
      }

      return true;
    }

}