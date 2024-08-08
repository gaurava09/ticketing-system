<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CuComplaint extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_customer(1,$this->CUST_LOGIN);

		$this->upload_path = 'documents/customer/'.$this->userid.'/complaint/';

		$this->load->model('Customer_model');
		$this->load->model('Company_model');
		$this->load->model('Project_model');
		$this->load->model('Complaint_model');
		$this->load->model('Equipment_model');
		$this->load->model('User_model');
		$this->load->model('Chat_model');
	}

	public function index() {
		//get GA _no list
		$list = [];
		if($this->company_id){
			$projects = $this->Project_model->get_projects(array('company_id' => $this->company_id), 'ga_no,equipment_id');
			 // dd($projects);
			if($projects){
				foreach ($projects as $key => $value) {
						$list[] = array(
						   	'value' => $value['ga_no'],
				          	'label' => $value['ga_no'],
						);
				}
			}
		}
		// dd($list);
		//get complaints
		$where_arr  = ['c.customer_id' => $this->userid,'cu.company_id' => $this->company_id];
		// $complaints = $this->Complaint_model->get_complaints($where_arr,'id,ticket_no,ga_no,complaint_type,status,created_at');
		$complaints = $this->Complaint_model->get_complaints_join_company($where_arr,'c.id,c.ticket_no,c.ga_no,c.complaint_type,c.status,c.created_at');

		dd($this->pq());

		$data = [];
		$data['template'] 	= 'complaint/cu_complaint_list';
		$data['title'] 		= "Complaint List";
		$data['data'] 		= $complaints;
		$data['projects'] 	= $list;
		$this->load->view('default', $data);
	}

	public function searchComplaint() {
		$ga_no 					=  trim($this->input->get('ga_no',TRUE));
		$ticket_no 				=  trim($this->input->get('ticket_no',TRUE));
		$complaint_type 		=  trim($this->input->get('type',TRUE));
		$status 				=  trim($this->input->get('status',TRUE));
		$cust_equi_no 			=  trim($this->input->get('cust_equi_no',TRUE));

		

		$where_arr  = ['c.customer_id' => $this->userid,'cu.company_id' => $this->company_id];
		if($ga_no){
			$where_arr['c.ga_no'] = $ga_no;
		}
		if($ticket_no){
			$where_arr['c.ticket_no'] = $ticket_no;
		}

		if($cust_equi_no){
			$where_arr['c.cust_equipment_no'] = $cust_equi_no;
		}

		if($complaint_type){
			$where_arr['c.complaint_type'] = $complaint_type;
		}

		$complaint_types = complaint_types();
		/*if($complaint_type && isset($types[$complaint_type])){
			$where_arr['complaint_type'] = $types[$complaint_type];
		}*/

		if($status != ''){
			$where_arr['c.status'] = $status;
		}

		// dd($where_arr);
		//get complaints
		$complaints = $this->Complaint_model->get_complaints_join_company($where_arr,'c.id,c.ticket_no,c.ga_no,c.complaint_type,c.status,c.created_at');

		if($complaints){
			foreach ($complaints as $key => $value) {

				$complaints[$key]['complaint_type'] = $complaint_types[$value['complaint_type']];
				$complaints[$key]['created_at'] = custDate($value['created_at']);
			}
		}
		sendResponse(1, 'success', array('list' => $complaints));
	}
	
	public function create() {
		$customer = $this->Customer_model->get_customer(['id' => $this->userid]);
		$list = [];

		if(!$customer){
			$this->sendFlashMsg(0, 'User Details not found','complaint');
		}
		$company = $this->Company_model->get_company(['id' => $customer['company_id']]);

		// $projects = $this->Project_model->get_projects(array('company_id' => $customer['company_id'], 'status' => 1), 'ga_no,equipment_id,model');

		$projects = $this->Project_model->get_projects(['status' => 1], 'ga_no,equipment_id,model',FALSE,FALSE,FALSE,FALSE, $customer['company_id']);

		if($projects){
			foreach ($projects as $key => $value) {

				$equipment = $this->Equipment_model->get_equipment(array('id' => $value['equipment_id']), 'name,model');
					if($equipment){
						$list[] = array(
						   	'value' => $value['ga_no'],
				          	'label' => strtolower($value['ga_no']),
				          	'equipment_name' => cap($equipment['name']),
				          	'equipment_model' => $value['model'],
						);
					}
			}
		}

		$data = [];
		$data['template'] 	= 'complaint/cu_complaint_add';
		$data['title'] 		= "Add Complaint";
		$data['data'] 		= $customer;
		$data['company'] 	= $company;
		$data['projects'] 	= $list;
		$this->load->view('default', $data);
	}

	public function store(){
		// dd($_POST);
		// echo count($_FILES); d($_POST); d($_FILES);
		$data 		= [];
		$file_array = [];

		$ga_no 					=  trim($this->input->post('ga_no',TRUE));
		$complaint_type 		=  trim($this->input->post('complaint_type',TRUE));
		$description 			=  trim($this->input->post('description',TRUE));
		$cust_equipment_no 		=  trim($this->input->post('cust_equipment_no',TRUE));
		$from_date 				=  $this->input->post('from_date',TRUE);
		$to_date 				=  $this->input->post('to_date',TRUE);
		$order_no 				=  trim($this->input->post('order_no',TRUE));
		$email_cc 				=  $this->input->post('email_cc',TRUE);
		
		$email_arr = [];
		$error  = '';
		if($email_cc){
			foreach ($email_cc as $key => $value) {
				if($value != ''){
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
						$error .='<div class="error">Add CC '.($key+1) .' : Invalid Email</div>';
					}else{
						$email_arr[] = $value;
					}					
				}
			}
		}
		
		if($error){
			sendResponse(0, $error);
		}

		$types = complaint_types();

		if( !array_key_exists($complaint_type, $types)){
			sendResponse(0, 'Invalid Compaint Type');
		}
		if($complaint_type == 1){ //engg visit
			$this->form_validation->set_rules('from_date', 'From Date', 'required');
			$this->form_validation->set_rules('to_date', 'To Date', 'required');
			// $this->form_validation->set_rules('order_no', 'Order No', 'required');

			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['order_no'] = $order_no;
		}
		else if($complaint_type == 3){ //Maintenance checkup
			$this->form_validation->set_rules('from_date', 'From Date', 'required');
			$this->form_validation->set_rules('to_date', 'To Date', 'required');

			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
		}

		// $this->form_validation->set_rules('ga_no', 'GA No.', 'required|alpha_dash');
		$this->form_validation->set_rules('ga_no', 'GA No.', array(
			'required',
			'alpha_dash',
			array(
                'ga_no_callable',
                function($str)
                {
                    $projectDetails = $this->Project_model->get_project(array('ga_no'=>$str,'company_id'=> $this->company_id));
			        if($projectDetails){
			        	return true;
			        }else{
			        	return false;
			        }
                }
            ),
		));
		$this->form_validation->set_message('ga_no_callable', 'Invalid GA No.');

		$this->form_validation->set_rules('complaint_type', 'Complaint Type', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');

		// $this->form_validation->set_rules('email_cc', 'Email CC', 'valid_email');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }


        if($complaint_type ==2 && isset($_FILES['file']) ){ //equi failure
        	$this->load->model('Files_model');

        	$this->load->library('upload');

        	if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir($this->upload_path, 0777, true);		        
		    }

           	//validate files
		    $files = $_FILES;
		    $cpt = count($_FILES['file']['name']);

		    if($cpt > 5){
		    	sendResponse(0, 'You can not upload more than 5 images');
		    }

		    $errors = '';
		    $allowed_image_extension = array("png","jpg","jpeg");
		   
		    $acceptable = array(
		        'image/jpeg',
		        'image/jpg',
		        'image/png'
		    );

		    for($i=0; $i<$cpt; $i++)
		    {           
		        $file_extension = strtolower(pathinfo($files['file']['name'][$i], PATHINFO_EXTENSION));

		        $fnm = $files['file']["name"][$i];

		         // Validate file input to check if is not empty
			    if (! file_exists($files['file']["tmp_name"][$i])) {
			        $errors .= "<div class='error'>Image ".($i+1)." -Choose image file to upload.</div>";
			    }    // Validate file input to check if is with valid extension
			    else if (! in_array($file_extension, $allowed_image_extension)) {
			        $errors .= "<div class='error'>Image ".($i+1)." - Upload valid image. Only PNG and JPEG are allowed.</div>";
			    }
			    else if (! in_array(mime_content_type($_FILES['file']['tmp_name'][$i]), $acceptable)) {
			        $errors .= "<div class='error'>Image ".($i+1)." - Upload valid image. Only PNG and JPEG are allowed</div>";
			    }
			        // Validate image file size
			    else if (($files['file']["size"][$i] > 1000000)) {
			        $errors .= "<div class='error'>Image ".($i+1)." -Image size exceeds 1MB</div>";
			    } 
		    }//end for
		    
		    if($errors){
		    	sendResponse(0, $errors);
		    }
		   
		    //start upload
		    $time = time();

		    for($i=0; $i<$cpt; $i++)
		    {   
		    	$config = array();
		    	$config['upload_path'] = $this->upload_path;
		        $config['allowed_types'] = 'jpg|jpeg|png';
		        $config['max_size'] = (1*1024); //1MB
		        $config['remove_spaces'] = TRUE;
	       		// $config['encrypt_name'] = TRUE;

		        $_FILES['file']['name']= $files['file']['name'][$i];
		        $_FILES['file']['type']= $files['file']['type'][$i];
		        $_FILES['file']['tmp_name']= $files['file']['tmp_name'][$i];
		        $_FILES['file']['error']= $files['file']['error'][$i];
		        $_FILES['file']['size']= $files['file']['size'][$i];    

		        $file_name = $time.'-'.$i.'-'.mt_rand(10000, 99999);
				$config['file_name'] = $file_name;


		        $this->upload->initialize($config);

		        $fnm = $files['file']["name"][$i];

		        if (!$this->upload->do_upload('file')) {
		            $error = $this->upload->display_errors();
		            sendResponse(0, $error." - ".$fnm);
		        }else{
		        	$filedata = $this->upload->data();
		        	// dd($this->upload->data());
		        	$file_array[] = array(
		        		'name' => $filedata['file_name'],
		        		'path' => $this->upload_path.$filedata['file_name'],
		        		// 'path' => $filedata['full_path'],
		        		'width' => $filedata['image_width'],
		        		'height' => $filedata['image_height'],
		        		'type' => $filedata['image_type'],
		        		'user_type' => 'customer',
		        		'user_id' => $this->userid,
		        		'status' => '1',
		        		'created_at' => getDt()
		        	);
		        }//end if file upload
		    } //end for

        }//end if compaint type 2

       //Store
        $data['ga_no'] = $ga_no;
		// $data['complaint_type'] = $types[$complaint_type];
		$data['complaint_type'] = $complaint_type;
		$data['description'] = $description;
		$data['cust_equipment_no'] = $cust_equipment_no;
		$data['email_cc'] = implode(",", $email_arr);
		$data['customer_id'] = $this->userid;
		$data['status'] = 2;
		$data['created_at'] = getDt();

		$complaint_id = $this->Complaint_model->add_complaint($data);
		if($complaint_id){

			//update ticket no
			$ticket_no = str_pad($complaint_id, 6, '0', STR_PAD_LEFT);
			$this->Complaint_model->update_complaint($complaint_id, ['ticket_no' => $ticket_no]);

			
			//add complaint files
			if($file_array){
				foreach ($file_array as $key => $value) {
					$value['complaint_id'] = $complaint_id;
					$this->Files_model->add_file($value);
				}
			}

			$admin = $this->User_model->get_user(['role' => 'admin']);

			//add to chat conversation
			if($admin){
				$this->Chat_model->add_conversation(
					array(
						'user_id' => $admin['id'],
						'customer_id' => $this->userid,
						'ticket_no' => $ticket_no,
						'created_at' => getDt(),
					)
				);
			}
			
			//add notification for admin
			$this->complaintNotification($data,$complaint_id,$admin,$ticket_no);

			//send email to admin plus customer
			$this->complaintMail($data,$admin,$email_arr,$ticket_no);
			
			
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Complaint created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create complaint');
		}
	}//end store coplaint

	private function complaintNotification($data,$complaint_id,$admin,$ticket_no){
		if($admin){
			$this->load->model('Notification_model');
			$notificationData = array(
				'user_id' 			=> $admin['id'],
				'user_type' 		=> 'admin',
				'title' 			=> 'Complaint',
				'description' 		=> 'A new complaint has been added to the query.'.ticketText($ticket_no),
				'context_id' 		=> $complaint_id,
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

	private function complaintMail($data,$admin,$email_arr,$ticket_no){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$customer = $this->Customer_model->get_customer(['id' => $this->userid]);

		//admin mail
		if($admin){
			$admSubject = 'A New Complaint Has Been Registered.'.ticketText($ticket_no);
			$admMsg 	= "
				Hello ".ucfirst($admin['first_name']).", <br><br>

				We've received a complaint by the customer (Ticket no.".$ticket_no.").<br><br>

				Please process the complaint and assign an Engineer for the same.<br><br>

				Best,<br>
				Team Ticketing. ";

			$sendMail = $this->sendMail($admin['email'], $admSubject, $admMsg);
		}
		

		//customer mail
		if($customer){
			$custSubject = 'Complaint Generated Successfully.'.ticketText($ticket_no);
			$custMsg 	= "
				Hello ".ucfirst($customer['first_name']).", <br><br>

				Thanks for reaching out to us. This email is to ensure that your complaint has been successfully generated. You'll hear from us soon after we take a much closer look to understand and work on the issue. <br><br>

				Your Ticket no is ".$ticket_no.".<br><br>
				
				We're sorry for the inconvenience.<br><br>

				Best,<br>
				Team Ticketing. ";

			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr);
		}
	}


	public function view($complaintId) {
		
		$this->load->model('Files_model');
		$this->load->model('Department_model');
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Feedback_model');

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId,'customer_id' => $this->userid]);
		if($complaint){
			$files = $this->Files_model->get_files(['complaint_id' => $complaintId,'user_type' => 'customer']);

			$complaint['files'] = $files;

			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));

		}else{
			$this->sendFlashMsg(0,'Complaint details not found', 'customer/complaint');
		}



		$department = $this->Department_model->get_departments([],'id,name,status');

		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaintId]);
		if($history){
			foreach ($history as $key => $value) {

				if($value['assigned_by'] == 'employee'){
					unset($history[$key]);
					continue;
				}

				foreach ($department as $k => $dept) {
					if($value['dept_id'] == $dept['id']){
					 	$history[$key]['dept_name'] = $dept['name'];
					}
				}

				$empDetail = $this->User_model->get_user(['id' => $value['emp_id']], 'concat(first_name," ",last_name) as emp_name, email,mobile');

				$history[$key]['emp_name'] = (isset($empDetail['emp_name'])) ? $empDetail['emp_name'] : '';
				$history[$key]['emp_email'] = (isset($empDetail['email'])) ? $empDetail['email'] : '';
				$history[$key]['emp_mobile'] = (isset($empDetail['mobile'])) ? $empDetail['mobile'] : '';

				$convoId = $this->Chat_model->get_conversation(['user_id' => $value['emp_id'],'customer_id' => $this->userid, 'ticket_no' => $complaint['ticket_no'] ], 'id');

				$history[$key]['conversation_id'] = ($convoId) ? $convoId['id'] : '';
			}
		}

		//admin convo id
		$adminConvoId = '';
		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$adminConvoId = $this->Chat_model->get_conversation(['user_id' => $admin['id'],'customer_id' => $this->userid, 'ticket_no' => $complaint['ticket_no']], 'id');

			$adminConvoId = ($adminConvoId) ? $adminConvoId['id'] : '';
		}

		//get feedback
		$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId]);


		// dd($history);
		// dd($complaint);

		$data = [];
		$data['template'] 		= 'complaint/cu_complaint_view';
		$data['title'] 			= "View Complaint";
		$data['complaint'] 		= $complaint;
		$data['history'] 		= $history;
		// $data['customer'] 		= $customer;
		$data['project'] 		= $project;
		$data['department'] 	= $department;
		$data['feedback'] 		= $feedback;
		$data['adminConvoId'] 	= $adminConvoId;
		$this->load->view('default', $data);
	}	


	public function remark(){
		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));
		$new_status 	= trim($this->input->post('status',TRUE));

		//Start validation
		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required');
		$this->form_validation->set_rules('status', 'Status', 'in_list[1,3]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id, 'customer_id' => $this->userid]);

		if(!$complaint){
			sendResponse(0, 'Complaint details not found');
		}

		/*$prev_status = $complaint['status'];
		if($prev_status == $new_status){
			sendResponse(0, 'Complaint status cant be changed');
		}*/

		//if prev status is not completed . so dont allow
		$prev_status = $complaint['status'];
		if($prev_status != 1){
			sendResponse(0, 'Complaint status cant be changed');
		}

		$this->load->model('ComplaintHistory_model');

		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint_id]);
		if($history){
			$history = array_reverse($history);
			
			if($history[0]['type'] == 'customer'){
				sendResponse(0, 'Complaint status cant be changed.');
			}
		}
        //end validation
		     		
		//store
		
		
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['type'] 				= 'customer';
		$data['prev_status'] 		= $prev_status;
		$data['new_status'] 		= $new_status;
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){
			$updateData = array('status' => $new_status);
			$this->Complaint_model->update_complaint($complaint_id,$updateData);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Status updated successfully' ));

			//add notification to admin and emp assigned by admin
			$this->remarkNotification($data,$complaint);

			sendResponse(1,'Status updated successfully.');
		}else{
			sendResponse(0,'Failed to updated Ticket.');
		}

	}//end remark


	private function remarkNotification($data,$complaint){
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Notification_model');

		$admin = $this->User_model->get_user(['role' => 'admin']);

		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint['id'], 'type'=>'assign','assigned_by' => 'admin'],'emp_id');

		if($admin){
			$history[] = ['emp_id' => $admin['id'],'role' => 'admin'];
		}
		
		// dd($history);
		if($history){
			foreach ($history as $key => $value) {
				
				if(isset($value['role'])){
					$role = $value['role'];
				}else{
					$role = 'employee';
				}

				$notificationData = array(
					'user_id' 			=> $value['emp_id'],
					'user_type' 		=> $role,
					'title' 			=> 'Complaint',
					'description' 		=> 'There is an update on the existing complaint from customer.'.ticketText($complaint['ticket_no']),
					'context_id' 		=> $complaint['id'],
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
				$this->Notification_model->add_notification($notificationData);
			}
		}//end if history
	}

	public function comment(){
		$this->load->model('ComplaintHistory_model');

		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));

		//Start validation
		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id, 'customer_id' => $this->userid]);

		if(!$complaint){
			sendResponse(0, 'Complaint details not found');
		}


    	//if status closed or deleted
		if( in_array($complaint['status'], [0,1,4])) { 
			sendResponse(0, 'You can not perform this action.');
		}

		//check mom doc
		if(isset($_FILES['mom_doc']['name']) && $_FILES['mom_doc']['name'] != ''){
			// $this->upload_path = 'documents/customer/complaint/'.$complaint_id.'/';

			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png|pdf|xlsx|doc|docx';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = $complaint_id.'-'.time().'-'.mt_rand(10000, 99999);
			$config['file_name'] = $file_name;

	        $this->load->library('upload', $config);

	        if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir( $this->upload_path , 0777, true);		        
		    }

        	if (!$this->upload->do_upload('mom_doc')) {
	            $error = $this->upload->display_errors();
	            sendResponse(0, $error);
	        } 

	        $image_data = $this->upload->data();

	        $file_name  =  $this->upload_path.$image_data['file_name'];
		}else{
			$file_name = '';
		}
        //end validation
		     		
		//insert
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['type'] 				= 'customer_comment';
		$data['remark'] 			= trim($this->input->post('comment',TRUE));
		$data['mom_doc'] 			= $file_name;
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Comment added successfully' ));

			//assigned employee and admin
			$this->commentNotification($data,$complaint);

			sendResponse(1,'Comment added successfully.');
		}else{
			sendResponse(0,'Failed to update data.');
		}

	}//end comment function 

	private function commentNotification($data,$complaint){
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Notification_model');

		$admin = $this->User_model->get_user(['role' => 'admin']);

		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint['id'], 'type'=>'assign','assigned_by' => 'admin'],'emp_id');

		if($admin){
			$history[] = ['emp_id' => $admin['id'],'role' => 'admin'];
		}
		
		// dd($history);
		if($history){
			foreach ($history as $key => $value) {
				
				if(isset($value['role'])){
					$role = $value['role'];
				}else{
					$role = 'employee';
				}

				$notificationData = array(
					'user_id' 			=> $value['emp_id'],
					'user_type' 		=> $role,
					'title' 			=> 'Complaint',
					'description' 		=> 'There is an update on the existing complaint from customer.'.ticketText($complaint['ticket_no']),
					'context_id' 		=> $complaint['id'],
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
				$this->Notification_model->add_notification($notificationData);
			}
		}
	}//end comment notification

	public function escalation(){
		$this->load->model('ComplaintHistory_model');

		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));

		//Start validation
		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id, 'customer_id' => $this->userid]);

		if(!$complaint){
			sendResponse(0, 'Complaint details not found');
		}

    	//if status closed or deleted
		if( in_array($complaint['status'], [0,4])) { 
			sendResponse(0, 'You can not perform this action.');
		}
		
		$diff_date = totalDaysBetTwo(date('Y-m-d'), date('Y-m-d', strtotime($complaint['created_at'])));
	
	  	if($diff_date < 1){
	  		sendResponse(0, 'Escalation can be performed after 1 day');
	  	}

	  	$today_escaltion = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' => $complaint_id, 'type' => 'customer_escalation'],'id',['raw_where' => 'DATE(created_at) = CURDATE()']);
	  	if($today_escaltion){
	  		sendResponse(0, 'You have already performed escalation today.');
	  	}
	  	//end validation


		//save
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['type'] 				= 'customer_escalation';
		$data['remark'] 			= trim($this->input->post('remark',TRUE));
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){
			$this->escalationMail($data,$complaint['ticket_no'],$diff_date);
			sendResponse(1,'Escalation mail sent.');
		}else{
			sendResponse(0,'Failed to send mail.');
		}

	}//end escalation function 


	private function escalationMail($data,$ticket_no,$diff_date){
		if(ALLOW_MAILS == 0){
			return false;
		}
		if($diff_date > 5){
			$to = 'VAIDYA.RAVINDRA@mahindra.com';
			$cc = ['BHAVE.PRADEEP@mahindra.com','PANDIT.NISHIKANT@mahindra.com'];
		}else{
			$to = 'BHAVE.PRADEEP@mahindra.com';
			$cc = ['PANDIT.NISHIKANT@mahindra.com'];
		}

		$to = 'nilesh@agency09.in';
		$cc = ['rasika@agency09.in','tech.agency09@gmail.com'];

		// $customer = $this->Customer_model->get_customer(['id' => $this->userid]);

		$admSubject = 'Complaint Escalation.'.ticketText($ticket_no);
		$admMsg 	= "
			Hello Team, <br><br>
			Complaint is escalated.<br><br>";

			if($data['remark']){
				$admMsg .="Customer comment : <br>".$data['remark']."<br><br>";
			}
			
		$admMsg .="
			Best,<br>
			Team Ticketing. ";

		$sendMail = $this->sendMail($to, $admSubject, $admMsg,FALSE,FALSE,$cc);
		
		
	} //end escalationMail

}

?>