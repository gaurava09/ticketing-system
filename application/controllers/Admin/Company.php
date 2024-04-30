<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends My_Controller 
{
    public function __construct()
    {
        parent::__construct();

        if(!$this->is_admin()){
            redirect($this->ADMIN_LOGIN);
        }

        $this->load->model('Company_model');
    }

    public function index() {
        $data = [];
        $data['template']   = 'company/company_list';
        $data['title']      = "Company List";
        $this->load->view('default', $data);
    }

    public function list() {        
        $params = $this->searchParam(['status'],['name','location']);

        $draw       = $params['draw'];
        $startrow   = $params['startrow'];
        $rowperpage = $params['rowperpage'];
        $whereArr   = $params['where'];
        $likeArr    = $params['like'];

        $wherein = [];

        ## Total number of records without filtering
        $allres  = $this->Company_model->count();
        $totalRecords = $allres;


        ## Total number of records with filtering
        $allres  = $this->Company_model->count($whereArr,$likeArr,$wherein);
        $totalRecordwithFilter = $allres;

        $columns = '*';
        $list = $this->Company_model->get_all_company($whereArr,$columns,$startrow,$rowperpage , $likeArr,$wherein);

        foreach ($list as $key => $value) {
            $list[$key]['name']       = cap($value['name']);
            $list[$key]['created_at'] = custDate($value['created_at']);
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

    public function create() {
        $data = [];
        $roles = ['admin', 'employee']; // Add the roles you want to retrieve
        $admin = $this->User_model->get_users('', '*', '', '', '', '', $roles);
        // echo "<pre>";
        // print_r($admin);
        // exit;
        $data['data']       = $admin;
        $data['template']   = 'company/company_add';
        $data['title']      = "Add Company";
        $this->load->view('default', $data);
    }

    public function store(){

        $this->form_validation->set_rules('name', 'Name', 'required|is_unique[company.name]');

        // $this->form_validation->set_rules('domain', 'Domain', array(
        //     'required',
        //     array(
        //         'domain_callable',
        //         function($str)
        //         {
        //             return true;
        //         }
        //     ),
        // ));

        $this->form_validation->set_rules('location', 'Location', array('required'));
        //$this->form_validation->set_message('domain_callable', 'Invalid domain');

        if ($this->form_validation->run() == FALSE)
        {   
            sendResponse(0, validation_errors());
        }

        $name                   =  trim($this->input->post('name',TRUE));
        //$domain                 =  trim($this->input->post('domain',TRUE));
        $location               =  trim($this->input->post('location',TRUE));
        $employee               =  $this->input->post('Employee_add');
        // echo "<pre>";
        // print_r($employee_comma);
        // exit;
        $employee_comma = implode(",", $employee);
        // $isInvalid = $this->validateDomain($domain);
        // if($isInvalid){
        //     sendResponse(0, $isInvalid);
        // }

        //Store
        $data = [];
        $data['name']       = $name;
        //$data['domain']     = $domain;
        $data['location']   = $location;
        $data['employees']   = $employee_comma;
        $data['status']     = 1;
        $data['created_by'] = $this->userid;
        $data['created_at'] = getDt();

        $insert = $this->Company_model->add_company($data);
        if($insert){
            $this->session->set_flashdata('message', array('status' => 1, 'message' => 'Company created successfully' ));

            sendResponse(1,'Success');
        }else{
            sendResponse(0,' Failed to create company');
        }
    }//end store dept


    public function view($companyId) {
        $company = $this->Company_model->get_company(['id' => $companyId]);
        if(!$company){
            $this->sendFlashMsg(0,'Company data not found', 'company');
        }

        $employees_array = explode(",", $company['employees']);
        $admin = $this->User_model->get_users('', '*', '', '', '', '', '',$employees_array);
       
        $first_names = '';
        foreach ($admin as $user) {
            $first_names .= $user['first_name'].' '.$user['last_name'].' ['.$user['id'].']('.$user['role']. '), ';
        }

        $first_names = rtrim($first_names, ', ');

        $data = [];
        $data['template']   = 'company/company_view';
        $data['title']      = "View Company";
        $data['data']       = $company;
        $data['employees_array'] = $first_names;
        // echo "<pre>";
        // print_r($data);
        // exit;
        $this->load->view('default', $data);
    }   

    public function edit($companyId) {
        $company = $this->Company_model->get_company(['id' => $companyId]);
        if(!$company){
            $this->sendFlashMsg(0,'Company data not found', 'company');
        }
        $employees_array = explode(",", $company['employees']);
        $admin = $this->User_model->get_users('', '*', '', '', '', '', '',$employees_array);
       
        $first_names = '';
        foreach ($admin as $user) {
            $first_names .= $user['first_name'].' '.$user['last_name'].' ['.$user['id'].']('.$user['role']. '), ';
        }

        $first_names = rtrim($first_names, ', ');

        $roles = ['admin', 'employee']; // Add the roles you want to retrieve
        $admin = $this->User_model->get_users('', '*', '', '', '', '', $roles);
        // echo "<pre>";
        // print_r($admin);
        // echo "<pre>";
        // print_r($company);
        // exit;
        //$data['data'] = $admin;
        //dd($company);

        $employeeIds = explode(',', $company['employees']);

        // Filter array1 to get only the employees whose IDs are in the $employeeIds array
        $matchedEmployees = array_filter($admin, function($employee) use ($employeeIds) {
            return !in_array($employee['id'], $employeeIds);
        });
        // echo "<pre>";
        // print_r($matchedEmployees);
        // exit;
        $data = [];
        $data['template']   = 'company/company_edit';
        $data['title']      = "Edit Company";
        $data['data']       = $company;
        $data['admin']       = $matchedEmployees;
        $data['first_names'] = $first_names;
        //dd($data);
        $this->load->view('default', $data);
    }   

    public function update($companyId){

        $_POST['companyId'] = $companyId;

        $this->form_validation->set_rules('companyId', 'Company id', 'required|integer|exists[company.id]');

        $this->form_validation->set_rules('name', 'Name', 'required|is_unique_except[company.name.'.$companyId.']', array('is_unique_except' =>'Company name must be unique.'));


        //$this->form_validation->set_rules('domain', 'Domain',  array('required') );
        $this->form_validation->set_rules('location', 'Location',  array('required') );
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');


        if ($this->form_validation->run() == FALSE)
        {   
            sendResponse(0, validation_errors());
        }


        $name                   =  trim($this->input->post('name',TRUE));
        //$domain                 =  trim($this->input->post('domain',TRUE));
        $location               =  trim($this->input->post('location',TRUE));
        $status                 =  trim($this->input->post('status',TRUE));
        $employee               =  $this->input->post('Employee_add');
        $already_emp               =  $this->input->post('already_emp');
        // $isInvalid = $this->validateDomain($domain);
        // if($isInvalid){
        //     sendResponse(0, $isInvalid);
        // }
        //end validation
        $added_emp = $already_emp;
        if ($employee[0] != '') {
            $employee_comma = implode(",", $employee);
            $added_emp = $employee_comma.','.$already_emp;
        }
        //Store
        $data = [];
        $data['name']       = $name;
        //$data['domain']     = $domain;
        $data['location']   = $location;
        $data['employees']  = $added_emp;
        $data['status']     = $status;
        $data['updated_by'] = $this->userid;
        $data['created_at'] = getDt();

        $where = ['id' => $companyId];
        $insert = $this->Company_model->update_company($where,$data);
        if($insert){
            $this->session->set_flashdata('message', array('status' => 1, 'message' => 'Company updated successfully' ));

            sendResponse(1,'Success');
        }else{
            sendResponse(0,' Failed to create company');
        }
    }//end store dept


    /*private function validateDomain($domain){

        $error = '';
        if (strpos($domain, 'https://') !== false || strpos($domain, 'http://') !== false ) {
            $error = 'Please remove protocol from domain name';
        }
        else if (strpos($domain, 'www.') !== false) {
            $error = 'Please remove www from domain name';
        }

        else if (!strpos($domain, '.') !== false ) {  // if dot does not exist
            $error = 'Invalid domain name';
        }

        else if(substr($domain, -1) == "."){  // if dot is a last character
            $error = 'Invalid domain name!';
        }

        else if (!preg_match('/^[a-zA-Z0-9-_\.]*$/', $domain)){  //if other than specified chars
            $error = 'Invalid domain name. Enter only alphanumeric and special chars like <b>. - _ </b> ';
        }else{
            $arr = explode(".", $domain);

            if (!preg_match('/^[a-zA-Z]*$/', end($arr) ) ){
                $error = 'Invalid domain name.';
            }
        }

        return $error;
    }*/


}

?>