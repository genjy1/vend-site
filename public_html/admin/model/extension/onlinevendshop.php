<?php
class ModelExtensionOnlinevendshop extends Model {


  public function edit($data)
  {
    $this->db->query("DELETE FROM " . DB_PREFIX . "ovs_ads WHERE 1");
    $this->db->query("INSERT INTO " . DB_PREFIX . "ovs_ads SET image = '" . $this->db->escape($data['image']) . "', link='" . $this->db->escape($data['link']) . "'");
  }

  public function getImage(){
    $image = "";

    $query = $this->db->query("SELECT image FROM " . DB_PREFIX . "ovs_ads WHERE 1");

    if($query->num_rows){
      return $query->row['image'];
    }

    return $image;
  }

public function getLink(){
    $link = "";

    $query = $this->db->query("SELECT link FROM " . DB_PREFIX . "ovs_ads WHERE 1");

    if($query->num_rows){
      return $query->row['link'];
    }

    return $link;
  }


}