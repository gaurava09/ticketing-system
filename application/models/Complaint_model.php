<?php

class Complaint_model extends CI_Model {

    private $table = "complaint";  
    private $action_table = "complaint_action";  

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

    public function count_a($whereArr = '', $likeArr = '', $whereInArr = '') {
        if ($whereArr) {
            $this->db->where($whereArr);
        }

        if ($likeArr) {
            foreach ($likeArr as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        if ($whereInArr) {
            $this->db->where_in('company_id', $whereInArr);
        }

        return $this->db->count_all_results($this->table);
    }



    public function monthWiseData($whereInArr='') {
        $result = array();

        $this->db->select("COUNT(id) as count,DATE_FORMAT(created_at, '%b') as month");
        $this->db->from($this->table);
        $this->db->where('YEAR(created_at) = YEAR(CURDATE())');
        if($whereInArr){
            $this->db->where_in('company_id', $whereInArr);
        }
        $this->db->group_by('YEAR(created_at)'); 
        $this->db->group_by("MONTH(created_at), DATE_FORMAT(created_at, '%b')"); 

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

     public function monthWiseDataEmployee($whereArr='') {
        $result = array();

        $this->db->select("COUNT(DISTINCT c.id) as count,DATE_FORMAT(c.created_at, '%b') as month");
         $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'left');

        $this->db->where('YEAR(c.created_at) = YEAR(CURDATE())');

        if($whereArr){
            $this->db->where($whereArr);
        }
        
        $this->db->group_by('YEAR(c.created_at)'); 
        $this->db->group_by('MONTH(c.created_at)'); 

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

     public function complaintAssignedToEmployee($whereArr='') {
        $result = array();

        $this->db->select("DISTINCT(c.id),DATE_FORMAT(c.created_at, '%b') as month");
         $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'left');

        $this->db->where('YEAR(c.created_at) = YEAR(CURDATE())');

        if($whereArr){
            $this->db->where($whereArr);
        }
        
        // $this->db->group_by('YEAR(c.created_at)'); 
        // $this->db->group_by('MONTH(c.created_at)'); 

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

     public function get_complaints($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='', $conditions = [])
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
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

    public function get_complaints_join_company($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
    {   
        $result = array();
        if($column){
           $this->db->select($column); 
       }else{
            $this->db->select('c.*,co.name as company');
       }
        
        $this->db->join('customers cu', 'cu.id = c.customer_id', 'left');
        $this->db->join('company co', 'co.id = cu.company_id', 'left');
        $this->db->from($this->table .' c');
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        
        $this->db->order_by("c.id", "desc");
        
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

    public function get_complaints_for_emp_new($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='',$raw_where='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'left');
        
        if($whereArr){
            if(isset($whereArr['action']) && $whereArr['action'] != '' ){
                $action = $whereArr['action'];
                unset($whereArr['action']);

                $this->db->join('complaint_action a', 'c.id = a.complaint_id', 'right');
                if($action == 'yes'){
                    $this->db->where('a.status',1);
                }else{
                    $this->db->where('a.status',0);
                }
                $this->db->where('a.emp_id',$whereArr['h.emp_id']);

                //check if status is provided or not.
                if(!isset($whereArr['c.status']) ){
                    $this->db->where(' c.status != 4 ');
                }

            }
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }

        if($raw_where){
            $this->db->where($raw_where);
        }
        /* if($remark == 'yes'){
            $this->db->where('(h.type', 'remark');
            $this->db->or_where("h.solution != '' )",NULL, FALSE);
        }*/
        // else if($remark == 'no'){
        //     $this->db->where('(h.type', 'remark');
        //     $this->db->or_where("h.solution == '' )",NULL, FALSE);
        // }
        
        $this->db->group_by('c.id'); 
        $this->db->order_by("c.id", "desc");
        $this->db->order_by("h.id", "desc");
        
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

    //need to check if it is assigned to  that eployee . so created this function
    public function get_complaints_for_emp($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='',$raw_where='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'left');
        
        if($whereArr){
            if(isset($whereArr['action']) && $whereArr['action'] != '' ){
                $action = $whereArr['action'];
                unset($whereArr['action']);

                $this->db->join('complaint_action a', 'c.complaint_id = a.complaint_id', 'right');
                if($action == 'yes'){
                    $this->db->where('a.status',1);
                }else{
                    $this->db->where('a.status',0);
                }
                    $this->db->where('a.status',0);
                
            }
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }

        if($raw_where){
            $this->db->where($raw_where);
        }
        /* if($remark == 'yes'){
            $this->db->where('(h.type', 'remark');
            $this->db->or_where("h.solution != '' )",NULL, FALSE);
        }*/
        // else if($remark == 'no'){
        //     $this->db->where('(h.type', 'remark');
        //     $this->db->or_where("h.solution == '' )",NULL, FALSE);
        // }
        
        $this->db->group_by('c.id'); 
        $this->db->order_by("c.id", "desc");
        $this->db->order_by("h.id", "desc");
        
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

    public function count_remarked_pending_ticket_count($whereArr = '',$column="*")
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' c');
        $this->db->join('complaint_action a', 'c.id = a.complaint_id', 'right');
        $this->db->where(' c.status != 4 ');

        if($whereArr){
            $this->db->where($whereArr);
        } 
        
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }


    public function count_complaint_remarked_by_emp_old($whereArr = '',$column="*",$whereRaw = '')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'left');
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        
        if($whereRaw){
            $this->db->where($whereRaw);
        } 

        // $this->db->where('(h.type', 'remark');
        // $this->db->or_where("h.solution != '' )",NULL, FALSE);

        // $this->db->where('(c.created_at >=', date("Y-m-d H:i:s"));
        // $this->db->or_where("c.created_at = '00-00-00 00:00:00')", NULL, FALSE);

        $query = $this->db->group_by('c.id');
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }


    public function count_pending_action_by_emp_old($whereArr = '',$column="*")
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' c');
        $this->db->join('complaint_history h', 'c.id = h.complaint_id', 'inner');
        
        $this->db->where(" h.emp_id = '".$this->userid."' AND h.type='assign' AND h.top_dept=0 AND h.solution = '' OR h.solution IS NULL  ");

        /* $this->db->where("
            (h.emp_id = '".$this->userid."' AND h.type='assign' AND h.top_dept=0 AND h.solution = '' OR h.solution IS NULL )
            OR 
            (NOT EXISTS (select id from complaint_history as ch where ch.complaint_id = c.id AND ch.emp_id = '".$this->userid."' AND ch.type='remark')
            )
        ");*/

        if($whereArr){
            $this->db->where($whereArr);
        } 
 

        $query = $this->db->group_by('c.id');
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_complaint($where,$col="*")
        {
            $this->db->select($col);
            $this->db->from($this->table);
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

    public function get_ticket_no($where,$col="*")
    {
        $this->db->select($col);
        $this->db->from($this->table);
        $this->db->where($where );
        $this->db->order_by("ticket_no", "desc");
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
        }else{
            $row = "";
        }
        return $row;
    }  
        
    public function update_complaint($id,$data)
    {       
        $this->db->update($this->table, $data, array('id' => $id) );
        return $this->db;
    }

    public function add_complaint($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }


    //comlaint action table
    public function get_complaint_action($where)
    {   
        $this->db->select('*');
        $this->db->from($this->action_table);
        $this->db->where($where);

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
        }else{
            $row = [];
        }
        return $row;
    }


    public function add_complaint_action($data)
    {   
        $where = [
                'complaint_id' => $data['complaint_id'],
                'emp_id' => $data['emp_id'],
             ];
        $this->db->select('*');
        $this->db->from($this->action_table);
        $this->db->where($where);
        // $this->db->where('complaint_id', $data['complaint_id'] );
        // $this->db->where('emp_id', $data['emp_id'] );

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {   
            $update = ['status' => 0];
            $this->db->update($this->action_table, $update, $where );
            return $this->db;
        }else{
            $this->db->insert($this->action_table, $data);
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
    }

    public function update_complaint_action($data,$where)
    {   
        $this->db->update($this->action_table, $data, $where );
        return $this->db;
    }
     
/*  public function delete_complaint($id)
    {       
        $this->db->delete($this->table, array('id' => $id) );
        return $this->db;
    }
    */

}
?>