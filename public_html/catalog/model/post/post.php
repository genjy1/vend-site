<?php
class ModelPostPost extends Model {
	public function getPost($post_id) {
		$result = array();
		$query = $this->db->query("SELECT DISTINCT * FROM __post p LEFT JOIN __post_description pd ON (p.post_id = pd.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) WHERE p.post_id = '" . (int)$post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1'");


		$result = $query->row;
		$products = array();
		$query = $this->db->getRows("SELECT product_id FROM __post_products WHERE post_id=" . (int)$post_id);
		foreach ($query as $key => $value) {
			$products[] = $value['product_id'];
		}
		$result['products'] = $products;
		return $result;
	}

	public function getPosts($data, $category_id) {
		$query = $this->db->query("SELECT * FROM __post p LEFT JOIN __post_description pd ON (p.post_id = pd.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) LEFT JOIN __post_to_category p2c ON (p.post_id = p2c.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' AND p2c.category_id = " . (int)$category_id . " ORDER BY p.post_date DESC, LCASE(pd.title) ASC LIMIT " . (int)$data['start'] * (int)$data['limit'] . ",".(int)$data['limit'] );

		return $query->rows;
	}


	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM __post_categories pc LEFT JOIN __post_categories_description pcd ON (pc.category_id = pcd.category_id) WHERE pc.category_id = '" . (int)$category_id . "' AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getPostCategory($post_id) {
		$query = $this->db->query("SELECT DISTINCT category_id FROM __post_to_category WHERE post_id=".(int)$post_id);
		if(!empty($query->row)){
			return $query->row['category_id'];
		} else {
			return false;
		}
	}

	public function getCategories($data = array()){
		$results = array();
		foreach ($data as $key => $category_id) {
			$results[] = $this->getCategory($category_id);
		}
		return $results;
	}

	public function getPostLayoutId($post_id) {
		$query = $this->db->query("SELECT * FROM __post_to_layout WHERE post_id = ?i AND store_id = ?i", $post_id, (int)$this->config->get('config_store_id'));

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getTotalPosts($category_id){
		$query = $this->db->query("SELECT COUNT(DISTINCT p.post_id) as total FROM __post p LEFT JOIN __post_description pd ON (p.post_id = pd.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' ORDER BY p.post_date, LCASE(pd.title)");
		$query = $this->db->query("SELECT COUNT(DISTINCT p.post_id) as total FROM __post p LEFT JOIN __post_description pd ON (p.post_id = pd.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) LEFT JOIN __post_to_category p2c ON (p.post_id = p2c.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' AND p2c.category_id = " . (int)$category_id . " ORDER BY p.post_date, LCASE(pd.title) ASC ");
		return $query->row['total'];
	}

}