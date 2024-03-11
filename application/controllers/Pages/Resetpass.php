<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resetpass extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_admin(1,$this->CUST_LOGIN);

		$this->load->model('Pages_model');
		$this->upload_path = 'documents/images/';

		$this->page_type = 'resetpass';
	}


	public function edit() {
		$page = $this->Pages_model->get_page(['page_type' => $this->page_type]);

		$data = [];
		$data['template'] 	= 'pages/resetpass_edit';
		$data['flag'] 		= "1";
		$data['title'] 		= "Reset password";
		$data['data'] 		= $page;
		$this->load->view('default', $data);
	}	

	public function update(){
		$file_array = [];
		//start validation
		$this->form_validation->set_rules('title', 'Title', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $current_file = '';
        $page = $this->Pages_model->get_page(['page_type' => $this->page_type]);
        if(!$page){
        	sendResponse(0, 'Reset password page not found');
        }

        $current_image_count =0;
        $image_arr = json_decode($page['image'],true);
        if(is_array($image_arr)){
        	$current_image_count = count($image_arr);
        }else{
        	$image_arr = [];
        }
	    

        //validate files
	    if(isset($_FILES['banner'])){
	    	$this->load->library('upload');

	    	$errors = '';
			$allowed_image_extension = array("png","jpg","jpeg");
		    $acceptable = array('image/jpeg','image/jpg','image/png');

	    	$files = $_FILES['banner'];
	    	$count = count($files['name']);

	    	$upload_count = 0;

	    	foreach ($files['name'] as $key => $value) {
	    		
	    		$file_name =  $files['name'][$key]['image'];
	    		$tmp_name =  $files['tmp_name'][$key]['image'];
	    		$file_size =  $files['size'][$key]['image'];

	    		$file_extension =  strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			    if (!file_exists($tmp_name)) { //skip iteration if file is empty
			    	continue;
			    } 

			    // Validate file input to check if is with valid extension
			    if (! in_array($file_extension, $allowed_image_extension)) {
			        $errors .= "<div class='error'>Image ".($key+1)." - Upload valid image. Only PNG and JPEG are allowed.</div>";
			    }
			    else if (! in_array(mime_content_type($tmp_name), $acceptable)) {
			        $errors .= "<div class='error'>Image ".($key+1)." - Upload valid image. Only PNG and JPEG are allowed</div>";
			    }
			        // Validate image file size
			    else if (($file_size > 1000000)) {
			        $errors .= "<div class='error'>Image ".($key+1)." -Image size exceeds 1MB</div>";
			    }
			    $upload_count++;
	    	}

	    	if($errors){
		    	sendResponse(0, $errors);
		    }

		    if( ($upload_count + $current_image_count) > 10 ){
		    	sendResponse(0, 'You can not upload more than 10 images');
		    }

		    $time = time();
	    	foreach ($files['name'] as $key => $value) {

	    		$_FILES['file']['name']= $files['name'][$key]['image'];
		        $_FILES['file']['type']= $files['type'][$key]['image'];
		        $_FILES['file']['tmp_name']= $files['tmp_name'][$key]['image'];
		        $_FILES['file']['error']= $files['error'][$key]['image'];
		        $_FILES['file']['size']= $files['size'][$key]['image'];

	    		if (!file_exists($files['tmp_name'][$key]['image'])) { //skip iteration if file is empty
			    	continue;
			    } 

	    		$config = array();
		    	$config['upload_path'] = $this->upload_path;
		        $config['allowed_types'] = 'jpg|jpeg|png';
		        $config['max_size'] = (1*1024); //1MB
		        $config['remove_spaces'] = TRUE;
	       		// $config['encrypt_name'] = TRUE;

		        $file_name = $time.'-'.$key.'-'.mt_rand(10000, 99999);
				$config['file_name'] = $file_name;

		        $this->upload->initialize($config);

		        $fnm = $files['name'][$key]['image'];

		        if (!$this->upload->do_upload('file')) {
		            $error = $this->upload->display_errors();
		            sendResponse(0, $error." - ".$fnm);
		        }else{
		        	$filedata = $this->upload->data();
		        	// dd($this->upload->data());
		        	$order = 0;
		        	if(isset($_POST['banner'][$key]['order'])){
		        		$order = (int)$_POST['banner'][$key]['order'];
		        	}
		        	$file_array[] = array(
		        		'file' => $filedata['file_name'],
		        		'path' => $this->upload_path.$filedata['file_name'],
		        		'order' => $order
		        	);
		        }//end if file upload
	    	}
	    } //if banners array sent in upload

	    // d($image_arr); echo 'end image arr <br>';
	    // d($_POST); echo 'end post arr <br>';
	    // d($_FILES);  echo 'end _FILES arr <br>';

	    $old_sent_img = [];
	    //get old data of images which are sent
	    if(isset($_POST['banner'] )){
	    	
	    	foreach ($_POST['banner'] as $key => $value) {
		    	if(isset($value['old']) && $value['old'] != ''){
		    		$old_sent_img[] = $value['old'];
		    		foreach ($image_arr as $k => $v) {
		    			if($value['old'] == $v['file']){
		    				$file_array[] = array(
		    									'file' => $v['file'],
		    									'path' => $v['path'],
		    									'order' => $value['order'],
		    									);
		    				break;
		    			}
		    		}
		    		
		    	}
		    }
	    }
	    
	    //remove images which are not sent
	    $removed_images = array_diff( array_column($image_arr, 'file'),$old_sent_img);
	    foreach ($removed_images as $key => $value) {
	    	if($value){
	        	$upload_path = './' . $this->upload_path.'/'.$value;
	        	if(file_exists($upload_path) ){
	        		unlink($upload_path);
	        	}				    
	        }
	    }
        //end validation

	    if($file_array){
	        usort($file_array, function($a, $b) {
			    return $a['order'] <=> $b['order'];
			});
	    }
	    // dd($file_array);

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('title',TRUE));
        $data['image'] 		= json_encode($file_array);
		$data['updated_by'] = $this->userid;

		$where = ['page_type' => $this->page_type];
		$insert = $this->Pages_model->update_page($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Page updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to update page');
		}
	}//end store dept


}

?>