<?php

class Jobs_model extends CI_Model {

    private $table = "email_jobs";  

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


     public function get_jobs($where=array(),$cols="*")
    {   
        $result = array();

        $this->db->select($cols);
        $this->db->from($this->table);
        if($where){
            $this->db->where($where);
        }
        $this->db->order_by("id", "asc");

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_job($where,$limit='')
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);

        if($limit){
            $this->db->limit($limit);
        }
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
        }else{
            $row = "";
        }
        return $row;
    }  
        

    public function add_job($data)
    {    
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function update_job($where,$data)
    {   
        if($where){
            $this->db->update($this->table, $data, $where );
            return $this->db;
        }
        return false;
    }
 
    public function delete_job($where)
    {       
        $this->db->delete($this->table, $where );
        return $this->db;
    }

}
?>