<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CuFeedback extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_customer(1,$this->CUST_LOGIN);

		$this->load->model('Customer_model');
		$this->load->model('User_model');
		$this->load->model('Project_model');
		$this->load->model('Complaint_model');
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Feedback_model');
	}

	public function index() {	
		$data = [];
		$data['template'] 	= 'feedback/cu_feedback_list';
		$data['title'] 		= "Feedback List";
		$this->load->view('default', $data);
	}

	public function list() {		
		$params = $this->searchParam([]);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$whereArr['c.customer_id'] = $this->userid;
		## Total number of records without filtering
		// $allres  = $this->Feedback_model->count();
		// $totalRecords = $allres;

		## Total number of records with filtering
		$columns = 'f.complaint_id,f.created_at, c.ticket_no,c.ga_no, c.complaint_type';
		$list  = $this->Feedback_model->get_feedbacks_by_join($whereArr,$columns,$startrow,$rowperpage , $likeArr);

		$totalRecordwithFilter = count($list);
		$totalRecords = $totalRecordwithFilter;

		// dd($list);
		// dd($this->pq());
		$complaint_types = complaint_types();
		
		foreach ($list as $key => $value) {
			$list[$key]['complaint_type'] 	= (isset($complaint_types[$value['complaint_type']])) ? $complaint_types[$value['complaint_type']] : '';
			$list[$key]['created_at'] 	= custDate($value['created_at']);
		}

		$response = array(
		 	"draw" 					=> intval($draw),
		 	"totalRecords" 			=> $totalRecords,
		 	"totalRecordwithFilter" => $totalRecordwithFilter,
		 	"aaData" 				=> $list
		);

		sendResponse(1, 'success', $response);
	}
	
	public function create($complaintId) {
		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId,'customer_id' => $this->userid,'status' => 4]);

		if($complaint){
			$feedbackExist = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId],'id');

			if($feedbackExist){
				$this->sendFlashMsg(0,'Feedback already exist for the complaint', 'customer/feedback');
			}
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));
		}else{
			$this->sendFlashMsg(0,'Complaint details not found', 'customer/feedback');
		}

		//
		$conditions = [];
		$conditions['order_by'] = ['id' => 'desc'];

		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaintId],'*',$conditions);

		$end_date = '';
		$representative ='';

		if($history){
			foreach ($history as $key => $value) {
				if($value['type'] != 'customer' && $value['new_status'] == 4){ //closed
					$end_date = date('Y-m-d',strtotime($value['created_at']));
					break;
				}
			}

			$rep_name = [];
			$history = array_reverse($history, true);
			foreach ($history as $key => $value) {
				if($value['type'] == 'assign' && $value['assigned_by'] == 'admin'){ //closed

					$employee = $this->User_model->get_user(['role' => 'employee', 'id' => $value['emp_id']],'first_name,last_name');

					if($employee && count($rep_name) < 2){
						$rep_name[] = cap($employee['first_name']).' '.cap($employee['last_name']);
					}
				}
			}

			$representative = implode(", ", $rep_name);
		}

		$start_date = date('Y-m-d',strtotime($complaint['created_at']));
		if($end_date){
			if($start_date == $end_date){
				$period = $start_date;
			}else{
				$period = $start_date .' to '.$end_date;
			}
		}else{
			$period = $start_date;
		}



		// d($complaint);
		// dd($history);

		$data = [];
		$data['template'] 			= 'feedback/cu_feedback_add';
		$data['title'] 				= "Add Feedback";
		$data['complaint'] 			= $complaint;
		$data['project'] 			= $project;
		$data['period'] 			= $period;
		$data['representative'] 	= $representative;
		$this->load->view('default', $data);
	}

	public function store($complaintId){

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId,'customer_id' => $this->userid]);

		if($complaint){

			$feedbackExist = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId],'id');

			if($feedbackExist){
				sendResponse(0, 'Feedback already exist for the complaint');
			}
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));

		}else{
			sendResponse(0, 'Complaint details not found');
		}


		//$this->form_validation->set_rules('period', 'Period', 'required');
		//$this->form_validation->set_rules('name', 'Representative Name', 'required');
		$this->form_validation->set_rules('suggestion', 'Suggestion', 'required');
		$this->form_validation->set_rules('comment', 'Comment', 'required');
		//$this->form_validation->set_rules('suitable_time', 'Was the visit timing suitable to your requirement?', 'required');

		//$this->form_validation->set_rules('tech_knowledge', 'Technical knowledge', 'required|in_list[1,2,3,4,5]', array('required'=> 'Please Rate Technical knowledge'));
		
		//$this->form_validation->set_rules('comm_skill', 'Communication skills', 'required|in_list[1,2,3,4,5]', array('required'=> 'Please Rate Communication skills'));
		
		//$this->form_validation->set_rules('punctuality', 'Punctuality', 'required|in_list[1,2,3,4,5]', array('required'=> 'Please Rate Punctuality'));
		
		//$this->form_validation->set_rules('safety', 'Commitment to Safety', 'required|in_list[1,2,3,4,5]', array('required'=> 'Please Rate Commitment to Safety'));
		
		//$this->form_validation->set_rules('equipment_performance', 'Equipment Performance', 'required|in_list[1,2,3,4,5]', array('required'=>'Please Rate Equipment Performance'));

		$this->form_validation->set_rules('service_rating', 'our service', 'required|in_list[1,2,3,4,5,6,7,8,9,10]', array('required'=> 'Please Rate our service'));


		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }
        //end validation


        //Store
        $ratings = array(
        	//'tech_knowledge' => $this->input->post('tech_knowledge',TRUE),
        	//'comm_skill' => $this->input->post('comm_skill',TRUE),
        	//'punctuality' => $this->input->post('punctuality',TRUE),
        	//'safety' => $this->input->post('safety',TRUE),
        	//'equipment_performance' => $this->input->post('equipment_performance',TRUE),
        	'service_rating' => $this->input->post('service_rating',TRUE),
        );

       	$data = [];
        $data['complaint_id'] 		= $complaintId;
        //$data['period'] 			= $this->input->post('period',TRUE);
		//$data['representative_name'] = $this->input->post('name',TRUE);
		$data['suggestion'] 		= $this->input->post('suggestion',TRUE);
		$data['comment'] 			= $this->input->post('comment',TRUE);
		//$data['suitable_time'] 		= $this->input->post('suitable_time',TRUE);
		$data['rating'] 			= json_encode($ratings);
		$data['status'] 			= 1;
		$data['created_at'] 		= getDt();

		$insertid = $this->Feedback_model->add_feedback($data);
		if($insertid){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Feedback added successfully' ));

			//send thankyou mail to customer
			$this->thankYouMail($data, $complaint);

			//feedback email and notification to admin
			$this->feedbackMail_Notify($data, $complaint);

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to add feedback');
		}
	}//end store feedback

	private function feedbackMail_Notify($data,$complaint){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$admin = $this->User_model->get_user(['role' => 'admin']);

		
		if($admin){
			$this->load->model('Notification_model');
			$notificationData = array(
				'user_id' 			=> $admin['id'],
				'user_type' 		=> 'admin',
				'title' 			=> 'Feedback',
				'description' 		=> 'There is a new feedback from customer.',
				'context_id' 		=> $complaint['id'],
				'is_read' 			=> 0,
				'created_by' 		=> $this->userid,
				'created_by_type' 	=> $this->role,
				'status' 			=> 1,
				'created_at' 		=> getDt()
			);
			// d($notificationData);
			$this->Notification_model->add_notification($notificationData);

			//admin mail
			$admSubject = 'New Complaint Feedback.'.ticketText($complaint['ticket_no']);
			$admMsg 	= 
				'Hello '.ucfirst($admin['first_name']).', <br><br>
				
				This is to inform you there is a new feedback from customer ('.ticketText($complaint['ticket_no']).'). <br><br>

				Best Regards,<br>
				Team AGENCY09.';
	
			$sendMail = $this->sendMail($admin['email'], $admSubject, $admMsg);
		}
		
	}

	private function thankYouMail($data,$complaint){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$customer = $this->Customer_model->get_customer(['id' => $this->userid]);

		//customer mail

		if($customer){
			$custSubject = 'Customer Experience is our Utmost Priority. Ticket No. '.$complaint['ticket_no'];
			$custMsg 	= '
				Hello '.cap($customer['first_name']).', <br><br>

				Thank you for the feedback on your experience with us. We appreciate your insight because it helps us build a better customer experience. <br><br>

				If you have more questions, comments or concerns, please feel welcome to reach back as we would be happy to assist. <br><br>

				Best Regards,<br>
				Team AGENCY09.
				';
			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg);
		}
	}

	public function view($complaintId) {

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId,'customer_id' => $this->userid]);

		if($complaint){
			$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId]);

			if(!$feedback){
				$this->sendFlashMsg(0,'Feedback details not found', 'customer/feedback');
			}
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));

		}else{
			$this->sendFlashMsg(0,'Complaint details not found', 'customer/feedback');
		}
		// d($history);
		// dd($complaint);

		$data = [];
		$data['template'] 		= 'feedback/cu_feedback_view';
		$data['title'] 			= "View Feedback";
		$data['complaint'] 		= $complaint;
		$data['feedback'] 		= $feedback;
		$data['project'] 		= $project;
		$this->load->view('default', $data);
	}	
}

?>