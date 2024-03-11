<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Customer extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->is_admin()){
			redirect($this->ADMIN_LOGIN);
		}

		$this->load->model('Customer_model');
		$this->load->model('Company_model');
		$this->load->model('Country_model');
	}

	public function index() {
		$company = $this->Company_model->get_all_company([],'id,name');

		$data = [];
		$data['template'] 	= 'ad_customer/adcust_list';
		$data['title'] 		= "Customer List";
		$data['data'] 		= '';
		$data['company'] 	= $company;
		$this->load->view('default', $data);
	}

	public function list() {
		$params = $this->searchParam(['company_id','status'],['first_name' => 'name','last_name' => 'name','email','mobile']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		// dd($params);
		## Total number of records without filtering
		$allres  = $this->Customer_model->count();
		$totalRecords = $allres;

		## Total number of records with filtering
		$allres  = $this->Customer_model->count($whereArr,$likeArr);
		$totalRecordwithFilter = $allres;

		$status_list = status_list();
		$columns = 'id,first_name,last_name,country_code, mobile, email, company_id,status,created_at';
		$list = $this->Customer_model->get_customers($whereArr,$columns,$startrow,$rowperpage,$likeArr);

		// dd($this->pq());

		foreach ($list as $key => $value) {
			$company = $this->Company_model->get_company(['id' => $value['company_id']]);
			if($company){
				$list[$key]['company'] = $company['name'];
			}
			if($value['country_code']){
				$list[$key]['mobile'] 		= '('.$value['country_code'].') '.$value['mobile'] ;
			}else{
				$list[$key]['mobile'] 		= $value['mobile'] ;
			}

			$list[$key]['name'] = ucfirst($value['first_name']).' '.ucfirst($value['last_name']);
			$list[$key]['created_at'] = custDate($value['created_at']);

			unset($list[$key]['company_id']);
			unset($list[$key]['first_name']);
			unset($list[$key]['last_name']);
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
		$phonecodes = $this->Country_model->get_phonecodes();
		$company = $this->Company_model->get_all_company(['status'=>1],'id,name');

		$data = [];
		$data['template'] 			= 'ad_customer/adcust_add';
		$data['title'] 				= "Add Customer";
		$data['data'] 				= '';
		$data['company'] 			= $company;
		$data['phonecodes'] 		= $phonecodes;
		$this->load->view('default', $data);
	}

	public function store(){

		$this->form_validation->set_rules('first_name', 'First Name', 'required|alpha|max[100]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha|max[100]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[customers.email]', array('is_unique' => 'The Email id is already registered.'));
		
		$this->form_validation->set_rules('country_code', 'Country code', 'required|exists[z_countries.phonecode]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		$this->form_validation->set_rules('company', 'Company', 'required|exists[company.id]');
		$this->form_validation->set_rules('location', 'Location', 'required|max[1000]');


		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $valid = $this->validDomain($this->input->post('company',TRUE),$this->input->post('email',TRUE), 1);

        if($valid['status'] == 0){
        	sendResponse(0, $valid['message']);
        }
        //Store
        $password = trim($this->input->post('password',TRUE));

        $data = [];
        $data['first_name'] 		=  trim($this->input->post('first_name',TRUE));
        $data['last_name'] 			=  trim($this->input->post('last_name',TRUE));
        $data['email'] 				=  trim($this->input->post('email',TRUE));
        $data['password'] 			=  hash('sha256', $password);
        $data['mobile'] 			=  trim($this->input->post('mobile',TRUE));
        $data['country_code'] 		=  trim($this->input->post('country_code',TRUE));
        $data['company_id'] 		=  trim($this->input->post('company',TRUE));
        $data['location'] 			=  trim($this->input->post('location',TRUE));
      	
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Customer_model->add_customer($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Customer created successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create customer');
		}
	}//end store dept


	public function view($id) {
		$customer = $this->Customer_model->get_customer_details(['c.id' => $id]);
		if(!$customer){
			$this->sendFlashMsg(0,'Customer data not found', 'customer');
		}

		$data = [];
		$data['template'] 			= 'ad_customer/adcust_view';
		$data['title'] 				= "View Customer";
		$data['data'] 				= $customer;
		$this->load->view('default', $data);
	}	

	public function edit($id) {
		$customer = $this->Customer_model->get_customer(['id' => $id]);
		if(!$customer){
			$this->sendFlashMsg(0,'Customer data not found', 'customer');
		}
		$phonecodes = $this->Country_model->get_phonecodes();
		$company = $this->Company_model->get_all_company(['status'=>1],'id,name');

		$data = [];
		$data['template'] 			= 'ad_customer/adcust_edit';
		$data['title'] 				= "Edit Customer";
		$data['data'] 				= $customer;
		$data['company'] 			= $company;
		$data['phonecodes'] 		= $phonecodes;
		$this->load->view('default', $data);
	}	

	public function update($id){
		$_POST['id'] = $id;

		$change_pass = trim($this->input->post('change_pass',TRUE));
		if($change_pass == 1){
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		}


		$this->form_validation->set_rules('id', 'Customer id', 'required|exists[customers.id]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique_except[customers.email.'.$id.']');

		$this->form_validation->set_rules('first_name', 'First Name', 'required|alpha|max[100]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha|max[100]');
		$this->form_validation->set_rules('country_code', 'Country code', 'required|exists[z_countries.phonecode]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('company', 'Company', 'required|exists[company.id]');
		$this->form_validation->set_rules('location', 'Location', 'required|max[1000]');

		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $valid = $this->validDomain($this->input->post('company',TRUE),$this->input->post('email',TRUE), 1);

        if($valid['status'] == 0){
        	sendResponse(0, $valid['message']);
        }
        //Store
        
        $data = [];
        if($change_pass == 1){
			$password = trim($this->input->post('password',TRUE));
			$data['password'] 			=  hash('sha256', $password);
		}

        $data['first_name'] 		=  trim($this->input->post('first_name',TRUE));
        $data['last_name'] 			=  trim($this->input->post('last_name',TRUE));
        $data['email'] 				=  trim($this->input->post('email',TRUE));
        $data['mobile'] 			=  trim($this->input->post('mobile',TRUE));
        $data['country_code'] 		=  trim($this->input->post('country_code',TRUE));
        $data['company_id'] 		=  trim($this->input->post('company',TRUE));
        $data['location'] 			=  trim($this->input->post('location',TRUE));
      	
		$data['status'] 			= trim($this->input->post('status',TRUE));;
		$data['updated_by'] 		= $this->userid;


		$where = ['id' => $id];
		$insert = $this->Customer_model->update_customer($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Customer updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to update customer');
		}
	}//end store 


	function export()
    {	
    	$params = $this->searchParam(['company_id','status'],['first_name' => 'name','last_name' => 'name','email','mobile']);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		// dd($params);
		## Total number of records without filtering
		$allres  = $this->Customer_model->count();
		$totalRecords = $allres;

		## Total number of records with filtering
		$allres  = $this->Customer_model->count($whereArr,$likeArr);
		$totalRecordwithFilter = $allres;

		$status_list = status_list();
		$columns = '*';
		$list = $this->Customer_model->get_customers($whereArr,$columns,$startrow,$rowperpage,$likeArr);

		// dd($this->pq());

		foreach ($list as $key => $value) {
			$list[$key]['company'] = '';
			$list[$key]['created_at'] = custDate($value['created_at']);

			$list[$key]['contact_no'] = '('.$value['country_code'].')'.$value['mobile'];

			$company = $this->Company_model->get_company(['id' => $value['company_id']]);
			if($company){
				$list[$key]['company'] = $company['name'];
			}
		}


		// dd($list);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*set column names*/
        $table_columns = array('Sr No','Company', 'First Name','Last Name','Email','Contact No','Location','Status','Created At');
        $column = 1;
        foreach ($table_columns as $field) {
            $sheet->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        /*end set column names*/

        $excel_row = 2; //now from row 2

        foreach ($list as $key=>$row) {
        	$status = ( $row['status'] == 1) ? 'Active' : 'Deactive';

            $sheet->setCellValueByColumnAndRow(1, $excel_row, ($key+1));
            $sheet->setCellValueByColumnAndRow(2, $excel_row, clean_cell_formula(cap($row['company'])));
            $sheet->setCellValueByColumnAndRow(3, $excel_row, cap($row['first_name']));
            $sheet->setCellValueByColumnAndRow(4, $excel_row, cap($row['last_name']));
            $sheet->setCellValueByColumnAndRow(5, $excel_row, $row['email']);
            $sheet->setCellValueByColumnAndRow(6, $excel_row, $row['contact_no']);
            $sheet->setCellValueByColumnAndRow(7, $excel_row, clean_cell_formula($row['location'])); 
            $sheet->setCellValueByColumnAndRow(8, $excel_row, $status); 
            $sheet->setCellValueByColumnAndRow(9, $excel_row, $row['created_at']);
            $excel_row++;
        }
        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="customer_list.xlsx"');
        $writer->save('php://output');
    }//end export

}

?>