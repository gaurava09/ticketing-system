<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationCron extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Notification_model');
	}

	public function deleteOld() {
		$key = $this->input->get('key');
		if($key != API_KEY){
			die('Access Denied.');
		}

		$start_date = date('Y-m-d', strtotime('-10 days'));
	
		$where = ['created_at < ' => $start_date];

		// $list = $this->Notification_model->get_notifications($where);
		// dd($list);

		$this->Notification_model->delete_notification($where);
		echo 'done';

		$log  = 'notifications '.date("F j, Y, g:i a").PHP_EOL;
		file_put_contents(CRON_LOG_DIR.'cronlog_noti.log', $log, FILE_APPEND);
		
	}

}

?>