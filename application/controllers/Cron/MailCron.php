<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MailCron extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Jobs_model');
	}

	public function News() {
		$key = $this->input->get('key');
		if($key != API_KEY){
			die('Access Denied.');
		}
		

		$limit = 1;
		$news = $this->Jobs_model->get_job(['type' => 'news'],$limit);

		// dd($news);
		if($news){

			$bcc_emails = json_decode($news['bcc_email'],true);
			
			$sendMail = $this->sendMail($news['to_email'], $news['subject'], $news['message'],FALSE,FALSE,FALSE,$bcc_emails);

			$this->Jobs_model->delete_job(['id' => $news['id']]);

			echo 'done';
		}else{
			echo 'No Result';
		}

		$log  = 'News '.date("F j, Y, g:i a").PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents(CRON_LOG_DIR.'cronlog_news.log', $log, FILE_APPEND);

	}

}

?>