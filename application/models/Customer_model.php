<?php

class Customer_model extends CI_Model {

    private $table = "customers";

    public function count($whereArr='',$likeArr ='') {
        if($whereArr){
            $this->db->where($whereArr);
        }
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                if($key == 'first_name'){
                    $this->db->like($key,$value);
                }
                else if($key == 'last_name'){
                    $this->db->or_like($key,$value);
                }
                else{
                    $this->db->like($key,$value);
                }
            }
        }
       return $this->db->count_all_results($this->table);
    }

    public function get_customers($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                if($key == 'first_name'){
                    $this->db->like($key,$value);
                }
                else if($key == 'last_name'){
                    $this->db->or_like($key,$value);
                }
                else{
                    $this->db->like($key,$value);
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


    public function get_customer($where,$col="*")
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

     public function get_customer_details($where)
    {   
        $this->db->select('c.*,co.name as company_name');
        $this->db->from($this->table .' c');
        $this->db->join('company co', 'co.id = c.company_id', 'left');

        $this->db->where($where);
        
        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }
  
    public function add_customer($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function update_customer($where,$data)
    {   
        if($where){
            $this->db->update($this->table, $data, $where );
            return $this->db;
        }
        return false;
    }
 
    public function delete_customer($userId)
    {       
        $this->db->delete($this->table, array('id' => $userId) );
        return $this->db;
    }
}
?>