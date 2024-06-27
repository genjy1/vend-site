<?php
class ModelCatalogCase extends Model {
	public function getCase($id){
		$slider = array();

		$slider['slides'] = $this->db->getRows("SELECT * FROM __case_slides WHERE case_id = ?i ORDER BY sort_order", $id);

		return $slider;
	}
}