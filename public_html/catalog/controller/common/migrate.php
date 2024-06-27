<?php
class ControllerCommonMigrate extends Controller {
	private $flip = array();
	private $names = array();

	public function index() {
		echo '<meta charset="utf-8">';
		$this->check();
		exit();
	}

    private function check(){
        $data = $this->db->getRows("SELECT * FROM oc_product_image WHERE image LIKE '%renamed%'");
        foreach ($data as $row) {
            $image = $row['image'];
            $image = explode("/", $image);

            $image = end($image);
            $this->db->query("UPDATE oc_product_image SET image = '" .$image. "' WHERE product_image_id = " . $row['product_image_id']);
        }
    }

	private function c1heck(){
		$data = $this->db->getRows("SELECT * FROM oc_product WHERE 1");
		
		if ($handle = opendir(DIR_IMAGE)) {
			echo "Файлы:\n";
			$i = 0;
			$size = 0;
		    while (false !== ($file = readdir($handle))) { 
		    	if( $file=="." || $file=="..") continue;
		    	$is = false;
        		$data = $this->db->getRow("SELECT * FROM oc_product WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_product_image WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_banner_image WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_category WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_download WHERE filename = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_gallery_items WHERE value = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_option_value WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_post_categories WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_post WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_spare_images WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_slides WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", $file);
        		if(!empty($data)){
        			$is = true;
        		}

        		if(!$is){
        			$size += filesize(DIR_IMAGE.$file);
        			// echo $file . "\n";
        			// unlink(DIR_IMAGE.$file);
        		}

        		// if(filesize(DIR_IMAGE.$file) == 0){
        			// unlink(DIR_IMAGE.$file);
        		// 	echo $file . "\n";
        		// }
        		$i++;
    		}
    		$size = $this->formatBytes($size);
    		echo "\n fs root: ". $size ."\n";
    		echo $i;

    		closedir($handle); 
		}

		if ($handle = opendir(DIR_IMAGE."photos/")) {
			echo "Файлы:\n";
			$i = 0;
			$size = 0;
		    while (false !== ($file = readdir($handle))) { 
		    	if( $file=="." || $file=="..") continue;
		    	$is = false;
        		$data = $this->db->getRow("SELECT * FROM oc_product WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_product_image WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_banner_image WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_category WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_download WHERE filename = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_gallery_items WHERE value = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_option_value WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_post_categories WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_post WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		$data = $this->db->getRow("SELECT * FROM oc_spare_images WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_slides WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}
        		$data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", "photos/".$file);
        		if(!empty($data)){
        			$is = true;
        		}

        		if(!$is){
        			$size += filesize(DIR_IMAGE."photos/".$file);
        			// echo $file . "\n";
        			$i++;
        			// unlink(DIR_IMAGE."photos/".$file);
        		}
        		
    		}
    		$size = $this->formatBytes($size);
    		echo "\n fs: ". $size ."\n";
    		echo $i;

    		closedir($handle); 
		}

		if ($handle = opendir(DIR_IMAGE."catalog/")) {
            echo "Файлы:\n";
            $i = 0;
            $size = 0;
            while (false !== ($file = readdir($handle))) { 
                if( $file=="." || $file=="..") continue;
                $is = false;
                $data = $this->db->getRow("SELECT * FROM oc_product WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_product_image WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_banner_image WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_category WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_download WHERE filename = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_gallery_items WHERE value = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_option_value WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }
                $data = $this->db->getRow("SELECT * FROM oc_post_categories WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }
                $data = $this->db->getRow("SELECT * FROM oc_post WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                $data = $this->db->getRow("SELECT * FROM oc_spare_images WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }
                $data = $this->db->getRow("SELECT * FROM oc_slides WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }
                $data = $this->db->getRow("SELECT * FROM oc_promo WHERE image = ?s", "catalog/".$file);
                if(!empty($data)){
                    $is = true;
                }

                if(!$is){
                    $size += filesize(DIR_IMAGE."catalog/".$file);
                    echo $file . "\n";
                    $i++;
                    // unlink(DIR_IMAGE."catalog/".$file);
                }
                
            }
            $size = $this->formatBytes($size);
            echo "\n fs: ". $size ."\n";
            echo $i;

            closedir($handle); 
        }
	}


	private function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

	private function fiximages(){
		$rows = $this->db->getRows("SELECT * FROM __product");
		foreach ($rows as $row) {
			// $p = $this->db->getRow("SELECT * FROM __product_description WHERE product_id=?i AND language_id = 1", $row['product_id']);

			// $name = $p['name'];
			// $name = explode(" ", $name);
			// $name = implode("_", $name);
			$p = $this->db->getRow("SELECT * FROM __url_alias WHERE query=?s", "product_id=".$row['product_id']);
			$image = $row['image'];
			$ext = explode(".", $image);
			$ext = end($ext);
			$p['keyword'] = str_replace("’","",$p['keyword']);
			$p['keyword'] = str_replace("'","",$p['keyword']);
			$newimage = "catalog/" . $p['keyword'] . "." . $ext;
			// echo $image;
			// echo "  - " . $row['product_id'] . "  -   ";
			// echo "  $newimage";
			// echo "\n";
			if(file_exists(DIR_IMAGE1 . $image)){
				copy(DIR_IMAGE1 . $image, DIR_IMAGE . $image);
				rename(DIR_IMAGE . $image, DIR_IMAGE . $newimage);
				$this->db->query("UPDATE __product SET image = '" . $newimage ."' WHERE product_id = " . $row['product_id']);
			}

			$k = 1;
			$images = $this->db->getRows("SELECT * FROM __product_image WHERE product_id = ?i", $row['product_id']);
			foreach ($images as $val) {
				$ext = explode(".", $image);
				$explode = end($ext);
				$ext = end($ext);
				$newimage1 = "catalog/" . $p['keyword'] . "-$k" . ".".$ext;
				if(file_exists(DIR_IMAGE1 . $val['image'])){
					$this->db->query("UPDATE __product_image SET image = '" . $newimage1 ."' WHERE product_id = " . $row['product_id'] . " AND image = ?s", $val['image']);
					copy(DIR_IMAGE1 . $val['image'], DIR_IMAGE . $val['image']);
					rename(DIR_IMAGE . $val['image'], DIR_IMAGE . $newimage1);
					$k++;
				}
			}
			// sleep(1);
		}
	}



	private function fixim(){
		$data = $this->db->getRows("SELECT * FROM __product WHERE 1");
		foreach ($data as $key => $value) {
			$image = preg_replace("/( )/", "_", $value['image']);
			$image = $this->translit($image);
			$this->db->query("UPDATE __product SET image=?s WHERE product_id=?i", $image, $value['product_id']);
		}
	}


	private  function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', '_');
    return str_replace($rus, $lat, $str);
  }

}