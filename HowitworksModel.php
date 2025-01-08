<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HowitworksModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function get_single() {
        return $this->db->get('howitworks')->row(); 
    }

    public function update($data)
    {
        return $this->db->update('howitworks', $data); 
    }

   

}
