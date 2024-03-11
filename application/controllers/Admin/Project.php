<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Project_model');
		$this->load->model('Company_model');
		$this->load->model('Equipment_model');
	}

	public function index() {
		$company = $this->Company_model->get_all_company([],'id,name');

		$data = [];
		$data['template'] 	= 'project/proj_list';
		$data['title'] 		= "Project List";
		$data['data'] 		= '';
		$data['company'] 		= $company;
		$this->load->view('default', $data);
	}

	public function list() {		
		$params = $this->searchParam([],['ga_no','project_name' => 'project']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$wherein = [];

		$result_found = 1;


		if(isset($_GET['equipment']) && $_GET['equipment'] != ''){
			$equipments = $this->Equipment_model->get_equipments(FALSE,'id,name',FALSE,FALSE,['name' => $_GET['equipment']]);

			if($equipments){
				$equi_ids = array_column($equipments, 'id');

				$wherein['equipment_id'] = $equi_ids;
				// $whereArr['equipment_id IN'] = implode(",", $equi_ids);
			}else{
				$result_found = 0;
			}
		}
		
		
		$whereCompany = '';
		if(isset($_GET['company_id']) && $_GET['company_id'] != ''){
			// $rawWhere = ' (company_id )'
			$whereCompany = $_GET['company_id'];
		}
		/*$company_name = isset($_GET['company']) ? $_GET['company'] : '';

		if($company_name){
			$company = $this->Company_model->get_all_company([],'id',FALSE,FALSE,['name' => $company_name]);
			
			if($company){
				$wherein['company_id'] = array_column($company, 'id');
			}else{
				$result_found = 0;
			}
		}*/
		// dd($wherein);

		## Total number of records without filtering
		$allres  = $this->Project_model->count();
		$totalRecords = $allres;

		if($result_found == 0){
			$totalRecordwithFilter = 0;
			$list = [];
		}else{
			## Total number of records with filtering
			$allres  = $this->Project_model->count($whereArr,$likeArr,$wherein,$whereCompany);
			$totalRecordwithFilter = $allres;

			// dd($this->pq());
			$status_list = status_list();
			$columns = 'id,ga_no,project_code, project_name,supply_date,warranty_till,status,created_at,company_id as company, company_id_2 as company_2, equipment_id as equipment_name, model as equipment_model';
			$list = $this->Project_model->get_projects($whereArr,$columns,$startrow,$rowperpage , $likeArr,$wherein,$whereCompany);

			// dd($this->pq());
			foreach ($list as $key => $value) {
				$list[$key]['company'] 			= '';
				$list[$key]['equipment_name'] 	= '';
				// $list[$key]['equipment_model'] 	= '';

				$company = $this->Company_model->get_company(['id' => $value['company']],'name');

				if($company) {
					$list[$key]['company'] = cap($company['name']);

					if($value['company_2']){
						$company_2 = $this->Company_model->get_company(['id' => $value['company_2']],'name');
						if($company_2) {
							$list[$key]['company'] = $list[$key]['company'].', '.cap($company_2['name']);
						}
					}
					
				}

				/*$comp_ids = [$value['company'],$value['company_2']];
				$company = $this->Company_model->get_all_company(FALSE,'name',FALSE,FALSE,FALSE,['id' => $comp_ids]);

				
				if($company) {
					$comp_name = array_column($company, 'name');
					// d($comp_name);
					if(count($comp_name) == 2){
						$list[$key]['company'] 	= cap($comp_name[0]).', '.cap($comp_name[1]);
					}else{
						$list[$key]['company'] 	= cap($comp_name[0]);
					}
				}*/

				$equipment = $this->Equipment_model->get_equipment(['id' => $value['equipment_name']],'name,model');
				if($equipment){
					$list[$key]['equipment_name'] 	= cap($equipment['name']);
					// $list[$key]['equipment_model'] 	= $equipment['model'];
				}
				
				$list[$key]['project_name'] 	= cap($value['project_name']);
				$list[$key]['supply_date'] 		= custDate($value['supply_date']);
				$list[$key]['warranty_till'] 	= custDate($value['warranty_till']);
				$list[$key]['created_at'] 		= custDate($value['created_at']);
			}
	}// if result
	// die;
		// dd($list);

		$response = array(
		 	"draw" 					=> intval($draw),
		 	"totalRecords" 			=> $totalRecords,
		 	"totalRecordwithFilter" => $totalRecordwithFilter,
		 	"aaData" 				=> $list
		);

		sendResponse(1, 'success', $response);
	}


	/*public function list() {
		$status_list = status_list();
		$list = $this->Project_model->get_projects([],'id,ga_no,project_name,supply_date,warranty_till,status,created_at,company_id as company,equipment_id as equipment_name, model as equipment_model');

		foreach ($list as $key => $value) {
			$list[$key]['company'] 			= '';
			$list[$key]['equipment_name'] 	= '';
			// $list[$key]['equipment_model'] 	= '';

			$company = $this->Company_model->get_company(['id' => $value['company']],'name');
			if($company){
				$list[$key]['company'] 		= $company['name'];
			}

			$equipment = $this->Equipment_model->get_equipment(['id' => $value['equipment_name']],'name,model');
			if($equipment){
				$list[$key]['equipment_name'] 	= $equipment['name'];
				// $list[$key]['equipment_model'] 	= $equipment['model'];
			}
			

			$list[$key]['supply_date'] 		= custDate($value['supply_date']);
			$list[$key]['warranty_till'] 	= custDate($value['warranty_till']);
			$list[$key]['created_at'] 		= custDate($value['created_at']);
			$list[$key]['created_at'] 		= custDate($value['created_at']);
			$list[$key]['status'] 			= $status_list[$value['status']];
		}

		sendResponse(1, 'success', $list);
	}*/

	public function create() {
		
		$company = $this->Company_model->get_all_company(['status' => 1]);
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
		$data['template'] 			= 'project/proj_add';
		$data['title'] 				= "Add Project";
		$data['data'] 				= '';
		$data['equipment'] 			= $equipment;
		$data['company'] 			= $company;
		$data['models'] 			= $models;
		$this->load->view('default', $data);
	}

	public function store(){
		$this->form_validation->set_rules('ga_no', 'GA No.', 'required|is_unique[project.ga_no]');
		$this->form_validation->set_rules('project_code', 'Project Code', 'required');
		$this->form_validation->set_rules('equipment', 'Equipment', 'required');
		$this->form_validation->set_rules('model', 'Model', 'required');
		$this->form_validation->set_rules('company', 'Company 1', 'required|exists[company.id]');
		$this->form_validation->set_rules('company_2', 'Company 2', 'exists[company.id]');
		$this->form_validation->set_rules('project_name', 'Project Name', 'required');

		$this->form_validation->set_rules('supply_date', 'Supply Date', 'required|date');
		$this->form_validation->set_rules('warranty_till', 'Warranty Valid Till', 'required|date');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $supply_date 	= trim($this->input->post('supply_date',TRUE));
		$warranty_till 	= trim($this->input->post('warranty_till',TRUE));

		if(strtotime($supply_date) > strtotime($warranty_till)){
			sendResponse(0, 'Warranty valid till date must be greater than supply date');
        }

        $equi_id 	= trim($this->input->post('equipment',TRUE));
        $model 		= trim($this->input->post('model',TRUE));
        //check if model name exist in equipment table
        $equipment = $this->Equipment_model->get_equipment(['id' => $equi_id]);
		if(!$equipment){
			sendResponse(0,'Equipment data not found');
		}else{
			$model_list = json_decode($equipment['model'],true);
			if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
					
				if(!in_array($model, $model_list)){
					sendResponse(0,'Equipment model does not exist.');
				}

			}else{
				sendResponse(0,'Equipments model data not found');
			}
		}

		$company 	= trim($this->input->post('company',TRUE));
		$company_2 	= trim($this->input->post('company_2',TRUE));
		if($company == $company_2){
			sendResponse(0,'Please select 2 different company');
		}
		//end validation

        //Store
        $data = [];
        $data['ga_no'] 					=  trim($this->input->post('ga_no',TRUE));
        $data['project_code'] 			=  trim($this->input->post('project_code',TRUE));
        $data['equipment_id'] 			=  $equi_id;
        $data['model'] 					=  $model;
        $data['company_id'] 			=  $company;
        $data['company_id_2'] 			=  $company_2;
        $data['project_name'] 			=  trim($this->input->post('project_name',TRUE));
        $data['supply_date'] 			=  $supply_date;
        $data['warranty_till'] 			=  $warranty_till;
       
		$data['status'] 				= 1;
		$data['created_by'] 			= $this->userid;
		$data['created_at'] 			= getDt();

		$project_id = $this->Project_model->add_project($data);
		if($project_id){

			//add project_code
		/*	$project_code = 'D-'.str_pad($project_id, 6, '0', STR_PAD_LEFT);
			$this->Project_model->update_project(['id' => $project_id], ['project_code' => $project_code]);*/

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Project created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create project');
		}
	}//end store dept


	public function view($id) {
		$project = $this->Project_model->get_project_details(['p.id' => $id]);
		if(!$project){
			$this->sendFlashMsg(0,'Project data not found', 'project');
		}
		// $project['company_2'] = '';
		$company = $this->Company_model->get_company(['id' => $project['company_id_2']], 'name');

		$project['company_2'] = ($company) ? $company['name'] : '';

		// d($this->pq()); 	dd($project);


		$temp = json_decode($project['equipment_model'],true);
		if(json_last_error() == JSON_ERROR_NONE && is_array($temp)){
			$models = $temp;
		}else{
			$models = $project['equipment_model'];
		}


		$data = [];
		$data['template'] 			= 'project/proj_view';
		$data['title'] 				= "View Project";
		$data['data'] 				= $project;
		$data['models'] 			= $models;
		$this->load->view('default', $data);
	}	


	public function edit($id) {
		$project = $this->Project_model->get_project(['id' => $id]);
		if(!$project){
			$this->sendFlashMsg(0,'Project data not found', 'project');
		}

		$company = $this->Company_model->get_all_company();

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
		$data['template'] 			= 'project/proj_edit';
		$data['title'] 				= "Edit Project";
		$data['data'] 				= $project;
		$data['company'] 			= $company;
		$data['equipment'] 			= $equipment;
		$data['models'] 			= $models;

		$this->load->view('default', $data);
	}	


	public function update($id){
		$_POST['id'] = $id;

		$this->form_validation->set_rules('ga_no', 'GA No', 'required|is_unique_except[project.ga_no.'.$id.']', array('is_unique_except' =>'GA Number already exists.'));


		$this->form_validation->set_rules('id', 'Project id', 'required|exists[project.id]');
		$this->form_validation->set_rules('project_code', 'Project Code', 'required');
		$this->form_validation->set_rules('equipment', 'Equipment', 'required');
		$this->form_validation->set_rules('company', 'Company', 'required|exists[company.id]');
		$this->form_validation->set_rules('company_2', 'Company 2', 'exists[company.id]');
		$this->form_validation->set_rules('project_name', 'Project Name', 'required');

		$this->form_validation->set_rules('supply_date', 'Supply Date', 'required|date');
		$this->form_validation->set_rules('warranty_till', 'Warranty Valid Till', 'required|date');

		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');


		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }


        $equi_id 		= trim($this->input->post('equipment',TRUE));
		$model 			= trim($this->input->post('model',TRUE));
		$supply_date 	= trim($this->input->post('supply_date',TRUE));
		$warranty_till 	= trim($this->input->post('warranty_till',TRUE));

		if(strtotime($supply_date) > strtotime($warranty_till)){
			sendResponse(0, 'Warranty valid till date must be greater than supply date');
        }


        //check if model name exist in equipment table
        $equipment = $this->Equipment_model->get_equipment(['id' => $equi_id]);
		if(!$equipment){
			sendResponse(0,'Equipment data not found');
		}else{
			$model_list = json_decode($equipment['model'],true);
			if(json_last_error() == JSON_ERROR_NONE && is_array($model_list)){
					
				if(!in_array($model, $model_list)){
					sendResponse(0,'Equipment model does not exist.');
				}

			}else{
				sendResponse(0,'Equipments model data not found');
			}
		}

		$company 	= trim($this->input->post('company',TRUE));
		$company_2 	= trim($this->input->post('company_2',TRUE));
		if($company == $company_2){
			sendResponse(0,'Please select 2 different company');
		}
		//end validation

        //Store
        $data = [];
        $data['ga_no'] 					=  trim($this->input->post('ga_no',TRUE));
        $data['project_code'] 			=  trim($this->input->post('project_code',TRUE));
        $data['equipment_id'] 			=  $equi_id;
        $data['model'] 					=  $model;
        $data['company_id'] 			=  $company;
        $data['company_id_2'] 			=  $company_2;
        $data['project_name'] 			=  trim($this->input->post('project_name',TRUE));
        $data['supply_date'] 			=  $supply_date;
        $data['warranty_till'] 			=  $warranty_till;
      	
		$data['status'] 				= trim($this->input->post('status',TRUE));;
		$data['updated_by'] 			= $this->userid;


		$where = ['id' => $id];
		$insert = $this->Project_model->update_project($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Project updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create project');
		}
	}//end store dept

	public function upload() {
		$data = [];
		$data['template'] 	= 'project/proj_upload';
		$data['title'] 		= "Project Upload";
		$this->load->view('default', $data);		
	}//end function import

	public function uploadProject() {
		$this->load->library('SimpleXLSX');
		//START check uploaded file
		$upload_error = "";
		if(isset($_FILES['file']['name']) ){  
			
			$filename = $_FILES['file']['name'];
			$max_file_size = 2; 
	        $max_file_size = $max_file_size*1000000;

	    	$ext = pathinfo($filename, PATHINFO_EXTENSION);

			if($ext != 'xlsx'){
				$upload_error = "Invalid file extension. Use only xlsx files for upload.";
			}
			else if( $_FILES['file']['error'] != 0){
				$upload_error = "Failed to upload file on server. Error code : ".$_FILES['file']['error'];
			}
			else if($_FILES['file']['size'] > $max_file_size) {
		        $upload_error = "File size is big";
		    }
		}else{
			$upload_error = "No files found for upload";
		}

		if($upload_error){
			$this->session->set_flashdata('message', array('status' => 0, 'message' => $upload_error ));
			redirect('project/upload');
		}
		//END

		$xlsx = SimpleXLSX::parse( $_FILES['file']['tmp_name']);

		// $expected_column_count = 15;
		$expected_column = array('sr.no','ga_no', 'project_code', 'equipment_name','equipment_model','company_name_1','company_name_2','project_name','date_of_supply','warantee_valid_till');

		$required_fields = array('sr.no','ga_no', 'project_code', 'equipment_name','equipment_model','company_name_1','project_name','date_of_supply','warantee_valid_till');

		$dim = $xlsx->dimension();
		$cols = $dim[0];


		//START validation
		$data_to_upload = array();
		$errors = array();
		$headers = array();

		$first_row = $xlsx->rows()[0];

		//check if expected column exist
		foreach ($expected_column as $key => $value) {
			if( !in_array($value, $first_row) ){
				$errors[] = array( "Expected Column is missing ", "", "", $value, 0, "" );
			}
		}

		//check if unknown columns are not submitted
		foreach ($first_row as $key => $value) {
			if( !in_array($value, $expected_column) ){
				$errors[] = array( "Unknown column header ", "", "", $value, 0, $key );
			}

			$headers[$value] = $key;
		}

		if($errors){
			$this->session->set_flashdata('errors', $errors);
			redirect('project/upload');
		}

		//get all ga_nos
		$ga_no_arr = array();

		foreach ( $xlsx->rows() as $row_index => $row ) {
			if ($row_index == 0){
				continue;
			}

			$ga_no = trim($row[$headers['ga_no']]);

			if( in_array($ga_no, $ga_no_arr)){
				$errors[] = array(
						"Duplicate GA No.",
						$ga_no,
						"",
						"ga_no",
						$row_index,
						$headers['ga_no']
					 );
			}
			$ga_no_arr[] = $ga_no;
		}

		// dd($errors);
		if($errors){
			$this->session->set_flashdata('errors', $errors);
			redirect('project/upload');
		}

		foreach ( $xlsx->rows() as $row_index => $row ) {

			if ($row_index == 0){
				continue;
			}

			//check required fields
			for ( $col = 0; $col < $cols; $col ++ ) {
				//check column of first row is in required field headers
				if( $row[ $col ] == "" &&  in_array($first_row[$col], $required_fields) ){

					$errors[] = array(
						"Required field is empty ",
						"",
						"",
						$first_row[$col],
						$row_index,
						$col
					);
				}

			}
			
			
			// dd($row);

			$data = array();
			$data['ga_no']    				= trim($row[$headers['ga_no']]);
			$data['project_code']    		= trim($row[$headers['project_code']]);
			$data['model']    				= trim($row[$headers['equipment_model']]);
			$data['project_name']    		= trim($row[$headers['project_name']]);

			$supply_date 					= trim($row[$headers['date_of_supply']]);
			$warranty_till					= trim($row[$headers['warantee_valid_till']]);

			$company_name_1    				= trim($row[$headers['company_name_1']]);
			$company_name_2    				= trim($row[$headers['company_name_2']]);
			$equipment_name   				= trim($row[$headers['equipment_name']]);


			//check ga no
			$project = $this->Project_model->get_project(['ga_no' => $data['ga_no'] ],'id');
			if($project){
				//dont add
				continue;
			}

			//equipment validation
			$equipment = $this->Equipment_model->get_equipment(['name' => $equipment_name], 'id,model');
			if($equipment){
				$data['equipment_id']    = $equipment['id'];

				//validate models
				$m_err = 0;
				$models = json_decode($equipment['model'],true);
				if(json_last_error() == JSON_ERROR_NONE && is_array($models)){
					if( !in_array($data['model'], $models)){
						$m_err = 1;
					}
				}else{
					$m_err = 1;
				}

				if($m_err == 1){
					$errors[] = array(
						"Equipment model not found",
						$data['model'],
						"",
						"equipment_model",
						$row_index,
						$headers['equipment_model']
					);
				}
			}else{
				$errors[] = array(
						"Equipment name not found",
						$equipment_name,
						"",
						"equipment_name",
						$row_index,
						$headers['equipment_name']
					);
			}

			//company 1 validation
			$company = $this->Company_model->get_company(['name' => $company_name_1], 'id');
			if($company){
				$data['company_id']    = $company['id'];
			}else{
				$errors[] = array(
						"Company data not found",
						$company_name_1,
						"",
						"company_name_1",
						$row_index,
						$headers['company_name_1']
					);
			}

			//company 2 validation
			if($company_name_2){
				$company_2 = $this->Company_model->get_company(['name' => $company_name_2], 'id');
				if($company_2){
					$data['company_id_2']    = $company_2['id'];
				}else{
					$errors[] = array(
							"Company data not found",
							$company_name_2,
							"",
							"company_name_2",
							$row_index,
							$headers['company_name_2']
						);
				}
			}
			


			//dates validation
			$supply_date_1 			= str_replace(".", "-", $supply_date);
			$supply_date_1 			= strtotime($supply_date_1);

			$data['supply_date'] 	= date('Y-m-d', $supply_date_1);

			$warranty_till_1 			= str_replace(".", "-", $warranty_till);
			$warranty_till_1 			= strtotime($warranty_till_1);
			$data['warranty_till'] 	= date('Y-m-d', $warranty_till_1);

			if($supply_date_1 > $warranty_till_1){
				$errors[] = array(
						"Date of supply can not be greater than warranty date",
						$supply_date,
						"",
						"date_of_supply",
						$row_index,
						$headers['date_of_supply']
					);
			}

			$data_to_upload[] = $data;
			
		}// end foreach excel sheet loop

// d($errors);dd($data_to_upload);
		

		if($errors){
			$this->session->set_flashdata('errors', $errors);
		}else{

			$date = date('Y-m-d');
			foreach ($data_to_upload as $key => $value) {
				$value['status'] 		= 1;
				$value['created_by'] 	= $this->userid;
				$value['created_at'] 	= $date;

				$project_id = $this->Project_model->add_project($value);

				/*if($project_id){
					$project_code = 'D-'.str_pad($project_id, 6, '0', STR_PAD_LEFT);
					$this->Project_model->update_project(['id' => $project_id], ['project_code' => $project_code]);
				}*/
			}

			$this->session->set_flashdata('message', 
				array('status' => 1, 'message' => 'Data uploaded successfully' ));
		}

		// d($errors);
		// dd($data_to_upload);

		redirect('project/upload');
	}//end function import project

}

?>