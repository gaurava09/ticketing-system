<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Designation_model');
	}

	public function index() {
		$designation = $this->Designation_model->get_designations();

		$data = [];
		$data['template'] 	= 'designation/desgn_list';
		$data['title'] 		= "Designation List";
		$data['data'] 		= $designation;
		$this->load->view('default', $data);
	}

	public function list() {
		$status_list = status_list();
		$list = $this->Designation_model->get_designations([],'id,name,status,created_at');

		foreach ($list as $key => $value) {
			$list[$key]['name'] = cap($value['name']);
			$list[$key]['created_at'] = custDate($value['created_at']);
			$list[$key]['status'] = $status_list[$value['status']];
		}
		sendResponse(0, 'success', $list);
	}

	public function create() {
		$data = [];
		$data['template'] 	= 'designation/desgn_add';
		$data['title'] 		= "Add Designation";
		$data['data'] 		= '';
		$this->load->view('default', $data);
	}

	public function store(){

		$this->form_validation->set_rules('name', 'Name', 'required|alpha_dash_spaces|is_unique[designation.name]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $name 					=  trim($this->input->post('name',TRUE));
		$status 				=  trim($this->input->post('status',TRUE));
      	
        //Store
        $data = [];
        $data['name'] 		= $name;
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Designation_model->add_designation($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Designation created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create designation');
		}
	}//end store dept


	public function view($desgnId) {
		$designation = $this->Designation_model->get_designation(['id' => $desgnId]);
		if(!$designation){
			$this->sendFlashMsg(0,'Designation data not found', 'designation');
		}

		$data = [];
		$data['template'] 	= 'designation/desgn_view';
		$data['title'] 		= "View Designation";
		$data['data'] 		= $designation;
		$this->load->view('default', $data);
	}	

	public function edit($desgnId) {
		$designation = $this->Designation_model->get_designation(['id' => $desgnId]);
		if(!$designation){
			$this->sendFlashMsg(0,'Designation data not found', 'designation');
		}
		
		$data = [];
		$data['template'] 	= 'designation/desgn_edit';
		$data['title'] 		= "Edit Designation";
		$data['data'] 		= $designation;
		$this->load->view('default', $data);
	}	

	public function update($desgnId){
		$_POST['desgnId'] = $desgnId;

		$this->form_validation->set_rules('desgnId', 'Designation id', 'required|alpha_dash|exists[designation.id]');

		$this->form_validation->set_rules('name', 'Name', 'required|alpha_dash_spaces|is_unique_except[designation.name.'.$desgnId.']', array('is_unique_except' =>'Department name must be unique.'));
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
		

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $name 					=  trim($this->input->post('name',TRUE));
		$status 				=  trim($this->input->post('status',TRUE));
      	
        //Store
        $data = [];
        $data['name'] 		= $name;
		$data['status'] 	= $status;
		$data['created_by'] = $this->userid;
		$data['updated_by'] = getDt();

		$where = ['id' => $desgnId];
		$insert = $this->Designation_model->update_designation($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Designation updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create designation');
		}
	}//end store dept

}

?>