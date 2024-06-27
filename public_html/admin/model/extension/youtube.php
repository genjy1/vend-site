<?php
class ModelExtensionYoutube extends Model {

  public function getPost($post_id) {
    $sql = "SELECT * FROM __youtube WHERE id = " . (int)$post_id;

    $query = $this->db->query($sql);

    return $query->row;
  }

  public function editPost($id, $data = array()) {

    $categories = implode(",", $data['category_ids']);

    $sql = "UPDATE __youtube SET name = '" . $this->db->escape($data['name']) . "', text = '" . $this->db->escape($data['description']) . "', image = '" . $this->db->escape($data['image']) . "', link='" . $this->db->escape($data['link']) . "', category_ids = '" . $categories . "' WHERE id = " . (int)$id;

    $query = $this->db->query($sql);

    return $query->row;
  }

  public function getPosts($data = array()) {
    if ($data) {
      $sql = "SELECT * FROM __youtube ";

      if (isset($data['start']) || isset($data['limit'])) {
        if ($data['start'] < 0) {
          $data['start'] = 0;
        }

        if ($data['limit'] < 1) {
          $data['limit'] = 20;
        }

        $sql .= " ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
      }

      $query = $this->db->query($sql);

      return $query->rows;
    } else {

      if (!$post_data) {
        $query = $this->db->query("SELECT * FROM __youtube ORDER BY id DESC");

        $post_data = $query->rows;

      }

      return $post_data;
    }
  }

  public function delete($sub_id) {
    $this->db->query("DELETE FROM " . DB_PREFIX . "youtube WHERE id = '" . (int)$sub_id . "'");
  }


  public function getTotalPosts(){
    $query = $this->db->query("SELECT COUNT(*) AS total FROM __youtube");

    return $query->row['total'];
  }

  public function addPost($data = array()){

    $categories = implode(",", $data['category_ids']);

    $this->db->query("INSERT INTO __youtube SET name = '" . $this->db->escape($data['name']) . "', text = '" . $this->db->escape($data['description']) . "', image = '" . $this->db->escape($data['image']) . "', link='" . $this->db->escape($data['link']) . "', category_ids = '" . $categories . "'");

  }

}