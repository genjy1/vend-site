<?php

if(!defined('DIR_SYSTEM')){
  die();
}

class Store 
{
  private $db = null;

  function __construct($db)
  {
    $this->db = $db;
  }

  public function getUnsendedMessages()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "push_history WHERE status=0");

    return $query->rows;
  }

  public function updateStatus($id)
  {
    $this->db->query("UPDATE " . DB_PREFIX . "push_history SET status = 1 WHERE id=" . (int)$id );
  }

  public function getApiKey($store_id = 0)
  {
    $query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = 'notification_key'");

    if ($query->num_rows) {
      return $query->row['value'];
    } else {
      return null;
    }
  }

  public function getSubscribers($topic = '')
  {
    $query = $this->db->query("SELECT DISTINCT token FROM " . DB_PREFIX . "push_tokents WHERE topic = '" . $topic . "' ");

    if ($query->num_rows)
    {
      return $query->num_rows;
    }
     else 
    {
      return 0;
    }
  }
}