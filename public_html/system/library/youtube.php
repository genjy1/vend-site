<?php
class Youtube {

	public function __construct($registry) {
		$this->db = $registry->get('db');
	}

  public function update($token = false){
    $response = $this->getResponse($token);


    $query = $this->db->query("SELECT videoid FROM " . DB_PREFIX . "gallery_items WHERE set_id = 11");

    $lastAddedVideoIDs = [];

    foreach ($query->rows as $row) {
      $lastAddedVideoIDs[] = $row['videoid'];
    }
    

    $response = json_decode($response);

    foreach ($response->items as $key => $item) {
      if(!isset($item->id->videoId)){
        continue;
      }
      $videoID = $item->id->videoId;

      if(!in_array($videoID, $lastAddedVideoIDs) && $videoID != null){

        $image = $this->loadImage($videoID, $item->snippet->thumbnails->high->url);

        $this->addVideo($videoID, $image, $item->snippet->title);
      }
    }

    if(isset($response->nextPageToken) && $response->nextPageToken != ''){
      $this->update($response->nextPageToken);
    }

  }

  private function getResponse($token = false){
    if(!$token){
      return file_get_contents("https://www.googleapis.com/youtube/v3/search?key=AIzaSyCQAZZhu5wRoX-aMt8aN7fdAxl0qa4xAsA&channelId=UC2KtzXJGNe2iRq5KD7fMVdA&part=snippet,id&order=date&maxResults=20");
    } else {
      return file_get_contents("https://www.googleapis.com/youtube/v3/search?key=AIzaSyCQAZZhu5wRoX-aMt8aN7fdAxl0qa4xAsA&channelId=UC2KtzXJGNe2iRq5KD7fMVdA&part=snippet,id&order=date&maxResults=20&pageToken=" . $token);
    }
  }

  public function addVideo($videoID, $image, $title){
    $query = $this->db->query("INSERT INTO " . DB_PREFIX . "gallery_items (set_id, value, sort, caption, videoid) VALUES ('11','" . $this->db->escape($image) . "','0', '" . $this->db->escape($title) . "', '" . $this->db->escape($videoID) . "')");
  }

  public function loadImage($videoID, $url){
    $ext = explode("/", $url);
    
    $file = "catalog/" . $videoID . end($ext);
    $file = preg_replace("/( )/", "_", $file);
    $file = preg_replace("/(20%)/", "_", $file);
    
    file_put_contents(DIR_IMAGE . $file, file_get_contents($url));

    return $file;
  }

}
