<?php

class Company_model extends CI_Model {

    private $table = "company";

    public function count($whereArr='',$likeArr ='',$wherein ='') {
        if($whereArr){
            $this->db->where($whereArr);
        }
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        if($wherein){
            foreach ($wherein as $key => $value) {
                $this->db->where_in($key,$value);
            }
        }
        
       return $this->db->count_all_results($this->table);
    }

    public function get_all_company($whereArr = '',$column="*",$startLimit ='', $endLimit ='', $likeArr ='',$wherein ='')
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

        if($wherein){
            foreach ($wherein as $key => $value) {
                $this->db->where_in($key,$value);
            }
        }

        // $this->db->order_by("status", "desc");
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

    public function get_company($where,$column='*')
    {   
        $this->db->select($column);
        $this->db->from($this->table);

        $this->db->where($where);
        
        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }

    public function add_company($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_company($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>