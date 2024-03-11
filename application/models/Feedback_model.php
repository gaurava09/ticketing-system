<?php

class Feedback_model extends CI_Model {

    private $table = "customer_feedback";  

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


    public function get_feedbacks($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
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

    public function get_feedbacks_by_join($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='',$join='left')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table .' f');
        $this->db->join('complaint c', 'c.id = f.complaint_id', $join);

        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        
        $this->db->order_by("f.id", "desc");
        
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

    public function get_feedback($where,$col="*")
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
        
    public function update_feedback($id,$data)
    {       
        $this->db->update($this->table, $data, array('id' => $id) );
        return $this->db;
    }

    public function add_feedback($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

     
/*  public function delete_feedback($id)
    {       
        $this->db->delete($this->table, array('id' => $id) );
        return $this->db;
    }
    */

}
?>