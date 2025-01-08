<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function check_login($username, $password)
    {

        $this->db->where('username', $username);
        $query = $this->db->get('users'); 

        if ($query->num_rows() == 1) {

            $admin = $query->row_array();

            if (password_verify($password, $admin['password'])) {
                return $admin;
            }
        }
        return false;
    }

    
}
