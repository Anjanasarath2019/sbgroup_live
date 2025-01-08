<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContactUsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function insert_data($data) {
        $this->db->insert('contact_messages', $data); 
    }

    public function get_single() {
        return $this->db->get('contactus')->row(); 
    }

    public function update($data)
    {
        return $this->db->update('contactus', $data); 
    }




}