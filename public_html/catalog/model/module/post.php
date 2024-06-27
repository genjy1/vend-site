<?php
class ModelModulePost extends Model {
	public function getPost($post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM __post p LEFT JOIN __post_description id ON (p.post_id = id.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) WHERE p.post_id = '" . (int)$post_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1'");

		return $query->row;
	}

	public function getPosts($setting) {
		$query = $this->db->query("SELECT * FROM __post p LEFT JOIN __post_description id ON (p.post_id = id.post_id) LEFT JOIN __post_to_store p2s ON (p.post_id = p2s.post_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' ORDER BY p.post_date, LCASE(id.title) ASC LIMIT ".$setting['limit']);

		return $query->rows;
	}

	public function getPostLayoutId($post_id) {
		$query = $this->db->query("SELECT * FROM __post_to_layout WHERE post_id = ? AND store_id = ?", $post_id, (int)$this->config->get('config_store_id'));

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}
}