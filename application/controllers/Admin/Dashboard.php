<?php
//https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends My_Controller 
{

	public function __construct()
	{
		parent::__construct();

		if(!$this->is_logged_in()){
			redirect($this->CUST_LOGIN);
		}

		if($this->is_customer()){
			redirect($this->CUST_HOME);
		}

		$this->load->model('Enquiry_model');
		$this->load->model('Complaint_model');
		$this->load->model('Customer_model');
		$this->load->model('Equipment_model');
	}


	public function index(){
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$this->adminDashboard();
		}
		else if($this->role == 'employee'){
			$this->employeeDashboard();
		}
		else if($this->role == 'sales'){
			$this->salesDashboard();
		}
	}
	
	private function adminDashboard(){
		$this->load->model('Feedback_model');

		//enquiry
		$open_enquiry  = $this->Enquiry_model->count(['status' => 2]);
		$att_enquiry  = $this->Enquiry_model->count(['status !=' => 2]);
		
		//complaint
		$open  = $this->Complaint_model->count(['status' => 2]);
		$ongoing  = $this->Complaint_model->count(['status' => 3]);
		$completed  = $this->Complaint_model->count(['status' => 1]);
		$closed  = $this->Complaint_model->count(['status' => 4]);

		//latest ticket
		$columns = 'id,ticket_no,ga_no,complaint_type,customer_id,status,created_at';
		$tickets = $this->Complaint_model->get_complaints([],$columns,'0','5');

		foreach ($tickets as $key => $value) {
			$customer = $this->Customer_model->get_customer_details(['c.id' => $value['customer_id']]);
	
			$tickets[$key]['company'] 		= ($customer) ? $customer['company_name'] : '';

			$tickets[$key]['created_at'] 	= custDate($value['created_at']);
		}

		//new customers
		$columns = 'id,first_name,last_name,email,profile_picture';
		$customers = $this->Customer_model->get_customers([],$columns,'0','5');

	/*	$result = array(
			'total_enquiry' => $total_enquiry,
			'open_ticket' => $open,
			'ongoing_ticket' => $ongoing,
			'completed_ticket' => $completed,
			'closed_ticket' => $closed,
			'latest_ticket' => $tickets,
			'customers' => $customers,
		);*/

		//new enquiry
		$columns 	= 'e.id,e.ga_no,e.spareparts,e.created_at,e.customer_id,c.company_id,cm.name as company';
		$enquiries  = $this->Enquiry_model->get_enquiries_by_join([],$columns,'0','5');
		foreach ($enquiries as $key => $value) {

			$equipment = $this->Equipment_model->get_equipemnt_details_by_project(['p.ga_no' => $value['ga_no']], 'e.name');

			$enquiries[$key]['equipment'] 	= ($equipment) ? $equipment['name'] : '';
			$enquiries[$key]['created_at'] 	= custDate($value['created_at']);
		}

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
		$complaint_data = $this->Complaint_model->monthWiseData();
		foreach ($complaint_data as $key => $value) {
			$month = $value['month'];
			if(isset($months_arr[$month])){
				$months_arr[$month] = $value['count'];
			}
		}
		// d($this->pq());
		// dd($dd);
		// SELECT COUNT(id) as Count,DATE_FORMAT(created_at, '%b') as 'Month Name' FROM complaint WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY YEAR(created_at),MONTH(created_at)

		//complaint types
		$complaint_types = complaint_types();
		$types_data = [];
		foreach ($complaint_types as $key => $type) {
			$count = $this->Complaint_model->count(['complaint_type' => $key]);
			$types_data[] = ['value' => $count,'name' => $type];
		}

		//pending feedbacks
		$feedbacks  = $this->Feedback_model->count(['status'=>1]);

		$pending_feedback = $closed - $feedbacks;
		// dd($feedbacks);


		$result = array(
			// 'total_enquiry' => $total_enquiry,
			'open_enquiry' => $open_enquiry,
			'att_enquiry' => $att_enquiry,
			'open_ticket' => $open,
			'ongoing_ticket' => $ongoing,
			'completed_ticket' => $completed,
			'closed_ticket' => $closed,
			'latest_ticket' => $tickets,
			'customers' => $customers,
			'enquiries' => $enquiries,
			'complaint_data' => $months_arr,
			'complaint_types' => $types_data,
			'pending_feedback' => $pending_feedback,
		);

		// dd($result);

		$data 					= array();
		$data['template'] 		= 'ad_dashboard';
		$data['title'] 			= "Dashboard";
		$data['data'] 			= $result;
		$this->load->view('default', $data);
	}//end function

	private function employeeDashboard(){
		$whereArr = array(
			'h.emp_id' => $this->userid, 
			'h.type' => 'assign'
			// 'c.status !=' => 0
		);

		$columns = 'c.id';

		//complaint
		// $assigned  = $this->Complaint_model->get_complaints_for_emp(['h.emp_id' => $this->userid,'h.type' => 'assign','c.status !=' => 0],$columns);
		
		$assigned  = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);

		$whereArr['c.status'] = 2;
		$open  = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);
		$open = count($open);
		
		$whereArr['c.status'] = 3;
		$ongoing  = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);
		$ongoing = count($ongoing);

		$whereArr['c.status'] = 1;
		$completed  = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);
		$completed = count($completed);

		$whereArr['c.status'] = 4;
		$closed  = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);
		$closed = count($closed);
		// d($this->pq());

		//ongoing complaint handled by employee
		unset($whereArr['c.status']);
		// $whereArr['c.status'] = 3;


        $columns = 'c.id,a.emp_id,c.status';

		$action_taken  = $this->Complaint_model->count_remarked_pending_ticket_count(['a.emp_id' => $this->userid , 'a.status' => 1],$columns);

		//get action not taken
		$pending_action  = $this->Complaint_model->count_remarked_pending_ticket_count(['a.emp_id' => $this->userid , 'a.status' => 0],$columns);

		$total_assigned = count($assigned);

		$count_pending = count($pending_action);
		$count_action = count($action_taken);


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
	    
		$complaint_data = $this->Complaint_model->monthWiseDataEmployee($whereArr);
		// d($complaint_data);
		// dd($this->pq());
		foreach ($complaint_data as $key => $value) {
			$month = $value['month'];
			if(isset($months_arr[$month])){
				$months_arr[$month] = $value['count'];
			}
		}
		// d($this->pq());
		// dd($dd);

		//complaint types


		$complaint_types = complaint_types();
		$types_data = [];
		foreach ($complaint_types as $key => $type) {

			if($key == 1){
				$types_data[] = ['value' => $completed,'name' => $type];
			}
			else if($key == 2){
				$types_data[] = ['value' => $open,'name' => $type];
			}
			else if($key == 3){
				$types_data[] = ['value' => $ongoing,'name' => $type];
			}
			else if($key == 4){
				$types_data[] = ['value' => $closed,'name' => $type];
			}
			

			/*$whereArr['complaint_type'] = $key;
			$result = $this->Complaint_model->get_complaints_for_emp($whereArr,$columns);

			$count = ($result) ? $result[0]['total'] : '0';
			$types_data[] = ['value' => $count,'name' => $type];*/
		}

		$result = array(
			// 'open_ticket' => $open,
			// 'ongoing_ticket' => $ongoing,
			'completed_ticket' => $completed,
			'closed_ticket' => $closed,
			'complaint_data' => $months_arr,
			'complaint_types' => $types_data,
			'pending_count' => $count_pending,
			'action_taken' => $count_action,
			'assigned' => count($assigned),
		);

		// dd($result);
		

		$data 				= array();
		$data['template'] 	= 'emp_dashboard';
		$data['title'] 		= "Dashboard";
		$data['data'] 		= $result;
		$this->load->view('default', $data);
	}


	private function salesDashboard(){
		$this->load->model('Feedback_model');

		//enquiry
		$open_enquiry  = $this->Enquiry_model->count(['status' => 2]);
		$att_enquiry  = $this->Enquiry_model->count(['status !=' => 2]);
		
		//new enquiry
		$columns 	= 'e.id,e.ga_no,e.spareparts,e.created_at,e.customer_id,c.company_id,cm.name as company';
		$enquiries  = $this->Enquiry_model->get_enquiries_by_join([],$columns,'0','5');
		foreach ($enquiries as $key => $value) {

			$equipment = $this->Equipment_model->get_equipemnt_details_by_project(['p.ga_no' => $value['ga_no']], 'e.name');

			$enquiries[$key]['equipment'] 	= ($equipment) ? $equipment['name'] : '';
			$enquiries[$key]['created_at'] 	= custDate($value['created_at']);
		}

		//enquiry graph data
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
		$enquiry_data = $this->Enquiry_model->monthWiseData();
		foreach ($enquiry_data as $key => $value) {
			$month = $value['month'];
			if(isset($months_arr[$month])){
				$months_arr[$month] = $value['count'];
			}
		}
		// d($this->pq());
		// dd($dd);
		// SELECT COUNT(id) as Count,DATE_FORMAT(created_at, '%b') as 'Month Name' FROM complaint WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY YEAR(created_at),MONTH(created_at)

		

		$result = array(
			'open_enquiry' => $open_enquiry,
			'att_enquiry' => $att_enquiry,
			'enquiries' => $enquiries,
			'enquiry_data' => $months_arr,
		);

		// dd($result);

		$data 					= array();
		$data['template'] 		= 'sales_dashboard';
		$data['title'] 			= "Dashboard";
		$data['data'] 			= $result;
		$this->load->view('default', $data);
	}//end function

}

?>