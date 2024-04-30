<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  

    function getDt(){
       return date('Y:m:d H:i:s');
    }

    function custDate($date){
       return date("d M Y", strtotime($date));
    }
    
    function custTime($time){
       return date("h:i A", strtotime($time));
    }

    function periodTypes(){
       return array(
          'today' => 'Today',
          'yesterday' => 'Yesterday',
          'this_week' => 'This Week',
          'last_week' => 'Last Week',
          'this_month' => 'This Month',
          'last_month' => 'Last Month',
          'this_quarter' => 'This Quarter',
          'last_quarter' => 'Last Quarter',
          'this_year' => 'This Year',
          'custom' => 'Custom',
       );
    }

    function createScript($data)
    {	
    	$scripts = "";
        foreach( $data as $js )
            {   
                $scripts .= "<script src='".base_url("/assets/js/".$js)."'></script>". "\n";
            }
            
            return $scripts;
    }  
    function createCss($data)
    {
        $cssHtml = "";
        foreach( $data as $css )
            {
                $cssHtml .= "<link rel='stylesheet' type='text/css' href='".base_url("/assets/css/".$css)."'>". "\n";
            }

            return $cssHtml;
    }    

    function dd($data){

        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }

    function d($data){

        echo "<pre>";
        print_r($data);
        echo "</pre>";

    }

    function json_validate($string)
    {   
        //d($string);
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

       echo $error;
    }


function getBrowser($browser) {

      $u_agent = $browser;
      $bname = 'Unknown';
      $platform = 'Unknown';
      $version= "";
      // First get the platform?
      if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
      } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
      } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
      }
      // Next get the name of the useragent yes seperately and for good reason
      if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
      } elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
      } elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
      } elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
      } elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
      } elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
      }else{
        $ub = '';
      }
      // finally get the correct version number
      $known = array('Version', $ub, 'other');
      $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
      if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
      }
      // see how many we have
      $i = count($matches['browser']);
      if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
          $version= $matches['version'][0];
        } else {
          $version= $matches['version'][1];
        }
      } else {
        $version= $matches['version'][0];
      }
      // check if we have a number
      if ($version==null || $version=="") {$version="?";}
    return array(
      'userAgent' => $u_agent,
      'name'      => $bname,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'    => $pattern
      );
    }



    function getProtectedProperties($obj){
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        return $array[$prefix.'_mysqli'];
    }


    function sendResponse($status, $msg,$data=""){
        header('Content-Type: application/json');
        $response = array('status' => $status, 'message' => $msg , 'data'=>$data);
        die(json_encode($response));
    }


  function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        if($d && $d->format($format) === $date){
           return true;
        }else{
          $format = 'Y-m-d h:i:s';
          return $d && $d->format($format) === $date;
        }
    }


  // Function to get all the dates in given range 
  function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      // Declare an empty array 
      $array = array(); 
        
      // Variable that store the date interval 
      // of period 1 day 
      $interval = new DateInterval('P1D'); 
    
      $realEnd = new DateTime($end); 
      $realEnd->add($interval); 
    
      $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
    
      // Use loop to store date into array 
      foreach($period as $date) {                  
          $array[] = $date->format($format);  
      } 
    
      // Return the array elements 
      return $array; 
  } 

  function hideMobile($mobile){
    return substr_replace($mobile,'*****',2,5);
  }

  function group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}


function complaint_types(){
  return array(
    '1' => 'Question',
    '2' => 'Problem',
    '3' => 'Feature',
    '4' => 'Others'
  );
}

function complaint_status_list($flag=''){

  if($flag){
    return array(
          //'0' => 'Delete',
          '1' => 'Complete',
          '2' => 'Open',
          '3' => 'Ongoing',
          '4' => 'Close',
        );
  }else{
    return array(
      //'0' => 'Deleted',
      '1' => 'Completed',
      '2' => 'Open',
      '3' => 'Ongoing',
      '4' => 'Closed',
      // '5' => 'Assigned',
    );
  }
    
}

function status_list(){
    return array(
      '1' => 'Active',
      '0' => 'Deactive',
    );
}

function enquiry_status_list(){
    return array(
      '2' => 'Open',
      '3' => 'Ongoing',
      '4' => 'Closed',
    );
}

function ps($arr,$key){
    if (isset($arr[$key])) {
       return $arr[$key];
    }
    return false;
}


function page_types(){
  return array(
    '1' => 'faq',
    '2' => 'faq_page',
    '3' => 'about',
    '4' => 'contact',
    '5' => 'news',
    '6' => 'registration',//banner
    '7' => 'login',//banner
    '7' => 'product', //banner
  );
}


function classifications(){
  return array(
      'AA' => 'TBS/TIANJIN ITEMS',
      'BB' => 'EQUIPMENT OPERATION ',
      'CC' => 'BOF ITEMS',
      'DD' => 'LACK OF MAINTENANCE BY CUSTOMER',
      'EE' => 'DAMAGED SUPPLY',
      'FF' => 'SHORT SUPPLY',
      'GG' => 'WRONG SUPPLY',
      'HH' => 'DESIGN / DRAWING ',
      'II' => 'MANUFACTURING',
      'JJ' => 'UNINTENDED USE',
      'KK' => 'WRONG SELECTION /APPLICATION',
      'LL' => 'USE OF  NON - OEM SPARES',
      'MM' => 'MATERIAL QUALITY',
      'NN' => 'INSTALLATION',
      'OO' => 'STORAGE AT SITE'
    );
}

function cap($string){
  return ucwords(strtolower($string));
}

function ticketText($ticket_no){
  return ' Ticket No. '.$ticket_no;
}


// $str The string to be truncated
// $chars The amount of characters to be stripped, can be overridden by $to_space
// $to_space boolean for whether or not to truncate from space near $chars limit

function truncateString($str, $chars, $to_space=FALSE, $replacement="...") {
   if($chars > strlen($str)) return $str;

   $str = substr($str, 0, $chars);
   $space_pos = strrpos($str, " ");
   if($to_space && $space_pos >= 0) 
       $str = substr($str, 0, strrpos($str, " "));

   return($str . $replacement);
}

function totalDaysBetTwo($later,$earlier){
  $earlier = new DateTime($earlier);
  $later = new DateTime($later);

  return $later->diff($earlier)->format("%a");
}


function partition(Array $list, $p) {
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for($px = 0; $px < $p; $px ++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}

function validateLoginTime($logintime){
    $current_time = time();

    if($logintime){
        $seconds = $current_time - $logintime; 
        // $onlyminutes = floor(($seconds / 60) % 60);
        $minutes = $seconds / 60;

        if($minutes < 16){
            sendResponse(0, 'Some error occured. Please try after some time.');
            // sendResponse(0, 'Some error occured. Please try after some time.'.$minutes);
        }
    }
    /*
        $w = $seconds / 86400 / 7;
        $d = $seconds / 86400 % 7;
        $h = $seconds / 3600 % 24;
        $m = $seconds / 60 % 60; 
        $s = $seconds % 60;
        echo "{$w} weeks, {$d} days, {$h} hours, {$m} minutes and {$s} secs away!";*/
}

function getScript($script){
    
    $data = [
        'jquery' => 'assets/dist-assets/js/plugins/jquery-3.6.0.min.js',
        'bootstrap' => 'assets/dist-assets/js/plugins/bootstrap-4.5.3-dist/js/bootstrap.bundle.min.js',
        'perfect-scrollbar' => 'assets/dist-assets/js/plugins/perfect-scrollbar-1.5.1.min.js',
    ];

    $url = (isset($data[$script])) ? $data[$script] : '';

    return '<script src="'.site_url($url).'"></script>';
}

function esc_sql($text)
{
    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $text);
}

function clean_cell_formula($string){
    $string = ltrim(trim($string), "=+-@");
    return $string;
}

function filter_unique_ticket_no($array) {
    $seen = [];
    return array_filter($array, function($item) use (&$seen) {
        if (isset($seen[$item['ticket_no']])) {
            return false;
        } else {
            $seen[$item['ticket_no']] = true;
            return true;
        }
    });
}