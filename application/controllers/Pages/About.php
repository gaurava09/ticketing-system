<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);

		$this->canAccess('employee',['index'],'about');
		$this->canAccess('customer',['index'],'about');

		$this->load->model('Pages_model');

		$this->upload_path = 'documents/images/';
	}

	public function index() {
		$page = $this->Pages_model->get_page(['page_type' => 'about']);

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/about/about_view';
		$data['title'] 		= "About Us";
		$data['data'] 		= $page;
		$this->load->view('default', $data);
	}


	public function edit() {
		$page = $this->Pages_model->get_page(['page_type' => 'about']);

		$data = [];
		$data['template'] 	= 'pages/about/about_edit';
		$data['flag'] 		= "1";
		$data['title'] 		= "About";
		$data['data'] 		= $page;
		$this->load->view('default', $data);
	}	

	public function update(){

		//start validation
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //Start File upload  x file validation
        $current_file = '';
        $page = $this->Pages_model->get_page(['page_type' => 'about']);
        if($page){
        	$current_file  =  $page['image'];
        }
        

		if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ''){
			$config['upload_path'] = $this->upload_path;
	        $config['allowed_types'] = 'jpg|jpeg|png';
	        $config['max_size'] = (1*1024); //1MB
	        $config['remove_spaces'] = TRUE;

       		$file_name = time().'-'.mt_rand(10000, 99999);
			$config['file_name'] = $file_name;

	        $this->load->library('upload', $config);

	        if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir( $this->upload_path , 0777, true);		        
		    }

        	if (!$this->upload->do_upload('file')) {
	            $error = $this->upload->display_errors();
	            sendResponse(0, $error);
	        } 

	        $image_data = $this->upload->data();

	        $file_name  =  $this->upload_path.$image_data['file_name'];

	        if($current_file){
	        	$upload_path = './' . $current_file;
	        	if(file_exists($upload_path) ){
	        		unlink($upload_path);
	        	}				    
	        }

		} // end if file upload
		else if($this->input->post('prev_file') == "" ){
			$file_name  =  '';

        	if($current_file){
        		$upload_path = './' . $current_file;
	        	if(file_exists($upload_path)){
	        		unlink($upload_path);
	        	}
        	}
		}else{
			$file_name  =   $current_file;
		}
        //end validation

        //Store
        $data = [];
        $data['title'] 		= $this->input->post('title',TRUE);
		$data['content'] 	= $this->input->post('content',TRUE);
		// $data['content'] 	= $this->security->xss_clean($this->input->post('content',TRUE));
		$data['page_type'] 	= 'about';
		$data['image'] 		= $file_name;
		$data['status'] 	= 1;
		

		//if about page exist
		if($page){ //update page
			$data['updated_by'] = $this->userid;
			$where = ['page_type' => 'about'];

			$this->Pages_model->update_page($where,$data);

			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Data updated successfully' ));
			sendResponse(1,'Success');

		}else{ //add page

			$data['created_by'] = $this->userid;
			$data['created_at'] = getDt();

			$insert = $this->Pages_model->add_page($data);
			if($insert){
				$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Data updated successfully' ));
				sendResponse(1,'Success');
			}else{
				sendResponse(0,' Failed to update');
			}
		}
		
	}//end function

}

?>