<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);
		$this->canAccess('employee',['index'],'faq');
		$this->canAccess('customer',['index'],'faq');

		$this->load->model('Pages_model');
		$this->upload_path = 'documents/images/';
	}

	public function index() {
		$page = $this->Pages_model->get_page(['page_type' => 'faq_page']);
		$faqs = $this->Pages_model->get_pages(['page_type' => 'faq', 'status' => 1]);

		$faq_tree = [];
		foreach ($faqs as $key => $value) {
			if($value['top_cat'] == 1){
				$newkey = 'faq_'.$value['id'];
				$faq_tree[$newkey] = $value;
				$faq_tree[$newkey]['subcat'] = [];
			}
		}

		foreach ($faqs as $key => $value) {
			if($value['top_cat'] == 0){
				$parentkey = 'faq_'.$value['parent'];

				if(isset($faq_tree[$parentkey])){
					$value['content'] = json_decode($value['content'],true);
					$faq_tree[$parentkey]['subcat'][] = $value;
				}
			}
		}

		// d($page);
		// dd($faq_tree);

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_view';
		$data['title'] 		= "FAQ";
		$data['data'] 		= $page;
		$data['faqs'] 		= $faq_tree;
		$this->load->view('default', $data);
	}

	public function edit() {
		$page = $this->Pages_model->get_page(['page_type' => 'faq_page']);

		$data = [];
		$data['template'] 	= 'pages/faq/faq_edit';
		$data['flag'] 		= "1";
		$data['title'] 		= "FAQ";
		$data['data'] 		= $page;
		$this->load->view('default', $data);
	}	

	public function update(){
		$page_type = 'faq_page';
		//start validation
		$this->form_validation->set_rules('title', 'Title', 'required');
		// $this->form_validation->set_rules('content', 'Content', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //Start File upload  x file validation
        $current_file = '';
        $page = $this->Pages_model->get_page(['page_type' => $page_type]);
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
			$file_name  =  $current_file;
		}
        //end validation

        //Store
        $data = [];
        $data['title'] 		= $this->input->post('title',TRUE);
		$data['content'] 	= $this->input->post('content',TRUE);
		$data['page_type'] 	= $page_type;
		$data['image'] 		= $file_name;
		$data['status'] 	= 1;
		
		//if about page exist
		if($page){ //update page
			$data['updated_by'] = $this->userid;
			$where = ['page_type' => $page_type];

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

	public function category() {
		$faq = $this->Pages_model->get_pages(['page_type' => 'faq', 'top_cat' => 1]);

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_cat_list';
		$data['title'] 		= "FAQ Category List";
		$data['faq'] 		= $faq;
		$this->load->view('default', $data);
	}


	public function categoryCreate() {
		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_cat_add';
		$data['title'] 		= "FAQ Category Create";
		$this->load->view('default', $data);
	}

	public function categoryStore(){

		$this->form_validation->set_rules('name', 'Name', 'required');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('name',TRUE));
		$data['top_cat'] 	= 1;
		$data['page_type'] 	= 'faq';
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Pages_model->add_page($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Category created successfully' ));
			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create category');
		}
	}//end store 


	public function categoryEdit($catId) {
		$faq = $this->Pages_model->get_page(['id' => $catId]);
		if(!$faq){
			$this->sendFlashMsg(0,'Category data not found', 'faq');
		}
		
		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_cat_edit';
		$data['title'] 		= "FAQ Category Edit";
		$data['faq'] 		= $faq;
		$this->load->view('default', $data);
	}	

	public function categoryUpdate($catId){
		$_POST['catId'] = $catId;

		$this->form_validation->set_rules('catId', 'Category id', 'required|exists[pages.id]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }
        //end validation

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('name',TRUE));
		$data['status'] 	= trim($this->input->post('status',TRUE));
		$data['updated_by'] = $this->userid;

		$where = ['id' => $catId];
		$insert = $this->Pages_model->update_page($where,$data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Category updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to update category');
		}
	}//end store dept


	//Start subcategory
	public function subCategory() {
		$subcategory = $this->Pages_model->get_pages(['page_type' => 'faq', 'top_cat' => 0]);

		$category = $this->Pages_model->get_pages(['page_type' => 'faq', 'top_cat' => 1],'id,title');
		$temp  = [];
		foreach ($category as $key => $value) {
			$temp[$value['id']] = $value['title'];
		}

		// dd($subcategory);
		$data = [];
		$data['flag'] 			= "1";
		$data['template'] 		= 'pages/faq/faq_subcat_list';
		$data['title'] 			= "FAQ Sub-Category List";
		$data['subcategory'] 	= $subcategory;
		$data['category'] 		= $temp;
		$this->load->view('default', $data);
	}


	public function subCategoryCreate() {
		$category = $this->Pages_model->get_pages(['page_type' => 'faq', 'top_cat' => 1, 'status' => 1],'id,title');

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_subcat_add';
		$data['title'] 		= "FAQ Sub-Category Create";
		$data['category'] 		= $category;
		$this->load->view('default', $data);
	}

	public function subCategoryStore(){
		
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required|exists[pages.id]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        $faq_list = [];
        $faqs = $this->input->post('faqs',TRUE);

        if($faqs){
        	foreach ($faqs as $key => $value) {
        		$title = trim($value['title']);
        		$desc = trim($value['desc']);
        		$order = trim($value['order']);
        		
        		$value['order'] = (int)$order;

        		if($title != '' && $desc != ''){
        			$faq_list[] = $value;
        		}        		
        	}
        }else{
        	sendResponse(0, 'FAQs are required.');
        }

        if(empty($faq_list)){
        	sendResponse(0, 'FAQs are required');
        }
        //end validation

        //sorting by order 
        usort($faq_list, function($a, $b) {
		    return $a['order'] <=> $b['order'];
		});

        // d($faq_list); // dd($faqs);

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('name',TRUE));
		$data['top_cat'] 	= 0;
		$data['parent'] 	= trim($this->input->post('category',TRUE));
		$data['page_type'] 	= 'faq';
		$data['content'] 	= json_encode($faq_list);
		$data['status'] 	= 1;
		$data['created_by'] = $this->userid;
		$data['created_at'] = getDt();

		$insert = $this->Pages_model->add_page($data);
		if($insert){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Sub Category created successfully' ));
			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to create sub category');
		}
	}//end store 


	public function subCategoryEdit($catId) {
		$faq = $this->Pages_model->get_page(['id' => $catId]);
		if(!$faq){
			$this->sendFlashMsg(0,'Category data not found', 'faq');
		}
		
		$category = $this->Pages_model->get_pages(['page_type' => 'faq', 'top_cat' => 1, 'status' => 1],'id,title');

		$data = [];
		$data['flag'] 		= "1";
		$data['template'] 	= 'pages/faq/faq_subcat_edit';
		$data['title'] 		= "FAQ Sub Category Edit";
		$data['faq'] 		= $faq;
		$data['category'] 	= $category;
		$this->load->view('default', $data);
	}	

	public function subCategoryUpdate($subcatId){
		
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

		if ($this->form_validation->run() == FALSE)
        {	
        	sendResponse(0, validation_errors());
        }

        //validate parent cat
        $parent_cat_id = trim($this->input->post('category',TRUE));
        $parent_category = $this->Pages_model->get_page(['id' => $parent_cat_id, 'page_type' => 'faq', 'top_cat' => 1],'id,title');

        if(!$parent_category){
        	sendResponse(0, 'Invalid parent category');
        }

        //validate sub cat which is updating
        $sub_category = $this->Pages_model->get_page(['id' => $subcatId, 'page_type' => 'faq', 'top_cat' => 0],'id,title');
        if(!$sub_category){
        	sendResponse(0, 'Invalid Sub category id');
        }


        $faq_list = [];
        $faqs = $this->input->post('faqs',TRUE);

        if($faqs){
        	foreach ($faqs as $key => $value) {
        		$title = trim($value['title']);
        		$desc = trim($value['desc']);
        		$order = trim($value['order']);
        		
        		$value['order'] = (int)$order;

        		if($title != '' && $desc != ''){
        			$faq_list[] = $value;
        		}        		
        	}
        }else{
        	sendResponse(0, 'FAQs are required.');
        }

        if(empty($faq_list)){
        	sendResponse(0, 'FAQs are required');
        }
        //end validation

        //sorting by order 
        usort($faq_list, function($a, $b) {
		    return $a['order'] <=> $b['order'];
		});

        //Store
        $data = [];
        $data['title'] 		= trim($this->input->post('name',TRUE));
        $data['parent'] 	= $parent_cat_id;
        $data['content'] 	= json_encode($faq_list);
		$data['status'] 	= trim($this->input->post('status',TRUE));
		$data['updated_by'] = $this->userid;

		$where = ['id' => $subcatId];
		$update = $this->Pages_model->update_page($where,$data);
		if($update){
			$this->session->set_flashdata('message', array('status' => 1, 'message' => 'Sub Category updated successfully' ));

			sendResponse(1,'Success');
		}else{
			sendResponse(0,' Failed to update sub category');
		}
	}//end store dept


}

?>