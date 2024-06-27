<?php

require_once('../config.php');
require_once('../system/startup.php');

// Registry
$registry = new Registry();

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$query = $db->query("SELECT image FROM " . DB_PREFIX . "ovs_ads WHERE 1");

$image = "";
$link = "";

if($query->num_rows){
  $image = $query->row['image'];
}


$query = $db->query("SELECT link FROM " . DB_PREFIX . "ovs_ads WHERE 1");

if($query->num_rows){
  $link = $query->row['link'];
}

if($image) {
?>

<a href="<?php echo $link ?>" target="_blank" style="position:relative;left:-8px"><img src="<?php echo HTTPS_SERVER ?>image/<?php echo $image ?>"></a>
<?php } ?>
