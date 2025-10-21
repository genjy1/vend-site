<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

  public function getYoutubeBanner($category_id) {

    $sql = "SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "' AND status = '1' ";
    $query = $this->db->query($sql);

    if(isset($query->row['parent_id'])){
      $parent_id = $query->row['parent_id'];
    } else {
      $parent_id = 0;
    }


    $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "youtube WHERE category_ids LIKE '%" . $category_id . "%' OR category_ids LIKE '%" . $parent_id . "%'");

// echo "SELECT DISTINCT * FROM " . DB_PREFIX . "youtube WHERE category_ids LIKE '%" . $category_id . "%' OR category_ids LIKE '%" . $parent_id . "%'";

    foreach ($query->rows as $row) {
      $category_ids = explode(",", $row['category_ids']);

      if(in_array($category_id, $category_ids)){
        return $row;
      }

      if(in_array($parent_id, $category_ids)){
        return $row;
      }

    }


    return false;
  }

	public function getCategories($parent_id = 0, $filter = array()) {
		if(empty($filter)){
			$order = "c.sort_order";
		} elseif (!empty($filter) && isset($filter['order'])) {
			$order = $filter['order'];
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY " . $order . ", LCASE(cd.name)";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategoryFilters($category_id = 0) {
		$implode = array();

		if($category_id > 0){
			$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		} else {
			$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE 1");
		}

		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}

		$filter_group_data = array();

		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();

				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']
					);
				}

				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);
				}
			}
		}

		return $filter_group_data;
	}

	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row['total'];
	}

	public function getMinPriceInCategory( $category_id ){

		if($category_id > 0){
			$where = "category_id IN ( SELECT DISTINCT category_id FROM ". DB_PREFIX ."category WHERE category_id = ".$category_id." OR parent_id = ".$category_id.") )";
		} else{
			$where = "1)";
		}
		$query = $this->db->query("SELECT MIN(price) FROM " . DB_PREFIX . "product WHERE product_id IN( SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . $where . " AND status = 1");
		$min = $query->row['MIN(price)'];

// echo "SELECT MIN(price) FROM " . DB_PREFIX . "product WHERE product_id IN( SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . $where . " AND status = 1";
		return floor($min);
	}

	public function inSpares($category_id){
		$id = $this->db->getField("SELECT parent_id FROM __category WHERE category_id = ?i", $category_id);
		$in = $this->db->getField("SELECT parent_id FROM __category WHERE category_id = ?i", $id);

		if($in == 5){
			return true;
		} else {
			return false;
		}
	}

	public function getMaxPriceInCategory( $category_id ){

		if($category_id > 0){
			$where = "category_id IN ( SELECT DISTINCT category_id FROM ". DB_PREFIX ."category WHERE category_id = ".$category_id." OR parent_id = ".$category_id.") )";
		} else{
			$where = "1)";
		}
		$query = $this->db->query("SELECT MAX(price) FROM " . DB_PREFIX . "product WHERE product_id IN( SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . $where . " AND status = 1");
		$max = $query->row['MAX(price)'];


		return ceil($max);
	}


	public function getMaps($id){
		$result = array();
		$map_ids = $this->db->getRows("SELECT id, sort_order FROM __spare_category WHERE category_id=?i ORDER BY sort_order",$id);
		foreach ($map_ids as $key => $map) {
			$map_id = $map['id'];
			$image = $this->db->getField("SELECT image FROM __spare_images WHERE map_id=?i",$map_id);
      $pins = $this->db->getField("SELECT pins FROM __spare_images WHERE map_id=?i",$map_id);
			$items = $this->db->getRows("SELECT * FROM __spare_list WHERE map_id=?i ORDER BY num DESC", $map_id);
			foreach ($items as $key => $value) {
				$items[$key]['name'] = $this->getProductName($value['product_id']);
				$items[$key]['image'] = $this->getProductImage($value['product_id']);
				$items[$key]['price'] = $this->getProductPrice($value['product_id']);
				$items[$key]['num'] = $value['num'];
				$items[$key]['description'] = $this->getProductDescription($value['product_id']);
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

	private function getProductImage($product_id){
		return $this->db->getField("SELECT image FROM __product WHERE product_id=?i",$product_id);
	}

	private function getProductPrice($product_id){
		return $this->db->getField("SELECT price FROM __product WHERE product_id=?i",$product_id);
	}

	private function getProductDescription($product_id){
		return $this->db->getField("SELECT description FROM __product_description WHERE product_id=?i AND language_id=?i",$product_id, $this->config->get('config_language_id'));
	}




}