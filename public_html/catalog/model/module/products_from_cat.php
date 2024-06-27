<?php
class ModelModuleProductsFromCat extends Model {

	public function getProducts($setting) {

		$product_data = array();

		$products_ids = $this->getProductsInCats($setting['categories'], $setting['limit']);

		if (count($products_ids) < 1) {
			return array();
		} else {
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
			foreach($products_ids as $id) {
				$product_query = $this->db->query("SELECT *, 
				(SELECT ps.price FROM " . DB_PREFIX . "product_special ps WHERE p.product_id = ps.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) ) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, 
				(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,
				(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews
				FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$id . "' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				
				if ($product_query->num_rows) {
					$product_data[] = $product_query->row;
				}
			}
		
			return $product_data;
		}
	}

	private function getProductsInCats( $categories, $limit ){

		$implode = implode(",", $categories);

		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN( ". $implode ." ) LIMIT ".$limit );

		$product_ids = array();

		foreach ($query->rows as $row) {
			$product_ids[] = $row['product_id'];
		}

		return $product_ids;
	}
	
}
?>