<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Equipment_model');
	}

	public function index() {
		$data = [];
		$data['template'] 	= 'equipment/equi_list';
		$data['title'] 		= "Equipment List";
		$data['data'] 		= '';
		$this->load->view('default', $data);
	}


	public function list() {		
		$params = $this->searchParam(['status'],['name','model']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		## Total number of records without filtering
		$allres  = $this->Equipment_model->count();
		$totalRecords = $allres;

		## Total number of records with filtering
		$allres  = $this->Equipment_model->count($whereArr,$likeArr);
		$totalRecordwithFilter = $allres;

		$status_list = status_list();
		$columns = 'id,name,model,status,created_at';
		$list = $this->Equipment_model->get_equipments($whereArr,$columns,$startrow,$rowperpage , $likeArr);

		// dd($this->pq());

		foreach ($list as $key => $value) {
			$html = $value['model'];

			$models = json_decode($value['model'],true);
			if(json_last_error() == JSON_ERROR_NONE && is_array($models)){
				$html = '';
				foreach ($models as $k => $v) {
					$html .= '<span>'.$v.'</span><br>';
				}
			}
		

			$list[$key]['model'] 	= $html;
			$list[$key]['name'] 	= cap($value['name']);
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

	public function create() {
		$data = [];
		$data['template'] 			= 'equipment/equi_add';
		$data['title'] 				= "Add Equipment";
		$data['data'] 				= '';
		$this->load->view('default', $data);
	}

	public function store(){
		$this->form_validation->set_rules('name', 'Name', 'required|is_unique[equipment.name]');
		$this->form_validation->set_rules('model[]', 'Model', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }
        
        $temp = [];
        $model = $this->input->post('model',TRUE);
        foreach ($model as $key => $value) {
        	if(trim($value) != ''){
        		$temp[] = trim($value);
        	}
        }
        $temp = array_unique($temp);
        if(empty($temp)){
        	sendResponse(0, 'model fields are required');
        }

        if(count($temp) > 20){
        	sendResponse(0, 'you can not add more than 20 models.');
        }
        //end validation


        //Store
        $data = [];
        $data['name'] 		=  trim($this->input->post('name',TRUE));
        $data['model'] 		=  json_encode($temp);

		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Equipment_model->add_equipment($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Equipment created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create equipment');
		}
	}//end store dept


	public function view($id) {
		$equipment = $this->Equipment_model->get_equipment(['id' => $id]);
		if(!$equipment){
			$this->sendFlashMsg(0,'Equipment data not found', 'equipment');
		}

		$data = [];
		$data['template'] 			= 'equipment/equi_view';
		$data['title'] 				= "View Equipment";
		$data['data'] 				= $equipment;
		$this->load->view('default', $data);
	}	

	public function edit($id) {
		$equipment = $this->Equipment_model->get_equipment(['id' => $id]);
		if(!$equipment){
			$this->sendFlashMsg(0,'Equipment data not found', 'equipment');
		}

		$data = [];
		$data['template'] 			= 'equipment/equi_edit';
		$data['title'] 				= "Edit Equipment";
		$data['data'] 				= $equipment;
		$this->load->view('default', $data);
	}	

	public function update($id){
		$_POST['id'] = $id;

		$this->form_validation->set_rules('id', 'Equipment id', 'required|exists[equipment.id]');
		$this->form_validation->set_rules('name', 'Name', 'required|is_unique_except[equipment.name.'.$id.']');
		$this->form_validation->set_rules('model[]', 'Model', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $temp = [];
        $model = $this->input->post('model',TRUE);
        foreach ($model as $key => $value) {
        	if(trim($value) != ''){
        		$temp[] = trim($value);
        	}
        }
        $temp = array_unique($temp);
        if(empty($temp)){
        	sendResponse(0, 'model fields are required');
        }
        
        if(count($temp) > 20){
        	sendResponse(0, 'you can not add more than 20 models.');
        }
        //end validation

        //Store
        $data = [];
        $data['name'] 		=  trim($this->input->post('name',TRUE));
        $data['model'] 		=  json_encode($temp);
		$data['status'] 	= trim($this->input->post('status',TRUE));;
		$data['updated_by'] = $this->userid;

		$where = ['id' => $id];
		$insert = $this->Equipment_model->update_equipment($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Equipment updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create equipment');
		}
	}//end store dept



}

?>