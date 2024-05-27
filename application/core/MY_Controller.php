<?php

class My_Controller extends CI_Controller {

	//Class-wide variable to store stats line
	protected $stats;
	protected $title;
	protected $main_content;

	public function __construct() {
	    parent::__construct();

	    $this->ADMIN_HOME 	= '/'; 
	    $this->EMP_HOME 	= '/'; 
	    $this->ADMIN_LOGIN 	= 'admin/login'; 
	    $this->CUST_HOME 	= 'customer/dashboard'; 
	    $this->CUST_LOGIN 	= 'customer/login'; 

	    $this->user = 'user'; //session names

	    $class = $this->router->fetch_class();
		$method = $this->router->fetch_method();
	
		if(ENVIRONMENT == 'production' && $class == 'AdAuth' && $method == 'login' && 1 > 2){
			// echo ' dont load library';
		}else{
			$this->load->library("session");

		    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		    
			if($this->session->userdata($this->user)){
				$userData = $this->session->userdata($this->user);
				//dd($userData);
				$this->userid = $userData['id'];
				$this->email = $userData['email'];
				$this->role = $userData['role'];

				if(isset($userData['profile_picture']) && $userData['profile_picture'] != ''){
					$this->picture = $userData['profile_picture'];
				}else{
					$this->picture = 'assets/dist-assets/images/faces/user.jpg';
				}
				
				$this->company_id = isset($userData['company_id']) ? $userData['company_id'] : 0;

				if($this->uri->segment(1) != 'logout'){
					$this->is_customer_active($this->userid);
				}
				
				$this->updateUserLogtime($this->userid, time());

				//validate malicious chars
				$this->validatePostFields();
			}
		}//end if production env dont load session library for login page
	}


	
	public function validatePostFields(){
		$directory = strtolower($this->router->directory);
		$class = $this->router->fetch_class();
		$method = $this->router->fetch_method();

		if($directory != 'pages/'){
			$is_ajax = 0;
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
				$is_ajax = 1;
			}

			// print_r($_SERVER);
			$referer = (isset($_SERVER['HTTP_REFERER']) ) ? $_SERVER['HTTP_REFERER'] : '/';
			
			foreach ($_POST as $key => $value) {
				// Skip the 'description' field
			    if ($key === 'description') {
			        continue;
			    }
				if(is_string($value)){
					$this->_validateInpFields($value,$is_ajax,$referer);
				}else if(is_array($value)){
					foreach ($value as $k => $v) {
						$this->_validateInpFields($v,$is_ajax,$referer);
					}
				}
			} //end foreach
		} //end if
		
	}

	private function _validateInpFields($value,$is_ajax,$referer){
		if (preg_match('/[\'"\^%*()}!{><;`=+]/', $value)){
			if($is_ajax == 1){
				sendResponse(0,'Please remove special characters from input fields.');
			}else{
				$this->session->set_flashdata('message', array('status' => 0, 'message' => 'Please remove special characters from input fields.' ));
				redirect($referer);
			}
		} 
	}

	public function updateUserLogtime($userid,$current_time=''){

		if($this->role == 'customer'){
			$this->load->model('Customer_model');
			$this->Customer_model->update_customer(['id' => $userid], ['log_time' => $current_time]);
		}else{
			$this->load->model('User_model');
			$this->User_model->update_user(['id' => $userid], ['log_time' => $current_time]);
		}
	}

	public function is_customer_active($userid){
		if($this->role == 'customer'){
			$this->load->model('Customer_model');
			
			$active = $this->Customer_model->get_customer_details(['c.id' => $userid, 'c.status' => 1, 'co.status' => 1]);
			if(!$active){
				redirect('logout');
			}
		}
	}
	public function is_logged_in($redirect=0,$url='/'){
		$user = $this->session->userdata($this->user);
		if($user){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

	public function is_sales($redirect=0,$url='/'){
		$user = $this->session->userdata($this->user);
		if(isset($user['role']) && $user['role'] == 'sales'){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

	public function is_admin($redirect=0,$url='/'){
		$user = $this->session->userdata($this->user);
		if(isset($user['role']) && $user['role'] == 'super_admin'){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

	public function is_a_admin($redirect=0,$url='/'){
		$user = $this->session->userdata($this->user);
		if(isset($user['role']) && $user['role'] == 'admin' || $user['role'] == 'super_admin'){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

	public function is_employee($redirect=0,$url=''){
		$user = $this->session->userdata($this->user);
		if(isset($user['role']) && $user['role'] == 'employee'){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

	function is_customer($redirect=0,$url=''){
		$user = $this->session->userdata($this->user);
		if(isset($user['role']) && $user['role'] == 'customer'){
			return true;
		}
		else{
			if($redirect == 1 ){
				redirect($url); die();
			}
			return false;
		}		
	}

    public function sendFlashMsg($status, $msg, $url){
        $this->session->set_flashdata('message', array('status' => $status, 'message' => $msg ));

        redirect($url);
        die();
    }


    public function pq($die=0){ //print_query
	  echo $str = $this->db->last_query(); 
	  if($die == 1){
	    die;
	  }
	}

	function sendMail($toEmail,$subject,$body,$file='',$filename='',$ccEmails='',$bccEmails=''){ 
		if(ALLOW_MAILS == 0){
			return false;
		}
	    $this->load->library("phpmailer_library");
        $mail = $this->phpmailer_library->load();

        $mail->IsSMTP();        
		// $mail->SMTPDebug  = 3;      
		// $mail->Debugoutput = 'html';                       
		$mail->Host = SMTP_HOST;               
		$mail->SMTPAuth = true;                              
		$mail->Username = SMTP_USERNAME;                
		$mail->Password = SMTP_PASSWORD; 
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';

	/*	$mail->smtpConnect([
		    'ssl' => [
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    ]
		]);
			*/

		$mail->From = SMTP_SENDER_EMAIL; 
		$mail->FromName = SMTP_SENDER_NAME;

		if(is_array($toEmail)){
			foreach($toEmail as $key => $value)
			{
			   $mail->AddAddress($value);
			}
		}else{
			$mail->AddAddress($toEmail);
		}

		if($ccEmails){
			foreach($ccEmails as $key => $value)
			{
			   $mail->AddCC($value);
			}
		}

		if($bccEmails){
			foreach($bccEmails as $key => $value)
			{
			   $mail->AddBCC($value);
			}
		}

		$mail->IsHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $body;

		if($file){
			$mail->addAttachment($file, $filename);
		}
		

		return $mail->Send();
    die;
		if(!$mail->send()) {

			$mail->ClearAddresses();
			$mail->ClearCCs();
			$mail->ClearBCCs();
			$mail->clearAttachments();

		    log_message('error', 'Email : '.$mail->ErrorInfo);
		    return false;
		} else {
			$mail->ClearAddresses();
			$mail->ClearCCs();
			$mail->ClearBCCs();
			$mail->clearAttachments();

		    return true;
		}
	}


	function validDomain($companyId,$email,$status = ''){
		$this->load->model('Company_model');

		$where = array('id'=>$companyId);
		if($status){
			$where['status'] = $status;
		}

		$flag = 1;
		$msg = '';

		//validate company and email id
        $companyDetails = $this->Company_model->get_company($where);

        if($companyDetails){
        	$domain = $companyDetails['domain'];
        	if($domain == ''){
        		$flag = 0;
				$msg = 'Company domain is not registered';
        	}else{
        		//validate with email
        		$split = explode("@",trim($email));
        		if($split[1] != $domain){
        			$flag = 0;
					$msg = 'Email id does not match with Company domain';
        		}
        	}
        }else{
        	$flag = 0;
			$msg = 'Company details not found';
        }

        return array('status' => $flag,'message' => $msg);
	}

	public function searchParam($where=[],$like=[])
	{	
		$data = [];
		$whereArr = [];
		$likeArr = [];

	
		if(isset($_GET['draw'])){
			$data['draw'] = $_GET['draw'];
		}else{
			$data['draw'] = 1;
		}

		if(isset($_GET['start'])){
			$data['startrow'] = $_GET['start'];
		}else{
			$data['startrow'] = 0;
		}

		if(isset($_GET['length'])){
			$data['rowperpage'] = $_GET['length'];
		}else{
			$data['rowperpage'] = 10;
		}

		if($where){
			foreach ($where as $key => $value) {
				if($value != ''){
					$newkey = $key;
					if(is_int($key)){
						$newkey = $value;
					}

					if(trim($this->input->get($value,TRUE)) != ''){
						$whereArr[$newkey] =trim($this->input->get($value,TRUE));
					}
				}
			}
		}

		if($like){
			foreach ($like as $key => $value) {
				if($value != ''){
					$newkey = $key;
					if(is_int($key)){
						$newkey = $value;
					}
					
					if(trim($this->input->get($value,TRUE)) != ''){
						$likeArr[$newkey] =trim($this->input->get($value,TRUE));
					}
				}
			}
		}

		$data['where'] = $whereArr;
		$data['like'] = $likeArr;
		return $data;
	}

	public function canAccess($role,$allowedMethods,$url=""){
		$method = $this->router->fetch_method();

		if($this->role == $role){
			if(!in_array($method, $allowedMethods)){
				redirect($url);
	        	die();
			}
		}
	}
}