<?php

class Notification_model extends CI_Model {

    private $table = "notifications";  

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


    public function get_notifications($whereArr = '',$column="*", $startLimit ='', $endLimit ='', $likeArr ='')
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
        
        $this->db->order_by("created_at", "desc");
        
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


    public function get_notification($where,$col="*")
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
        

    public function add_notification($data)
    {      
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function update_notification($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }

    public function delete_notification($where)
    {       
        if($where){
            $this->db->delete($this->table, $where );
            return $this->db;
        }
    }

}
?>