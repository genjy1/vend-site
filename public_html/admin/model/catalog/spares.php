<?php
class ModelCatalogSpares extends Model {

	public function editMap($category_id, $data){
		// print_r($data);
		// exit;
		$map_ids = $this->db->getRows("SELECT id FROM __spare_category WHERE category_id=?i", $category_id);

		if(!empty($map_ids)){
			$ids = array();
			foreach ($map_ids as $key => $value) {
				$ids[] = $value['id'];
			}
			$this->db->query("DELETE FROM __spare_category WHERE category_id=?i",$category_id);
			$this->db->query("DELETE FROM __spare_images WHERE map_id IN(".implode(",", $ids).")");
			$this->db->query("DELETE FROM __spare_list WHERE map_id IN(".implode(",", $ids).")");
			// $this->db->query("INSERT INTO __spare_category SET category_id=?i", $category_id);
			// $map_id = $this->db->getLastId();
		}

		$n = 0;

		foreach ($data['map'] as $k => $m) {
			$this->db->query("INSERT INTO __spare_category SET category_id=?i, sort_order=?i", $category_id, $m['sort_order']);
			$map_id = $this->db->getLastId();
			$this->db->query("INSERT INTO __spare_images SET map_id=?i, image=?s, pins=?i",$map_id, $m['image'], $m['pins']);
			if(isset($data['items'][$k]) && !empty($data['items'][$k])){
				foreach ($data['items'][$k]['products'] as $i => $product_id) {
					echo $i . "\n";
					$this->db->query("INSERT INTO __spare_list SET map_id=?i, coords=?s, product_id=?i, num=?i",$map_id, $data['items'][$k]['coords'][$i], $product_id, $data['num'][$n]);
					$n++;
				}
			}
		}

	}

	public function getCategories(){
		return $this->db->getRows("SELECT * FROM __category c LEFT JOIN __category_description cd ON(c.category_id=cd.category_id) WHERE (c.parent_id=257 OR c.parent_id=244) AND cd.language_id=?i",$this->config->get('config_language_id'));
	}

	public function getMaps($id){
		$result = array();
		$map_ids = $this->db->getRows("SELECT id, sort_order FROM __spare_category WHERE category_id=?i ORDER BY sort_order",$id);
		foreach ($map_ids as $key => $map) {
			$map_id = $map['id'];
			$image = $this->db->getField("SELECT image FROM __spare_images WHERE map_id=?i",$map_id);
      $pins = $this->db->getField("SELECT pins FROM __spare_images WHERE map_id=?i",$map_id);
			$items = $this->db->getRows("SELECT * FROM __spare_list WHERE map_id=?i ORDER BY num", $map_id);
			foreach ($items as $key => $value) {
				$name = $this->getProductName($value['product_id']);
				if(!$name || $name == ''){
					unset($items[$key]);
					continue;
				}
				$items[$key]['name'] = $name;
			}
			$result[] = array(
				'map_id' => $map_id,
				'image' => $image,
        'pins' => $pins,
				'items' => $items,
				'sort_order' => $map['sort_order'],
				);
		}
		return $result;
	}

	private function getProductName($product_id){
		return trim($this->db->getField("SELECT name FROM __product_description WHERE product_id=?i AND language_id=?i",$product_id, $this->config->get('config_language_id')));
	}

	public function delete($id){
		$query = $this->db->query("DELETE FROM __spare_list WHERE map_id=?i",$id);
		$query = $this->db->query("DELETE FROM __spare_images WHERE map_id=?i",$id);
		$query = $this->db->query("DELETE FROM __spare_category WHERE map_id=?i",$id);
	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM __spare_list";


		// $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
