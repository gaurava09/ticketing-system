<?php

class Equipment_model extends CI_Model {

    private $table = "equipment";

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

    public function get_equipments($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
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

    public function get_equipment($where,$column='*')
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

    public function get_equipemnt_details_by_project($where,$column='e.*')
    {   
        // $this->db->select('p.*,e.name as equipment_name,e.model as equipment_model,');
        $this->db->select($column);
        $this->db->from($this->table .' e');
        $this->db->join('project p', 'e.id = p.equipment_id', 'left');

        $this->db->where($where);
        
        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }

    public function add_equipment($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_equipment($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>