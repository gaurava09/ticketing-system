<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function curlPost($apiurl, $data, $headerArr='')
  {
      if(empty($headerArr)){
      	$headerArr = array(
            "Content-Type: application/x-www-form-urlencoded",
          );
      }

      $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
         // CURLOPT_POSTFIELDS =>"{\"params\":{}}",
        CURLOPT_HTTPHEADER => $headerArr
    ));

    $response = curl_exec($curl);

    if ($response === false) {
      $response = curl_error($curl);
    }

  // echo stripslashes($response);
    // print_r($response);
    curl_close($curl);
    return $response;
  }

  function curlGet($apiurl)
  {
      $curl = curl_init($apiurl);
 
      curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          // Set Here Your Requesred Headers
            'Content-Type: application/json',
        ),
      ));
            
      $response = curl_exec($curl);

      if ($response === false) {
        $response = curl_error($curl);
      }

      curl_close($curl);
      return $response;
  }


  	function sendSMS($mobile,$msg){
     	$mobile = "91".$mobile;

     	$msg1 = urlencode($msg);
      $msg2 = urlencode('add your msg');

      $msg = $msg1.'+%0A+'.$msg2;
      
     	$apiurl = "http://mobile1.ssexpertsystem.com/vendorsms/pushsms.aspx?user=".SMS_USER."&password=".SMS_PASSWORD."&msisdn=".$mobile."&sid=".SMS_SENDER."&msg=".$msg."&fl=0&gwid=2";

     	$res = curlGet($apiurl);

     	return $res;
    }

function sendPushNotification($registrationIds, $data){
    // $registrationIds[0] = 'cTCTR6BKm_w:APA91bHjGsuEi2xYQDZXxcIuVLJzbzFVLNpXMPb1-hgshe7wGPFp5KOWPJHqbftACPn6YR27ED9JVjSMkqnjm8cYG0N6OvGD1IxL_yTj19DSmpoZ5XYMxGUlObA5IVcaA_5ESvbv76bg';
	if(empty($registrationIds)){
		die();
	}

    $header = [
        'Authorization: Key=' . SERVER_API_KEY,
        'Content-Type: Application/json'
    ];

    $msg = [
        'title'         => $data['title'],
        'message'       => $data['description'],
        // "style"         => "picture",
        // "picture"       => 'https://sasapa.synergyace.in/admin/assets/images/fav.jpg',   
    ];

    $payload = [
        'registration_ids'  => $registrationIds,
        'data'              => $msg
    ];
    $payload = json_encode( $payload );

    $apiurl = "https://fcm.googleapis.com/fcm/send";
    $response = curlPost($apiurl, $payload, $header);

    return $response;
    /*$msg = [
        'title'         => 'Testing Notification',
        'body'          => 'Testing Notification from body',
        'message'       => 'Testing Notification from message',
        //'icon'          => 'https://sasapa.synergyace.in/admin/assets/images/fav.jpg',
        //'image' => 'http://u1-services.agencymoksa.in/www/images/drawable-land-hdpi-screen.png',
        "style"         =>"picture",
        "picture"       => 'https://sasapa.synergyace.in/admin/assets/images/fav.jpg',   
        'summaryText'   =>"Testing Notification from summary text"  
    ];*/
}