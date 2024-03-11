<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    protected $CI;

    public function __construct() {
        parent::__construct();
            // reference to the CodeIgniter super object
        $this->CI =& get_instance();
    }

    public function access_code_unique($access_code, $table_name) {
        $this->CI->form_validation->set_message('access_code_unique', $this->CI->lang->line('access_code_invalid'));

        $where = array (
            'access_code' => $access_code
        );

        $query = $this->CI->db->limit(1)->get_where($table_name, $where);
        return $query->num_rows() === 0;
    }

    public function exists($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 1)
            : FALSE;
    }

    public function is_unique_except($str, $field)
    {   
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field,$ignoreId);

        $where = array('id !=' => $ignoreId, $field => $str);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, $where)->num_rows() === 0)
            : FALSE;
    }

    public function alpha_dash_spaces($str)
    {
        return (bool) preg_match('/^[A-Z0-9 _-]+$/i', $str);
    }

    public function max($str, $val)
    {
        if ( ! is_numeric($val))
        {
            return FALSE;
        }
        return ($val >= mb_strlen($str));
    }

    public function min($str, $val)
    {
        if ( ! is_numeric($val))
        {
            return FALSE;
        }
        return ($val <= mb_strlen($str));
    }


}