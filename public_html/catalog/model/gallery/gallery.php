<?php
class ModelGalleryGallery extends Model {

	public function getSets($data = array()){
		$sql = "SELECT * FROM __gallery WHERE 1 ORDER BY sort";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getSet($gallery_id, $start, $limit){
		return $this->db->getRows("SELECT * FROM __gallery_items WHERE set_id = ?i ORDER BY sort LIMIT ". $start ."," . $limit, $gallery_id);
	}

	public function getSetName($gallery_id){
		return $this->db->getField("SELECT name FROM __gallery WHERE id = ?i ORDER BY sort", $gallery_id);
	}

	public function countVideo($gallery_id){
		return $this->db->getField("SELECT COUNT(videoid) as count FROM __gallery_items WHERE set_id = ?i AND videoid != '' ORDER BY sort", $gallery_id);
	}

	public function getTotalImages($gallery_id) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM __gallery_items WHERE set_id = " . (int)$gallery_id;


		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}