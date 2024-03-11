<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);
		
		$this->load->model('User_model');
		$this->load->model('Customer_model');
		$this->load->model('Notification_model');
	}


	public function getNotifications() {	
		$start_date = date('Y-m-d', strtotime('-7 days'));
		$end_date = date('Y-m-d', strtotime('+1 days'));

		$whereArr = array(
				'user_id' => $this->userid,
				// 'is_read' => 0,
				'user_type' => $this->role,
				'created_at >' => $start_date,
				'created_at <' => $end_date,
			);

		

		// $list  = $this->Notification_model->get_notifications($whereArr, $columns, $startrow, $rowperpage , $likeArr);
		$list  = $this->Notification_model->get_notifications($whereArr);

		$today = date('Y-m-d');

		$data = [];
		foreach ($list as $key => $value) {
			$notiDate = date('Y-m-d',strtotime($value['created_at']));

	        if($notiDate == $today){
	           // $date_time = custTime($value['created_at']);
	           $date_time =  $this->humanTiming(strtotime($value['created_at'])) .' ago';
	        }else{
	           $date_time = custDate($value['created_at']) .', '.custTime($value['created_at']);
	        }
			$list[$key]['customdate'] 	= $date_time;

			$link = '';
			$title = strtolower($value['title']);
			if($title == 'complaint' || $title == 'assign'){
				if($this->role == 'customer'){
					$link = site_url('customer/complaint/'.$value['context_id']);
				}else{
					$link = site_url('complaint/'.$value['context_id']);
				}
				
			}else if($title == 'message'){
				$link = site_url('chat/'.$value['context_id']);
			}
			else if($title == 'news'){
				$link = site_url('news/'.$value['context_id']);

				$value['description'] = truncateString($value['description'], 60);
			}
			else if($title == 'feedback'){
				$link = site_url('feedback/'.$value['context_id']);
			}
			else if($title == 'enquiry' && $this->role == 'customer'){
				$link = site_url('customer/enquiry/'.$value['context_id']);
			}
			else if($title == 'enquiry' && $this->role != 'customer'){
				$link = site_url('enquiry/'.$value['context_id']);
			}

			$data[] = array(
				'id' => $value['id'],
				'message' => $value['description'],
				'time' => $date_time,
				'link' => $link,
				'is_read' => $value['is_read'],
			);
		}


		// dd($data);
		sendResponse(1, 'Success', $data);
	} //end function

	private function humanTiming ($time)
        {

            $time = time() - $time; // to get the time since that moment
            $time = ($time<1)? 1 : $time;
            $tokens = array (
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );

            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
            }

    }

	public function markRead($notId) {	
		$whereArr = array(
			'user_id' => $this->userid,
			'is_read' => 0,
			'user_type' => $this->role,
			'id' => $notId,
		);

		$notification  = $this->Notification_model->get_notification($whereArr);

		if($notification){
			$data = ['is_read' => 1];
			$update = $this->Notification_model->update_notification($whereArr,$data);

			if($update){
				sendResponse(1,'Success');
			}else{
				sendResponse(0,' Failed');
			}
		}
	} //end function


}

?>