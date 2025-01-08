<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CareerModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('careers', $data);
    }

    public function get_all()
    {
        return $this->db->get('careers')->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get('careers')->row_array(); 
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('careers', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('careers'); 
    }

    public function submit_application($data)
    {
        $this->db->insert('applications', $data);  
    }

    public function insert_enquiry($data)
    {
        $this->db->insert('enquiries', $data);
    }

   

}