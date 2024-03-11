<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Chat extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);
		
		$this->load->model('User_model');
		$this->load->model('Customer_model');
		$this->load->model('Chat_model');
		$this->load->model('Complaint_model');
	}

	public function index() {	
		if($this->role =='customer'){
			$convolist = $this->getCustomerChatlist();
		}else{
			$convolist = $this->getUserChatlist();
		}

		$data = [];
		$data['flag'] 				= '1';
		$data['template'] 			= 'chat/chat_view';
		$data['title'] 				= "Chat List";
		$data['convolist'] 			= $convolist;
		$data['chatdata'] 			= [];
		$data['conversationId'] 	= '';

		$this->load->view('default', $data);
	}

	function getCustomerChatlist(){
		$convolist = [];

		$conversations = $this->Chat_model->get_conversations(['customer_id' => $this->userid]);

		if($conversations){
			foreach ($conversations as $key => $value) {
				$user = $this->User_model->get_user(['id' => $value['user_id']], 'id,concat(first_name," ",last_name) as name,status,profile_picture');

				$user['id'] 		= $value['id'];
				$user['ticket_no'] 	= $value['ticket_no'];
				$convolist[] = $user;
			}
		}

		return $convolist;
	}

	function getUserChatlist(){
		$convolist = [];

		$conversations = $this->Chat_model->get_conversations(['user_id' => $this->userid]);
		
		if($conversations){
			foreach ($conversations as $key => $value) {
				$customer = $this->Customer_model->get_customer_details(['c.id' => $value['customer_id']]);

				if($customer){
					// dd($customer);

					$convolist[] = array(
						'ticket_no'  		=> $value['ticket_no'],
						'id'  				=> $value['id'],
						'cust_id' 			=> $customer['id'],
						'name' 				=> ucfirst($customer['first_name']).' '.ucfirst($customer['last_name']),
						'profile_picture' 	=> $customer['profile_picture'],
					);
				}
				
			}
		}
		return $convolist;
	}
	public function view($conversationId) {	
		
		$convolist = [];
		$chatdata = [];

		if($this->role =='customer'){
			$whereArr = array('id' => $conversationId,'customer_id' => $this->userid);
		}else{
			$whereArr = array('id' => $conversationId,'user_id' => $this->userid);
		}
// echo $this->role;
		// die;
		$conversation = $this->Chat_model->get_conversation($whereArr);

		if(!$conversation){
			$this->sendFlashMsg(0, 'Conversation details not found','chat');
		}

		$chats = $this->Chat_model->get_messages(['conversation_id' => $conversationId]);

		$customer = $this->Customer_model->get_customer_details(['c.id' => $conversation['customer_id']]);
		$user = $this->User_model->get_user(['id' => $conversation['user_id']], 'id,concat(first_name," ",last_name) as name,status,profile_picture');

		if($this->role == 'customer'){
			$chatdata['current_user'] 	= $customer; 
			$chatdata['other_user'] 	= $user; 
			$chatdata['chats'] 			= $chats; 

			$convolist = $this->getCustomerChatlist();
		}
		else{ //admin or employee conversations with customer
			$customer['name'] = ucfirst($customer['first_name']).' '.ucfirst($customer['last_name']);
			$chatdata['current_user'] 	= $user; 
			$chatdata['other_user'] 	= $customer; 
			$chatdata['chats'] 			= $chats; 

			$convolist = $this->getUserChatlist();
		}

		$complaint = $this->Complaint_model->get_complaint(['ticket_no' => $conversation['ticket_no']],'status');

		if(!$complaint){
			$this->sendFlashMsg(0, 'Complaint details not found','chat');
		}

		$data = [];
		$data['flag'] 				= '1';
		$data['template'] 			= 'chat/chat_view';
		$data['title'] 				= "Chat List";
		$data['convolist'] 			= $convolist;
		$data['chatdata'] 			= $chatdata;
		$data['conversationId'] 	= $conversationId;
		$data['conversation'] 		= $conversation;
		$data['complaint'] 			= $complaint;

		// dd($data);
		$this->load->view('default', $data);
	}//end function
	
	public function getChat($conversationId) {	

		$chatid = trim($this->input->get('lastid',TRUE));

		// dd($chatid);
		
		$whereArr = array(
					'id >' => $chatid,
					'conversation_id' => $conversationId
				);

		if($this->role == 'customer'){
			$conversations = $this->Chat_model->get_conversations(['customer_id' => $this->userid]);
		}else{
			$conversations = $this->Chat_model->get_conversations(['user_id' => $this->userid]);
		}

		if($conversations){

			$chats = $this->Chat_model->get_messages($whereArr,FALSE,FALSE,1);

			$chatdata = [];
			foreach ($chats as $key => $value) {
				$value['date'] = custDate($value['created_at']);
				$value['time'] = custTime($value['created_at']);

				if($this->role == 'customer'){
					if($value['sender'] == 'customer'){
						$value['sender'] = 'you';
					}else{
						$value['sender'] = 'other';
					}
				}else { //employee or admin
					if($value['sender'] == 'customer'){
						$value['sender'] = 'other';
					}else{
						$value['sender'] = 'you';
					}
				}

				unset($value['conversation_id']);
				unset($value['created_at']);

				$chatdata[] = $value;
			}
			sendResponse(1,'Success',$chatdata);
			// d($chatdata);
			// dd($this->pq());
			
		}else{
			sendResponse(0,'Failed');
		}

	}

	public function store($conversationId){
		

		$message = trim($this->input->post('message',TRUE));

		if($message == ''){
			sendResponse(0,'Message can not be empty');
		}

		if($this->role =='customer'){
			$conversation = $this->Chat_model->get_conversation(['customer_id' => $this->userid, 'id' => $conversationId]);
		}else{
			$conversation = $this->Chat_model->get_conversation(['user_id' => $this->userid, 'id' => $conversationId]);
		}
		if(empty($conversation)){
			sendResponse(0,'You can not send message');
		}
        

		$complaint = $this->Complaint_model->get_complaint(['ticket_no' => $conversation['ticket_no']],'status');

		if($complaint){
			if($complaint['status'] == 4){
				sendResponse(0,'Complaint is closed. You can not reply.');
			}
		}else{
			sendResponse(0,'Complaint not found');
		}
		//End validation


        //notification data
        $this->load->model('Notification_model');

        $notiData = array(
			'title' 			=> 'Message',
			'context_id' 		=> $conversationId,
			'created_by' 		=> $this->userid,
			'created_by_type' 	=> $this->role,
			'status' 			=> 1,
			'created_at' 		=> getDt()
		);

        if($this->role =='customer'){
			$userinfo = $this->Customer_model->get_customer(['id' => $this->userid], 'first_name,last_name');

			$empInfo = $this->User_model->get_user(['id' => $conversation['user_id']],'id,role');

			if($empInfo){
				$notiData['user_type'] 	= $empInfo['role'];
			}else{
				$notiData['user_type'] 	= 'employee';
			}

			$notiData['user_id'] 	= $conversation['user_id'];


		}else{
			$userinfo = $this->User_model->get_user(['id' => $this->userid], 'first_name,last_name');
			$notiData['user_id'] 	= $conversation['customer_id'];
			$notiData['user_type'] 	= 'customer';
		}

		$notiData['description'] = 'You have received a new message from '.ucfirst($userinfo['first_name']).' '.ucfirst($userinfo['last_name']);


		$whereArr = array(
			'title' 			=> 'Message',
			'user_id' 			=> $notiData['user_id'],
			'user_type' 		=> $notiData['user_type'],
			'created_by' 		=> $this->userid,
			'created_by_type' 	=> $this->role,
			'DATE(created_at)' => date('Y-m-d'),
		);
		// d($notiData);
		// d($whereArr);
		

        //Store
        $data = [];
        $data['conversation_id'] 	= $conversationId;
        $data['message'] 			= $message;
		$data['sender'] 			= $this->role;
		$data['created_at'] 		= getDt();

		$insert = $this->Chat_model->add_message($data);
		if($insert){

			//check if notification for today exist for message
			$found = $this->Notification_model->get_notifications($whereArr);

			if($found){
				$updateData = ['is_read' =>0, 'created_at' => getDt()];
				$this->Notification_model->update_notification($whereArr,$updateData);
			}else{
				$this->Notification_model->add_notification($notiData);
			}

			sendResponse(1,'Success');
		}else{
			sendResponse(0,'Failed to send message');
		}
	}//end function

	public function export($conversationId)
    {	
		if($this->role =='customer'){
			$whereArr = array('id' => $conversationId,'customer_id' => $this->userid);
		}else{
			$whereArr = array('id' => $conversationId,'user_id' => $this->userid);
		}

		$conversation = $this->Chat_model->get_conversation($whereArr);

		if(!$conversation){
			$this->sendFlashMsg(0, 'Conversation details not found','chat');
		}

		$chats = $this->Chat_model->get_messages(['conversation_id' => $conversationId]);

		
		$user_name = '';
		$customer_name = '';

		$customer = $this->Customer_model->get_customer(['id' => $conversation['customer_id']],'first_name,last_name');
		if($customer){
			$customer_name = cap($customer['first_name']).' '.cap($customer['last_name']);
		}

		$user = $this->User_model->get_user(['id' => $conversation['user_id']], 'id,first_name,last_name');
		if($user){
			$user_name = cap($user['first_name']).' '.cap($user['last_name']);
		}

		$list = [];
		foreach ($chats as $key => $value) {
			
			if($value['sender'] == 'customer'){
				$name = $customer_name;
			}else{
				$name = $user_name;
			}

			$list[] = array(
				'name' => $name,
				'sender' => $value['sender'],
				'message' => $value['message'],
				'date' => custDate($value['created_at']),
				'time' => custTime($value['created_at'])
			);
		}
		// d($chats);dd($list);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*set column names*/
        $table_columns = array('Sr No.', 'Name','Sender Type','Message','Date', 'Time');
        $column = 1;
        foreach ($table_columns as $field) {
            $sheet->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        /*end set column names*/

        $excel_row = 2; //now from row 2

        foreach ($list as $key=>$row) {
            $sheet->setCellValueByColumnAndRow(1, $excel_row, ($key+1));
            $sheet->setCellValueByColumnAndRow(2, $excel_row, $row['name']);
            $sheet->setCellValueByColumnAndRow(3, $excel_row, $row['sender']);
            $sheet->setCellValueByColumnAndRow(4, $excel_row, $row['message']);
            $sheet->setCellValueByColumnAndRow(5, $excel_row, $row['date']);
            $sheet->setCellValueByColumnAndRow(6, $excel_row, $row['time']);
            $excel_row++;
        }
        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // header('Content-Type: application/vnd.ms-excel');

        $filename = 'chat_history_'.$conversation['ticket_no'].'.xlsx';

        header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        $writer->save('php://output');
    }//end export
}

?>