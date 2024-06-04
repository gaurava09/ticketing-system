<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Department_model');
	}

	public function index() {
		$department = $this->Department_model->get_departments();

		$data = [];
		$data['template'] 	= 'department/dept_list';
		$data['title'] 		= "Department List";
		$data['data'] 		= $department;
		$this->load->view('default', $data);
	}

	public function list() {
		$status_list = status_list();
		$list = $this->Department_model->get_departments([],'id,name,top_dept,status,created_at');

		foreach ($list as $key => $value) {
			$list[$key]['name']       = $value['name'];
			$list[$key]['created_at'] = custDate($value['created_at']);
			$list[$key]['top_dept'] = ($value['top_dept'] == 1) ? 'Yes' : 'No';
		}
		sendResponse(0, 'success', $list);
	}

	public function create() {
		$data = [];
		$data['template'] 	= 'department/dept_add';
		$data['title'] 		= "Add Department";
		$data['data'] 		= '';
		$this->load->view('default', $data);
	}

	public function store(){

		$this->form_validation->set_rules('name', 'Name', 'required|alpha_dash_spaces|is_unique[department.name]');
		// $this->form_validation->set_rules('topdept', 'Top Department', 'required|in_list[0,1]');


		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $name 					=  $this->input->post('name',TRUE);
		$top_dept 				=  1;
		$status 				=  $this->input->post('status',TRUE);
      	
        //Store
        $data = [];
        $data['name'] 		= $name;
		$data['top_dept'] 	= $top_dept;
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Department_model->add_department($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Department created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create department');
		}
	}//end store dept


	public function view($deptId) {
		$department = $this->Department_model->get_department(['id' => $deptId]);
		if(!$department){
			$this->sendFlashMsg(0,'Department data not found', 'department');
		}

		$data = [];
		$data['template'] 	= 'department/dept_view';
		$data['title'] 		= "View Department";
		$data['data'] 		= $department;
		$this->load->view('default', $data);
	}	

	public function edit($deptId) {
		$department = $this->Department_model->get_department(['id' => $deptId]);
		if(!$department){
			$this->sendFlashMsg(0,'Department data not found', 'department');
		}
		
		$data = [];
		$data['template'] 	= 'department/dept_edit';
		$data['title'] 		= "Edit Department";
		$data['data'] 		= $department;
		$this->load->view('default', $data);
	}	

	public function update($deptId){
		$_POST['deptid'] = $deptId;

		$this->form_validation->set_rules('deptid', 'Department id', 'required|alpha_dash|exists[department.id]');

		$this->form_validation->set_rules('name', 'Name', 'required|alpha_dash_spaces|is_unique_except[department.name.'.$deptId.']', array('is_unique_except' =>'Department name must be unique.'));

	
		// $this->form_validation->set_rules('topdept', 'Top Department', 'required|in_list[0,1]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		
		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $name 					=  $this->input->post('name',TRUE);
		$top_dept 				=  1;
		$status 				=  $this->input->post('status',TRUE);
      	
        //Store
        $data = [];
        $data['name'] 		= $name;
		$data['top_dept'] 	= $top_dept;
		$data['status'] 	= $status;
		$data['created_by'] = $this->userid;
		$data['updated_by'] = getDt();

		$where = ['id' => $deptId];
		$insert = $this->Department_model->update_department($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Department updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create department');
		}
	}//end store dept

}

?>