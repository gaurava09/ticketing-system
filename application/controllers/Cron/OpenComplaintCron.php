<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OpenComplaintCron extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Complaint_model');
		$this->load->model('User_model');
		$this->load->model('Notification_model');
	}

	public function index() {

		$key = $this->input->get('key');
		if($key != API_KEY){
			die('Access Denied.');
		}
		
		// $list = $this->Complaint_model->get_complaints(['status' => 2,'created_at < ' => $start_date], 'id,ticket_no');
		
		$start_date = date('Y-m-d', strtotime('-3 days'));

		$list = $this->Complaint_model->get_complaints_join_company(['c.status' => 2,'c.created_at < ' => $start_date], 'c.id,c.ticket_no,co.name as company');

		// dd($list);
		
		if($list){
			$tickets = '';
			foreach ($list as $key => $value) {
				$tickets .= $value['ticket_no']. " - ".$value['company']."<br>";
			}
			// d($list);
			//mail
			$admSubject = 'Ticketing : Open Complaints';
			$admMsg 	= "
				Hello Pradeep Bhave, <br><br>

				Following complaints are waiting for response.<br><br>
				".$tickets."
				<br>
				Please process the complaints and assign an Engineer for the same.<br><br>

				Best Regards,<br>
				Team Ticketing.";

			$sendMail = $this->sendMail('BHAVE.PRADEEP@mahindra.com', $admSubject, $admMsg);
			// $sendMail = $this->sendMail('nilesh@agency09.in', $admSubject, $admMsg);

		}else{
			echo 'No Result : 3 days <bR>';
		}//end if complaints

/////////////////////////////////////////////////////////////////////////////////////////

		//AFTER 5 DAYS
		$start_date = date('Y-m-d', strtotime('-5 days'));
	
		$list = $this->Complaint_model->get_complaints_join_company(['c.status' => 2,'c.created_at < ' => $start_date], 'c.id,c.ticket_no,co.name as company');

		if($list){
			$tickets = '';
			foreach ($list as $key => $value) {
				$tickets .= $value['ticket_no']. " - ".$value['company']."<br>";
			}
			// d($list);
			//mail
			$admSubject = 'Ticketing : Open Complaints';
			$admMsg 	= "
				Hello Ravindra Vaidya, <br><br>

				Following complaints are waiting for response.<br><br>
				".$tickets."
				<br>
				Please process the complaints and assign an Engineer for the same.<br><br>

				Best Regards,<br>
				Team Ticketing.";

			$sendMail = $this->sendMail('VAIDYA.RAVINDRA@mahindra.com', $admSubject, $admMsg);

		}else{
			echo 'No Result : 5 days <br>';
		}//end if complaints

		$log  = 'open_complaints '.date("F j, Y, g:i a").PHP_EOL;
		file_put_contents(CRON_LOG_DIR.'cronlog_complnt.log', $log, FILE_APPEND);
	}

}

?>