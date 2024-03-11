<?php

class Country_model extends CI_Model {
    private $table = "z_countries";

    public function get_country($where)
    {
        $this->db->select('*');
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


    public function get_all_country($where=array(),$cols="*")
    {   
        $result = array();

        $this->db->select($cols);
        $this->db->from($this->table);
        if($where){
            $this->db->where($where);
        }
        $this->db->order_by("name", "asc");

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_phonecodes()
    {   
        $result = array();

        $this->db->select('name,phonecode');
        $this->db->from($this->table);
        $this->db->where('status',1);

        $this->db->order_by("phonecode", "asc");
        $this->db->group_by("phonecode");

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }
}
?>