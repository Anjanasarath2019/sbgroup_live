<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('services', $data);
    }

    public function get_all()
    {
        return $this->db->get('services')->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where('services', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('services', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('services'); 
    }

    public function get_all_services() {
        return $this->db->get('services')->result();  
    }

    public function get_service_details_by_name($service_name)
    {
        $this->db->where('name', $service_name);
        return $this->db->get('services')->row();
    }


    public function get_latest_home_services($limit = 4)
    {
        $this->db->where('home_page', 1);
        $this->db->order_by('id', 'DESC'); 
        $this->db->limit($limit);
        return $this->db->get('services')->result();
    }

    public function get_latest_footer_services($limit = 4)
    {
        $this->db->where('footer_section', 1);
        $this->db->order_by('id', 'DESC'); 
        $this->db->limit($limit);
        return $this->db->get('services')->result();
    }



   



}
