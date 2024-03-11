<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}


	public function index() {
   /* echo ENVIRONMENT;
    echo '<br>';
    echo SMTP_HOST;echo '<br>';
    echo SMTP_USERNAME;
    echo '<br>';
    echo SMTP_PASSWORD;

    echo '<br>';
    echo '<br>';
    echo '<br>';*/

    die();

/*	die;
	$ip = $this->input->ip_address();
echo $ip;
die;	*/
		$a = $this->sendMail('nilesh@agency09.in','tsubaki','Test msg');
		dd($a);
	}

}

?>