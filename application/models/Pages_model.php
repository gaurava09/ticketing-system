<?php

class Pages_model extends CI_Model {

    private $table = "pages";

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


    public function get_pages($whereArr = '',$column="*",$startLimit ='', $endLimit ='', $likeArr ='',$ignore='')
    {   
        $result = array();

        $this->db->select($column);
        $this->db->from($this->table);
        
        if($whereArr){
            $this->db->where($whereArr);
        } 
        if($ignore){
            $this->db->where_not_in('id', $ignore);
        }

        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }

        $this->db->order_by("id", "desc");
        
        if( $endLimit != ''){
             $this->db->limit( $endLimit , $startLimit);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_page($where)
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

    public function get_pages_after_id($id,$limit=5)
    {   
        $sql = 'SELECT t.* 
            FROM 
                    '.$this->table.' AS t
                JOIN
                    ( SELECT page_type
                      FROM '.$this->table.' 
                      WHERE id = '.$id.' AND page_type = "news" AND status = 1
                    ) AS o
                  ON t.page_type = o.page_type AND t.id <= '.$id.'
            ORDER BY t.id DESC 
              LIMIT '.$limit.' ';

        $query = $this->db->query($sql);

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->result_array();
        }
        return $result;
    }

    public function add_page($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_page($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>