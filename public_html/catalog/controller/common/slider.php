<?php
class ControllerCommonSlider extends Controller {
	public function index() {
		$this->load->model('catalog/slider');

		$this->document->addScript('catalog/view/javascript/timer/TimeCircles.js');
		$this->document->addStyle('catalog/view/javascript/timer/TimeCircles.css');

		$slider = $this->model_catalog_slider->getSlider(13);

		switch ($slider['slider_info']['effect']) {
			case '0':
				$data['effect'] = '';
				break;
			case '1':
				$data['effect'] = 'vertical';
				break;
			case '2':
				$data['effect'] = 'fade';
				break;
			default:
				$data['effect'] = '';
				break;
		}

		$this->load->model('tool/image');

		$data['slides'] = array();


		foreach ($slider['slides'] as $key => $value) {
			// print_r($value);
			// $image = $this->model_tool_image->resize($value['image'], 1170, 634);
			$image = $this->model_tool_image->resize($value['image'], 450, 560);
			$description =  $this->model_catalog_slider->getDescription($value['slide_id']);

			// $timer = explode("-", $value['timer']);
			// if(count($timer)>2){
			// 	$time_start = mktime(0,0,0,(int)$date[1],((int)$date[2] + 1),(int)$date[0]);
			// 	$time_now = time();
			// 	$time_end = mktime(0,0,0,$timer[1],($timer[2] + 1),$timer[0]);
			// } else {
			// 	$time_start = '';
			// 	$time_now = '';
			// 	$time_end = '';
			// }
			if($value['timer'] != ""){

				if(strtotime(date("Y-m-d")) > strtotime($value['timer'])){ 
					$value['timer'] = false;
				}
			}

			$data['slides'][] = array(
				'id' => $value['slide_id'],
				'image' => $image,
				'time_end' => $value['timer'],
				'color_text' => $value['color_text'],
				'background' => $this->model_tool_image->resize($value['background'], 1920, 630),
				'color_caption' => $value['color_caption'],
				'color_button' => $value['color_button'],
				'text' => $description['text'],
				'caption' => $description['caption'],
				'text_links' => explode(",", $description['text_link']),
				'position' => $value['position'],
				'links' => explode(",", $value['links']),
				);
		}
// print_r($data['slides']);
		return $this->load->view('common/slider', $data);
	}
}