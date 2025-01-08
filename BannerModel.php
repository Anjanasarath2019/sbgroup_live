<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BannerModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function get_single() {
        return $this->db->get('banner')->row(); 
    }

    public function update($data)
    {
        return $this->db->update('banner', $data); 
    }

    public function get_banner_data()
    {
        $query = $this->db->get('banner');
        return $query->row(); 
    }

   

}
