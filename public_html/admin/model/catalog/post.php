<?php
class ModelCatalogPost extends Model {
	public function addPost($data) {
		$date = (empty($data['post_date']) || $data['post_date'] == '')?"CURDATE()":"'".$data['post_date']."'";

		$this->db->query("INSERT INTO __post SET post_date = " . $date . ", image = '" . $data['image'] . "', status = '" . (int)$data['status'] . "', timer='".$data['timer']."', form='".$data['form']."'");

		$post_id = $this->db->getLastId();

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO __post_description SET post_id = '" . (int)$post_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['post_store'])) {
			foreach ($data['post_store'] as $store_id) {
				$this->db->query("INSERT INTO __post_to_store SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO __post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['products']) && !empty($data['products'])) {
			foreach ($data['products'] as $product_id) {
				$this->db->query("INSERT INTO __post_products SET post_id = '" . (int)$post_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['keyword']) && $data['keyword'] != "") {
			$this->db->query("INSERT INTO __url_alias SET query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['category']) && $data['category'] != '' && $data['category'] != 0) {
			$this->db->query("INSERT INTO __post_to_category SET post_id = ?i, category_id = ?i", $post_id, $data['category']);
		}


		return $post_id;
	}

	public function editPost($post_id, $data) {
		$date = (empty($data['post_date']) || $data['post_date'] == '')?"CURDATE()":"'".$data['post_date']."'";
		$this->db->query("UPDATE __post SET post_date = " . $date . ", image = '" . $data['image'] . "', status = '" . (int)$data['status'] . "', timer='" . $data['timer'] . "', form='".$data['form']."' WHERE post_id = '" . (int)$post_id . "'");

		$this->db->query("DELETE FROM __post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO __post_description SET post_id = '" . (int)$post_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM __post_products WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['products']) && !empty($data['products'])) {
			foreach ($data['products'] as $product_id) {
				$this->db->query("INSERT INTO __post_products SET post_id = '" . (int)$post_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM __post_to_store WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_store'])) {
			foreach ($data['post_store'] as $store_id) {
				$this->db->query("INSERT INTO __post_to_store SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}


		$this->db->query("DELETE FROM __post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO __post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM __url_alias WHERE query = 'post_id=" . (int)$post_id . "'");

		if (isset($data['keyword']) && $data['keyword'] != "") {
			$this->db->query("INSERT INTO __url_alias SET query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM __post_to_category WHERE post_id = ?i", $post_id);

		if (isset($data['category'])) {
      foreach ($data['category'] as $key => $category_id) {
        $this->db->query("INSERT INTO __post_to_category SET post_id = ?i, category_id = ?i", $post_id, (int)$category_id);
      }
		}


	}

	public function deletePost($post_id) {

		$this->db->query("DELETE FROM __post WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM __post_description WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM __post_to_store WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM __post_to_layout WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM __url_alias WHERE query = 'post_id=" . (int)$post_id . "'");
		$this->db->query("DELETE FROM __post_to_category WHERE post_id = ?i", $post_id);

	}

	public function getPost($post_id) {
		$result = array();
		// die("SELECT DISTINCT *, (SELECT keyword FROM __url_alias WHERE query = 'post_id=" . (int)$post_id . "') AS keyword, (SELECT category_id FROM __post_to_category WHERE post_id =  ". (int)$post_id . ") as category FROM __post WHERE post_id = '" . (int)$post_id . "'");
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM __url_alias WHERE query = 'post_id=" . (int)$post_id . "') AS keyword FROM __post WHERE post_id = '" . (int)$post_id . "'");

		$result = $query->row;
		$products = array();

		$query = $this->db->getRows("SELECT product_id FROM __post_products WHERE post_id=".$post_id);
		foreach ($query as $key => $value) {
			$products[] = $value['product_id'];
		}
		$result['products'] = $products;

    $categories = [];

    $query = $this->db->getRows("SELECT * FROM __post_to_category ptc LEFT JOIN __post_categories_description pcd ON ptc.category_id = pcd.category_id WHERE post_id=".$post_id ." AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

    foreach ($query as $key => $value) {
      $categories[] = $value;
    }

    $result['categories'] = $categories;
		return $result;
	}

	public function getPosts($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM __post i LEFT JOIN __post_description id ON (i.post_id = id.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " ORDER BY i.post_date DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {

			if (!$post_date) {
				$query = $this->db->query("SELECT * FROM __post i LEFT JOIN __post_description id ON (i.post_id = id.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$post_date = $query->rows;

			}

			return $post_date;
		}
	}

	public function getPostDescriptions($post_id) {
		$post_description_data = array();

		$query = $this->db->query("SELECT * FROM __post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $post_description_data;
	}

	public function getPostStores($post_id) {
		$post_store_data = array();

		$query = $this->db->query("SELECT * FROM __post_to_store WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_store_data[] = $result['store_id'];
		}

		return $post_store_data;
	}

	public function getPostLayouts($post_id) {
		$post_layout_data = array();

		$query = $this->db->query("SELECT * FROM __post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $post_layout_data;
	}

	public function editCategories($data){
		foreach ($data['category'] as $key => $value) {
			$category_id = $value['category_id'];

			if($value['category_id'] == 0 || $value['category_id'] == '' ){
				$this->db->query("INSERT INTO __post_categories SET image=?s, sort_order=?i", $value['image'], $value['sort_order']);
				$category_id = $this->db->getLastId();
			} else {
				$this->db->query("UPDATE __post_categories SET image=?s, sort_order=?i WHERE category_id=?i", $value['image'], $value['sort_order'], $category_id);
			}

			$this->db->query("DELETE FROM __post_categories_description WHERE category_id=?i", $category_id);

			foreach ($value['category_description'] as $language_id => $cat) {
				$this->db->query("INSERT INTO __post_categories_description SET category_id=?i, language_id=?i, name=?s", $category_id, $language_id, $cat['name']);
			}
		}
	}

	public function getCategoryDescription($category_id){
		$query = $this->db->query("SELECT * FROM __post_categories_description WHERE category_id = ?i AND language_id = ?i", $category_id, (int)$this->config->get('config_language_id'));

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT DISTINCT * FROM __post_categories pc LEFT JOIN __post_categories_description pcd ON(pc.category_id = pcd.category_id) WHERE 1 AND pcd.language_id = " . (int)$this->config->get('config_language_id');

		if(isset($data['filter_name'])){
			$sql .= " AND pcd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategory($category_id) {
		$sql = "SELECT DISTINCT * FROM __post_categories pc LEFT JOIN __post_categories_description pcd ON(pc.category_id = pcd.category_id) WHERE pc.category_id = ".$category_id . " AND pcd.language_id = " . (int)$this->config->get('config_language_id');

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getCategoryDescriptions($category_id) {
		$cat_data = array();

		$query = $this->db->query("SELECT * FROM __post_categories_description WHERE category_id = ?i", $category_id);

		foreach ($query->rows as $result) {
			$cat_data[$result['language_id']] = array(
				'name'            => $result['name'],
			);
		}

		return $cat_data;
	}

	public function getTotalPosts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM __post");

		return $query->row['total'];
	}

	public function getTotalPostsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM __post_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}