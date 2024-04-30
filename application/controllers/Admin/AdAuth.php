<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdAuth extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Pages_model');
	}

	public function login() {
		
		//for sso
		if(ENVIRONMENT == 'production' && 1 > 2 ){

			// require_once (dirname(__FILE__) . '/simplesaml/lib/_autoload.php');
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/simplesaml/lib/_autoload.php');
			$auth = new SimpleSAML_Auth_Simple('default-sp');
			
			SimpleSAML_Session::getSessionFromRequest()->cleanup();

			if (!$auth->isAuthenticated()) {
				$auth->requireAuth(); //this will redirect if not authenticated
			}else{
				$attributes = $auth->getAttributes();

				if(! isset($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/upn'][0])){
				    
				    $data = [];
				    $data['title'] 		= "SSO Error";
				    $data['heading'] 		= "SSO Error";
					$data['message'] 	= "Result Data is missing";
					$data['flag'] 		= 1;
					$this->load->view('errors/html/error_sso', $data);
				}else{

					SimpleSAML_Session::getSessionFromRequest()->cleanup();

					// destroy the session
					// this is important because current session was started by saml.
					// so saml and CI sessions can work simultaneously
					session_destroy(); 

					$this->load->library("session");

					$email = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/upn'][0];

					//check if email exist
					$user = $this->User_model->get_user(['email' => $email, 'status' => 1]);
					if($user) {	
						unset($user['password']);
						$this->session->set_userdata( $this->user, $user);

						$this->User_model->update_user(['id' => $user['id']], ['log_time' => time()]);

						redirect($this->ADMIN_HOME);
					} 
					else {	

						$url = $auth->getLogoutURL();
						$extra = '<p><a href="' . htmlspecialchars($url) . '">Logout SSO session</a></p>';

						$data = [];
					    $data['title'] 		= "Error";
					    $data['heading'] 	= "Error";
						$data['message'] 	= "Email does not exist.";
						// $data['extra'] 		= $extra;
						$data['flag'] 		= 1;
						$this->load->view('errors/html/error_sso', $data);
					}	
				}

			}//end - check if authenticate

		}else{
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
				$this->validateLogin();
			} 
			else{
				/*if($this->is_logged_in()) {
					redirect('employee');
				}*/
				$page = $this->Pages_model->get_page(['page_type' => 'login']);
				
				$data['title'] 		= "Login";
				$data['data'] 		= '';
				$data['page'] 		= $page;
				$data['RECAPTCHA_SITEKEY'] 		= RECAPTCHA_SITEKEY;
				$this->load->view('admin/login', $data);
			}
		}
	}

	//https://codeigniter.com/userguide3/libraries/form_validation.html
	private function validateLogin()
	{	
		
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{	
			$email 				= $this->input->post('email',TRUE);
			$password 			= $this->input->post('password',TRUE);
			$recaptcha_response = $this->input->post('captcha',TRUE);

			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == FALSE)
            {	
            	sendResponse(0, validation_errors());
            }

            //validate google captcha
           /* $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
			$recaptcha_secret = '6Lfh-GIaAAAAALdQt_uIyx-rP8WiKB1IFFtocbAg';

			// Make and decode POST request:
			$recaptcha = file_get_contents(RECAPTCHA_URL . '?secret=' . RECAPTCHA_SECRET . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);
			if ($recaptcha->success != 1) {
				sendResponse(2,'Invalid google captcha');
			}*/
			
			//validate password
            $password = hash('sha256', $password);
			$user = $this->User_model->get_user(['email' => $email, 'password' => $password, 'status' => 1]);
			// dd($user);
			if($user) {	
				//validate login time

				validateLoginTime($user['log_time']);
	
				unset($user['password']);
				$this->session->set_userdata( $this->user, $user);

				$this->User_model->update_user(['id' => $user['id']], ['log_time' => time()]);

				sendResponse(1, 'success');
			} 
			else {	
				sendResponse(0, 'Invalid email or password');
			}	
		}else{
			redirect('/');
		}
	}
	

	function logout()
	{	
		if($this->role == 'customer'){
			$redirect = 'customer/login';
		}else{
			$redirect = 'admin/login';
		}
		$this->updateUserLogtime($this->userid);
		
		$this->session->unset_userdata($this->user);
    	redirect($redirect);
	}


}

?>