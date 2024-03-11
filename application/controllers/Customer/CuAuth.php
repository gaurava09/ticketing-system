<?php
//https://codeigniter.com/userguide3/libraries/form_validation.html
defined('BASEPATH') OR exit('No direct script access allowed');

class CuAuth extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_model');
		$this->load->model('Company_model');

		$this->load->model('Pages_model');
	}

	public function login() {
		if($this->is_logged_in()){
			if($this->is_customer()){
				redirect($this->CUST_HOME);
			}
			else if($this->is_employee()){
				redirect($this->EMP_HOME);
			}else{
				redirect($this->ADMIN_HOME);
			}
		}

		if($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$email = $this->input->post('email',TRUE);
			$password = $this->input->post('password',TRUE);
			$recaptcha_response = $this->input->post('captcha',TRUE);

			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }

            //validate google captcha
  
			// Make and decode POST request:
			$recaptcha = file_get_contents(RECAPTCHA_URL . '?secret=' . RECAPTCHA_SECRET . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);
			if ($recaptcha->success != 1) {
				sendResponse(2,'Invalid google captcha');
			}

			//validate password
            $password = hash('sha256', $password);
			$user = $this->Customer_model->get_customer(
				array('email'=>$email, 'password' => $password, 'status' => 1)
			);
			
			if($user) {	
				$result = $this->Company_model->get_company(['id' => $user['company_id'],'status' => 1]);
				if(!$result){
					sendResponse(0, 'Invalid access');
				}

				validateLoginTime($user['log_time']);

				$user['role'] = 'customer';
				$this->session->set_userdata( $this->user, $user );

				$this->Customer_model->update_customer(['id' => $user['id']], ['log_time' => time()]);
				
				sendResponse(1, 'success');
			} 
			else {	
				sendResponse(0, 'Invalid email or password');
			}	
		} 
		else{
			$page = $this->Pages_model->get_page(['page_type' => 'login']);

			$data = [];
			$data['title'] 		= "Login";
			$data['data'] 		= '';
			$data['page'] 		= $page;
			$data['RECAPTCHA_SITEKEY'] 		= RECAPTCHA_SITEKEY;
			$this->load->view('customer/login', $data);
		}
	}
	

	public function verifyDetails(){

		if($this->input->server('REQUEST_METHOD') == 'POST') {
			//dd($_POST);
			$f_name 				=  $this->input->post('f_name',TRUE);
			$l_name 				=  $this->input->post('l_name',TRUE);
			$email 					=  $this->input->post('email',TRUE);
			$country_code 			=  $this->input->post('country_code',TRUE);
			$mobile 				=  $this->input->post('mobile',TRUE);
			$company 				=  $this->input->post('company',TRUE);
			$location 				=  $this->input->post('location',TRUE);
			
			$this->form_validation->set_rules('location', 'Location', 'required|max_length[1000]');
			$this->form_validation->set_rules('f_name', 'First Name', 'required|alpha');
			$this->form_validation->set_rules('l_name', 'Last Name', 'required|alpha');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[customers.email]', array('is_unique' => 'The Email id is already registered.'));
			$this->form_validation->set_rules('country_code', 'Country code', 'required');
			$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|numeric');
			$this->form_validation->set_rules('company', 'Company', 'required|alpha_numeric');

			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }
            //dd($_POST);
            //validate company and email id
            // $companyDetails = $this->Company_model->get_company(array('id' => $company,'status' => 1));
            // if($companyDetails){
            // 	$domain = $companyDetails['domain'];
            // 	if($domain == ''){
            // 		sendResponse(0, 'Company domain is not registered');
            // 	}else{
            // 		//validate with email
            // 		$split = explode("@",trim($email));
            // 		if($split[1] != $domain){
            // 			sendResponse(0, 'Email id does not match with Company domain');
            // 		}
            // 	}
            // }else{
            // 	sendResponse(0, 'Invalid company');
            // }
            $verification_code 	= mt_rand(100000, 999999);

            //send verification code to email
            $subject = 'Tsubaki Account Verification Code';
			$bodyMsg = "Hi ".ucfirst($f_name).",<br><br>

					You have requested verification code.<br>
					Your Verification code is <b>".$verification_code."</b> to verify your e-mail ID.<br><br>

					Please enter this code to complete registration activity.<br>
					We are happy to have you on board. <br><br>

					Best Regards,<br>
					Team Tsubaki.
					";
			$sendMail = $this->sendMail($email, $subject, $bodyMsg);



            $hash = md5($f_name.'|'.$l_name.'|'.$email.'|'.$country_code.'|'.$mobile.'|'.$company);
            $this->session->set_userdata( 'temp_register_hash', $hash);
            $this->session->set_userdata( 'temp_code_attempt', 0);
            $this->session->set_userdata( 'temp_v_code', $verification_code);

			// sendResponse(1, $verification_code);
			sendResponse(1, 'success');
		}else{
			redirect('/');
		}
	}

	public function verifyCode(){

		if($this->input->server('REQUEST_METHOD') == 'POST') {
			$vcode =  $this->input->post('vcode',TRUE);
			//dd($vcode);

			$this->form_validation->set_rules('vcode', 'Verification Code', 'required');

			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }

            //validate attempts
            $attempts = $this->session->userdata( 'temp_code_attempt');
            if($attempts >= 4){
            	sendResponse(0, 'Please reload page and register again');
            }
            $attempts++;
            $this->session->set_userdata( 'temp_code_attempt', $attempts);

            $verification_code = $this->session->userdata( 'temp_v_code');

            if($verification_code != $vcode){
            	sendResponse(0, 'Invalid Code');
            }

			sendResponse(1, 'Success');
		}else{
			redirect('/');
		}
	}


	public function register(){

		if($this->input->server('REQUEST_METHOD') == 'POST') {
			//dd($_POST);
			$f_name 				=  $this->input->post('f_name',TRUE);
			$l_name 				=  $this->input->post('l_name',TRUE);
			$email 					=  $this->input->post('email',TRUE);
			$country_code 			=  $this->input->post('country_code',TRUE);
			$mobile 				=  $this->input->post('mobile',TRUE);
			$company 				=  $this->input->post('company',TRUE);
			$location 				=  $this->input->post('location',TRUE);
			$pass_1 				=  trim($this->input->post('pass_1',TRUE));
			$recaptcha_response 	= $this->input->post('captcha',TRUE);

			//Start validation
			$this->form_validation->set_rules('captcha', 'Captcha', 'required');
			$this->form_validation->set_rules('f_name', 'First Name', 'required|alpha');
			$this->form_validation->set_rules('l_name', 'Last Name', 'required|alpha');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[customers.email]', array('is_unique' => 'The Email id is already registered.'));
			$this->form_validation->set_rules('country_code', 'Country code', 'required|exists[z_countries.phonecode]');
			$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|numeric');
			$this->form_validation->set_rules('company', 'Company', 'required|integer');

			$this->form_validation->set_rules('pass_1', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('pass_2', 'Confirm Password', 'required|matches[pass_1]');
			$this->form_validation->set_rules('location', 'Location', 'required|max_length[1000]');


			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }
			
			//validate google captcha

			// Make and decode POST request:
			$recaptcha = file_get_contents(RECAPTCHA_URL . '?secret=' . RECAPTCHA_SECRET . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);
			if ($recaptcha->success != 1) {
				sendResponse(2,'Invalid google captcha');
			}

			//check if data is not modified
            $hash = md5($f_name.'|'.$l_name.'|'.$email.'|'.$country_code.'|'.$mobile.'|'.$company);
			$oldhash = $this->session->userdata( 'temp_register_hash');

			if($hash != $oldhash){
				sendResponse(0, 'Failed to validate data integrity');
			}

			$activeCompany = $this->Company_model->get_company(['id'=>$company, 'status'=>1]);
			if(!$activeCompany){
				sendResponse(0, 'Company is deactivated');
			}
			//end validation
		

			$data = array();
			$data['status'] 				= 1;
			$data['first_name'] 			= $f_name;
			$data['last_name'] 				= $l_name;
			$data['email'] 					= $email;
			$data['password'] 				= hash('sha256', $pass_1);
			$data['country_code'] 			= $country_code;
			$data['mobile'] 				= $mobile;
			$data['company_id'] 			= $company;
			$data['location'] 				= $location;

			$data['created_at'] 			= getDt();

			$custId = $this->Customer_model->add_customer($data);
			if($custId){

				$customer = $this->Customer_model->get_customer(['id' => $custId]);
				if($customer){
					$customer['role'] = 'customer';
					$this->session->set_userdata( $this->user, $customer );
				}

				sendResponse(1, 'Customer Registration successful');
			}else{
				sendResponse(0, 'Failed to register. Please contact admin.');
			}
		}
		else{
			if($this->is_logged_in()){
				if($this->is_customer()){
					redirect($this->CUST_HOME);
				}
				else if($this->is_employee()){
					redirect($this->EMP_HOME);
				}else{
					redirect($this->ADMIN_HOME);
				}
			}

			$this->load->model('Country_model');
			// $countries = $this->Country_model->get_all_country([],'distinct(dial)');

			$phonecodes = $this->Country_model->get_phonecodes();

			$page = $this->Pages_model->get_page(['page_type' => 'registration']);

			$companyDetails = $this->Company_model->get_all_company(['status'=>1],'id,name');
			$data 						= [];
			$data['title'] 				= "Register";
			$data['data'] 				= $companyDetails;
			$data['page'] 				= $page;
			$data['phonecodes'] 		= $phonecodes;
			$data['RECAPTCHA_SITEKEY'] 	= RECAPTCHA_SITEKEY;
			$this->load->view('customer/register', $data);
		} //end if post
	}


	function logout()
	{	
		$this->updateUserLogtime($this->userid);
		$this->session->unset_userdata($this->user);
    	redirect($redirect);
	}

	public function forgotpassword(){
		if($this->input->server('REQUEST_METHOD') == 'POST') {
			$email 		=  $this->input->post('email',TRUE);
			$recaptcha_response = $this->input->post('captcha',TRUE);

			$this->form_validation->set_rules('captcha', 'Captcha', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }

            //validate google captcha

			// Make and decode POST request:
			$recaptcha = file_get_contents(RECAPTCHA_URL . '?secret=' . RECAPTCHA_SECRET . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);
			if ($recaptcha->success != 1) {
				sendResponse(2,'Invalid google captcha');
			}

            //validate attempts
            // $user = $this->Customer_model->get_customer(['email'=>$email,'status' =>1]);
            $user = $this->Customer_model->get_customer_details(['c.email' => $email, 'c.status' => 1, 'co.status' => 1]);
	
			if($user) {	

				$this->load->model('PasswordReset_model');
				$where = array(
					'email' => $email,
					'user_type' => 'customer'
				);
				$this->PasswordReset_model->delete_data($where);

				//send password reset link
				$token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);

				$data = array(
					'email' => $email,
					'user_type' => 'customer',
					'token' => $token,
					'created_at' => getDt(),
				);
				$insertid = $this->PasswordReset_model->add_data($data);

				if($insertid){
					$subject = 'Tsubaki: Customer Support Portal - Password Reset Link';


					$message = "
								Dear ".cap($user['first_name']).", <br><br>
								Please click on below link to reset password of account. <br>
	        		
	        					".site_url('customer/resetpassword/'.$token)." <br><br>

	        					Best Regards, <br>
	        					Team Tsubaki
	        					";


					
					//send email
			   		$sendMail = $this->sendMail($email, $subject, $message);

	            	sendResponse(1, 'Success.');
				}else{
					sendResponse(0, 'Failed to save token. Please contact administrator');
				}
            	
            }else{
            	sendResponse(0, 'Invalid Email');
            }
		}else{
			$page = $this->Pages_model->get_page(['page_type' => 'forgotpass']);

			$data 						= [];
			$data['title'] 				= "Forgot Password";
			$data['page'] 				= $page;
			$data['data'] 				= '';
			$data['RECAPTCHA_SITEKEY'] 	= RECAPTCHA_SITEKEY;
			$this->load->view('customer/forgot_pass', $data);
		}
	}


	public function resetPassword($reset_token){
		$page = $this->Pages_model->get_page(['page_type' => 'resetpass']);

		$data 						= [];
		$data['title'] 				= "Reset Password";
		$data['data'] 				= '';
		$data['page'] 				= $page;
		$data['reset_token'] 		= $reset_token;
		$data['RECAPTCHA_SITEKEY'] 	= RECAPTCHA_SITEKEY;
		$this->load->view('customer/reset_pass', $data);
	}

	public function resetPasswordVerify(){
		$email 					=  $this->input->post('email',TRUE);
		$reset_token 			=  $this->input->post('reset_token',TRUE);
		$pass_1 				=  trim($this->input->post('pass_1',TRUE));
		$recaptcha_response 	= $this->input->post('captcha',TRUE);

		//Start validation
		$this->form_validation->set_rules('captcha', 'Captcha', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('reset_token', 'Reset Token', 'required|min_length[8]', array('min_length' => 'Invalid token'));
		$this->form_validation->set_rules('pass_1', 'Password', 'required|min_length[8]');
		$this->form_validation->set_rules('pass_2', 'Confirm Password', 'required|matches[pass_1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //validate google captcha

		// Make and decode POST request:
		$recaptcha = file_get_contents(RECAPTCHA_URL . '?secret=' . RECAPTCHA_SECRET . '&response=' . $recaptcha_response);
		$recaptcha = json_decode($recaptcha);
		if ($recaptcha->success != 1) {
			sendResponse(2,'Invalid google captcha');
		}

        //validate token with email
        $this->load->model('PasswordReset_model');

        $where = array(
					'email' => $email,
					'user_type' => 'customer',
					'token' => $reset_token,
				);
        $exist = $this->PasswordReset_model->get_data($where);

        if($exist){

        	$isActive = $this->Customer_model->get_customer_details(['c.email' => $email, 'c.status' => 1, 'co.status' => 1]);
			if(!$isActive){
				sendResponse(0, 'Account is deactivated.');
			}

        	$this->load->model('PasswordReset_model');
			$where = array('email' => $email,'user_type' => 'customer');
			$this->PasswordReset_model->delete_data($where);

			//set new customer password
			$data = array('password' => hash('sha256', $pass_1));
			$cust_where = array('email' => $email);
			$custId = $this->Customer_model->update_customer($cust_where,$data);

			if($custId){
				sendResponse(1, 'Reset Password successful');
			}else{
				sendResponse(0, 'Failed to Reset Password. Please contact admin.');
			}
        }else{
        	sendResponse(0, 'Invalid token or email.');
        }
		//end validation
	
	}//end function


	public function getCompany() {
		$email	=  trim($this->input->post('email',TRUE));

		if($email == ''){
			sendResponse(0, 'Email is required');
		}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			sendResponse(0, 'Invalid email');
		}

		$email_arr = explode('@', $email);

		// $result = $this->Company_model->get_company(array('domain'=>$email_arr[1], 'status' => 1));
		$result = $this->Company_model->get_all_company(array('domain'=>$email_arr[1], 'status' => 1), 'id,name');
        if($result){        	
        	$temp = [];
        	foreach ($result as $key => $value) {
        		$value['name'] = cap($value['name']);
        		$temp[] = $value;
        	}
        	sendResponse(1, 'success', $temp);
        	// sendResponse(1, 'success', array('id'=>$result['id'], 'name'=>$result['name']));
        }else{
        	sendResponse(0, 'Company details not found');
        }
	}//end function

}

?>