<?php
class ModelModuleFilter extends Model {
	public function getGroups($category_id = 0){
		$where = '1';
		if($category_id != 0){
			$query = $this->db->query("SELECT DISTINCT category_id FROM " . DB_PREFIX . "category WHERE parent_id = ".$category_id. " OR category_id=".$category_id);
			foreach ($query->rows as $row) {
				$category_ids[] = $row['category_id'];
			}
			$category_ids = implode(",", $category_ids);
			$query = $this->db->query("SELECT DISTINCT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN(".$category_ids.")");

			if(!empty($query->num_rows)){
				foreach ($query->rows as $row) {
					$product_ids[] = $row['product_id'];
				}
				$product_ids = implode(",", $product_ids);
				$query = $this->db->query("SELECT DISTINCT flower_id FROM " . DB_PREFIX . "bb_products WHERE product_id IN (".$product_ids.")");

				if(!empty($query->num_rows)){
					foreach ($query->rows as $row) {
						$flower_ids[] = $row['flower_id'];
					}
					$flower_ids = implode(",", $flower_ids);
					$query = $this->db->query("SELECT DISTINCT group_id FROM " . DB_PREFIX . "buketbase WHERE id IN (".$flower_ids.")");
					if(!empty($query->num_rows)){
						foreach ($query->rows as $row) {
							$group_ids[] = $row['group_id'];
						}
						$where = " id IN (". implode(",", $group_ids) .")";
					}
				}
			}
		}
		$query = $this->db->query("SELECT DISTINCT id, name, image FROM " . DB_PREFIX . "buket_groups WHERE ".$where);
		return $query->rows;
	}

	public function InPrice($category_id){
		$query = $this->db->query("SELECT DISTINCT parent_id FROM " . DB_PREFIX . "category WHERE category_id = ".$category_id);
		if(empty($query->row)){
			return false;
		}
		if($query->row['parent_id'] == 168){
			return true;
		} else {
			return false;
		}
	}
}
?>