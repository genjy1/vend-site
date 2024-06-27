<?php
if(!defined('DIR_SYSTEM')){
  die();
}

class Fcm
{
  private $apiKey = null;
  private $url = 'https://fcm.googleapis.com/fcm/send';

  function __construct($apiKey)
  {
    $this->apiKey = $apiKey;
  }

  public function push( $message )
  {
    $request_body = array(
      'to' => '/topics/' . $message['topic'],
      // 'to' => 'c7Wsq5zmXNc:APA91bHMvkBomtMvcXz-RWE3bcAkhC-RO1QIQcvsY8cD1U1lk5WheHFkMSpHuG1BK5WP0tdMzOaAP6p95yy3-TwWICpAiqxJJejtCHpyhBTqu_FhurF3DKi9N-uWS0-645GgCHxQBLS8', // opera
      // 'to' => 'cYk-zOoUcTQ:APA91bFZcZ9xdV6REwcnDSrSFBk-vy4N9jQJbKwBcq9Z3hiQnF20WkR8slnO4zcPyORHcrCxUvJu00fQmJHgrjFqvTlp82JIXKLhqKLdMcyMsIXw_Kz0qe90JtkdY1M61v2n9ude-lZV', // alina
      // 'to' => 'fulJy2tkrfs:APA91bENR1vgpp974Blh8BIwo2tLcc9wO0FbXlLDriDWS3t5AsjbmQzoPzAyNztbxm95FvgZ3YdD0pRjokj6E8KPMIdgGBsoAPv3E5FCLVJI3EVs6j8ZkTHlZJZ4x_7U3Lgy016o1kkE', // alina tel
      // 'to' => 'ewbpYYPro7o:APA91bGrTjT-vsVWU5Q7RzLkThVHzMtBWN-b9xnXHNxaF9PKxvtusLML-9wWVoG3hlKJ2Yl1P_Wio6151S04jT0_N7Fc8NIlogRVY6HnK2ZdkWeLd0xpYUJvPfqsB7mcLCaLZPAuUwEc', // my tel
      'notification' => array(
        'title' => $message['title'],
        'body' => $message['body'],
        'icon' =>  $message['icon'],
      ),
      'data' => array( 
        'notification' => array(
        'title' => $message['title'],
        'body' => $message['body'],
        'icon' => $message['icon'],
      )),
    );

    if(isset($message['url']) && $message['url'] != ''){
      $request_body['notification']['click_action'] = $message['url'];
    }

    $fields = json_encode($request_body);
 
    $request_headers = array(
      'Content-Type: application/json',
      'Authorization: key=' . $this->apiKey,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);

  }

  public function subscribe($token, $topic)
  {

    $request_headers = array(
      'Content-Type: application/json',
      'Authorization: key=' . $this->apiKey,
    );

    $fields = json_encode(array());

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://iid.googleapis.com/iid/v1/" . $token . "/rel/topics/" . $topic);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }
}