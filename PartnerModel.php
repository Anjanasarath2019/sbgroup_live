<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PartnerModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('partners', $data);
    }

    public function get_all()
    {
        return $this->db->get('partners')->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where('partners', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partners', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partners'); 
    }

   



}
