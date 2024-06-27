<?php

if(!defined('DIR_SYSTEM')){
  die("check it");
}

require_once('library/store.php');
require_once('library/fcm.php');

class Pusher 
{
  
  // private $registry = null;
  private $store = null;
  private $fcm = null;
  private $model_tool_image = null;

  public function __construct($registry) 
  {
    $this->store = new Store($registry->get('db'), $registry->get('config'));

    $apiKey = $this->store->getApiKey();

    $this->fcm = new Fcm($apiKey);

    $registry->get('load')->model('tool/image');

    $this->model_tool_image = $registry->get('model_tool_image');
  }

  public function send()
  {
    $result = array();

    $messages = $this->store->getUnsendedMessages();

    foreach ($messages as $message) 
    {
      $message['icon'] = $this->model_tool_image->resize($message['icon'], 250, 250);

      $response = $this->fcm->push($message);

      if(isset($response->message_id)){

        $result['subscribers'] = $this->store->getSubscribers($message['topic']);

        $this->store->updateStatus($message['id']);
      }
    }

    return $result;
  }

  public function subscribe($token, $topic)
  {
    $this->fcm->subscribe($token, $topic);
  }

}