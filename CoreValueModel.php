<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoreValueModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('corevalues', $data);
    }

    public function get_all()
    {
        return $this->db->get('corevalues')->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get('corevalues')->row_array(); 
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('corevalues', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('corevalues'); 
    }


}