<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('projects', $data);
    }

    public function get_all()
    {
        return $this->db->get('projects')->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where('projects', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('projects', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('projects'); 
    }

    public function get_projects() {
        return $this->db->get('projects')->result();
    }

   



}
