<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoreValue extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('CoreValueModel'); 

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
 
    }

    public function index()
    {
        $corevalues = $this->CoreValueModel->get_all(); 
        $data['corevalues'] = $corevalues; 
        $this->load->view('backend/corevalue/index', $data); 
    }

    public function create()
    {
        $this->load->view('backend/corevalue/create');
    }

    public function save()
    {        
            $data = [
                'name' => $this->input->post('name'),
            ];
    
            $this->CoreValueModel->save($data);
            $this->session->set_flashdata('success', 'Data created successfully!');
            redirect('coreValue');
    }

   
    public function edit($id)
    {
        $corevalue = $this->CoreValueModel->get($id);
        $this->load->view('backend/corevalue/edit', ['corevalue' => $corevalue]);
    }

    public function update($id)
    {
        $data = [
            'name' => $this->input->post('name'),
        ];
    
        $this->CoreValueModel->update($id, $data);
    
        $this->session->set_flashdata('success', 'Data updated successfully!');
        redirect('coreValue');
    }
    
    public function delete($id)
    {
        $corevalue = $this->CoreValueModel->get($id);
    
        if ($corevalue) {
    
            $this->CoreValueModel->delete($id);
    
            $this->session->set_flashdata('success', 'Data deleted successfully.');
        } 
    
        redirect('coreValue');
    }
    
}