<?php
class ModelCatalogPromo extends Model {

	public function addPromo($data = array()){
		$data['usename'] = (isset($data['usename']) && $data['usename']!='')?1:0;
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "promo (`name`, `image`, `width`, `height`, `usename`, `position`) VALUES ('" . $this->db->escape($data['name']) . "','" . $this->db->escape($data['image']) . "','" . (int)$this->db->escape($data['width']) . "', '" . (int)$this->db->escape($data['height']) . "', '" . (int)$this->db->escape($data['usename']) . "', " . (int)$this->db->escape($data['position']) . ")");
	}

	public function editPromo($id, $data = array()){
		$data['usename'] = (isset($data['usename']) && $data['usename']!='')?1:0;
		$query = $this->db->query("UPDATE " . DB_PREFIX . "promo SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($data['image']) . "', width = '" . (int)$this->db->escape($data['width']) . "', height = '" . (int)$this->db->escape($data['height']) . "', usename = '" . (int)$this->db->escape($data['usename']) . "', position = " . (int)$this->db->escape($data['position']) . " WHERE promo_id=".$id);

	}

	public function getPromoProduct($product_id){
		$result = array();
		$query = $this->db->query("SELECT promo_id FROM " . DB_PREFIX . "promo_to_product WHERE product_id = " . (int)$product_id);
		if(!empty($query->rows)){
			foreach ($query->rows as $key => $value) {
				$result[] = $value['promo_id'];
			}
			return $result;
		} else {
			return array();
		}
	}

	public function getPromo($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "promo WHERE promo_id=" .(int)$id);
		return $query->row;
	}


	public function getPromoList(){
		$sql = "SELECT * FROM " . DB_PREFIX . "promo WHERE 1";

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
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "promo WHERE promo_id=".(int)$id);
	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT promo_id) AS total FROM " . DB_PREFIX . "promo";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
