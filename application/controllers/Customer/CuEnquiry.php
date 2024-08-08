<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CuEnquiry extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_customer(1,$this->CUST_LOGIN);

		$this->load->model('Project_model');
		$this->load->model('Enquiry_model');
		$this->load->model('Equipment_model');
		$this->load->model('Sparepart_model');
		$this->load->model('User_model');
	}

	public function index() {	
		$data = [];
		$data['template'] 	= 'enquiry/cu_enquiry_list';
		$data['title'] 		= "Enquiry List";
		$this->load->view('default', $data);
	}

	public function list() {		
		// $params = $this->searchParam([]);

		$params = $this->searchParam(['status'],['enquiry_no','ga_no']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$whereArr['customer_id'] = $this->userid;
		## Total number of records without filtering
		$totalRecords  = $this->Enquiry_model->count(['customer_id' => $this->userid]);

		$totalRecordwithFilter  = $this->Enquiry_model->count($whereArr,$likeArr);


		## Total number of records with filtering
		$columns = 'id,ga_no,spareparts,enquiry_no,status,created_at';
		$list  = $this->Enquiry_model->get_enquiries($whereArr,$columns,$startrow,$rowperpage , $likeArr);

		
// dd($list);
		foreach ($list as $key => $value) {
			// $spareids = explode(",", $value['spareparts']);
			$list[$key]['spareparts'] = '';
			
			$spareids = [];
			$spareparts = json_decode($value['spareparts'],true);
			
			if(is_array($spareparts)){
				/*$spareids = array_column($spareparts, 'sparepart');
				// $spareids = explode(",", $spareparts);

				$sparepartDetails = $this->Sparepart_model->get_spareparts([],'id,name','id',$spareids);
				if($sparepartDetails){
					$temp = array_column($sparepartDetails, 'name');
					$list[$key]['spareparts'] 	= implode(",", $temp);
				}*/
				$temp = '';
				foreach ($spareparts as $k => $v) {
					if(isset($v['name'])){
						$temp .= cap($v['name']).', ';
					}
				}
				$list[$key]['spareparts'] 	= rtrim($temp,', ');
			}
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
	
	public function getSpareparts() {
		$ga_no 			= $this->input->get('ga_no',TRUE);
		$equipment_id 	= $this->input->get('equi_id',TRUE);
		$model 			= $this->input->get('model',TRUE);

		$project = $this->Project_model->get_project(['ga_no' => $ga_no, 'equipment_id' => $equipment_id],'id');

		if($project){
			$equipemnt = $this->Equipment_model->get_equipment(['id' => $equipment_id],'id');

			if($equipemnt){
				/*$list = $this->Sparepart_model->get_spareparts(['equipment_id' => $equipment_id, 'model' => $model, 'status' => 1],'id,name');
				if($list){
					sendResponse(1, 'success', $list);
				}else{
					sendResponse(0, 'Spareparts not found');
				}*/


				//check if model exist in sparepart model column
				$sparelist = [];

				$list = $this->Sparepart_model->get_spareparts(['equipment_id' => $equipment_id, 'status' => 1],'id,name,model,unit',FALSE,FALSE, ['model' => $model]);
				if($list){
					foreach ($list as $key => $value) {
						$model_list = json_decode($value['model'],true);
						if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
							if(in_array($model, $model_list)){
								unset($value['model']);
								$value['name'] = cap($value['name']);
								$value['unit'] = cap($value['unit']);
								$sparelist[] = $value;
							}
						}
					}
				}

				if($sparelist){
					sendResponse(1, 'success', $sparelist);
				}else{
					sendResponse(0, 'Spareparts not found');
				}
				// dd($sparelist);
			}else{
				sendResponse(0, 'Equipment details not found');
			}
		}else{
			sendResponse(0, 'Invalid GA No. OR equipment detail');
		}
	}

	public function create() {
		$ga_nos = [];
		$models = [];

		$projects = $this->Project_model->get_projects(array('company_id' => $this->company_id, 'status' => 1), 'ga_no,equipment_id,model');

		if($projects){
			foreach ($projects as $key => $value) {

				$equipment = $this->Equipment_model->get_equipment(array('id' => $value['equipment_id']), 'id,name,model');
				if($equipment){
					$ga_nos[] = array(
					   	'value' => $value['ga_no'],
			          	'label' => $value['ga_no'],
			          	'equipment_id' => $equipment['id'],
			          	'equipment_name' => cap($equipment['name']),
			          	'model' => $value['model'],
			          	'equipment_model' => $equipment['model'],
					);

					//get all models
					$models[$equipment['id']] = [];
					$temp = json_decode($equipment['model'],true);
					if(json_last_error() == JSON_ERROR_NONE && is_array($temp)){
						$models[$equipment['id']] = $temp;
					}
					
				}
			}
		}else{
			$this->sendFlashMsg(0,'Projects not found', 'customer/enquiry');
		}

		// dd($ga_nos);

		$data = [];
		$data['template'] 	= 'enquiry/cu_enquiry_add';
		$data['title'] 		= "Add Enquiry";
		$data['projects'] 	= $ga_nos;
		$data['models'] 	= $models;
		$this->load->view('default', $data);
	}

	public function store(){
		// d($_POST);
		$this->form_validation->set_rules('ga_no', 'GA No.', 'required');
		$this->form_validation->set_rules('equipment', 'Equipment Id', 'required');
		$this->form_validation->set_rules('model', 'Model', 'required');
		// $this->form_validation->set_rules('sparepart[]', 'Spareparts', 'required');
		// $this->form_validation->set_rules('qty', 'Quantity', 'required');
		// $this->form_validation->set_rules('query', 'Query', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //validate ga no against company
        $sparepart_names_arr  = [];
        $sparepart_arr  = [];
		$ga_no 			= trim($this->input->post('ga_no',TRUE));
		$equi_id 		= trim($this->input->post('equipment',TRUE));
		$model 			= trim($this->input->post('model',TRUE));
		$spareparts 	= $this->input->post('sparepart',TRUE);
		$qty 			= $this->input->post('qty',TRUE);
		// $spareparts   	= array_unique($spareparts);

		$project = $this->Project_model->get_project(array('company_id' => $this->company_id, 'ga_no' => $ga_no,'equipment_id' => $equi_id,), 'equipment_id');

		if($project){

			if(!empty($spareparts)){

			
			foreach ($spareparts as $key => $spare_id) {
				$ischeck_key='check_'.$spare_id;

				//check if sparepart and equipemnt details match
				if($spare_id && isset($_POST[$ischeck_key]) ) {

					if(! preg_match('/^\d+$/', $qty[$key])){
						sendResponse(0, 'Invalid Quantity');
					}

					
					$sparedetail = $this->Sparepart_model->get_sparepart(['id'=> $spare_id, 'equipment_id' => $equi_id, 'status' => 1],'id,name,model,unit');
					if($sparedetail){
						//validate received equi model with spare model
						$model_list = json_decode($sparedetail['model'],true);
						if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
							if(!in_array($model, $model_list)){
								sendResponse(0, 'Model not found for '.$sparedetail['name']);
							}
						}else{
							sendResponse(0, 'Model details not found '.$sparedetail['name']);
						}

						$sparepart_arr[] = ['sparepart' => $spare_id, 'qty' => $qty[$key], 'name' => $sparedetail['name'],'unit' => $sparedetail['unit']];
						$sparepart_names_arr[] = $sparedetail['name'];

					}
					else{
						sendResponse(0, 'Spareparts not found');
					}
					
				}
					
			}//end foreach
		   }//end not empty sparepart
		}else{
			sendResponse(0, 'Proejct details not found');
		}

		/*if(empty($sparepart_arr)){
			sendResponse(0, 'Spareparts data is missing');
		}*/

		// dd($sparepart_arr);
        //end validation

        //Store
       	$data = [];
        $data['ga_no'] 				= $ga_no;
        $data['customer_id'] 		= $this->userid;
		$data['spareparts'] 		= json_encode($sparepart_arr);
		$data['sparepart_names'] 	= implode(",", $sparepart_names_arr);
		$data['model'] 				= $model;
		$data['query'] 				= trim($this->input->post('query',TRUE));
		$data['status'] 			= 2;
		$data['created_at'] 		= getDt();

		$insertid = $this->Enquiry_model->add_enquiry($data);
		if($insertid){
			//update enquiry_no
			$enquiry_no = 'S'.str_pad($insertid, 5, '0', STR_PAD_LEFT);
			$this->Enquiry_model->update_enquiry($insertid, ['enquiry_no' => $enquiry_no]);


			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Enquiry added successfully' ));

			//feedback email and notification to admin
			$this->enquiryNotification($insertid, $enquiry_no);

			//enquiry email to admin // pre defined email ids
			$this->enquiryMail($data,$enquiry_no);


			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to add enquiry');
		}
	}//end store enquiry

	private function enquiryNotification($enq_id,$enquiry_no){
		$users= [];

		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$users[] = ['id' => $admin['id'], 'role' => 'admin'] ;
		}

		// $sales = $this->User_model->get_user(['role' => 'sales']);
		$sales = $this->User_model->get_users(['role' => 'sales']);
		if($sales){
			foreach ($sales as $key => $value) {
				$users[] = ['id' => $value['id'], 'role' => 'sales'] ;
			}
			// $users[] = ['id' => $sales['id'], 'role' => 'sales'] ;
		}

		if($users){
			$this->load->model('Notification_model');
			foreach ($users as $key => $value) {
				$notificationData = array(
					'user_id' 			=> $value['id'],
					'user_type' 		=> $value['role'],
					'title' 			=> 'Enquiry',
					'description' 		=> 'We have received new spare enquiry. ('.$enquiry_no.')',
					'context_id' 		=> $enq_id,
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
				// d($notificationData);
				$this->Notification_model->add_notification($notificationData);
			}
			
		}
	}

	private function enquiryMail($data,$enquiry_no){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$to = [];
		$cc = [];
		$bcc = [];

		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$bcc[] = $admin['email'];
		}

		$ga_prefix =  strtolower(substr($data['ga_no'], 0, 2));
		
		if($ga_prefix == 's-'){
			$to = ['gurule.arun@tsubaki-conveyor.in'];
			$cc = ['rasika@agency09.in'];
		}
		else if($ga_prefix == 'uh'){
			$to = ['kadam.sambhaji@tsubaki-conveyor.in'];
			$cc = ['pawar.ajay@tsubaki-conveyor.in','rasika@agency09.in'];
		}else{
			$to = $bcc;
			$bcc = [];
		}

		// d($to); d($cc); d($bcc);die;
		//admin mail
		
		$project = $this->Project_model->get_project_details(array('p.ga_no' => $data['ga_no']));
		$product_text = '';
		if($project){
			$product_text = 'about spare for '.cap($project['equipment_name']);
		}

		$customer = $this->session->userdata($this->user);
		$customer_name = cap($customer['first_name'].' '.$customer['last_name']);


		$admSubject = 'Spare Enquiry - Ticket no. '.$enquiry_no;
		$admMsg 	= 
			'Hello Team, <br><br>
			
			This is to inform you there is a new enquiry received from '.$customer_name.' '.$product_text.'.  <br><br>

			Spare ticket enquiry no. is '.$enquiry_no.'.<br><br>

			Appreciate your quick replies to customer query. <br><br>

			Best Regards,<br>
			Team Tsubaki.';

		if($to){
			$sendMail = $this->sendMail($to, $admSubject, $admMsg, FALSE,FALSE,$cc,$bcc);
		}



		//customer mail
		$customer = $this->Customer_model->get_customer(['id' => $this->userid]);

		if($customer){
			$custSubject = 'Spare Enquiry Generated Successfully. Spare Enquiry No. '.$enquiry_no;
			$custMsg 	= '
				Hello '.cap($customer['first_name']).', <br><br>

				Thank you for your interest in our products<br><br>

				Spare ticket enquiry no. is '.$enquiry_no.'.<br><br>

				If you have more questions, comments or concerns, please feel welcome to reach back as we would be happy to assist. <br><br>

				Best Regards,<br>
				Team Tsubaki.
				';
			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg);
		}
	}

	public function view($enquiryId) {
		$enquiry = $this->Enquiry_model->get_enquiry(['id' => $enquiryId,'customer_id' => $this->userid]);

		// $sparepart_arr = [];
		if($enquiry){
			$spareparts = json_decode($enquiry['spareparts'],true);

			/*if(is_array($spareparts)){

				foreach ($spareparts as $key => $value) {
					$sparepartDetails = $this->Sparepart_model->get_sparepart(['id' =>$value['sparepart'] ]);
					$spareparts[$key]['name'] = $sparepartDetails['name'];
				}

				$spareids = array_column($enquiry, 'sparepart');
				$sparepartDetails = $this->Sparepart_model->get_spareparts([],'id,name','id',$spareids);

				if($sparepartDetails){
					$temp = array_column($sparepartDetails, 'name');
					$enquiry['spareparts'] 	= implode(", ", $temp);
				}
			}*/
			// dd($spareparts);
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $enquiry['ga_no']));
			$history = $this->Enquiry_model->get_enquiry_history(['h.enquiry_id' => $enquiryId]);

		}else{
			$this->sendFlashMsg(0,'Enquiry details not found', 'customer/enquiry');
		}
		// d($history);
		// dd($complaint);

		$data = [];
		$data['template'] 		= 'enquiry/cu_enquiry_view';
		$data['title'] 			= "View Enquiry";
		$data['enquiry'] 		= $enquiry;
		$data['history'] 		= $history;
		$data['project'] 		= $project;
		$data['spareparts'] 	= $spareparts;
		$this->load->view('default', $data);
	}	
}

?>