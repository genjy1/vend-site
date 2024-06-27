<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->addScript('catalog/view/javascript/owlcarousel/owl.carousel.js');
		$this->document->addScript('catalog/view/javascript/countUp.js');
		$this->document->addScript('catalog/view/javascript/wow.min.js');
        $this->document->addScript('catalog/view/javascript/jquery.visible.min.js');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/animate.css');

		$title = $this->subdomains->getMainTitle($this->config->get('config_meta_title'));
		$description = $this->subdomains->getMainDescription($this->config->get('config_meta_description'));
		$this->document->setTitle($title);
		$this->document->setDescription($description);
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['slider'] = $this->load->controller('common/slider');
        $data['cases'] = $this->load->controller('common/case');
		$data['banner'] = $this->load->controller('common/banner');

		$data['mainpage'] = htmlspecialchars_decode($this->subdomains->getMainPageDescription());

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        $this->load->model('extension/module');

        $module_settings = $this->model_extension_module->getModule(35);
        $module_settings['is_home'] = true;
        $data['top_module'] = $this->load->controller('module/featured', $module_settings);
		
 

		$this->response->setOutput($this->load->view('common/home', $data));
	}
	public function setTemplate(){
		$json = array();
		$json['success'] = true;

		if(isset($this->request->post['tmpl'])) { 
            $_SESSION['tmpl'] = $this->request->post['tmpl'];
        } else {
            $_SESSION['tmpl'] = 1;
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}