<?php
class ModelExtensionPush extends Model {
  public function addToken($token, $topic = "default") 
  {
    $query = $this->db->query("SELECT id FROM " . DB_PREFIX . "push_tokents WHERE token='" . $this->db->escape($token) . "' AND topic='" . $this->db->escape($topic) . "'");

    if(!$query->row){
      $this->db->query("INSERT INTO " . DB_PREFIX . "push_tokents SET token='" . $this->db->escape($token) . "', topic='" . $this->db->escape($topic) . "'");

      $this->pusher->subscribe($token, $topic);
    }

  }

}