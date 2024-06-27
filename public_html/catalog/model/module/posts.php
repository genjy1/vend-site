<?php
class ModelModulePosts extends Model {


	public function getPosts($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM __post i LEFT JOIN __post_description id ON (i.post_id = id.post_id) LEFT JOIN __post_to_category ptc ON(ptc.post_id=i.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i.status=1";


			if(!empty($data['categories'])){
				$categories = implode(',',$data['categories']);
				$sql .= " AND ptc.category_id IN(".$categories.")";
			}

			$sql .= " ORDER BY i.post_date DESC LIMIT 0," . (int)$data['limit'] . "";

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


}
?>