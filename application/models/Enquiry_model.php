<?php

class Enquiry_model extends CI_Model {

    private $table = "enquiry";  
    private $history = "enquiry_history";  

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


    public function get_enquiries($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
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

    public function get_enquiries_by_join($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' e');
        $this->db->join('customers c', 'c.id = e.customer_id', 'left');
        $this->db->join('company cm', 'cm.id = c.company_id', 'left');

        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        
        $this->db->order_by("e.id", "desc");
        
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

    public function get_enquiry($where,$col="*")
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
        
    public function update_enquiry($id,$data)
    {       
        $this->db->update($this->table, $data, array('id' => $id) );
        return $this->db;
    }

    public function add_enquiry($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }


    public function monthWiseData($whereArr='') {
        $result = array();

        $this->db->select("COUNT(id) as count,DATE_FORMAT(created_at, '%b') as month");
        $this->db->from($this->table);
        $this->db->where('YEAR(created_at) = YEAR(CURDATE())');
        if($whereArr){

        }
        $this->db->group_by('YEAR(created_at)'); 
        $this->db->group_by('MONTH(created_at)'); 

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }
    

    //////////////////////////// enquiry history ////////////////////////

    public function add_enquiry_history($data)
    {      
        $this->db->insert($this->history, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function get_enquiry_history($where,$column="")
    {

        if(empty($column)){
            $column = 'h.*,u.first_name,u.last_name';
        }
        $this->db->select($column);
        $this->db->from($this->history .' h');
        $this->db->join('users u', 'u.id = h.user_id', 'left');

        $this->db->where($where);

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
        }else{
            $row = "";
        }
        return $row;
    } 

    public function get_enquiry_handled_by($where,$column="")
        {

            if(empty($column)){
                $column = 'h.*,u.first_name,u.last_name';
            }
            $this->db->select($column);
            $this->db->from($this->history .' h');
            $this->db->join('users u', 'u.id = h.user_id', 'left');

            $this->db->where($where);
            $this->db->limit(1);
            $this->db->order_by('id','asc');

            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $row = $query->row_array();
            }else{
                $row = "";
            }
            return $row;
        }

}
?>