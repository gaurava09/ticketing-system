<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdEnquiry extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);
		
		if($this->is_customer()){
			redirect($this->CUST_LOGIN);
		}

		if($this->is_employee()){
			redirect($this->EMP_HOME);
		}
		
		// $this->is_admin(1,$this->CUST_LOGIN);

		$this->load->model('Project_model');
		$this->load->model('Enquiry_model');
		$this->load->model('Equipment_model');
		$this->load->model('Sparepart_model');
		$this->load->model('Customer_model');
	}

	public function index() {	
		$data = [];
		$data['template'] 	= 'ad_enquiry/ad_enquiry_list';
		$data['title'] 		= "Enquiry List";
		$this->load->view('default', $data);
	}

	public function list() {	
		if(isset($_GET['to_date']) && $_GET['to_date'] != ''){
			$_GET['to_date'] = date('Y-m-d', strtotime($_GET['to_date'] . ' +1 day'));
		}


		$params = $this->searchParam(
			['e.created_at >=' => 'from_date', 'e.created_at <=' => 'to_date', 'e.status' => 'status'],
			['e.enquiry_no' => 'enquiry_no','e.ga_no' => 'ga_no','e.sparepart_names' => 'sparepart','cm.name' => 'company']);

		if(isset($_GET['ga_type']) && $_GET['ga_type'] != ''){
			$params['like']['e.ga_no'] = $_GET['ga_type'];
		}

		// dd($params);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];


		// dd($likeArr);
		## Total number of records without filtering
		$allres  = $this->Enquiry_model->count();
		$totalRecords = $allres;

		## Total number of records with filtering
		$columns = 'e.id';
		$allres  = $this->Enquiry_model->get_enquiries_by_join($whereArr,$columns,FALSE,FALSE , $likeArr);

		$totalRecordwithFilter = count($allres);
		// dd($this->pq());

		$columns = 'e.id,e.ga_no,e.enquiry_no, e.status, e.spareparts, e.created_at, e.customer_id, c.company_id,cm.name as company';
		$list  = $this->Enquiry_model->get_enquiries_by_join($whereArr,$columns,$startrow,$rowperpage , $likeArr);



		foreach ($list as $key => $value) {
			$spareids = [];
			$spareparts = json_decode($value['spareparts'],true);
			
			$list[$key]['spareparts'] = '';

			if(is_array($spareparts)){
				/*$spareids = array_column($spareparts, 'sparepart');

				$sparepartDetails = $this->Sparepart_model->get_spareparts([],'id,name','id',$spareids);
				if($sparepartDetails){
					foreach ($sparepartDetails as $k => $v) {
						$sparepartDetails[$k]['name'] = cap($v['name']);
					}
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
			$list[$key]['company'] 	= cap($value['company']);
			$list[$key]['created_at'] 	= custDate($value['created_at']);

			$handledBy = $this->Enquiry_model->get_enquiry_handled_by(['enquiry_id' => $value['id']]);
			if($handledBy){
				$list[$key]['handled_by'] 	= cap($handledBy['first_name'] .' '.$handledBy['last_name']);
			}

		}
		// dd($list);

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

		$project = $this->Project_model->get_project(['ga_no' => $ga_no, 'equipment_id' => $equipment_id],'id');

		if($project){
			$equipemnt = $this->Equipment_model->get_equipment(['id' => $equipment_id],'id');

			if($equipemnt){
				$list = $this->Sparepart_model->get_spareparts(['equipment_id' => $equipment_id, 'status' => 1],'id,name');
				if($list){
					sendResponse(1, 'success', $list);
				}else{
					sendResponse(0, 'Spareparts not found');
				}
				
			}else{
				sendResponse(0, 'Equipment details not found');
			}
		}else{
			sendResponse(0, 'Invalid GA No. OR equipment detail');
		}
	}


	public function view($enquiryId) {
		$this->load->model('User_model');

		$enquiry = $this->Enquiry_model->get_enquiry(['id' => $enquiryId]);

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


			$project = $this->Project_model->get_project_details(array('p.ga_no' => $enquiry['ga_no']));

			$customer = $this->Customer_model->get_customer_details(['c.id' => $enquiry['customer_id']]);

			$user = $this->User_model->get_user(['id' => $enquiry['user_id']]);

			$history = $this->Enquiry_model->get_enquiry_history(['h.enquiry_id' => $enquiryId]);
			// dd($history);
		}else{
			$this->sendFlashMsg(0,'Enquiry details not found', 'enquiry');
		}
		// d($history);
		// dd($complaint);

		$data = [];
		$data['template'] 		= 'ad_enquiry/ad_enquiry_view';
		$data['title'] 			= "View Enquiry";
		$data['customer'] 		= $customer;
		$data['enquiry'] 		= $enquiry;
		$data['history'] 		= $history;
		$data['user'] 			= $user;
		$data['project'] 		= $project;
		$data['spareparts'] 	= $spareparts;
		
		$this->load->view('default', $data);
	}	//end function


	public function remark(){

		$enquiry_id 	= trim($this->input->post('id',TRUE));

		//Start validation
		$this->form_validation->set_rules('id', 'Enquiry id', 'required|exists[enquiry.id]');
		$this->form_validation->set_rules('remark', 'Remark', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $enquiry = $this->Enquiry_model->get_enquiry(['id' => $enquiry_id]);

		if(!$enquiry){
			sendResponse(0, 'Enquiry details not found');
		}
		/*else if($enquiry['remark'] != ''){
			sendResponse(0, 'Remark already exist.');
		}*/

    	//if status closed
		if( $enquiry['status'] == 4) { 
			sendResponse(0, 'Enquiry is closed.');
		}

		//check mom doc
		if(isset($_FILES['mom_doc']['name']) && $_FILES['mom_doc']['name'] != ''){
			$this->upload_path = 'documents/admin/enquiry/';

			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png|pdf|xlsx|doc|docx';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = $enquiry_id.'-'.time().'-'.mt_rand(10000, 99999);
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
		     		
		//store
		$this->load->model('Notification_model');

		$data 						= [];
		$data['enquiry_id'] 		= $enquiry_id;
		$data['user_id'] 			= $this->userid;
		$data['remark'] 			= trim($this->input->post('remark',TRUE));
		$data['document'] 			= $file_name;		
		$data['created_at'] 		= getDt();
		
		$insert = $this->Enquiry_model->add_enquiry_history($data);
		if($insert){

			//change status to ongoing in enquiry table
			$updata 					= [];
			$updata['status'] 			= 3;
			$updata['user_id'] 			= $this->userid;
			$updata['updated_by'] 		= $this->userid;
			$update = $this->Enquiry_model->update_enquiry($enquiry_id,$updata);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Remark added successfully' ));

			//add notification to customer
			$notificationData = array(
				'user_id' 			=> $enquiry['customer_id'],
				'user_type' 		=> 'customer',
				'title' 			=> 'Enquiry',
				'description' 		=> 'There is an update on the enquiry no. '.$enquiry['enquiry_no'],
				'context_id' 		=> $enquiry['id'],
				'is_read' 			=> 0,
				'created_by' 		=> $this->userid,
				'created_by_type' 	=> $this->role,
				'status' 			=> 1,
				'created_at' 		=> getDt()
			);
			$this->Notification_model->add_notification($notificationData);

			//send mail to customer
			$this->enquiryUpdateMail($enquiry);

			sendResponse(1,'Remark added successfully.');
		}else{
			sendResponse(0,'Failed to update data.');
		}

	}//end remark function 


	public function status(){
		$enquiry_id 	= trim($this->input->post('id',TRUE));

		//Start validation
		$this->form_validation->set_rules('id', 'Enquiry', 'required|exists[enquiry.id]');
		$this->form_validation->set_rules('status', 'Status', 'in_list[2,3,4]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        if($this->role != 'sales'){
        	sendResponse(0, 'You are not authorized to change status.');
        }
        //end validation
        $this->load->model('Notification_model');

        $new_status = trim($this->input->post('status',TRUE));
		$enquiry = $this->Enquiry_model->get_enquiry(['id' => $enquiry_id]);
		if($new_status == $enquiry['status']){
			sendResponse(0, 'Status must be different from current status.');
		}

		$data 						= [];
		$data['status'] 			= $new_status;
		$data['user_id'] 			= $this->userid;
		$data['updated_by'] 		= $this->userid;
		
		$update = $this->Enquiry_model->update_enquiry($enquiry_id,$data);
		if($update){

			//add history
			$newdata 					= [];
			$newdata['enquiry_id'] 		= $enquiry_id;
			$newdata['user_id'] 		= $this->userid;
			$newdata['status'] 			= $data['status'];		
			$newdata['created_at'] 		= getDt();
			
			$insert = $this->Enquiry_model->add_enquiry_history($newdata);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Status updated successfully' ));

			//add notification to customer
			$notificationData = array(
					'user_id' 			=> $enquiry['customer_id'],
					'user_type' 		=> 'customer',
					'title' 			=> 'Enquiry',
					'description' 		=> 'Enquiry status updated for the enquiry no. '.$enquiry['enquiry_no'],
					'context_id' 		=> $enquiry['id'],
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
			$this->Notification_model->add_notification($notificationData);

			//send mail to customer
			$this->enquiryUpdateMail($enquiry);

			sendResponse(1,'Status changed successfully.');
		}else{
			sendResponse(0,'Failed to change status.');
		}
	}//end status

	private function enquiryUpdateMail($enquiry){
		if(ALLOW_MAILS == 0){
			return false;
		}

		//customer mail
		$customer = $this->Customer_model->get_customer(['id' => $enquiry['customer_id'] ]);

		if($customer){
			$custSubject = 'Ticket updates : Spare Enquiry No. '.$enquiry['enquiry_no'];
			$custMsg 	= '
				Hello '.cap($customer['first_name']).', <br><br>

				There is a remark on your spare enquiry no. '.$enquiry['enquiry_no'].'.<br><br>

				Please check customer support portal for further details.<br><br>

				Best Regards,<br>
				Team Tsubaki. ';
			$sendMail = $this->sendMail($customer['email'], $custSubject, $custMsg);
		}
	}


	function export()
    {	
    	if(isset($_GET['to_date']) && $_GET['to_date'] != ''){
			$_GET['to_date'] = date('Y-m-d', strtotime($_GET['to_date'] . ' +1 day'));
		}

		$params = $this->searchParam(['e.created_at >=' => 'from_date', 'e.created_at <=' => 'to_date','e.id' => 'id'],['e.ga_no' => 'ga_no','e.sparepart_names' => 'sparepart','cm.name' => 'company']);

		if(isset($_GET['ga_type']) && $_GET['ga_type'] != ''){
			$params['like']['e.ga_no'] = $_GET['ga_type'];
		}

		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		
		$columns = 'e.*,c.company_id,cm.name as company,c.first_name,c.last_name';
		$list  = $this->Enquiry_model->get_enquiries_by_join($whereArr,$columns,FALSE,FALSE, $likeArr);
// dd($list);
		
		$status_list = enquiry_status_list();
		$newlist = [];

		foreach ($list as $key => $value) {
			//get who replied to enquiry first
			$handledBy = $this->Enquiry_model->get_enquiry_handled_by(['enquiry_id' => $value['id']]);
			if($handledBy){
				$value['handled_by'] 	= cap($handledBy['first_name'] .' '.$handledBy['last_name']);
			}else{
				$value['handled_by'] = '';
			}

			//get equipment name
			$equiDetails = $this->Equipment_model->get_equipemnt_details_by_project(['p.ga_no' => $value['ga_no']],'e.name');

			$equipment_name = ($equiDetails) ? $equiDetails['name'] : '';
			$created_at 	= custDate($value['created_at']);

			$value['status_name'] = (isset($status_list[$value['status']])) ? $status_list[$value['status']] : $value['status'];

			$spareids = [];
			$spareparts = json_decode($value['spareparts'],true);
			
			if(is_array($spareparts) && !empty($spareparts)){

				foreach ($spareparts as $k => $val) {
					$temp = $value;
					$temp['sparepart_name'] = (isset($val['name'])) ? $val['name'] : '';
					$temp['sparepart_qty'] 	= $val['qty'];
					$temp['equipment_name'] 	= $equipment_name;
					$temp['created_at'] 	= $created_at;
					$newlist[] = $temp;
				}

				/*$qty_arr = [];

				foreach ($spareparts as $k => $val) {
					$qty_arr[$val['sparepart']] = $val['qty'];
				}
				$spareids = array_column($spareparts, 'sparepart');
				
				$sparepartDetails = $this->Sparepart_model->get_spareparts([],'id,name','id',$spareids);
				if($sparepartDetails){
					// $temp = array_column($sparepartDetails, 'name');
					$temp = [];
					foreach ($sparepartDetails as $k => $val) {
						if(isset($qty_arr[$val['id']])){
							$temp[] = $val['name'].' ['.$qty_arr[$val['id']].']';
						}else{
							$temp[] = $val['name'];
						}
					}

					$list[$key]['spareparts'] 	= implode(",", $temp);
				}*/
			} else{
				$temp = $value;
				$temp['sparepart_name'] = '';
				$temp['sparepart_qty'] 	= '';
				$temp['equipment_name'] = $equipment_name;
				$temp['created_at'] 	= $created_at;
				$newlist[] = $temp;
			}
			//end if sparepart array
		}
		// d($newlist);
		// dd($newlist);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*set column names*/
        // $table_columns = array('Sr No.','Enquiry No.', 'Customer','Company','GA No','Equipment','Equipment Model','Sparepart','Qty','Additional Spare','Remark','Attachment','Status','Enquiry Date');
        $table_columns = array('Sr No.','Enquiry No.', 'Customer','Company','GA No','Equipment','Equipment Model','Sparepart','Qty','Additional Spare', 'Handled By', 'Status', 'Enquiry Date');
        $column = 1;
        foreach ($table_columns as $field) {
            $sheet->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        /*end set column names*/

        $excel_row = 2; //now from row 2

        foreach ($newlist as $key=>$row) {
        	$doc = '';
        	if(trim($row['document']) != ''){
        		$doc = base_url($row['document']);
        	}
        	$i=1;
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, ($key+1));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, cap($row['enquiry_no']));

            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula(cap($row['first_name']) .' '.cap($row['last_name'])));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula(cap($row['company'])));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $row['ga_no']);
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula(cap($row['equipment_name'])));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['model']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula(cap($row['sparepart_name'])));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['sparepart_qty']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['query']));

            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['handled_by']));
            // $sheet->setCellValueByColumnAndRow($i++, $excel_row, $row['remark']);
            // $sheet->setCellValueByColumnAndRow($i++, $excel_row, $doc);
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['status_name']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $row['created_at']);
            $excel_row++;
        }
        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="enquiry_list.xlsx"');
        $writer->save('php://output');
    }//end export

}

?>