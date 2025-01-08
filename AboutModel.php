<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function get_single() {
        return $this->db->get('about')->row(); 
    }

    public function update($data)
    {
        return $this->db->update('about', $data); 
    }

   

}
