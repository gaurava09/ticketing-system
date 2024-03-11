<?php

class Department_model extends CI_Model {

    private $table = "department";

    public function get_departments($whereArr = '',$column="*",$data=[])
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        }

        // $this->db->order_by("status", "desc");

        if(isset($data['order']) && !empty($data['order'])){
            foreach ($data['order'] as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }else{
            $this->db->order_by("id", "desc");
        }
        

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_department($where)
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

    public function add_department($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_department($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>