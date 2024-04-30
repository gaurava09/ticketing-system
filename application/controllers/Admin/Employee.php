<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);

		if($this->is_customer()){
			redirect($this->CUST_LOGIN);
		}

		$this->canAccess('employee',['empByDepartment'],'complaint');

		$this->load->model('User_model');
		$this->load->model('Department_model');
		$this->load->model('Designation_model');
		$this->load->model('Country_model');

		$this->emp_domain = 'tsubaki-conveyor.in';
	}

	public function index() {

		$departments = $this->Department_model->get_departments([],'id,name');

		$data = [];
		$data['template'] 	= 'employee/emp_list';
		$data['title'] 		= "Employee List";
		$data['data'] 		= '';
		$data['departments'] = $departments;
		$this->load->view('default', $data);
	}

	public function list() {        
        $params = $this->searchParam(['status','department_id'],['first_name' => 'name', 'last_name' => 'name','mobile','email']);

        $draw       = $params['draw'];
        $startrow   = $params['startrow'];
        $rowperpage = $params['rowperpage'];
        $whereArr   = $params['where'];
        $likeArr    = $params['like'];

        $whereArr = '';

        //$wherein = [];
        $wherein = ['role' => ['employee', 'admin']];

        // d($likeArr);
        // dd($whereArr);
        ## Total number of records without filtering
        $allres  = $this->User_model->count($whereArr);
        $totalRecords = $allres;


        ## Total number of records with filtering
        $allres  = $this->User_model->count($whereArr,$likeArr,$wherein);
        $totalRecordwithFilter = $allres;

        $columns = 'id,first_name,last_name,country_code, mobile, email, status, created_at,department_id as department,designation_id as designation';
        $list = $this->User_model->get_users($whereArr,$columns,$startrow,$rowperpage , $likeArr,$wherein);

        foreach ($list as $key => $value) {

			$department = $this->Department_model->get_department(['id' => $value['department']]);
			$designation = $this->Designation_model->get_designation(['id' => $value['designation']]);

			$list[$key]['designation'] 		= cap($designation['name']);
			$list[$key]['department'] 		= $department['name'];

			if($value['country_code']){
				$list[$key]['mobile'] 		= '('.$value['country_code'].') '.$value['mobile'] ;
			}else{
				$list[$key]['mobile'] 		= $value['mobile'] ;
			}

			$list[$key]['name'] 		= cap($value['first_name'].' '.$value['last_name']);
			$list[$key]['created_at'] 	= custDate($value['created_at']);
		}
        // dd($list);

        $response = array(
            "draw"                  => intval($draw),
            "totalRecords"          => $totalRecords,
            "totalRecordwithFilter" => $totalRecordwithFilter,
            "aaData"                => $list
        );

        sendResponse(1, 'success', $response);
    }
    /*
	public function list() {
		$status_list = status_list();
		$list = $this->User_model->get_users(['role' => 'employee'],'id,first_name,last_name,mobile,email,status,created_at,department_id as department,designation_id as designation');

		foreach ($list as $key => $value) {

			$department = $this->Department_model->get_department(['id' => $value['department']]);
			$designation = $this->Designation_model->get_designation(['id' => $value['designation']]);

			$list[$key]['designation'] 		= $designation['name'];
			$list[$key]['department'] 		= $department['name'];

			$list[$key]['name'] 		= ucfirst($value['first_name']).' '.ucfirst($value['last_name']);
			$list[$key]['created_at'] 	= custDate($value['created_at']);
			$list[$key]['status'] 		= $status_list[$value['status']];
		}

		sendResponse(1, 'success', $list);
	}
*/
	public function create() {
		// echo "<pre>";
		// print_r($this->input->post);
		// exit;
		$department = $this->Department_model->get_departments(['status' => 1]);
		$designation = $this->Designation_model->get_designations(['status' => 1]);
		$phonecodes = $this->Country_model->get_phonecodes();

		$data = [];
		$data['template'] 			= 'employee/emp_add';
		$data['title'] 				= "Add Employee";
		$data['data'] 				= '';
		$data['department'] 		= $department;
		$data['designation'] 		= $designation;
		$data['phonecodes'] 		= $phonecodes;
		$this->load->view('default', $data);
	}

	public function store(){
		//dd($this->input->post);
		$this->form_validation->set_rules('first_name', 'First Name', 'required|alpha');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha');
		$this->form_validation->set_rules('emp_id', 'Employee ID', 'is_natural');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('designation', 'Designation', 'required|exists[designation.id]');
		$this->form_validation->set_rules('department', 'Department', 'required|exists[department.id]');
		$this->form_validation->set_rules('role', 'Role', 'required|alpha');
		$this->form_validation->set_rules('country_code', 'Country code', 'required|exists[z_countries.phonecode]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $email = trim($this->input->post('email',TRUE));
        //dd($email);
  //       $split = explode("@",trim($email));
		// if(strtolower($split[1]) != $this->emp_domain){
		// 	sendResponse(0, 'Email id does not match with the domain');
		// }

        //end validation

        //Store
        $password = trim($this->input->post('password',TRUE));

        $data = [];
        $data['first_name'] 			=  trim($this->input->post('first_name',TRUE));
        $data['last_name'] 				=  trim($this->input->post('last_name',TRUE));
        $data['emp_id'] 				=  trim($this->input->post('emp_id',TRUE));
        $data['email'] 					=  $email;
        $data['password'] 				=  hash('sha256', $password);
        $data['mobile'] 				=  trim($this->input->post('mobile',TRUE));
        $data['country_code'] 			=  trim($this->input->post('country_code',TRUE));
        $data['designation_id'] 		=  trim($this->input->post('designation',TRUE));
        $data['department_id'] 			=  trim($this->input->post('department',TRUE));
      	
		$data['role'] 		= trim($this->input->post('role',TRUE));
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->User_model->add_user($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Employee created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create employee');
		}
	}//end store dept


	public function view($id) {
		$employee = $this->User_model->get_user(['id' => $id]);
		// if(!$employee){
		// 	$this->sendFlashMsg(0,'Employee data not found', 'employee');
		// }

		$department = $this->Department_model->get_departments();
		$designation = $this->Designation_model->get_designations();

		$data = [];
		$data['template'] 			= 'employee/emp_view';
		$data['title'] 				= "View Employee";
		$data['data'] 				= $employee;
		$data['department'] 		= $department;
		$data['designation'] 		= $designation;
		$this->load->view('default', $data);
	}	

	public function edit($id) {
		$employee = $this->User_model->get_user(['id' => $id]);
		// if(!$employee){
		// 	$this->sendFlashMsg(0,'Employee data not found', 'employee');
		// }
		$department = $this->Department_model->get_departments(['status' => 1]);
		$designation = $this->Designation_model->get_designations(['status' => 1]);
		$phonecodes = $this->Country_model->get_phonecodes();

		$data = [];
		$data['template'] 			= 'employee/emp_edit';
		$data['title'] 				= "Edit Employee";
		$data['data'] 				= $employee;
		$data['department'] 		= $department;
		$data['designation'] 		= $designation;
		$data['phonecodes'] 		= $phonecodes;
		$this->load->view('default', $data);
	}	

	public function update($id){
		$_POST['id'] = $id;

		$change_pass = trim($this->input->post('change_pass',TRUE));
		if($change_pass == 1){
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		}
		$this->form_validation->set_rules('id', 'Id', 
			array(
				'required',
				array(
	                'id_callable',
	                function($str)
	                {	
	                	$employee = $this->User_model->get_user(['id' => $str, 'role' => 'employee']);
	                    if($employee){
	                    	return true;
	                    }else{
	                    	return false;
	                    }
	                }
	            ),
			)
		);

		$this->form_validation->set_rules('first_name', 'First Name', 'required|alpha');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha');
		$this->form_validation->set_rules('emp_id', 'Employee ID', 'is_natural');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique_except[users.email.'.$id.']');
		$this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('designation', 'Designation', 'required|exists[designation.id]');
		$this->form_validation->set_rules('department', 'Department', 'required|exists[department.id]');
		$this->form_validation->set_rules('role', 'Role', 'required|alpha');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		$this->form_validation->set_message('id_callable', 'Employee details not found.');

		$this->form_validation->set_rules('country_code', 'Country code', 'required|exists[z_countries.phonecode]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $email = trim($this->input->post('email',TRUE));
        
  //       $split = explode("@",trim($email));
		// if(strtolower($split[1]) != $this->emp_domain){
		// 	sendResponse(0, 'Email id does not match with the domain');
		// }

        //Store
        
        $data = [];
        if($change_pass == 1){
			$password = trim($this->input->post('password',TRUE));
			$data['password'] 			=  hash('sha256', $password);
		}

        $data['first_name'] 			=  trim($this->input->post('first_name',TRUE));
        $data['last_name'] 				=  trim($this->input->post('last_name',TRUE));
        $data['emp_id'] 				=  trim($this->input->post('emp_id',TRUE));
        $data['email'] 					=  $email;
        $data['mobile'] 				=  trim($this->input->post('mobile',TRUE));
        $data['country_code'] 			=  trim($this->input->post('country_code',TRUE));
        $data['designation_id'] 		=  trim($this->input->post('designation',TRUE));
        $data['department_id'] 			=  trim($this->input->post('department',TRUE));
      	
      	$data['role'] 					= trim($this->input->post('role',TRUE));
		$data['status'] 				= trim($this->input->post('status',TRUE));;
		$data['updated_by'] 			= $this->userid;


		$where = ['id' => $id];
		$insert = $this->User_model->update_user($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Employee updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create employee');
		}
	}//end store dept

	public function empByDepartment() {

		$deptid =  $this->input->get('id',TRUE);

		$list = $this->User_model->get_users(['role' => 'employee','department_id' => $deptid,'status' => 1],'id,first_name,last_name');

		if($list){
			foreach ($list as $key => $value) {
				$list[$key]['name'] 		= ucfirst($value['first_name']).' '.ucfirst($value['last_name']);

				unset($list[$key]['first_name']);
				unset($list[$key]['last_name']);


				if($this->userid == $value['id']){
					unset($list[$key]);
				}
			}

			sendResponse(1, 'success', $list);
		}else{
			sendResponse(1, 'Employee not found',[]);
		}
	}

}

?>