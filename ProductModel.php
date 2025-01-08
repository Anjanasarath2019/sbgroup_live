<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('products', $data);
    }

    public function get_all()
    {
        return $this->db->get('products')->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where('products', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products'); 
    }

   



}
