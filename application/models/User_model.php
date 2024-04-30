<?php

class User_model extends CI_Model {

        private $table = "users";

        public function count($whereArr='',$likeArr ='',$wherein ='') {
            if($whereArr){
                $this->db->where($whereArr);
            }
            if($likeArr){
                foreach ($likeArr as $key => $value) {
                    if($key == 'first_name'){
                        $this->db->like($key,$value);
                    }
                    else if($key == 'last_name'){
                        $this->db->or_like($key,$value);
                    }
                    else{
                        $this->db->like($key,$value);
                    }
                }
            }
            if($wherein){
                foreach ($wherein as $key => $value) {
                    $this->db->where_in($key,$value);
                }
            }
            
           return $this->db->count_all_results($this->table);
        }

        public function get_user($where,$column="*")
        {
            $this->db->select($column);
            $this->db->from($this->table);
            $this->db->where($where);

            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $row = $query->row_array();
            }else{
                $row = "";
            }
            return $row;
        }  

        public function get_users($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='',$wherein ='',$roles=[],$employees=[])
        {   
            $result = array();

            $this->db->select($column);
            $this->db->from($this->table);
            
            if($whereArr){
                $this->db->where($whereArr);
            }

            if ($roles) {
                $this->db->where_in('role', $roles);
            }

            if ($employees) {
                $this->db->where_in('id', $employees);
            }

            if($likeArr){
                foreach ($likeArr as $key => $value) {
                    if($key == 'first_name'){
                        $this->db->like($key,$value);
                    }
                    else if($key == 'last_name'){
                        $this->db->or_like($key,$value);
                    }
                    else{
                        $this->db->like($key,$value);
                    }
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

        public function get_emp_details($where)
        {   
            $this->db->select('u.*,desg.name as designation_name,dept.name as department_name,dept.top_dept');
            $this->db->from($this->table .' u');
            $this->db->join('department dept', 'dept.id = u.department_id', 'left');
            $this->db->join('designation desg', 'desg.id = u.designation_id', 'left');

            $this->db->where('u.role','employee');
            $this->db->where($where);
            
            $query = $this->db->get();

            $result = array();
            if ( $query->num_rows() > 0 )
            {
                $result = $query->row_array();
            }
            return $result;
        }

        public function update_user($where,$data)
        {       
            $this->db->update($this->table, $data, $where );
            return $this->db;
        }
    
        public function add_user($data)
        {      
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }

     
        public function delete_user($userId)
        {       
            $this->db->delete($this->table, array('id' => $userId) );
            return $this->db;
        }
        
       

}
?>