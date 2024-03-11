<?php

class ComplaintHistory_model extends CI_Model {

    private $table = "complaint_history";  

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

     public function get_all_complaint_history($whereArr = '',$column="*", $data='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 

        if(isset($data['order_by'])){
            foreach ($data['order_by'] as $key => $value) {
                 $this->db->order_by($key, $value);
            }
        }else{
            $this->db->order_by("id", "asc");
        }

        if(isset($data['limit'])){
             $this->db->limit( $data['limit']);
        }
        
        
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_complaint_history($where,$col="*",$data='')
        {   
            $result = array();

            $this->db->select($col);
            $this->db->from($this->table);
            $this->db->where($where );

            if(isset($data['raw_where'])){
                 $this->db->where( $data['raw_where']);
            }
            
            if(isset($data['order_by'])){
                foreach ($data['order_by'] as $key => $value) {
                     $this->db->order_by($key, $value);
                }
            }

            if(isset($data['limit'])){
                 $this->db->limit( $data['limit']);
            }

            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $result = $query->row_array();
            }

            return $result;
        }  
        
    public function update_complaint_history($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }

    public function add_complaint_history($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }



}
?>