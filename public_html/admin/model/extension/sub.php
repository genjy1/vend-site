<?php
class ModelExtensionSub extends Model {

	public function add($data) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "subdomains SET sub = '" . $this->db->escape($data['sub']) . "', city = '" . $this->db->escape($data['city']) . "', city2='" . $this->db->escape($data['city2']) . "', country = '" . $this->db->escape($data['country']) . "', country2='" . $this->db->escape($data['country2']) . "', pad = '" . $this->db->escape($data['pad']) . "',  mainpage='" . $this->db->escape($data['mainpage']) . "'");

	}

	public function edit($sub_id, $data) {

			$this->db->query("UPDATE " . DB_PREFIX . "subdomains SET sub = '" . $this->db->escape($data['sub']) . "', city = '" . $this->db->escape($data['city']) . "', city2='" . $this->db->escape($data['city2']) . "', country = '" . $this->db->escape($data['country']) . "', country2='" . $this->db->escape($data['country2']) . "', pad = '" . $this->db->escape($data['pad']) . "',  mainpage='" . $this->db->escape($data['mainpage']) . "' WHERE id=".$sub_id);
	}

	public function delete($sub_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "subdomains WHERE id = '" . (int)$sub_id . "'");
	}

	public function get($sub_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "subdomains WHERE id = '" . (int)$sub_id . "' ");

		return $query->row;
	}

	public function getSubs($data = array()) { 
		// $this->import();
		$sql = "SELECT * FROM " . DB_PREFIX . "subdomains WHERE 1";

		

		if (isset($data['filter']) && $data['filter'] != "") {
			$sql .= " AND sub LIKE '%" . $this->db->escape($data['filter']) . "%'";
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

	public function import($value='')
	{
		$xml = file_get_contents("s.xml");
		$xml = new SimpleXMLElement($xml);
		$i = 0;
		foreach ($xml->Worksheet->Table->Row as $key => $row) {
			if($i == 0) {
				$i++;
				continue;
			}
			$data['country'] = $row->Cell[0]->Data;
			$data['city'] = $row->Cell[1]->Data;
			$data['city2'] = $row->Cell[2]->Data;
			$data['sub'] = $row->Cell[3]->Data;
			$data['pad'] = $row->Cell[5]->Data;
			$data['country2'] = $row->Cell[6]->Data;

			// $this->db->query("INSERT INTO " . DB_PREFIX . "subdomains SET sub = '" . $this->db->escape($data['sub']) . "', city = '" . $this->db->escape($data['city']) . "', city2='" . $this->db->escape($data['city2']) . "', country = '" . $this->db->escape($data['country']) . "', country2='" . $this->db->escape($data['country2']) . "', pad = '" . $this->db->escape($data['pad']) . "',  mainpage='" . $this->db->escape($data['mainpage']) . "'");
		}
	}


	public function getTotalSubs($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "subdomains";
		if (isset($data['filter']) && $data['filter'] != "") {
			$sql .= " WHERE sub LIKE '%" . $this->db->escape($data['filter']) . "%'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}