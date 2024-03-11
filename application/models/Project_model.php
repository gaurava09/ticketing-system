<?php

class Project_model extends CI_Model {

    private $table = "project";

    public function count($whereArr='',$likeArr ='',$wherein ='',$company_id='') {
        if($whereArr){
            $this->db->where($whereArr);
        }
        if($likeArr){
            foreach ($likeArr as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        if($wherein){
            foreach ($wherein as $key => $value) {
                $this->db->where_in($key,$value);
            }
        }

        if($company_id){

            // $this->db->where('company_id', $company_id);
            // $this->db->or_where('company_id_2',$company_id);

            $company_id = esc_sql($company_id);
            $custWhere = " (`company_id` = '{$company_id}' OR `company_id_2` = '{$company_id}') ";
            $this->db->where($custWhere);
            
            // $this->db->where('(company_id', $company_id);
            // $this->db->or_where("company_id_2 = '".$company_id."' )",NULL, FALSE);
        }
        
       return $this->db->count_all_results($this->table);
    }

    public function get_projects($whereArr = '',$column="*",$startLimit ='', $endLimit ='', $likeArr ='',$wherein ='',$company_id='')
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

        if($wherein){
            foreach ($wherein as $key => $value) {
                $this->db->where_in($key,$value);
            }
        }

        if($company_id){

            // $this->db->where('company_id', $company_id);
            // $this->db->or_where('company_id_2',$company_id);
            $company_id = esc_sql($company_id);
            $custWhere = " (`company_id` = '{$company_id}' OR `company_id_2` = '{$company_id}') ";
            $this->db->where($custWhere);

            // $this->db->where('(company_id', esc_sql($company_id));
            // $this->db->or_where('company_id_2 = "'.esc_sql($company_id).'" )',NULL, FALSE);
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

    public function get_project($where,$column='*',$company_id='')
    {   
        $this->db->select($column);
        $this->db->from($this->table);

        $this->db->where($where);
        
        if($company_id){
            $company_id = esc_sql($company_id);
            $custWhere = " (`company_id` = '{$company_id}' OR `company_id_2` = '{$company_id}') ";
            $this->db->where($custWhere);

            // $this->db->where('(company_id', $company_id);
            // $this->db->or_where("company_id_2 = '".$company_id."' )",NULL, FALSE);
        }

        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }

     public function get_project_details($where)
    {   
        $this->db->select('p.*,c.name as company,e.name as equipment_name,e.model as equipment_model,');
        $this->db->from($this->table .' p');
        $this->db->join('equipment e', 'e.id = p.equipment_id', 'left');
        $this->db->join('company c', 'c.id = p.company_id', 'left');

        $this->db->where($where);
        
        $query = $this->db->get();

        $result = array();
        if ( $query->num_rows() > 0 )
        {
            $result = $query->row_array();
        }
        return $result;
    }

    public function add_project($data)
    {       
        $this->db->insert($this->table, $data);
        return $insert_id = $this->db->insert_id();
        // return $this->db;
    }

    public function update_project($where,$data)
    {       
        $this->db->update($this->table, $data, $where );
        return $this->db;
    }


}
?>