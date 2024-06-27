<?php
class ModelCatalogSet extends Model {

	public function addSet($data = array()){
		$this->db->query("INSERT INTO __set SET name = ?, description = ?", (string)$data['name'], (string)$data['description']);
		$id = $this->db->getLastId();
		foreach ($data['position'] as $key => $value) {
			$value['set_id'] = $id;
			$this->db->query("INSERT INTO __set_positions SET ?%", $value);
		}
	}

	public function editSet($id, $data = array()){
		print_r($data);exit();
		$update = array();
		$update['name'] = $data['name'];
		$update['description'] = $data['description'];
		$query = $this->db->query("UPDATE __set SET ?% WHERE id=?", $update, $id);

		$query = $this->db->query("SELECT id FROM __set_positions WHERE set_id=?", $id);

		$ids = array();
		foreach ($query->rows as $key => $value) {
			$ids[] = $value['id'];
		}

		$updated = array();

		if(!empty($data['position'])){
			foreach ($data['position'] as $key => $value) {
				$value['set_id'] = $id;
				$value_id = $value['id'];
				unset($value['id']);
				if(!in_array($value_id, $ids)){
					$query = $this->db->query("INSERT INTO __set_positions ?%", $value);
					$updated[] = $value_id;
				}
				if(in_array($value_id, $ids)){
					$query = $this->db->query("UPDATE __set_positions SET ?% WHERE id=?", $value, $value_id);
					$updated[] = $value_id;
				}
			}

			foreach ($ids as $value_id) {
				if(!in_array($value_id, $updated)){
					$this->db->query("DELETE FROM __set_positions WHERE id=?", $value_id);
				}
			}
		}
	}

	public function getSet($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "set WHERE id=" .$id);
		return $query->row;
	}

	public function getSetPositions($id){
		$query = $this->db->query("SELECT * FROM __set_positions WHERE set_id=?", (int)$id);
		return $query->rows;
	}

	public function getSets($data = array()){
		$sql = "SELECT * FROM __set WHERE 1";

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

	public function getFirstImage($id){
		return $this->db->getField("SELECT image FROM __set_positions WHERE set_id=?", $id);
	}

	public function delete($id){
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "set WHERE id=".$id);
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "set_items WHERE set_id=".$id);
	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "set";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
