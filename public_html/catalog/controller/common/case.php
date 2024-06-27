<?php
class ControllerCommonCase extends Controller {
	public function index() {
		$this->load->model('catalog/case');

		$slider = $this->model_catalog_case->getCase(2);


		$this->load->model('tool/image');

		$data['slides'] = array();


		foreach ($slider['slides'] as $key => $value) {
			$image = $this->model_tool_image->resize($value['image'], 450, 560);
			$description =  $value['description'];


			$data['slides'][] = array(
				'id' => $value['id'],
				'image' => $image,
				'text' => $description,
				);
		}
		return $this->load->view('common/cases', $data);
	}
}