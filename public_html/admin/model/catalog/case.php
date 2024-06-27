<?php
class ModelCatalogCase extends Model {

	public function addCase($data = array()){
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "cases (`name`) VALUES ('" . $this->db->escape($data['name']) . "')");

		$id = $this->db->getLastId();
		if(!empty($data['value'])){
			foreach ($data['value'] as $key => $value) {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "case_slides (case_id, image, sort_order, description) VALUES (". $id .", '" . $value['value'] . "', ".$value['sort_order'].", '" . $this->db->escape($value['description']) . "' )");
			}
		}
	}

	public function getCase($case_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cases WHERE id=" . $case_id);
		return $query->row;
	}

	public function getCaseSlides($case_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_slides WHERE case_id=" . $case_id);
		return $query->rows;
	}


	public function editCase($id, $data = array()){

		$query = $this->db->query("UPDATE " . DB_PREFIX . "cases SET name = '" . $this->db->escape($data['name']) . "' WHERE id = ". (int)$id);

		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "case_slides WHERE case_id=".(int)$id);


		foreach ($data['value'] as $key => $value) {

			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "case_slides (case_id, image, sort_order, description) VALUES (". $id .",  '" . $value['value'] . "',  " . $value['sort_order'] . ", '" . $this->db->escape($value['description']) . "' )");
		}

	}



	public function getCases($data = array()){
		$sql = "SELECT * FROM __cases WHERE 1";

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

		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "cases WHERE id=". (int)$id);
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "case_slides WHERE case_id=".(int)$id);

	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "cases";


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
