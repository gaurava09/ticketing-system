<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdFeedback extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_admin(1,$this->CUST_LOGIN);

		$this->load->model('Customer_model');
		$this->load->model('Company_model');
		$this->load->model('Project_model');
		$this->load->model('Complaint_model');
		$this->load->model('Equipment_model');
		$this->load->model('Feedback_model');
	}

	public function index() {	
		$data = [];
		$data['template'] 	= 'ad_feedback/ad_feedback_list';
		$data['title'] 		= "Feedback List";
		$this->load->view('default', $data);
	}

	public function list() {		
		$params = $this->searchParam(['c.complaint_type' => 'complaint_type','c.classification' => 'classification'],['c.ga_no' => 'ga_no','c.ticket_no' => 'ticket_no']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		## Total number of records without filtering
		$allres  = $this->Feedback_model->get_feedbacks_by_join([],'f.id');
		$totalRecords = count($allres);

		## Total number of records with filtering
		$columns = 'f.complaint_id,f.created_at, c.ticket_no,c.ga_no, c.complaint_type,c.classification';
		$list  = $this->Feedback_model->get_feedbacks_by_join($whereArr,$columns,$startrow,$rowperpage , $likeArr);

		$totalRecordwithFilter = count($list);


		$complaint_types = complaint_types();
  		$classifications = classifications();

		// dd($list);
		// dd($this->pq());
		foreach ($list as $key => $value) {

			$list[$key]['complaint_type'] 	= (isset($complaint_types[$value['complaint_type']])) ? $complaint_types[$value['complaint_type']] : '';
			$list[$key]['created_at'] 		= custDate($value['created_at']);

			$list[$key]['classification'] = '';

			if(isset($classifications[$value['classification']])) {
				$list[$key]['classification'] 	= $value['classification'].' - '.$classifications[$value['classification']];
			}
		}

		$response = array(
		 	"draw" 					=> intval($draw),
		 	"totalRecords" 			=> $totalRecords,
		 	"totalRecordwithFilter" => $totalRecordwithFilter,
		 	"aaData" 				=> $list
		);

		sendResponse(1, 'success', $response);
	}
	

	function export()
    {	
    	$params = $this->searchParam(['c.complaint_type' => 'complaint_type','c.classification' => 'classification'],['c.ga_no' => 'ga_no','c.ticket_no' => 'ticket_no']);

		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$columns = 'f.*, c.ticket_no,c.ga_no,c.customer_id, c.complaint_type,c.classification';
		$list  = $this->Feedback_model->get_feedbacks_by_join($whereArr,$columns,FALSE,FALSE , $likeArr);

		
		// dd($this->pq());
		foreach ($list as $key => $value) {

			$ratings = json_decode($value['rating'],TRUE);
			$list[$key]['tech_knowledge'] 			= ps($ratings,'tech_knowledge');
			$list[$key]['comm_skill'] 				= ps($ratings,'comm_skill');
			$list[$key]['punctuality'] 				= ps($ratings,'punctuality');
			$list[$key]['safety'] 					= ps($ratings,'safety');
			$list[$key]['equipment_performance'] 			= ps($ratings,'equipment_performance');
			$list[$key]['service_rating'] 			= ps($ratings,'service_rating');

			$list[$key]['created_at'] 	= custDate($value['created_at']);

			//get company name and equi name
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $value['ga_no']));
			// dd($project);
			$customer = $this->Customer_model->get_customer_details(['c.id' => $value['customer_id']], 'first_name, last_name');

			$list[$key]['equipment_name'] 	= cap(ps($project,'equipment_name'));
			$list[$key]['company_name'] 	= cap(ps($project,'company'));
			$list[$key]['customer_name'] 	= cap(ps($customer,'first_name')). ' '. cap(ps($customer,'last_name'));
			$list[$key]['company_name'] 	= cap(ps($customer,'company_name'));
		}

		// dd($list);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*set column names*/
        $table_columns = array('Sr No', 'Ticket ID','Customer','Company','GA Number','Equipment','Visit Period','MTC Representative Name','Complaint Type','Classification',
        	'Was the visit timing suitable to your requirement?',
        	'Technical knowledge',
        	'Communication skills',
        	'Punctuality',
        	'Commitment to Safety',
        	'Feedback on Equipment Performance',
        	'Any Suggestion for Improvement in Service',
        	'Comments',
        	'Please rate us on the scale of 1-10 for the service provided',
        	'Created At'
        	);
        $column = 1;
        foreach ($table_columns as $field) {
            $sheet->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        /*end set column names*/

        $complaint_types = complaint_types();
		$classifications = classifications();

        $excel_row = 2; //now from row 2

        foreach ($list as $key=>$row) {

        	if($row['complaint_type'] == 1) {
        		if (strstr($row['period'], 'to')) {
				     $dateArr = explode("to", $row['period']);
				     if(isset($dateArr[0]) && $dateArr[0] != ''){
				        $from = custDate(trim($dateArr[0]));
				     }
				     if(isset($dateArr[1]) && $dateArr[1] != ''){
				        $to = custDate(trim($dateArr[1]));
				     }
				     $visit_date = $from.' to '.$to;
				  }else{
				    $visit_date = custDate($row['period']);
				  }
			    
			}else{
				$visit_date = '';
			}

			$comp_type = (isset($complaint_types[$row['complaint_type']])) ? $complaint_types[$row['complaint_type']] : '';

			$class = '';

			if(isset($classifications[$row['classification']])){
				$class	= $row['classification'].' - '.$classifications[$row['classification']];
			}

			$i=1;

            $sheet->setCellValueByColumnAndRow($i++, $excel_row, ($key+1));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $row['ticket_no']);
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['customer_name']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['company_name']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['ga_no']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['equipment_name'])); 
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $visit_date ); 
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['representative_name']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $comp_type);
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $class);
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['suitable_time']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['tech_knowledge']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['comm_skill']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['punctuality']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['safety']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['equipment_performance']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['suggestion']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['comment']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, clean_cell_formula($row['service_rating']));
            $sheet->setCellValueByColumnAndRow($i++, $excel_row, $row['created_at']);
            $excel_row++;
        }
        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="feedback_list.xlsx"');
        $writer->save('php://output');
    }//end export

	public function view($complaintId) {
		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId]);

		if($complaint){
			$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId]);

			if(!$feedback){
				$this->sendFlashMsg(0,'Feedback details not found', 'feedback');
			}
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));

			$customer = $this->Customer_model->get_customer_details(['c.id' => $complaint['customer_id']]);

		}else{
			$this->sendFlashMsg(0,'Complaint details not found', 'feedback');
		}
		// d($feedback);
		// d($project);
		// dd($complaint);

		$data = [];
		$data['template'] 		= 'ad_feedback/ad_feedback_view';
		$data['title'] 			= "View Feedback";
		$data['complaint'] 		= $complaint;
		$data['feedback'] 		= $feedback;
		$data['project'] 		= $project;
		$data['customer'] 		= $customer;
		$this->load->view('default', $data);
	}	
}

?>