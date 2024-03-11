<?php

class Designation_model extends CI_Model {

    private $table = "designation";

    public function get_designations($whereArr = '',$column="*")
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
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

    public function get_designation($where)
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

    public function add_designation($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_designation($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>