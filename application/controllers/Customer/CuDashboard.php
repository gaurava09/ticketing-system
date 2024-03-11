<?php
//https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
defined('BASEPATH') OR exit('No direct script access allowed');

class CuDashboard extends My_Controller 
{

	public function __construct()
	{
		parent::__construct();

		if(!$this->is_logged_in()){
			redirect($this->CUST_LOGIN);
		}
		$this->is_customer(1);

		$this->load->model('Complaint_model');
		$this->load->model('Customer_model');
		$this->load->model('Pages_model');
		$this->load->model('Feedback_model');
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Enquiry_model');
	}


	public function index(){

		//complaint
		$open  	= $this->Complaint_model->count(['status' => 2, 'customer_id' => $this->userid]);
		$ongoing  = $this->Complaint_model->count(['status' => 3, 'customer_id' => $this->userid]);
		$completed  = $this->Complaint_model->count(['status' => 1, 'customer_id' => $this->userid]);
		$closed  = $this->Complaint_model->count(['status' => 4, 'customer_id' => $this->userid]);

		//complaint graph data
		$months_arr = array(
	        "Jan"   => 0,
	        "Feb"   => 0,
	        "Mar"   => 0,
	        "Apr"   => 0,
	        "May"   => 0,
	        "Jun"   => 0,
	        "Jul"   => 0,
	        "Aug"   => 0,
	        "Sep"   => 0,
	        "Oct"   => 0,
	        "Nov"   => 0,
	        "Dec"   => 0
	    );
		$complaint_data = $this->Complaint_model->monthWiseData(['customer_id' => $this->userid]);
		foreach ($complaint_data as $key => $value) {
			$month = $value['month'];
			if(isset($months_arr[$month])){
				$months_arr[$month] = $value['count'];
			}
		}
		// d($this->pq());
		// dd($dd);
		// SELECT COUNT(id) as Count,DATE_FORMAT(created_at, '%b') as 'Month Name' FROM complaint WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY YEAR(created_at),MONTH(created_at)

		$pending_feedbacks  = $this->Feedback_model->get_feedbacks_by_join(
			[
				'c.customer_id' => $this->userid,
				'c.status' => 4,
				'f.complaint_id' => NULL
			],
			'c.id,c.ticket_no',
			FALSE,
			FALSE,
			FALSE,
			'right'
		);

		if($pending_feedbacks){
			foreach ($pending_feedbacks as $key => $value) {
				$history = $this->ComplaintHistory_model->get_complaint_history(
					[
						'complaint_id' => $value['id'],
						'type' => 'remark'
					],
					'prev_status,new_status,created_at',
					['limit' => 1, 'order_by' => ['id' => 'desc']]
				);
				// d($this->pq()); d($history);
				if($history){
					$pending_feedbacks[$key]['closed_date'] = custDate($history['created_at']);
				}
			}
		}
		// d($this->pq()); dd($pending_feedbacks);

		//enquiry
		$open_enquiry  = $this->Enquiry_model->count(['customer_id' => $this->userid,'status' => 2]);
		$att_enquiry  = $this->Enquiry_model->count(['customer_id' => $this->userid,'status !=' => 2]);

		$result = array(
			'open_enquiry' => $open_enquiry,
			'att_enquiry' => $att_enquiry,
			'open_ticket' => $open,
			'ongoing_ticket' => $ongoing,
			'completed_ticket' => $completed,
			'closed_ticket' => $closed,
			'complaint_data' => $months_arr,
			'pending_feedbacks' => $pending_feedbacks,
		);

		$page = $this->Pages_model->get_page(['page_type' => 'product']);

		//latest news
		$whereArr = array('page_type' => 'news', 'status' => 1);
		$columns = 'id,title';
		$news  = $this->Pages_model->get_pages($whereArr, $columns, '0', '5');


		// dd($result);

		$data 				= array();
		$data['template'] 	= 'cu_dashboard';
		$data['title'] 		= "Dashboard";
		$data['data'] 		= $result;
		$data['product']	= $page;
		$data['news']		= $news;
		$this->load->view('default', $data);
	}//end function

}

?>