<?php

class Files_model extends CI_Model {

        private $table = "files";

        public function get_file($where,$col="*")
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

    public function get_files($whereArr='',$column="*")
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        }

        $this->db->order_by("id", "asc");

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function update_file_table($id,$data)
    {       
        $this->db->update($this->table, $data, array('id' => $id) );
        return $this->db;
    }

    public function add_file($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }


}
?>