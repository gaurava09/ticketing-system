<?php

class Sparepart_model extends CI_Model {

    private $table = "sparepart";

    public function count($whereArr='',$likeArr ='') {
        if($whereArr){
            $this->db->where($whereArr);
        }
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
       return $this->db->count_all_results($this->table .' as s');
    }

     public function get_spareparts_list($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
    {   
        $result = array();

        // $this->db->select('s.*,e.name as equipment_name,e.model as equipment_model,');
        $this->db->select($column);
        $this->db->from($this->table .' s');
        $this->db->join('equipment e', 'e.id = s.equipment_id', 'left');

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

    public function get_spareparts($whereArr = '',$column="*", $where_in_column='',$where_in='',$likeArr ='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($where_in){
            $this->db->where_in($where_in_column,$where_in);
        }

        if($whereArr){
            $this->db->where($whereArr);
        }
       
       if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }

        // $this->db->order_by("status", "desc");
        $this->db->order_by("id", "desc");
        

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_sparepart($where)
    {   
        $this->db->select('*');
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

    public function get_sparepart_details($where)
    {   
        $this->db->select('s.*,e.name as equipment_name,e.model as equipment_model,');
        $this->db->from($this->table .' s');
        $this->db->join('equipment e', 'e.id = s.equipment_id', 'left');

        $this->db->where($where);
        
        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }


    public function add_sparepart($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_sparepart($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>