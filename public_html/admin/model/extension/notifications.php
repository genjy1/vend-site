<?php
class ModelExtensionNotifications extends Model {

  public function getTopics()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "push_topics WHERE 1");

    return $query->rows;
  }

  public function saveTopics( $data = array() )
  {
    $this->db->query("DELETE FROM " . DB_PREFIX . "push_topics WHERE 1");

    foreach ($data as $key => $value) 
    {
      $this->db->query("INSERT INTO " . DB_PREFIX . "push_topics SET label = '" . $this->db->escape($value['label']) . "', topic = '" . $this->db->escape($value['topic']) . "' ");
    }
  }

  public function addHistory($data)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "push_history SET title = '" . $this->db->escape($data['title']) . "', body = '" . $this->db->escape($data['body']) . "', url = '" . $this->db->escape($data['url']) . "', icon = '" . $this->db->escape($data['image']) . "', topic = '" . $this->db->escape($data['topic']) . "' ");
  }

  public function addMailHistory($data, $count)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "mail_history SET subject = '" . $this->db->escape($data['subject']) . "', message = '" . $this->db->escape($data['message']) . "', count = '" . (int)$count . "' ");
  }

  public function getEmails()
  {
    $result = array();

    $query = $this->db->query("SELECT email FROM " . DB_PREFIX . "notifications WHERE 1");

    if($query->num_rows){
      foreach( $query->rows as $row )
      {
        $result[] = $row['email'];
      }
    } else {
      return array();
    }

    return $result;
  }

  public function getPushHistory($data = array()){
        $result = array();

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "push_history ORDER BY id DESC LIMIT " . (int)$data['start'] . ", " . (int)$data['limit']);

    if($query->num_rows){
      foreach( $query->rows as $row )
      {
        $result[] = $row;
      }
    } else {
      return array();
    }

    return $result;
  }

  public function getTotalPushHistory() {
    $sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "push_history WHERE 1";

    $query = $this->db->query($sql);

    return $query->row['total'];
  }


  public function getMailHistory($data = array()){
    $result = array();

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mail_history ORDER BY id DESC LIMIT " . (int)$data['start'] . ", " . (int)$data['limit']);

    if($query->num_rows){
      foreach( $query->rows as $row )
      {
        $result[] = $row;
      }
    } else {
      return array();
    }

    return $result;
  }

  public function getTotalMailHistory() {
    $sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "mail_history WHERE 1";

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

  public function getTotalPushSubscribers() {
    $sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "push_tokents WHERE 1";

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

  public function getTotalMailSubscribers() {
    $sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "notifications WHERE 1";

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

}