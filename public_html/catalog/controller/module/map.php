<?php
class ControllerModuleMap extends Controller {
	public function index($setting) {
		$this->load->language('module/map');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addStyle('catalog/view/javascript/flex/css/flexslider.css');
		$this->document->addScript('catalog/view/javascript/flex/js/jquery.flexslider.js');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('tool/image');

		foreach ($setting['value'] as $key => $value) {
			if (is_file(DIR_IMAGE . $value['value'])) {
				$image = $value['value'];
			} else {
				$image = '';
			}

			$data['images'][$key] = array(
				'image'      => $this->model_tool_image->resize($image, 768, 460),
			);
		}

		return $this->load->view('module/map', $data);
	}
}