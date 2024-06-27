<?php
class ControllerCommonBanner extends Controller {
	public function index() {

		$this->load->model('catalog/slider');
		$slider = $this->model_catalog_slider->getSlider(13);

		$this->load->model('tool/image');

		$data['slides'] = array();


		foreach ($slider['slides'] as $key => $value) {
			//$image = $this->model_tool_image->resize($value['image'], 450, 560);
			//$bg = $this->model_tool_image->resize($value['background'], 450, 560);
			$data['slides'][] = array(
				'id' => $value['slide_id'],
				'image' => $value['image'],
				'bg' => $value['background'],
				'color_text' => $value['color_text'],
				'color_caption' => $value['color_caption'],
				'color_button' => $value['color_button'],
				'description' => $this->model_catalog_slider->getDescription($value['slide_id']),
				'links' => explode(",", $value['links'])
				);
		}

		return $this->load->view('common/banner', $data);
	}
}