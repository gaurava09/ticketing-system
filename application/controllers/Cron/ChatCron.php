<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatCron extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Chat_model');
	}

	public function index() {
		$key = $this->input->get('key');
		if($key != API_KEY){
			die('Access Denied.');
		}

		$result = $this->Chat_model->get_latest_messages_for_cron();
		if($result){
			foreach ($result as $key => $value) {
				//mail
				$subject = 'Ticketing : Chat Notification';

				if($value['sender'] == 'customer'){
					$message 	= "Hello ".cap($value['emp_name']).", <br><br>";
					$to_email 	= $value['emp_email'];
				}else{
					$message 	= "Hello ".cap($value['cust_name']).", <br><br>";
					$to_email 	= $value['cust_email'];
				}
				
				

				$message .="You have a new message. Please check customer support portal for further details.<br><br>

					Best Regards,<br>
					Ticketing.";

				$sendMail = $this->sendMail($to_email, $subject, $message);
			}
			echo 'done';
		}else{
			echo 'No Result';
		}

		$log  = 'News '.date("F j, Y, g:i a").PHP_EOL;
		file_put_contents(CRON_LOG_DIR.'cronlog_chat.log', $log, FILE_APPEND);

		// dd($result);
	}

}

?>