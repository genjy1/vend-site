<?php
class ModelCatalogGallery extends Model {

	public function addSet($data = array()){
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "gallery (`name`, `image`, `description`, `sort`) VALUES ('" . $this->db->escape($data['name']) . "','" . $this->db->escape($data['image']) . "','" . $this->db->escape($data['description']) . "', 0)");

		$id = $this->db->getLastId();
		if(!empty($data['value'])){
			foreach ($data['value'] as $key => $value) {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "gallery_items (set_id, value, sort, caption, videoid) VALUES ('" . $id . "','" . $this->db->escape($value['value']) . "','" . $this->db->escape($value['sort_order']) . "', '" . $this->db->escape($value['caption']) . "', '" . (string)$this->db->escape($value['videoid']) . "')");
			}
		}
		if(!empty($data['seo'])){
			$query = $this->db->query("INSERT INTO __url_alias SET query=?s, keyword=?s", "gallery_id=" . $id, $data['seo']);
		}
	}

	public function editSet($id, $data = array()){
		$query = $this->db->query("UPDATE " . DB_PREFIX . "gallery SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($data['image']) . "', description = '" . $this->db->escape($data['description']) . "' WHERE id=".$id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_items WHERE set_id=".$id);
		$this->db->query("DELETE FROM __url_alias WHERE query=?s", "gallery_id=" .$id);

		if(!empty($data['value'])){
			foreach ($data['value'] as $key => $value) {
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "gallery_items (set_id, value, sort, caption, videoid) VALUES ('" . $id . "','" . $this->db->escape($value['value']) . "','" . $this->db->escape($value['sort_order']) . "', '" . $this->db->escape($value['caption']) . "', '" . $this->db->escape($value['videoid']) . "')");
			}
		}

		if(!empty($data['seo'])){
			$query = $this->db->query("INSERT INTO __url_alias SET query=?s, keyword=?s", "gallery_id=" . $id, $data['seo']);
		}

	}

	public function getSet($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery WHERE id=" .$id);
		return $query->row;
	}

	public function getSeo($id){
		$query = $this->db->query("SELECT * FROM __url_alias WHERE query=?s", "gallery_id=" .$id);
		if(!empty($query->row)){
			return $query->row['keyword'];
		} else {
			return "";
		}
	}

	public function getSetItems($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_items WHERE set_id=" .$id);
		return $query->rows;
	}

	public function getSets($data = array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "gallery WHERE 1";

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

	public function delete($id){
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "gallery WHERE id=".$id);
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "gallery_items WHERE set_id=".$id);
	}

	public function getTotal($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "gallery";


		// $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

  public function getFolders()
  {
    $root = DIR_IMAGE . "catalog/";

    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD 
    );


    foreach ($iter as $path => $dir) {
        if ($dir->isDir()) {
          if(strpos($path, $root . "cache") !== false){
            continue;
          }
          $name = str_replace(DIR_IMAGE, "", $path);
          $paths[$name] = $name;
        }
    }

    return $paths;
  }

}
