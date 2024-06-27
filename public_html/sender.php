<?php 

$url = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = "AAAAf6exHZk:APA91bF-QcPGFpaMZOzySzLloOb6KSbFxLA2zBLC3lTDjnfN4ikThJN89VkpwWi1BRyhKfKi8iS0u3nlqlGWikSDk_itWKKE-S6wz9GccQp2RLyaym1hTVeQawyqV07HQ_wn5MdD0xV1";
// $YOUR_TOKEN_ID = "fNAdm285oiQ:APA91bH4QJajbyz72F7WSWnKBFXm6Rcx5jfTsji7PaOy8qlz888gtTjvgpaRO2-jK2_mTgt0NRj-uTrTKO2dC9CytUSKg2kKtu6M6NunU42pnYqvZCG1mzjcRrWn-rpVxJtYNKPUqk6T"; //chrome

$YOUR_TOKEN_ID = "c95sB3F1MJs:APA91bEkq7pKFZs6NnoxTEK4SkUrfeV_3mpRJ0EQedlLnj7OVxE-HxG0yF1RT2u8sghHEBISqTfwTqgkD49FpmCKRqKBVfwtuER0RsTjZfoxlptk_fn1BVjt_kT6fYEsyunMQYWdLO7c"; // ff

// $YOUR_TOKEN_ID = "dVRu95speqc:APA91bEzWaDkp3_zUw7DFGL7-AXIh_hBpmaeJF9ffkZCrzIjVf0iuQP5Ax82rujupMzfykQo6I6kFySl-eR1MNxD4XSQ4NOsLBLgTRZ0fQnZIgPapwcM0OUYysxWEE8DIuCR_CA0Bgb0";// opera
 


$request_body = array(
  // 'to' => $YOUR_TOKEN_ID,
  'to' => '/topics/default',
  'notification' => array(
    'title' => 'Web PUSH с vendshop.com',
    'body' => 'Body PUSH',
    'icon' => 'push-button.png',
    'click_action' => 'https://vend-shop.com/kontakty/',
  ),
);
$fields = json_encode($request_body);
 
$request_headers = array(
  'Content-Type: application/json',
  'Authorization: key=' . $YOUR_API_KEY,
);
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;