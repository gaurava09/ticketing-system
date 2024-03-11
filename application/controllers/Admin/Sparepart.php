<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Equipment_model');
		$this->load->model('Sparepart_model');
	}

	public function index() {
		$data = [];
		$data['template'] 	= 'sparepart/sp_list';
		$data['title'] 		= "Sparepart List";
		$data['data'] 		= '';
		$this->load->view('default', $data);
	}

	/*public function list() {
		$status_list = status_list();
		$list = $this->Sparepart_model->get_spareparts([],'id,name,model as equipment_model, equipment_id,status,created_at');

		foreach ($list as $key => $value) {
			$equipment = $this->Equipment_model->get_equipment(['id' => $value['equipment_id'] ],'name,model');
			if($equipment){
				$list[$key]['equipment_name'] 	= $equipment['name'];
				// $list[$key]['equipment_model'] 	= $equipment['model'];
			}
			unset($list[$key]['equipment_id']);

			$list[$key]['created_at'] 	= custDate($value['created_at']);
			$list[$key]['status'] 		= $status_list[$value['status']];
		}

		sendResponse(1, 'success', $list);
	}*/


	public function list() {		
		$params = $this->searchParam(['s.status' => 'status'],['s.name' => 'sparepart','s.model' => 'model', 'e.name' => 'equipment']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		
		## Total number of records without filtering
		$allres  = $this->Sparepart_model->count();
		$totalRecords = $allres;

		$equi_name = isset($likeArr['e.name']) ? $likeArr['e.name'] : '';
		if($equi_name == ''){
			## Total number of records with filtering
			$allres  = $this->Sparepart_model->count($whereArr,$likeArr);
			$totalRecordwithFilter = $allres;
		}else{
			## Total number of records with filtering
			$allres  = $this->Sparepart_model->get_spareparts_list($whereArr,'s.id',$startrow,$rowperpage , $likeArr);
			
			$totalRecordwithFilter = count($allres);
		}
		

		$status_list = status_list();
		$columns = 's.*,e.name as equipment_name';
		$list = $this->Sparepart_model->get_spareparts_list($whereArr,$columns,$startrow,$rowperpage , $likeArr);

		// dd($this->pq());

		foreach ($list as $key => $value) {
			/*$equipment = $this->Equipment_model->get_equipment(['id' => $value['equipment_id'] ],'name');
			if($equipment){
				$list[$key]['equipment_name'] 	= $equipment['name'];
				// $list[$key]['equipment_model'] 	= $equipment['model'];
			}
			unset($list[$key]['equipment_id']);*/

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
			$list[$key]['equipment_name'] 	= cap($value['equipment_name']);
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
		$equipment = $this->Equipment_model->get_equipments(['status' => 1]);

		$models = [];
		if($equipment){
			foreach ($equipment as $key => $value) {
				$models[$value['id']] = [];

				$temp = json_decode($value['model'],true);
				if(json_last_error() == JSON_ERROR_NONE && is_array($temp)){
					$models[$value['id']] = $temp;
				}
			}
		}
		
		$data = [];
		$data['template'] 			= 'sparepart/sp_add';
		$data['title'] 				= "Add Sparepart";
		$data['data'] 				= '';
		$data['equipment'] 			= $equipment;
		$data['models'] 			= $models;
		$this->load->view('default', $data);
	}

	public function store(){

		$equi_id 	= trim($this->input->post('equipment',TRUE));
		$model 		= $this->input->post('model',TRUE);
		$sparepart 	= trim($this->input->post('name',TRUE));
		$unit 	= trim($this->input->post('unit',TRUE));

		$this->form_validation->set_rules('equipment', 'Equipment', 'required|exists[equipment.id]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('unit', 'Unit', 'required');
		$this->form_validation->set_rules('model[]', 'Model', 'required');
		
		

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //check if sparepart name is unique to that equipment  / / // // /////& model
		foreach ($model as $key => $value) {
			$where = ['equipment_id =' => $equi_id, 'name' => $sparepart];
	        $details = $this->Sparepart_model->get_sparepart($where);
	        if($details){  
	        	sendResponse(0, 'Sparepart name is not unique to equipment['.$value.']');
	        }
		}
		
        
        //check if model name exist in equipment table
        $equipment = $this->Equipment_model->get_equipment(['id' => $equi_id]);
		if(!$equipment){
			sendResponse(0,'Equipment data not found');
		}else{
			$model_list = json_decode($equipment['model'],true);
			if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
					
				foreach ($model as $key => $value) {
					if(!in_array(trim($value), $model_list)){
						sendResponse(0,'Invalid Equipment model ['.$value.'].');
					}
				}//end foreach

			}else{
				sendResponse(0,'Equipments model data not found');
			}
		}
		//end validation
		

        //Store
        $data = [];
        $data['name'] 			=  $sparepart;
        $data['unit'] 			=  $unit;
        $data['equipment_id'] 	=  $equi_id;
        $data['model'] 			=  json_encode($model);
		$data['status'] 		= 1;
		$data['created_by'] 	= $this->userid;
		$data['created_at'] 	= getDt();

		$insert = $this->Sparepart_model->add_sparepart($data);

		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Sparepart created successfully.' ));

			sendResponse(1,'Sparepart created successfully.');
		}else{
			sendResponse(0,' Failed to create sparepart');
		}

	}//end store sparepart


	public function view($id) {
		$sparepart = $this->Sparepart_model->get_sparepart_details(['s.id' => $id]);
		if(!$sparepart){
			$this->sendFlashMsg(0,'Sparepart data not found', 'sparepart');
		}

		$data = [];
		$data['template'] 			= 'sparepart/sp_view';
		$data['title'] 				= "View Sparepart";
		$data['data'] 				= $sparepart;
		$this->load->view('default', $data);
	}	

	public function edit($id) {
		$sparepart = $this->Sparepart_model->get_sparepart_details(['s.id' => $id]);
		if(!$sparepart){
			$this->sendFlashMsg(0,'Sparepart data not found', 'sparepart');
		}
		$equipment = $this->Equipment_model->get_equipments();

		$models = [];
		if($equipment){
			foreach ($equipment as $key => $value) {
				$models[$value['id']] = [];

				$temp = json_decode($value['model'],true);
				if(json_last_error() == JSON_ERROR_NONE && is_array($temp)){
					$models[$value['id']] = $temp;
				}
			}
		}

		$data = [];
		$data['template'] 			= 'sparepart/sp_edit';
		$data['title'] 				= "Edit Sparepart";
		$data['data'] 				= $sparepart;
		$data['equipment'] 			= $equipment;
		$data['models'] 			= $models;
		$this->load->view('default', $data);
	}	

	public function update($id){
		$equi_id 		= trim($this->input->post('equipment',TRUE));
		$model 			= $this->input->post('model',TRUE);
		$sparepart 		= trim($this->input->post('name',TRUE));
		$unit 		= trim($this->input->post('unit',TRUE));

		$_POST['id'] = $id;

		$this->form_validation->set_rules('id', 'Sparepart id', 'required|exists[sparepart.id]');
		$this->form_validation->set_rules('equipment', 'Equipment', 'required|exists[equipment.id]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('unit', 'Unit', 'required');
		$this->form_validation->set_rules('model[]', 'Model', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }


		//check if sparepart name is unique to that equipment  ///  // / /& model
        // $where = ['id !=' => $id , 'equipment_id =' => $equi_id, 'name' => $sparepart,'model' => $model];
        $where = ['id !=' => $id , 'equipment_id =' => $equi_id, 'name' => $sparepart];
        $details = $this->Sparepart_model->get_sparepart($where);
        if($details){  
        	sendResponse(0, 'Sparepart name is not unique to equipment.');
        }

        // dd($model);

        //check if model name exist in equipment table
        $equipment = $this->Equipment_model->get_equipment(['id' => $equi_id]);
		if(!$equipment){
			sendResponse(0,'Equipment data not found');
		}else{
			$model_list = json_decode($equipment['model'],true);
			if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
					
				foreach ($model as $key => $value) {
					if(!in_array(trim($value), $model_list)){
						sendResponse(0,'Invalid Equipment model ['.$value.'].');
					}
				}

			}else{
				sendResponse(0,'Equipments model data not found');
			}
		}
		//end validation

        //Store
        $data = [];
        $data['name'] 			=  $sparepart;
        $data['unit'] 			=  $unit;
        $data['equipment_id'] 	=  $equi_id;
        $data['model'] 			=  json_encode($model);
		$data['status'] 		= trim($this->input->post('status',TRUE));;
		$data['updated_by'] 	= $this->userid;


		$where = ['id' => $id];
		$insert = $this->Sparepart_model->update_sparepart($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Sparepart updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,'Failed to update sparepart');
		}
	}//end store dept

}

?>