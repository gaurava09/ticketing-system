<?php

class Chat_model extends CI_Model {

    private $conversation = "chat_conversation";  
    private $message = "chat_message";  

    public function count($whereArr='',$likeArr ='') {
        if($whereArr){
            $this->db->where($whereArr);
        }
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
       return $this->db->count_all_results($this->table);
    }


    public function get_conversations($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->conversation);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        
        $this->db->order_by("id", "desc");
        
        if($startLimit != '' && $endLimit != ''){
             $this->db->limit( $endLimit , $startLimit);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }


    public function get_conversation($where,$col="*")
        {
            $this->db->select($col);
            $this->db->from($this->conversation);
            $this->db->where($where );

            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $row = $query->row_array();
            }else{
                $row = "";
            }
            return $row;
        }  
        

    public function add_conversation($data)
    {    
        $insert_query = $this->db->insert_string($this->conversation, $data);
        $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
        $this->db->query($insert_query);
        $insert_id = $this->db->insert_id();
    }
/* public function add_conversation($data)
    {      
        $this->db->insert($this->conversation, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
*/
    //Start Message

    public function add_message($data)
    {      
        $this->db->insert($this->message, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function get_messages($whereArr = '', $startLimit ='', $endLimit ='',$dateClause='')
    {   
        $result = array();

        $this->db->select('*');
        $this->db->from($this->message);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 

        if($dateClause){
            $this->db->where("created_at >= DATE_SUB(NOW(),INTERVAL 1 HOUR)", NULL, FALSE);
        } 

        // $this->db->order_by("id", "asc");
        
        if($startLimit != '' && $endLimit != ''){
             $this->db->limit( $endLimit , $startLimit);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }



    public function get_latest_messages_for_cron()
    {   
        $result = array();

        $this->db->select('m.*,cc.*,u.first_name as emp_name,u.email as emp_email, cu.first_name as cust_name,cu.email as cust_email,');
        $this->db->from($this->message. ' as m');
        $this->db->join('chat_conversation cc', 'cc.id = m.conversation_id', 'left');
        $this->db->join('users u', 'cc.user_id = u.id');
        $this->db->join('customers cu', 'cc.customer_id = cu.id');
        // $this->db->where($whereArr);
        

        $this->db->where("m.created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)", NULL, FALSE);

        $this->db->group_by("cc.ticket_no");
        $this->db->group_by("m.sender");
   
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

}
?>