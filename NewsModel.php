<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function save($data)
    {
        $this->db->insert('news', $data);
    }

    public function get_all()
    {
        return $this->db->get('news')->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get('news')->row_array(); 
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('news', $data); 
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('news'); 
    }

    public function get_news_by_id($id)
    {
        $query = $this->db->get_where('news', array('id' => $id));
        return $query->row_array(); 
    }



}