<?php
class ModelCatalogSlider extends Model {

	public function addSlider($data = array()){
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "sliders (`name`, `effect`) VALUES ('" . $this->db->escape($data['name']) . "', '" . $this->db->escape($data['effect']) . "')");

		$id = $this->db->getLastId();
		if(!empty($data['value'])){
			foreach ($data['value'] as $key => $value) {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "slides (slider_id, links, color_text, color_caption, color_button, image, position, sort_order, background, timer) VALUES (". $id .", '". implode(",",$value['links']) ."', '" . $this->db->escape($value['color_text']) . "', '" . $this->db->escape($value['color_caption']) . "', '" . $this->db->escape($value['color_button']) . "', '" . $value['value'] . "', ".$value['position'].", " . $value['sort_order'] . ", '" . $value['backgimage'] . "', '".$this->db->escape($value['timer'])."' )");
				$slide_id = $this->db->getLastId();
				foreach ($value['description'] as $k => $description) {
					$query = $this->db->query("INSERT INTO " . DB_PREFIX . "slide_description (slide_id, language_id, caption, text, text_link) VALUES (". $slide_id .", ". $k .", '".$this->db->escape($description['caption'])."', '".$this->db->escape($description['text'])."', '".$this->db->escape($description['text_link'])."')");
				}
			}
		}
	}

	public function getSlider($slider_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sliders WHERE slider_id=".$slider_id);
		return $query->row;
	}

	public function getSlides($slider_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "slides WHERE slider_id=".$slider_id);
		return $query->rows;
	}


	public function getDescription($slide_id){
		$description = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "slide_description WHERE slide_id=".$slide_id);
		foreach ($query->rows as $key => $value) {
			$description[$value['language_id']] = $value;
		}
		return $description;
	}


	public function editSlider($id, $data = array()){

		$query = $this->db->query("UPDATE " . DB_PREFIX . "sliders SET name = '" . $this->db->escape($data['name']) . "', effect = '" . $this->db->escape($data['effect'])  . "' WHERE slider_id=".$id);

		$slidesquery = $this->db->query("SELECT slide_id FROM " . DB_PREFIX . "slides WHERE slider_id=".$id);
		$slides = array();

		foreach ($slidesquery->rows as $key => $value) {
			$slides[] = $value['slide_id'];
		}
		$slides = implode(",", $slides);

		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "slides WHERE slider_id=".$id);
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "slide_description WHERE slide_id IN(".$slides.")");


		foreach ($data['value'] as $key => $value) {
			$links = array();
			foreach ($value['links'] as $k => $link) {
				if($link != ''){
					$links[] = $link;
				}
			}
			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "slides (slider_id, links, color_text, color_caption, color_button, image, position, sort_order, background, timer) VALUES (". $id .", '". implode(",", $links) ."', '" . $value['color_text'] . "', '" . $value['color_caption'] . "', '" . $this->db->escape($value['color_button']) . "', '" . $value['value'] . "', ".$value['position'].", " . $value['sort_order'] . ", '" . $value['backgimage'] . "', '".$this->db->escape($value['timer'])."' )");
			$slide_id = $this->db->getLastId();
			foreach ($value['description'] as $k => $description) {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "slide_description (slide_id, language_id, caption, text, text_link) VALUES (". $slide_id .", ". $k .", '".$this->db->escape($description['caption'])."', '".$this->db->escape($description['text'])."', '".$this->db->escape($description['text_link'])."')");
			}
		}

	}



	public function getSliders($data = array()){
		$sql = "SELECT * FROM __sliders WHERE 1";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function delete($id){
		$slidesquery = $this->db->query("SELECT slide_id FROM " . DB_PREFIX . "slides WHERE slider_id=".$id);
		$slides = array();

		foreach ($slidesquery->rows as $key => $value) {
			$slides = $value['slide_id'];
		}
		$slides = implode(",", $slides);
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "sliders WHERE slider_id=".$id);
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "slides WHERE slider_id=".$id);
		if(!empty($slides)){
			$query = $this->db->query("DELETE FROM " . DB_PREFIX . "slide_description WHERE slide_id IN(".$slides.")");
		}
	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "gallery";


		// $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
