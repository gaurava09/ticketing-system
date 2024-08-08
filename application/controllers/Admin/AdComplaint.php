<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdComplaint extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);

		if($this->is_customer()){
			redirect($this->CUST_LOGIN);
		}

		$this->load->model('User_model');
		$this->load->model('Customer_model');
		$this->load->model('Company_model');
		$this->load->model('Project_model');
		$this->load->model('Complaint_model');
		$this->load->model('ComplaintHistory_model');
		$this->load->model('Department_model');
		$this->load->model('Notification_model');
		
	}

	public function index() {
		//get GA _no list
		$list = [];
		$projects = $this->Project_model->get_projects([], 'ga_no');

		if($projects){
			foreach ($projects as $key => $value) {
				$list[] = array(
					   	'value' => $value['ga_no'],
			          	'label' => $value['ga_no'],
				);
			}
		}
		
		$data = [];
		$data['template'] 	= 'ad_complaint/ad_complaint_list';
		$data['title'] 		= "Complaint List";
		$data['data'] 		= '';
		$data['projects'] 	= $list;
		$this->load->view('default', $data);
	}

	public function list() {
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$params = $this->searchParam(['status','ticket_no','ga_no','complaint_type','classification','feedback']);

		} else{
			$params = $this->searchParam(['c.status' => 'status','ticket_no','ga_no','complaint_type','classification','action']);
		}	
		//dd($params);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$action_taken = '';
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$this->load->model('Feedback_model');

			## Total number of records without filtering
			$allres  = $this->Complaint_model->count();
			$totalRecords = $allres;

			## Total number of records with filtering
			$allres  = $this->Complaint_model->count($whereArr,$likeArr);
			$totalRecordwithFilter = $allres;

			$columns = 'id,ticket_no,ga_no,complaint_type,customer_id,status,created_by,created_at,classification,feedback';
			$list = $this->Complaint_model->get_complaints($whereArr,$columns,$startrow,$rowperpage , $likeArr);
		}else{
			$whereArr_a['h.emp_id'] = $this->userid;
            $whereArr_a['h.type'] = 'assign';
            $assignWhere = ['h.emp_id' => $this->userid,'h.type' => 'assign'];

            $columns1 = 'c.id,c.ticket_no,c.ga_no,c.complaint_type,c.customer_id,c.status,c.created_by,c.created_at,classification,feedback';
            $list1 = $this->Complaint_model->get_complaints_for_emp_new($whereArr_a,$columns1,$startrow,$rowperpage , $likeArr);
            // dd($this->pq());
            if (!empty($list1)) {
			    foreach ($list1 as &$item1_a) {
			        $item1_a['assign'] = 1;
			    }
			}

            $columns2 = 'id,ticket_no,ga_no,complaint_type,customer_id,status,created_by,created_at,classification,feedback';
			$list2 = $this->Complaint_model->get_complaints($whereArr,$columns2,$startrow,$rowperpage , $likeArr);
			if (!empty($list2)) {
			    foreach ($list2 as &$item2_a) {
			        $item2_a['assign'] = 0;
			    }
			}

            
            $list = array_merge($list1, $list2);
            ## Total number of records without filtering
            $totalRecords = $this->Complaint_model->get_complaints_for_emp_new($assignWhere,'c.id','','', $likeArr);
            $totalRecords = count($totalRecords);

            ## Total number of records with filtering
            
            $allres = $this->Complaint_model->get_complaints_for_emp_new($whereArr,'c.id',FALSE,FALSE, $likeArr);
            $totalRecordwithFilter = count($allres);
	  			
		}

		$complaint_types = complaint_types();
  		$classifications = classifications();

  		
		// dd($this->pq());
		$status_list = complaint_status_list();
		foreach ($list as $key => $value) {
			$unset = '';

			$customer = $this->Customer_model->get_customer_details(['c.id' => $value['customer_id']]);

			if($customer){
				$company = $this->Company_model->get_company(['name' => $customer['company_name']]);
		        if(!$company){
		            $this->sendFlashMsg(0,'Company data not found', 'company');
		        }
				//d($customer['company_name']);
				$list[$key]['company'] 	= cap($customer['company_name']);

				$assigned = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' => $value['id']],'created_by',['type' => 'assign']);
					//dd($assigned);
				if(!empty($assigned)){
				    $assigned_admin = $this->User_model->get_user(['id' => $assigned['created_by']],'id,first_name,last_name');
				    $list[$key]['assigned_emp'] = $assigned_admin['first_name'].' '.$assigned_admin['last_name'];
				}else{
					$list[$key]['assigned_emp'] = '-';
				}

			}

			if($this->role == 'employee'){
				$assigned = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' => $value['id']],'created_by',['type' => 'assign']);
					//dd($assigned);
				if(!empty($assigned)){
				    $assigned_admin = $this->User_model->get_user(['id' => $assigned['created_by']],'id,first_name,last_name');
				    $list[$key]['assigned_emp'] = $assigned_admin['first_name'].' '.$assigned_admin['last_name'];
				}else{
					$list[$key]['assigned_emp'] = '-';
				}
				/*$list[$key]['action_taken'] = '';
				if($value['solution'] != ''){
					$list[$key]['action_taken'] = 'Yes';
				}*/

				/*$raw_where = "(
        		(emp_id = '".$this->userid."' AND (type='remark' OR solution != '') )
        		OR 
        		(created_by = '".$this->userid."' AND type='assign' AND assigned_by='employee') 
        		)";*/


        		/*$raw_where = "(
        		(emp_id = '".$this->userid."' AND type='assign' AND top_dept=0 AND solution != '' )
        		OR
        		(emp_id = '".$this->userid."' AND type='remark')
        		OR 
        		(created_by = '".$this->userid."' AND type='assign' AND assigned_by='employee') 
        		)";

				$remark = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' => $value['id']],'id',['raw_where' => $raw_where]);
				$list[$key]['action_taken'] = (!empty($remark)) ? 'Yes' : '';

				// d($this->pq());

				//get action not taken
				$raw_where = "(emp_id = '".$this->userid."' AND type='assign' AND top_dept=0 AND solution = '' OR solution IS NULL ) ";

				$pending_action = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' => $value['id']],'id',['raw_where' => $raw_where]);

				// d($this->pq());
				$list[$key]['action_taken'] = (!empty($pending_action)) ? '' : $list[$key]['action_taken']; */

				$list[$key]['action_taken'] = '-';

				$action_taken = $this->Complaint_model->get_complaint_action(['complaint_id' => $value['id'], 'emp_id' =>$this->userid]);

				if(!empty($action_taken)){
					$list[$key]['action_taken'] = ($action_taken['status'] == 1) ? 'yes' : '-';
				}
				

				// echo '\n\n/n/n';
			/*	if($action_taken == 'no' && $remark){
					$unset = 1;
				}
				else if($action_taken == 'yes' && empty($remark)){
					$unset = 1;
				}*/
				
				
			}else{
				$list[$key]['action_taken'] = ''; 
			}
			// $list[$key]['status'] 	= $status_list[$value['status']];
			$list[$key]['complaint_type'] 	= $complaint_types[$value['complaint_type']];
			
			$list[$key]['classification'] = '';

			if(isset($classifications[$value['classification']])){
				$list[$key]['classification'] 	= $value['classification'].' - '.$classifications[$value['classification']];
			}
			
			$list[$key]['created_at'] 	= custDate($value['created_at']);

			$list[$key]['feedback'] = ($value['feedback'] == 1) ? 'Yes' : ' - ';

			/*if($unset){
				unset($list[$key]);
			}*/
			//dd($value);
			if ($value['created_by'] != '') {
				$created_by_arr = $array = explode("_", $value['created_by']);
			}else{
				$created_by_arr = array();
			}
			//dd($created_by_arr);
			if ($created_by_arr[0] == 'c') {
				$whereArr 	= ['id' => $created_by_arr[1]];
				$columns 	= 'id,first_name,last_name';
				$customer 	= $this->Customer_model->get_customer($whereArr,$columns);
				//dd($customer);
				$created_by = $customer['first_name'].' '.$customer['last_name'];
			}elseif ($created_by_arr[0] == 'sa') {
				$whereArr 	= ['id' => $created_by_arr[1]];
				$columns 	= 'id,first_name,last_name';
				$superAdmin = $this->User_model->get_user($whereArr,$columns);
				//dd($superAdmin);
				$created_by = $superAdmin['first_name'].' '.$superAdmin['last_name'];
				
			} else{
				$created_by = '';
			}	
			$list[$key]['created_by'] = $created_by;
		}
		//dd($list);
		// $list = array_values($list);

		/*if($action_taken){
			$totalRecords = $totalRecordwithFilter = count($list);
		}*/
		if ($this->role == 'admin' || $this->role == 'employee') {
			$login_user = "FIND_IN_SET('".$this->userid."', employees) > 0";
			$company_n = $this->Company_model->get_all_company('','','','', '', '',$login_user);
			//dd($company_n);
			if (empty($company_n)) {
				//dd($list);
				//$list=array();
				//$totalRecords=0;
				//$totalRecordwithFilter=0;
				$company_names = [];
				$filtered_array = array_filter($list, function($element) use ($company_names) {
				    return isset($element['assign']) && $element['assign'] == 1 || in_array($element, $company_names);
				});

				$response = array(
				 	"draw" 					=> intval($draw),
				 	"totalRecords" 			=> $totalRecords,
				 	"totalRecordwithFilter" => $totalRecordwithFilter,
				 	"aaData" 				=> $filtered_array
				);
			}else{
				//dd($list);
				foreach ($company_n as $item) {
				    $company_names[] = cap($item['name']);
				}
				//dd($list);
				$filtered_array = array_filter($list, function($element) use ($company_names) {
				    return in_array($element['company'], $company_names) || (isset($element['assign']) && $element['assign'] == 1);
				});

				$filtered_array = array_values(filter_unique_ticket_no($filtered_array));
				//$filtered_array = array_values($filtered_array);
				//$totalRecords = count($filtered_array);
				//$totalRecordwithFilter = count($filtered_array);
				$response = array(
				 	"draw" 					=> intval($draw),
				 	"totalRecords" 			=> $totalRecords,
				 	"totalRecordwithFilter" => $totalRecordwithFilter,
				 	"aaData" 				=> $filtered_array
				);
			}
		}else{
			$response = array(
			 	"draw" 					=> intval($draw),
			 	"totalRecords" 			=> $totalRecords,
			 	"totalRecordwithFilter" => $totalRecordwithFilter,
			 	"aaData" 				=> $list
			);
		}
		//dd($response);

		sendResponse(1, 'success', $response);
	}

	public function getCompanyData() {
		$id 					=  $this->input->get('id',TRUE);

		//get customers
		$whereArr 	= ['company_id' => $id];
		$columns 	= 'id,first_name,last_name';
		$customers 	= $this->Customer_model->get_customers($whereArr,$columns);


		if(!$customers){
			sendResponse(0, 'Customers not found');
		}

		$this->load->model('Equipment_model');


		/////////////////////////////////////////////changes for Project data not found///////////////////////////////////
		//get ga details
		// $projects = $this->Project_model->get_projects(['status' => 1], 'ga_no,equipment_id,model',FALSE,FALSE,FALSE,FALSE, $id);

		// // dd($this->pq());
		// $list = [];

		// if($projects){
		// 	foreach ($projects as $key => $value) {
		// 		$equipment = $this->Equipment_model->get_equipment(array('id' => $value['equipment_id']), 'name,model');
		// 			if($equipment){
		// 				$list[] = array(
		// 				   	'value' => $value['ga_no'],
		// 		          	'label' => $value['ga_no'],
		// 		          	'equipment_name' => $equipment['name'],
		// 		          	'equipment_model' => $value['model'],
		// 				);
		// 			}
		// 	}
		// }
		// if(!$list){
		// 	sendResponse(0, 'Project data not found');
		// }

		//sendResponse(1, 'success', array('customers' => $customers,'projects' => $list));
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		sendResponse(1, 'success', array('customers' => $customers));
	}
	
	public function create() {
		$this->is_admin(1);

		$list = [];

		$company = $this->Company_model->get_all_company(['status' => 1]);

		$data = [];
		$data['template'] 	= 'ad_complaint/ad_complaint_add';
		$data['title'] 		= "Add Complaint";
		$data['data'] 		= '';
		$data['company'] 	= $company;
		$this->load->view('default', $data);
	}

	public function store(){
		$this->is_admin(1);
		// echo count($_FILES); d($_POST); d($_FILES);
		$data 		= [];
		$file_array = [];

		$company_id				=  trim($this->input->post('company',TRUE));
		$customer_id 			=  trim($this->input->post('customer',TRUE));
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

		$this->upload_path = 'documents/customer/'.$customer_id.'/complaint/';

		$types = complaint_types();

		if( !array_key_exists($complaint_type, $types)){
			sendResponse(0, 'Invalid Compaint Type');
		}
		// if($complaint_type == 1){ //engg visit
		// 	$this->form_validation->set_rules('from_date', 'From Date', 'required');
		// 	$this->form_validation->set_rules('to_date', 'To Date', 'required');
		// 	// $this->form_validation->set_rules('order_no', 'Order No', 'required');

		// 	$data['from_date'] = $from_date;
		// 	$data['to_date'] = $to_date;
		// 	$data['order_no'] = $order_no;
		// }
		// else if($complaint_type == 3){ //Maintenance checkup
		// 	$this->form_validation->set_rules('from_date', 'From Date', 'required');
		// 	$this->form_validation->set_rules('to_date', 'To Date', 'required');

		// 	$data['from_date'] = $from_date;
		// 	$data['to_date'] = $to_date;
		// }

		$this->form_validation->set_rules('company', 'Company', 'required|exists[company.id]');
		$this->form_validation->set_rules('customer', 'Customer', 'required|exists[customers.id]');
		//$this->form_validation->set_rules('ga_no', 'GA No.', 'required|alpha_dash|exists[project.ga_no]');


		$this->form_validation->set_rules('complaint_type', 'Complaint Type', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		// $this->form_validation->set_rules('email_cc', 'Email CC', 'valid_email');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //check if company id, ga_no and customer id is co-related
        // $projects = $this->Project_model->get_project(array('company_id' => $company_id, 'ga_no' => $ga_no), 'ga_no');
        //$projects = $this->Project_model->get_project(['ga_no' => $ga_no], 'ga_no',$company_id);

        // if($projects){
        // 	$customer = $this->Customer_model->get_customer(array('id' => $customer_id, 'company_id' => $company_id), 'id');
        // 	if(!$customer){
        // 		sendResponse(0, 'Company and Customer mismatch');
        // 	}
        // }else{
        // 	sendResponse(0, 'GA No. does not match with Company.');
        // }

        if($complaint_type ==2 && isset($_FILES['file']) ){ //equi failure
        	$this->load->model('Files_model');

        	$this->load->library('upload');

        	if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir($this->upload_path, 0777, true);		        
		    }

           	//validate files
           	$imageLimit = 3;
            $maxfilesize = 3;
            $bytes_size = $maxfilesize * 1048576; //1048576 = 1mb

		    $files 	= $_FILES;
		    $cpt 	= count($_FILES['file']['name']);

		    if($cpt > $imageLimit){
		    	sendResponse(0, 'You can not upload more than '.$imageLimit.' images');
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
			    else if (($files['file']["size"][$i] > $bytes_size)) {
			        // $errors .= "<div class='error'>Image ".($i+1)." -Image size exceeds ".$maxfilesize." MB</div>";
			        $errors .= "<div class='error'>Image size exceeds ".$maxfilesize." MB. (".htmlspecialchars($files['file']["name"][$i]).")</div>";

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
		        $config['max_size'] = ($maxfilesize*1024); //1MB
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
		        		'user_id' => $customer_id,
		        		'status' => '1',
		        		'created_at' => getDt()
		        	);
		        }//end if file upload
		    } //end for

        }//end if compaint type 2
        $company = $this->Customer_model->get_customer(['id' => $customer_id],'company_id');
        $company_id = $company['company_id'];
       //Store
        $data['customer_id'] = $customer_id;
        $data['company_id'] = $company_id;
		// $data['company_id'] = $company_id;
        $data['ga_no'] = $ga_no;
		// $data['complaint_type'] = $types[$complaint_type];
		$data['complaint_type'] = $complaint_type;
		$data['description'] = $description;
		$data['cust_equipment_no'] = $cust_equipment_no;
		$data['email_cc'] = implode(",", $email_arr);
		$data['status'] = 2;
		$data['created_by'] = 'sa_'.$this->userid;
		$data['created_at'] = getDt();

		$complaint_id = $this->Complaint_model->add_complaint($data);
		if($complaint_id){
			$cust_detail = $this->Complaint_model->get_ticket_no(['company_id' => $company_id],'ticket_no');
			//d($cust_detail);
			$company_n = $this->Company_model->get_company(['id' => $company_id],'name');
			//dd($company_n);
			$ftl = strtoupper(substr($company_n['name'], 0, 2));
			if ($cust_detail['ticket_no'] != '') {
				$n_t_no = substr($cust_detail['ticket_no'], 2); // Remove the first two characters
				$t_no = $n_t_no + 1;
				//update ticket no
				$ticket_no = $ftl.''.str_pad($t_no, 4, '0', STR_PAD_LEFT);
				//dd($ticket_no);
			}else{
				$cust_detail = 1;
				$ticket_no = $ftl.''.str_pad($cust_detail, 4, '0', STR_PAD_LEFT);
			}

			$this->Complaint_model->update_complaint($complaint_id, ['ticket_no' => $ticket_no]);

			if($file_array){
				foreach ($file_array as $key => $value) {
					$value['complaint_id'] = $complaint_id;
					$this->Files_model->add_file($value);
				}
			}

			//add notification
				$notificationData = array(
					'user_id' 			=> $customer_id,
					'user_type' 		=> 'customer',
					'title' 			=> 'Complaint',
					'description' 		=> 'Complaint has been created on your behalf',
					'context_id' 		=> $complaint_id,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
				$this->Notification_model->add_notification($notificationData);
			//end notification

			//mail to customer and admin
				$this->complaintMail($data,$customer_id,$email_arr,$ticket_no);

				$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Ticket created successfully' ));

				sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create complaint');
		}
	}//end store coplaint


	private function complaintMail($data,$customer_id,$email_arr,$ticket_no){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$customer = $this->Customer_model->get_customer(['id' => $customer_id]);

		//admin mail
		if($this->email){
			$userData = $this->session->userdata($this->user);

			$admSubject = 'A New Complaint Has Been Registered.'.ticketText($ticket_no);
			$admMsg 	= "
				Hello ".ucfirst($userData['first_name']).", <br><br>

				We've received a complaint by the customer (Ticket no.".$ticket_no.").<br><br>

				Please process the complaint and assign an Engineer for the same.<br><br>

				Best Regards,<br>
				Team AGENCY09. ";

			$sendMail = $this->sendMail($this->email, $admSubject, $admMsg);
		}
		

		//customer mail
		if($customer){
			$custSubject = 'Complaint / Service Request Generated Successfully - Ticket No. '.$ticket_no;

			$custMsg 	= "
				Hello ".ucfirst($customer['first_name']).", <br><br>

				Thanks for reaching out to us. This email is to ensure that your complaint / service request has been successfully generated. We will revert shortly after we take a much closer look to understand and work on the issue. <br><br>

				Your Ticket no is ".$ticket_no.".<br><br>
				
				We assure you of our best services at all times.<br><br>

				Best Regards,<br>
				Team AGENCY09. ";

			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr);
		}
	}


	public function view($complaintId) {

		// if($this->is_employee()){
		// 	$canView = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaintId, 'emp_id' => $this->userid, 'type'=>'assign']);

  //       	if(!$canView ){
  //       		$this->sendFlashMsg(0,'Complaint details not found', 'complaint');
  //       	}
		// }

		$this->load->model('Files_model');

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId]);
		if($complaint){
			$files = $this->Files_model->get_files(['complaint_id' => $complaintId,'user_type' => 'customer']);

			$complaint['files'] = $files;

			//customer data
			// $customer = $this->Customer_model->get_customer(['id' => $complaint['customer_id']]);
			$customer = $this->Customer_model->get_customer_details(['c.id' => $complaint['customer_id']]);
			if(!$customer){
				$this->sendFlashMsg(0,'Customer data not found', 'complaint');
			}

		}else{
			$this->sendFlashMsg(0,'Ticket data not found', 'complaint');
		}

		$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));


		$department = $this->Department_model->get_departments([],'id,name,status,top_dept',
			array(
				'order' => ['top_dept' => 'DESC']
			)
		);
		// dd($department);
		//complaint history
		$topLevel = 0;
		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint['id']]);
		if($history){

			foreach ($history as $key => $value) {
				// $index = array_search($value['dept_id'], array_column($department, 'id'));
				// $history[$key]['dept_name'] = (isset($index)) ? $department[$index]['name'] : '';
				foreach ($department as $k => $dept) {
					if($value['dept_id'] == $dept['id']){
					 	$history[$key]['dept_name'] = $dept['name'];
					}
				}

				$empDetail = $this->User_model->get_user(['id' => $value['emp_id']], 'concat(first_name," ",last_name) as emp_name');

				$history[$key]['emp_name'] = (isset($empDetail['emp_name'])) ? $empDetail['emp_name'] : '';

				/*if($value['assigned_by'] == 'admin' && $this->userid == $value['emp_id']){
					$topLevel = 1;
				}
				else if($value['assigned_by'] == 'employee' && $this->userid == $value['emp_id'] && $value['solution'] == ''){
					$topLevel = 2;
				}*/
				if($value['top_dept'] == 1 && $this->userid == $value['emp_id']){
					$topLevel = 1;
				}
				else if($value['top_dept'] == 0 && $this->userid == $value['emp_id'] && $value['solution'] == ''){
					$topLevel = 2;
				}

				// if($value['assigned_by'] == 'employee'){
					$assigneeDetails = $this->User_model->get_user(['id' => $value['created_by']], 'concat(first_name," ",last_name) as assignee_name');

					$history[$key]['assignee_name'] = (isset($assigneeDetails['assignee_name'])) ? $assigneeDetails['assignee_name'] : '';
				// }
				
			}
		}
		// d($this->userid);
		// dd($topLevel);
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$topLevel = 1;
		}

		//customer convo id	
		$this->load->model('Chat_model');
		$custConvoId = $this->Chat_model->get_conversation(['user_id' => $this->userid,'customer_id' => $complaint['customer_id'], 'ticket_no' => $complaint['ticket_no']], 'id');

		$custConvoId = ($custConvoId) ? $custConvoId['id'] : '';
		

		// d($complaint);
		// d($customer);
		// dd($history);

		$data = [];
		$data['template'] 		= 'ad_complaint/ad_complaint_view';
		$data['title'] 			= "View Complaint";
		$data['complaint'] 		= $complaint;
		$data['history'] 		= $history;
		$data['customer'] 		= $customer;
		$data['project'] 		= $project;
		$data['department'] 	= $department;
		$data['topLevel'] 		= $topLevel;
		$data['custConvoId'] 		= $custConvoId;
		$this->load->view('default', $data);
	}	

	public function assign(){
		$this->load->model('Chat_model');

		//Start validation
		if($this->role == 'admin' || $this->role == 'super_admin'){
        	$assigned_by = 'admin'; 
        	$new_status = 3; //ongoing
        }else{
         	$assigned_by = 'employee'; 
         	$this->form_validation->set_rules('remark[]', 'Remark', 'required');
        }

		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('department[]', 'Department', 'required|integer');
		$this->form_validation->set_rules('employee[]', 'Employee', 'required|integer');
		

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $data 			= [];
		$complaint_id 	= $this->input->post('complaint_id',TRUE);
		$department 	= $this->input->post('department',TRUE);
		$employee 		= $this->input->post('employee',TRUE);
		$remark 		= $this->input->post('remark',TRUE);

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id]);
		$status = $complaint['status'];
		$new_status = 3;

		if( count($department) != count($employee) || count($department) != count($remark)) {
			sendResponse(0, 'Invalid count for department, employee and remark');
		}

		//if status closed or deleted
		if( in_array($status, [0,4])) { 
			sendResponse(0, 'You can not perform this action.');
		}

        $department_name = [];

		foreach ($department as $key => $value) {
			if($value != ''){
				$deptid = $value;
			}else{
				sendResponse(0, 'Assign'.($key+1).' : Employee data is empty');
			}
			

			//if employee and department are co-related
			if(isset($employee[$key]) && $employee[$key] != ''){
				$empid = $employee[$key];
				$found = $this->User_model->get_emp_details(['u.id' => $empid, 'dept.id' => $deptid]);

				if(!$found){
					sendResponse(0, 'Assign'.($key+1).' : Employee and department data mismatch');
				}

				//for sending email purpose
				$data[$key]['empdetails'] 	= $found;

				$department_name[] = $found['department_name'];

			}else{
				sendResponse(0, 'Assign'.($key+1).' : Employee data is empty');
			}

			if($empid == $this->userid){
				sendResponse(0, 'Assign'.($key+1).' : You can not assign to yourself');
			}

			if($this->role != 'admin' || $this->role == 'super_admin'){
				if(!isset($remark[$key]) || $remark[$key] == ''){
					sendResponse(0, 'Assign'.($key+1).' : Remark data is empty');
				}
			}
			

			//check if not already assigned with no solution[]
			// $isDuplicate = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $empid, 'type'=>'assign','assigned_by' => $assigned_by]);
			$isDuplicate = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $empid, 'type'=>'assign']);
			if($isDuplicate){
				if(trim($isDuplicate['solution']) == ''){
					sendResponse(0, 'Assign'.($key+1).' : You can not assign to same employee');
				}
			}


			$data[$key]['complaint_id'] 		= $complaint_id;
			$data[$key]['dept_id'] 				= $deptid;
			$data[$key]['emp_id'] 				= $empid;
			$data[$key]['remark'] 				= $remark[$key];
			$data[$key]['type'] 				= 'assign';
			$data[$key]['assigned_by'] 			= $assigned_by;
			$data[$key]['top_dept'] 			= $found['top_dept'];
			$data[$key]['prev_status'] 			= $status;
			$data[$key]['new_status'] 			= $new_status;
			$data[$key]['created_by'] 			= $this->userid;
			$data[$key]['created_at'] 			= getDt();
		}
		//end validation
		
		// dd($employee);

		// dd($data);
		$msg = '';
		$success = 0;
		$failed = 0;

		$mail_details = [];
		$product_text ='';

		$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));
		if($project){
			$product_text = 'about '.$project['equipment_name'];
		}

		$notify_customer = 0;

		foreach ($data as $key => $value) {
			$empdetails = $value['empdetails'];
			unset($value['empdetails']);
			$insert = $this->ComplaintHistory_model->add_complaint_history($value);
			if($insert){

				//store emp action for easy retrieval.action pending for assigned emp
				$this->Complaint_model->add_complaint_action([
	                'complaint_id' => $complaint['id'],
	                'emp_id' => $value['emp_id'],
	                'admin_id' => $this->userid,
	                'created_at' => getDt()
	               ]);


				//emp mail setup
				$success = 1;

				$this->assignNotification($value['emp_id'],'employee','A new customer complaint has been assigned to you.'.ticketText($complaint['ticket_no']),$complaint_id);

				$emp_body = 'Hello '.ucfirst($empdetails['first_name']).', <br><br>
				
				There is a new ticket in the queue to be resolved and following are the details

				Ticket no - '.$complaint['ticket_no'].'<br>
				Ticket Type - '.complaintTName($complaint['complaint_type']).'<br><br>

				Best Regards,<br>
				Team AGENCY09.';

				$mail_details[] = array(
					'email' => $empdetails['email'],
					'subject' => ticketText($complaint['ticket_no']).' assigned to you.',
					'body' => $emp_body
				);

				//add to chat conversion table
				// if($this->role == 'admin'){
				if($value['top_dept'] == 1){
					$notify_customer = 1;
					
					$this->Chat_model->add_conversation(
						array(
							'user_id' => $value['emp_id'],
							'customer_id' => $complaint['customer_id'],
							'ticket_no' => $complaint['ticket_no'],
							'created_at' => getDt(),
						)
					);
				}

			}else{
				$failed = 1;
				$msg .= 'Assign'.($key+1).' : Failed to insert data. ';
			}
		} //end foreach

		//send notification and mail to customer
		// if($this->role == 'admin'){
		if($notify_customer){
			//$this->assignNotification($complaint['customer_id'],'customer','Your ticket have been assigned to an engineer',$complaint_id);

			//mail to customer about assign emp
			$customer = $this->Customer_model->get_customer(['id' => $complaint['customer_id']]);
			if($customer){
				$email_arr= '';
				if($complaint['email_cc']){
					$email_arr = explode(",", $complaint['email_cc']);
				}

				$department_name = array_unique($department_name);
				$department_name = implode(",", $department_name);

				$custBody = 'Hello '.ucfirst($customer['first_name']).', <br><br>
				
				We are writing this to inform you that your complaint (Ticket no. '.$complaint['ticket_no'].') has been assigned to '.$department_name.'. <br><br>

				Please check customer support portal for further details.<br><br>
				
				Best Regards,<br>
				Team AGENCY09.';

				// $mail_details[] = array(
				// 	'email' => $customer['email'],
				// 	'subject' => 'Your Complaint have been assigned to the respective department. Ticket No. '.$complaint['ticket_no'],
				// 	'body' => $custBody,
				// 	'cc' => $email_arr
				// );
			}
		}

		//update complaint status when assigned by admin. status to ongoing
		// if($this->role == 'admin'){
        	$updateData = array('status' => $new_status);
			$this->Complaint_model->update_complaint($complaint_id,$updateData);
        // }

		//user has performed an action
		$this->Complaint_model->update_complaint_action(['status' => 1], [
                'complaint_id' => $complaint['id'],
                'emp_id' => $this->userid
               ]);

        //send mails
        if($mail_details){
        	foreach ($mail_details as $key => $value) {
        		$email_arr = (isset($value['cc'])) ? $value['cc'] : '';
        		$this->assignMail($value['email'], $value['subject'], $value['body'], $email_arr);
        	}
        }

		if($success == 1 && $failed == 0){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Ticket assigned successfully' ));
			sendResponse(1,'Success');
		}
		else if($success == 1 && $failed == 1){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Ticket assigned successfully.'.$msg ));
			sendResponse(1,'Success');
		}else{
			sendResponse(0,'Failed to assign Ticket.');
		}
		
	}//end assign function

	private function assignNotification($user_id,$user_type,$description,$complaint_id){
		//add notification
		$notificationData = array(
			'user_id' 			=> $user_id,
			'user_type' 		=> $user_type,
			'title' 			=> 'Assign',
			'description' 		=> $description,
			'context_id' 		=> $complaint_id,
			'created_by' 		=> $this->userid,
			'created_by_type' 	=> $this->role,
			'status' 			=> 1,
			'created_at' 		=> getDt()
		);
		$this->Notification_model->add_notification($notificationData);
	//end notification
	}

	private function assignMail($email,$subject,$msg,$cc_email=''){
		if(ALLOW_MAILS == 0){
			return false;
		}

		$sendMail = $this->sendMail($email,$subject,$msg,FALSE,FALSE,$cc_email);
	}

	public function remark(){
		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));
		$new_status 	= trim($this->input->post('status',TRUE));
		$action_type 	= trim($this->input->post('action_type',TRUE));
		
		//$visit_date 	= trim($this->input->post('visit_date',TRUE));

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id]);
		$prev_status 	= $complaint['status'];

		$complaint_type =$complaint['complaint_type'];
		// $complaint_type = strtolower($complaint['complaint_type']);
		// $engg_visit 	= 'request for engineer visit';
		 

		//Start validation
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$this->form_validation->set_rules('status', 'Status', 'in_list[0,1,2,3,4]');
		}else{
			$this->form_validation->set_rules('status', 'Status', 'in_list[1,3]');
		}

		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('remark', 'Remark', 'required');
		//$this->form_validation->set_rules('visit_date', 'Visit Date', 'date');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //check if user can remark. only admin or emp assigned by admin. 
         if($this->role == 'employee'){
        	// $canRemark = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','assigned_by' => 'admin','top_dept' => 1]);
        	$canRemark = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','top_dept' => 1]);

        	if(!$canRemark ){
        		sendResponse(0, 'You are not authorized to remark');
        	}
        	$assigned_by = 'admin';

        	//if status closed or deleted
			if( in_array($prev_status, [0,4])) { 
				sendResponse(0, 'You can not perform this action.');
			}
         }else{
         	$assigned_by = '';
         }

         //check mom doc
		if(isset($_FILES['mom_doc']['name']) && $_FILES['mom_doc']['name'] != ''){
			$this->upload_path = 'documents/admin/complaint/'.$complaint_id.'/';

			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png|pdf|xlsx|doc|docx';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = 'mom-'.time().'-'.mt_rand(10000, 99999);
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
		     		
		
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['emp_id'] 			= $this->userid;
		$data['remark'] 			= trim($this->input->post('remark',TRUE));
		$data['type'] 				= 'remark';
		$data['reply_to'] 			= 'customer';
		$data['mom_doc'] 			= $file_name;
		$data['assigned_by'] 		= $assigned_by;
		$data['top_dept'] 			= 1;
		$data['prev_status'] 		= $prev_status;
		$data['new_status'] 		= $new_status;
		//$data['visit_date'] 		= $visit_date;
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){
			$updateData = array('status' => $new_status, 'updated_by' => $this->userid);
			$this->Complaint_model->update_complaint($complaint_id,$updateData);


			//update emp action for easy retrieval. dat he performed an action
			$this->Complaint_model->update_complaint_action(['status' => 1],[
	                'complaint_id' => $complaint_id,
	                'emp_id' => $this->userid
	               ]);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Status updated successfully' ));

			//complaint is closed. notify customer for feedback form
			if(($this->role == 'admin' || $this->role == 'super_admin') && $new_status == 4){
				$this->load->model('Feedback_model');
				$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaint_id]);
				if(!$feedback){
					$remark_msg = 'Please give feedback on your complaint.'.ticketText($complaint['ticket_no']);
					$this->remarkNotification($data,$complaint,$remark_msg);
				}
			}

			//add notification to customer
			$remark_msg = 'There is an update on the existing complaint.'.ticketText($complaint['ticket_no']);
			//$this->remarkNotification($data,$complaint,$remark_msg);

			//remark notification among team ie admin and employee
			if($this->role == 'employee' && $new_status == 1){
				$this->userRemarkNotification($data,$complaint,1); //notify admin also
			}
			else if($this->role == 'employee'){
				$this->userRemarkNotification($data,$complaint,0); //skip admin
			}
			// dd($data);
			// dd($complaint);

			//remark email to customer and admin[bcc]
			// if($complaint_type == $engg_visit && $visit_date != ''){ //engg visit for all complaint
			// if($visit_date != ''){
			// 	$this->enggVisitMail($data,$complaint);
			// }else{
			if ($action_type == 3) {
				$this->remarkAdminMail($data,$complaint);
			}else{
				$this->remarkMail($data,$complaint);
			}
			//}
			

			sendResponse(1,'Status updated successfully.');
		}else{
			sendResponse(0,'Failed to update Ticket.');
		}

	}//end remark

	public function remarkEmp(){
		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));
		$new_status 	= trim($this->input->post('status',TRUE));
		//$visit_date 	= trim($this->input->post('visit_date',TRUE));

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id]);
		$prev_status 	= $complaint['status'];

		$complaint_type =$complaint['complaint_type'];
		// $complaint_type = strtolower($complaint['complaint_type']);
		// $engg_visit 	= 'request for engineer visit';
		 

		//Start validation
		if($this->role == 'admin' || $this->role == 'super_admin'){
			$this->form_validation->set_rules('status', 'Status', 'in_list[0,1,2,3,4]');
		}else{
			$this->form_validation->set_rules('status', 'Status', 'in_list[1,3]');
		}

		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('remark', 'Remark', 'required');
		//$this->form_validation->set_rules('visit_date', 'Visit Date', 'date');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //check if user can remark. only admin or emp assigned by admin. 
         if($this->role == 'employee'){
        	// $canRemark = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','assigned_by' => 'admin','top_dept' => 1]);
        	$canRemark = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','top_dept' => 1]);

        	if(!$canRemark ){
        		sendResponse(0, 'You are not authorized to remark');
        	}
        	$assigned_by = 'admin';

        	//if status closed or deleted
			if( in_array($prev_status, [0,4])) { 
				sendResponse(0, 'You can not perform this action.');
			}
         }else{
         	$assigned_by = '';
         }

         //check mom doc
		if(isset($_FILES['mom_doc']['name']) && $_FILES['mom_doc']['name'] != ''){
			$this->upload_path = 'documents/admin/complaint/'.$complaint_id.'/';

			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png|pdf|xlsx|doc|docx';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = 'mom-'.time().'-'.mt_rand(10000, 99999);
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
		     		
		
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['emp_id'] 			= $this->userid;
		$data['remark'] 			= trim($this->input->post('remark',TRUE));
		$data['type'] 				= 'remark';
		$data['reply_to'] 			= 'employee';
		$data['mom_doc'] 			= $file_name;
		$data['assigned_by'] 		= $assigned_by;
		$data['top_dept'] 			= 1;
		$data['prev_status'] 		= $prev_status;
		$data['new_status'] 		= $new_status;
		//$data['visit_date'] 		= $visit_date;
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){
			$updateData = array('status' => $new_status, 'updated_by' => $this->userid);
			$this->Complaint_model->update_complaint($complaint_id,$updateData);


			//update emp action for easy retrieval. dat he performed an action
			$this->Complaint_model->update_complaint_action(['status' => 1],[
	                'complaint_id' => $complaint_id,
	                'emp_id' => $this->userid
	               ]);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Status updated successfully' ));

			//complaint is closed. notify customer for feedback form
			if(($this->role == 'admin' || $this->role == 'super_admin') && $new_status == 4){
				$this->load->model('Feedback_model');
				$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaint_id]);
				if(!$feedback){
					$remark_msg = 'Please give feedback on your complaint.'.ticketText($complaint['ticket_no']);
					$this->remarkNotification($data,$complaint,$remark_msg);
				}
			}

			//add notification to customer
			$remark_msg = 'There is an update on the existing complaint.'.ticketText($complaint['ticket_no']);
			$this->remarkNotification($data,$complaint,$remark_msg);

			//remark notification among team ie admin and employee
			if($this->role == 'employee' && $new_status == 1){
				$this->userRemarkNotification($data,$complaint,1); //notify admin also
			}
			else if($this->role == 'employee'){
				$this->userRemarkNotification($data,$complaint,0); //skip admin
			}
			// dd($data);
			// dd($complaint);
			//remark email to customer and admin[bcc]
			// if($complaint_type == $engg_visit && $visit_date != ''){ //engg visit for all complaint
			// if($visit_date != ''){
			// 	$this->enggVisitMail($data,$complaint);
			// }else{
				$this->remarkEmpMail($data,$complaint);
			//}
			

			sendResponse(1,'Status updated successfully.');
		}else{
			sendResponse(0,'Failed to update Ticket.');
		}

	}//end remarkEmp

	private function userRemarkNotification($data,$complaint,$notify_admin = 0){
		
		// $history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint['id'], 'type'=>'assign','assigned_by' => 'admin','top_dept' => 1],'emp_id');
		$history = $this->ComplaintHistory_model->get_all_complaint_history(['complaint_id' =>$complaint['id'], 'type'=>'assign','top_dept' => 1],'emp_id');

		if($notify_admin){
			$admin = $this->User_model->get_user(['role' => 'admin']);
			if($admin){
				$history[] = ['emp_id' => $admin['id'],'role' => 'admin'];
			}
		}
		
		// dd($history);
		if($history){
			foreach ($history as $key => $value) {
				
				if($value['emp_id'] == $this->userid){
					continue;
				}

				if(isset($value['role'])){
					$role = $value['role'];
				}else{
					$role = 'employee';
				}

				$notificationData = array(
					'user_id' 			=> $value['emp_id'],
					'user_type' 		=> $role,
					'title' 			=> 'Complaint',
					'description' 		=> 'There is an update on the existing complaint.'.ticketText($complaint['ticket_no']),
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

	private function remarkNotification($data,$complaint,$remark_msg){

		$notificationData = array(
			'user_id' 			=> $complaint['customer_id'],
			'user_type' 		=> 'customer',
			'title' 			=> 'Complaint',
			'description' 		=> $remark_msg,
			'context_id' 		=> $complaint['id'],
			'is_read' 			=> 0,
			'created_by' 		=> $this->userid,
			'created_by_type' 	=> $this->role,
			'status' 			=> 1,
			'created_at' 		=> getDt()
		);
		$this->Notification_model->add_notification($notificationData);
	}

	private function enggVisitMail($data,$complaint){
		if(ALLOW_MAILS == 0){
			return false;
		}
		$customer = $this->Customer_model->get_customer(['id' => $complaint['customer_id']]);

		$firstname = ($customer) ? ucfirst($customer['first_name']) : '';
		$lastname = ($customer) ? ucfirst($customer['last_name']) : '';
		//customer mail
		if($customer){
			$email_arr = [];
			if($complaint['email_cc']){
				$email_arr = explode(",", $complaint['email_cc']);
			}

			$custSubject = 'Engineer Visit Has Been Scheduled. Ticket No. '.$complaint['ticket_no'];
			$custMsg 	= '
					Hello '.$firstname.', <br><br>

					The Engineer visit on your ticket no. '.$complaint['ticket_no'].' have been scheduled on '.$data['visit_date'].'.<br><br>

					Please be available at the given address to assist the engineer with all the necessary information. <br><br>

					Best Regards, <br>
					Team AGENCY09. ';
			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr);
		}

		//admin mail
		$admin = $this->User_model->get_user(['role' => 'admin']);

		if($admin){
			$admSubject = 'Engineer Visit Scheduled For Customer '.$firstname.' '.$lastname.'.'.ticketText($complaint['ticket_no']);
			$admMsg 	= '
					Hello '.ucfirst($admin['first_name']).', <br><br>

				The Engineer visit for ticket no. '.$complaint['ticket_no'].' have been scheduled on '.$data['visit_date'].'.<br><br>

				Please note this down for the records. <br><br>

				Best Regards, <br>
				Team AGENCY09. ';
			$sendMail = $this->sendMail($admin['email'], $admSubject, $admMsg);
		}
	}
	
	private function remarkMail($data,$complaint){
		// dd($data);
		// dd($complaint);
		if(ALLOW_MAILS == 0){
			return false;
		}
		$customer = $this->Customer_model->get_customer(['id' => $complaint['customer_id']]);

		$bcc = [];
		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$bcc[] = $admin['email'];
		}

		//customer mail
		if($customer){

			$email_arr = [];
			if($complaint['email_cc']){
				$email_arr = explode(",", $complaint['email_cc']);
			}

			$custSubject = 'Ticket updates : Ticket No. '.$complaint['ticket_no'];
			$custMsg 	= 'Hello '.ucfirst($customer['first_name']).', <br><br>

				There is a remark on your ticket<br><br>

				Ticket no - '.$complaint['ticket_no'].'<br>
				Ticket Remark - '.$data['remark'].'<br>
				Ticket Status - '.complaintStatus($data['new_status']).'<br><br>

				Please check customer support portal for further details. <br><br>

				Best Regards, <br>
				Team AGENCY09.';

			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr,$bcc);
		}
	}
	
	private function remarkEmpMail($data,$complaint){
		// dd($data);
		// dd($complaint['complaint_id']);
		if(ALLOW_MAILS == 0){
			return false;
		}
		$asigned_emp = $this->Complaint_model->get_complaint_action(['complaint_id' => $data['complaint_id']]);

		$employee = $this->User_model->get_user(['id' => $asigned_emp['emp_id']]);

		$bcc = [];
		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$bcc[] = $admin['email'];
		}

		//employee mail
		if($employee){

			$email_arr = [];
			if($complaint['email_cc']){
				$email_arr = explode(",", $complaint['email_cc']);
			}

			$custSubject = 'Ticket updates : Ticket No. '.$complaint['ticket_no'];
			$custMsg 	= 'Hello '.ucfirst($employee['first_name']).', <br><br>

				There is a remark on your ticket<br><br>

				Ticket no - '.$complaint['ticket_no'].'<br>
				Ticket Remark - '.$data['remark'].'<br>
				Ticket Status - '.complaintStatus($data['new_status']).'<br><br>

				Please check Customer support portal for further details. <br><br>

				Best Regards, <br>
				Team AGENCY09.';

			$sendMail = $this->sendMail($employee['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr,$bcc);
		}
	}

	private function remarkAdminMail($data,$complaint){
		// dd($data);
		// dd($complaint['complaint_id']);
		if(ALLOW_MAILS == 0){
			return false;
		}
		$asigned_emp = $this->Complaint_model->get_complaint_action(['complaint_id' => $data['complaint_id']]);

		$employee = $this->User_model->get_user(['id' => $asigned_emp['admin_id']]);

		$bcc = [];
		$admin = $this->User_model->get_user(['role' => 'admin']);
		if($admin){
			$bcc[] = $admin['email'];
		}

		//employee mail
		if($employee){

			$email_arr = [];
			if($complaint['email_cc']){
				$email_arr = explode(",", $complaint['email_cc']);
			}

			$custSubject = 'Ticket updates : Ticket No. '.$complaint['ticket_no'];
			$custMsg 	= 'Hello '.ucfirst($employee['first_name']).', <br><br>

				There is a remark on your ticket<br><br>

				Ticket no - '.$complaint['ticket_no'].'<br>
				Ticket Remark - '.$data['remark'].'<br>
				Ticket Status - '.complaintStatus($data['new_status']).'<br><br>

				Please check Customer support portal for further details. <br><br>

				Best Regards, <br>
				Team AGENCY09.';

			$sendMail = $this->sendMail($employee['email'], $custSubject, $custMsg,FALSE,FALSE,$email_arr,$bcc);
		}
	}
	public function solution(){

		$this->is_employee(1);

		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));

		//Start validation
		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('remark', 'Remark', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //check if user can send comment/sollution. only emp assigned by employee. 
        // $where = ['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','assigned_by' => 'employee','solution'=>''];

        //check if user can send comment/sollution. only top dept = 0
        $where = ['complaint_id' =>$complaint_id, 'emp_id' => $this->userid, 'type'=>'assign','top_dept' => 0,'solution'=>''];
    	$canRemark = $this->ComplaintHistory_model->get_complaint_history($where);

    	// dd($canRemark);
    	if(!$canRemark ){
    		sendResponse(0, 'You are not authorized to remark.');
    	}


    	//if status closed or deleted
    	$complaint = $this->Complaint_model->get_complaint(['id' => $complaint_id],'status');
		if( in_array($complaint['status'], [0,4])) { 
			sendResponse(0, 'You can not perform this action.');
		}

		//check mom doc
		if(isset($_FILES['mom_doc']['name']) && $_FILES['mom_doc']['name'] != ''){
			$this->upload_path = 'documents/admin/complaint/'.$complaint_id.'/';

			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png|pdf|xlsx|doc|docx';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = 'mom-'.time().'-'.mt_rand(10000, 99999);
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
		     		
		//update
		$data 						= [];
		$data['solution'] 			= trim($this->input->post('remark',TRUE));
		// $data['mom_text'] 			= trim($this->input->post('mom_text',TRUE));
		$data['mom_doc'] 			= $file_name;
		$data['updated_by'] 		= $this->userid;
		
		$this->ComplaintHistory_model->update_complaint_history($where,$data);


		//update emp action for easy retrieval. that he performed an action
		$this->Complaint_model->update_complaint_action(['status' => 1], [
                'complaint_id' => $complaint_id,
                'emp_id' => $this->userid
               ]);

		//if complaint assigned by emp, add ticket to PENDING for that emp [top dept employee]
    	if($canRemark['assigned_by'] == 'employee'){
    		$assigned_by_emp_id = $canRemark['created_by'];

    		$this->Complaint_model->update_complaint_action(['status' => 0], [
                'complaint_id' => $complaint_id,
                'emp_id' => $assigned_by_emp_id
               ]);
    	}

		$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Remark added successfully' ));

		//solution email and notification to employee for comments
		//later added to admin also
		$this->solutionMail($data,$canRemark,$complaint_id);
		sendResponse(1,'Remark added successfully.');

	}//end solution

	private function solutionMail($data,$assignHistory,$complaint_id){ //comment mail
		if(ALLOW_MAILS == 0){
			return false;
		}
		//$ticket_no = str_pad($complaint_id, 6, '0', STR_PAD_LEFT);
		$cust_detail = $this->Complaint_model->get_ticket_no(['company_id' => $company_id],'ticket_no');
		//d($cust_detail);
		$company_n = $this->Company_model->get_company(['id' => $company_id],'name');
		//dd($company_n);
		$ftl = strtoupper(substr($company_n['name'], 0, 2));
		if ($cust_detail['ticket_no'] != '') {
			$n_t_no = substr($cust_detail['ticket_no'], 2); // Remove the first two characters
			$t_no = $n_t_no + 1;
			//update ticket no
			$ticket_no = $ftl.''.str_pad($t_no, 4, '0', STR_PAD_LEFT);
			//dd($ticket_no);
		}else{
			$cust_detail = 1;
			$ticket_no = $ftl.''.str_pad($cust_detail, 4, '0', STR_PAD_LEFT);
		}

		$emp = $this->User_model->get_emp_details(['u.id' => $this->userid]);

		$assignee = $this->ComplaintHistory_model->get_all_complaint_history(array(
			'complaint_id' => $complaint_id,
			'type' => 'assign',
			// 'assigned_by' => 'admin',
			//'top_dept' => 1,
		), 'emp_id');

		// d($emp);
		// dd($assignee);

		if($emp){

			$notificationData = array(
					'user_type' 		=> 'employee',
					'title' 			=> 'Complaint',
					'description' 		=> 'There is a comment on complaint no. '.$ticket_no.' from '.ucfirst($emp['first_name']),
					'context_id' 		=> $complaint_id,
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
			


			$subject = 'Comment on the ticket no. '.$ticket_no.' from '.$emp['department_name'].' Department';
			$msg 	= '
			Hello Team, <br><br>

			We have received new comment on ticket no. '.$ticket_no.' from '.ucfirst($emp['first_name']).'.<br><br>

			Get in touch if there is any query.<br><br>

			Best Regards,<br>
			Team AGENCY09. ';

			if($assignee){
				foreach ($assignee as $key => $value) {
					$user = $this->User_model->get_user(['id' => $value['emp_id'], 'status' => 1],'id,email');

					if($user){
						$notificationData['user_id'] = $user['id'];

						$this->Notification_model->add_notification($notificationData);

						$sendMail = $this->sendMail($user['email'], $subject, $msg);
					}
				}
			}//end if assignee


			//admin mail and notification
			$admin = $this->User_model->get_user(['role' => 'admin']);
			
			if($admin){
				$notificationData['user_type'] = 'admin';
				$notificationData['user_id'] = $admin['id'];

				$this->Notification_model->add_notification($notificationData);
				$sendMail = $this->sendMail($admin['email'], $subject, $msg);
			}
		}//end if emp
	}//end function

	public function classification(){

		$complaint_id 	= trim($this->input->post('complaint_id',TRUE));
		$class 			= trim($this->input->post('class',TRUE));

		$classifications = classifications();

		$keys = array_keys($classifications);
		$keys = implode(",", $keys);
		

		//Start validation
		$this->form_validation->set_rules('complaint_id', 'Complaint', 'required|exists[complaint.id]');
		$this->form_validation->set_rules('class', 'Classification', 'required|in_list['.$keys.']');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        // dd($keys);
        //end validation
		     		
		$data 						= [];
		$data['complaint_id'] 		= $complaint_id;
		$data['emp_id'] 			= $this->userid;
		$data['remark'] 			= $class;
		$data['type'] 				= 'classification';
		$data['created_by'] 		= $this->userid;
		$data['created_at'] 		= getDt();
		
		// dd($data);
		$insert = $this->ComplaintHistory_model->add_complaint_history($data);
		if($insert){

			$updateData = array('classification' => $class, 'updated_by' => $this->userid);
			$this->Complaint_model->update_complaint($complaint_id,$updateData);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Classification updated successfully' ));

			sendResponse(1,'Classification updated successfully.');
		}else{
			sendResponse(0,'Failed to update Ticket.');
		}

	}//end classification

	function export()
    {	
    	$this->load->model('Equipment_model');

		if($this->role == 'admin' || $this->role == 'super_admin'){
			$params = $this->searchParam(['status','ticket_no','ga_no','complaint_type','classification','feedback']);

		} else{
			$params = $this->searchParam(['status','ticket_no','ga_no','complaint_type','classification','action']);
		}

		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		if($this->role == 'admin' || $this->role == 'super_admin'){
			$columns = '*';
			$list = $this->Complaint_model->get_complaints($whereArr,$columns,FALSE,FALSE, $likeArr);
			/*set column names*/
        	$table_columns = array('Sr No', 'Ticket ID','Customer','Company','GA Number','Equipment','Model No','Customer Equipment No','Complaint Type','Classification','Feedback Submitted','From Date','To Date','Order No','Message','Status','Complaint Date','Completion date');
		}else{
			$whereArr['h.emp_id'] = $this->userid;
			$whereArr['h.type'] = 'assign';
			$columns = '*';
			$list = $this->Complaint_model->get_complaints_for_emp_new($whereArr,$columns,FALSE,FALSE, $likeArr);

			/*set column names*/
        	$table_columns = array('Sr No', 'Ticket ID','Customer','Company','GA Number','Equipment','Model No','Customer Equipment No','Complaint Type','Classification','From Date','To Date','Order No','Message','Status','Complaint Date','Completion date');
		}

		// dd($this->pq());
		$status_list = complaint_status_list();
		foreach ($list as $key => $value) {
			//customer data
			$customer = $this->Customer_model->get_customer_details(['c.id' => $value['customer_id']]);
			if($customer){
				$list[$key]['customer'] 	= ucfirst($customer['first_name']).' '.ucfirst($customer['last_name']);
				$list[$key]['company'] 	= $customer['company_name'];
			}else{
				$list[$key]['customer'] ='';
				$list[$key]['company'] 	='';
			}

			//equipment data
			$equiDetails = $this->Equipment_model->get_equipemnt_details_by_project(['p.ga_no' => $value['ga_no']],'e.name,p.model');

			$list[$key]['equipment'] = ($equiDetails) ? $equiDetails['name'] : '';
			$list[$key]['model'] = ($equiDetails) ? $equiDetails['model'] : '';

			$list[$key]['status'] 	= $status_list[$value['status']];
			$list[$key]['created_at'] 	= custDate($value['created_at']);


			$list[$key]['completed_at'] = '';

			//if closed, add completion date
			if($value['status'] == 4){
				$whereHistory = array(
					'order_by' => ['id' => 'desc'],
					'limit' => 1,
				);
				// $history = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$value['id'], 'type'=>'remark','assigned_by' => ''],'*',$whereHistory);
				$history = $this->ComplaintHistory_model->get_complaint_history(['complaint_id' =>$value['id'], 'type'=>'remark'],'*',$whereHistory);
				if($history){
					$list[$key]['completed_at'] 	= custDate($history['created_at']);
				}
			}
		}
		// dd($list);
// die;
		// dd($list);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        
        $column = 1;
        foreach ($table_columns as $field) {
            $sheet->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        /*end set column names*/

        $excel_row = 2; //now from row 2

        $complaint_types = complaint_types();
        $classifications = classifications();

        foreach ($list as $key=>$row) {

        	$class_e = isset($classifications[$row['classification']]) ? $classifications[$row['classification']] : '';
        	$feedback = ($row['feedback'] == 1) ? 'Yes' : '-';

        	$col_count = 1;

            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, ($key+1));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['ticket_no']);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['customer']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['company']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['ga_no']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['equipment'])); //Equipment
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['model'])); //model
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['cust_equipment_no']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $complaint_types[$row['complaint_type']]);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $class_e);

            if($this->role == 'admin' || $this->role == 'super_admin'){
           	 	$sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $feedback);
            }

            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['from_date']);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['to_date']);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['order_no']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, clean_cell_formula($row['description']));
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['status']);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['created_at']);
            $sheet->setCellValueByColumnAndRow($col_count++, $excel_row, $row['completed_at']);
            $excel_row++;
        }
        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ticket_list.xlsx"');
        $writer->save('php://output');
    }//end export

}

?>