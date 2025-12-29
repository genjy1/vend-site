<?php

class ModelInformationVideos extends Model
{
    public function getVideos() {
        // Автоматически использует префикс из config.php
        $query = $this->db->query("
    SELECT DISTINCT 
        p.product_id, 
        p.video, 
        pd.name
    FROM " . DB_PREFIX . "product AS p
    JOIN " . DB_PREFIX . "product_description AS pd 
        ON pd.product_id = p.product_id
    WHERE p.video LIKE '%youtube%'
");
        return $query->rows;
    }

    public function setVideo()
    {
        
    }
}