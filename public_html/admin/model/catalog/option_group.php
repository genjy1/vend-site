<?php
class ModelCatalogOptionGroup extends Model {
	public function addoptiOnGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "option_group SET sort_order = '" . (int)$data['sort_order'] . "'");

		$option_group_id = $this->db->getLastId();

		foreach ($data['option_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_group_description SET option_group_id = '" . (int)$option_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		return $option_group_id;
	}

	public function editOptionGroup($option_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "option_group SET sort_order = '" . (int)$data['sort_order'] . "' WHERE option_group_id = '" . (int)$option_group_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "option_group_description WHERE option_group_id = '" . (int)$option_group_id . "'");

		foreach ($data['option_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_group_description SET option_group_id = '" . (int)$option_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
	}

	public function deleteOptionGroup($option_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_group WHERE option_group_id = '" . (int)$option_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_group_description WHERE option_group_id = '" . (int)$option_group_id . "'");
	}

	public function getOptionGroup($option_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_group WHERE option_group_id = '" . (int)$option_group_id . "'");

		return $query->row;
	}

	public function getOptionGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "option_group ag LEFT JOIN " . DB_PREFIX . "option_group_description agd ON (ag.option_group_id = agd.option_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'agd.name',
			'ag.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY agd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	public function getOptionGroupDescriptions($option_group_id) {
		$option_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_group_description WHERE option_group_id = '" . (int)$option_group_id . "'");

		foreach ($query->rows as $result) {
			$option_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $option_group_data;
	}

	public function getTotalOptionGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "option_group");

		return $query->row['total'];
	}
}