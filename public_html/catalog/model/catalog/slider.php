<?php
class ModelCatalogSlider extends Model {
	public function getSlider($slider_id){
		//$this->document->addStyle("catalog/view/javascript/mobileslider/css/default.css");
		$this->document->addScript("catalog/view/javascript/mobileslider/js/mobilyslider.js");
		$slider = array();
		$slider['slider_info'] = $this->db->getRow("SELECT * FROM __sliders WHERE slider_id = ?i", $slider_id);

		$slider['slides'] = $this->db->getRows("SELECT * FROM __slides WHERE slider_id = ?i ORDER BY sort_order", $slider_id);

		return $slider;
	}

	public function getDescription($slide_id){

		return $this->db->getRow("SELECT * FROM __slide_description WHERE slide_id = ?i AND language_id = ?i", $slide_id, $this->config->get('config_language_id'));
	}
}