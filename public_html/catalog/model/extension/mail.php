<?php
class ModelExtensionMail extends Model {
  public function addMail($email, $topic = "default") 
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notifications WHERE email = '" . $this->db->escape($email) . "'");



    if(!$query->num_rows){
      $this->db->query("INSERT INTO " . DB_PREFIX . "notifications SET email = '" . $this->db->escape($email) . "', topic = '" . $this->db->escape($topic) . "'");
      error_log("subscribe:" . $email);
      $this->unisender->subscribe($email);

    }

  }

}