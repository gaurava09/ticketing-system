<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);

		$this->canAccess('employee',['newsList','view'],'complaint');
		$this->canAccess('customer',['index','newsList','view'],'complaint');


		$this->load->model('Pages_model');
		$this->upload_path = 'documents/news/';
	}

	public function index() {
		if($this->is_customer()){
			$this->customerIndex();
			return false;
		}
		$news = $this->Pages_model->get_pages(['page_type' => 'news']);

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/news/news_list';
		$data['title'] 		= "News Updates";
		$this->load->view('default', $data);
	}

	private function customerIndex(){
		$latestnews = $this->Pages_model->get_pages(['page_type' => 'news','status' => 1],'id','0','1');
		if($latestnews){	
			if( $this->session->flashdata('message') !== null){	
				$response = $this->session->flashdata('message');

				$this->session->set_flashdata('message', array('status' => $response['status'], 'message' => $response['message'] ));
			}

			$id = $latestnews[0]['id'];
			redirect('news/'.$id);
		}
	}

	public function list() {	
		$params = $this->searchParam([]);

		$draw 		= $params['draw'];
		$startrow 	= $params['startrow'];
		$rowperpage = $params['rowperpage'];
		$whereArr 	= $params['where'];
		$likeArr 	= $params['like'];

		$whereArr['page_type'] ='news';

		## Total number of records without filtering
		// $allres  = $this->Enquiry_model->count();
		// $totalRecords = $allres;

		## Total number of records with filtering
		$columns = 'id,title,image,status,created_at';
		$list  = $this->Pages_model->get_pages($whereArr, $columns, $startrow, $rowperpage , $likeArr);

		$totalRecordwithFilter = count($list);
		$totalRecords = $totalRecordwithFilter;

		$status_list = status_list();

		foreach ($list as $key => $value) {
			$list[$key]['status'] 		= $status_list[$value['status']];
			$list[$key]['created_at'] 	= custDate($value['created_at']);
		}

		$response = array(
		 	"draw" 					=> intval($draw),
		 	"totalRecords" 			=> $totalRecords,
		 	"totalRecordwithFilter" => $totalRecordwithFilter,
		 	"aaData" 				=> $list
		);

		sendResponse(1, 'success', $response);
	}

	public function newsList() {	
		$rowperpage = 3;
		$startrow = $this->input->get('row',true);

		$whereArr = array('page_type' => 'news', 'status' => 1);

		$columns = 'id,title,image';
		$list  = $this->Pages_model->get_pages($whereArr, $columns, $startrow, $rowperpage);

		foreach ($list as $key => $value) {
			$images = json_decode($value['image'],true);
			if(is_array($images) && !empty($images)){
				$list[$key]['thumb'] = site_url($images[0]['path']);
			}else{
				$list[$key]['thumb'] ='';
			}
			$list[$key]['title'] 	= truncateString($value['title'], 34);
			$list[$key]['url'] 		= site_url('news/'.$value['id']);
			unset($list[$key]['image']);
		}
		// dd($list);
		sendResponse(1, 'success', $list);
	}

	public function view($newsId) {
		$news = $this->Pages_model->get_page(['id' => $newsId,'page_type' => 'news', 'status' => 1]);
		if(!$news){
			$this->session->set_flashdata('message', array('status' => 0, 'message' => 'News data not found' ));
			// $this->sendFlashMsg(0,'News data not found','news');
		}

		//trending list
		$whereArr = ['page_type' => 'news', 'status' => 1];
		// $whereArr = ['page_type' => 'news', 'status' => 1, 'id !=' => $newsId];
		// dd(json_decode($news['image'],true));
		$totalNews  = $this->Pages_model->count($whereArr);

		$startrow= 0;
		$rowperpage = 3;

		$columns = 'id,title,image';
		$list = $this->Pages_model->get_pages($whereArr,$columns,$startrow,$rowperpage);
		// dd($list);
		foreach ($list as $key => $value) {
			$images = json_decode($value['image'],true);
			if(is_array($images) && !empty($images)){
				$list[$key]['thumb'] = $images[0]['path'];
			}else{
				$list[$key]['thumb'] ='';
			}
			$list[$key]['title'] 		= truncateString($value['title'], 34);
		}

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/news/news_view';
		$data['title'] 		= "News View";
		$data['data'] 		= $news;
		$data['list'] 		= $list;
		$data['totalNews'] 	= $totalNews;
		$this->load->view('default', $data);
	}

	public function create() {
		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/news/news_add';
		$data['title'] 		= "News Create";
		$this->load->view('default', $data);
	}

	public function store(){
	    $file_array = [];

	    //start validation
	    $this->form_validation->set_rules('title', 'Title', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //validate files
	    if(isset($_FILES['banner'])){
	    	$this->load->library('upload');

	    	$errors = '';
			$allowed_image_extension = array("png","jpg","jpeg");
		    $acceptable = array('image/jpeg','image/jpg','image/png');

	    	$files = $_FILES['banner'];
	    	$count = count($files['name']);

	    	foreach ($files['name'] as $key => $value) {
	    		
	    		$file_name =  $files['name'][$key]['image'];
	    		$tmp_name =  $files['tmp_name'][$key]['image'];
	    		$file_size =  $files['size'][$key]['image'];

	    		$file_extension =  strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

		         // Validate file input to check if is not empty
			    if (! file_exists($tmp_name)) {
			        $errors .= "<div class='error'>Image ".($key+1)." -Choose image file to upload.</div>";
			    }    // Validate file input to check if is with valid extension
			    else if (! in_array($file_extension, $allowed_image_extension)) {
			        $errors .= "<div class='error'>Image ".($key+1)." - Upload valid image. Only PNG and JPEG are allowed.</div>";
			    }
			    else if (! in_array(mime_content_type($tmp_name), $acceptable)) {
			        $errors .= "<div class='error'>Image ".($key+1)." - Upload valid image. Only PNG and JPEG are allowed</div>";
			    }
			        // Validate image file size
			    else if (($file_size > 1000000)) {
			        $errors .= "<div class='error'>Image ".($key+1)." -Image size exceeds 1MB</div>";
			    }
	    	}

	    	if($errors){
		    	sendResponse(0, $errors);
		    }

		    if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir($this->upload_path, 0777, true);		        
		    }

		    $time = time();
	    	foreach ($files['name'] as $key => $value) {
	    		$config = array();
		    	$config['upload_path'] = $this->upload_path;
		        $config['allowed_types'] = 'jpg|jpeg|png';
		        $config['max_size'] = (1*1024); //1MB
		        $config['remove_spaces'] = TRUE;
	       		// $config['encrypt_name'] = TRUE;

		        $_FILES['file']['name']= $files['name'][$key]['image'];
		        $_FILES['file']['type']= $files['type'][$key]['image'];
		        $_FILES['file']['tmp_name']= $files['tmp_name'][$key]['image'];
		        $_FILES['file']['error']= $files['error'][$key]['image'];
		        $_FILES['file']['size']= $files['size'][$key]['image'];

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
	    }
	    //end validation

		// d($file_array); d($_FILES); dd($_POST);
		if($file_array){
	        usort($file_array, function($a, $b) {
			    return $a['order'] <=> $b['order'];
			});
	    }

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('title',TRUE));
        $data['content'] 	= trim($this->input->post('content',TRUE));
        $data['image'] 		= json_encode($file_array);
		$data['page_type'] 	= 'news';
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$newsId = $this->Pages_model->add_page($data);
		if($newsId){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'News created successfully' ));

			//add notification for customers
			$this->sendMailAndNewsNotification($data,$newsId);

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create news');
		}
	}//end store 

	private function sendMailAndNewsNotification($data,$newsId){
		$this->load->model('Customer_model');
		$this->load->model('Notification_model');
		$this->load->model('Jobs_model');

		$customers = $this->Customer_model->get_customers(['status' => 1],'id,email');

		if($customers){
			$bcc_arr = [];
			foreach ($customers as $key => $value) {
				
				//notification
				$notificationData = array(
					'user_id' 			=> $value['id'],
					'user_type' 		=> 'customer',
					'title' 			=> 'News',
					'description' 		=> 'Breaking! '.$data['title'],
					'context_id' 		=> $newsId,
					'is_read' 			=> 0,
					'created_by' 		=> $this->userid,
					'created_by_type' 	=> $this->role,
					'status' 			=> 1,
					'created_at' 		=> getDt()
				);
				$this->Notification_model->add_notification($notificationData);

				
				$bcc_arr[] = $value['email'];
			}//end foreach


			//mail queue
			$chunks = ceil(count($customers)/ 3); //no of bcc = 3 per job

			$result = array_chunk($bcc_arr, $chunks);

			if($result){
				foreach ($result as $key => $value) {
					$message = "What's trending in around us? <br><br>

							".$data['title']."<br><br>

							The latest news of Tsubaki optimization, conversation and trends served fresh right to your inbox!<br><br>

							Best,<br>
							Team Tsubaki.";

					$job_data = array(
						'type' => "news",
						'subject' => "What's New in the world of Tsubaki!",
						'message' => $message,
						'to_email' => 'nilesh@agency09.in',
						'bcc_email' => json_encode($value),
						'created_at' => getDt()
					);

					$this->Jobs_model->add_job($job_data);
				}
			}//end if result
					
				
		}//end if customers
	}//end function


	public function edit($newsId) {
		$news = $this->Pages_model->get_page(['id' => $newsId]);
		if(!$news){
			$this->sendFlashMsg(0,'News data not found', 'news');
		}

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/news/news_edit';
		$data['title'] 		= "News Edit";
		$data['news'] 		= $news;
		$this->load->view('default', $data);
	}	

	public function update($newsId){
		$file_array = [];
		//start validation
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $current_file = '';
        $news = $this->Pages_model->get_page(['id' => $newsId, 'page_type' => 'news']);
        if(!$news){
        	sendResponse(0, 'News not found');
        }

        $current_image_count =0;
        $image_arr = json_decode($news['image'],true);
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

	    		$file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

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

		    if( ($upload_count + $current_image_count) > 4 ){
		    	sendResponse(0, 'You can not upload more than 4 images');
		    }

		    if (!is_dir( $this->upload_path ))
		    {	
		    	mkdir($this->upload_path, 0777, true);		        
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
        $data['content'] 	= trim($this->input->post('content',TRUE));
        $data['image'] 		= json_encode($file_array);
		$data['status'] 	= trim($this->input->post('status',TRUE));
		$data['updated_by'] = $this->userid;

		$where = ['id' => $newsId];
		$insert = $this->Pages_model->update_page($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'News updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to update news');
		}
	}//end store dept


}

?>