<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MapModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function get_single() {
        return $this->db->get('homepage_map')->row(); 
    }

    public function update($data)
    {
        return $this->db->update('homepage_map', $data); 
    }

   

}
