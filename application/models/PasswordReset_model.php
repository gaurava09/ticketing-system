<?php

class PasswordReset_model extends CI_Model {

        private $table = "password_resets";

        public function get_data($where)
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


        public function add_data($data)
        {      
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }

        public function update_data($where,$data)
        {       
            $this->db->update($this->table, $data, $where );
            return $this->db;
        }


        public function delete_data($where)
        {       
            $this->db->delete($this->table, $where );
            return $this->db;
        }

}
?>