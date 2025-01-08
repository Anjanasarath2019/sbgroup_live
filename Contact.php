<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ContactUsModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $contactus = $this->ContactUsModel->get_single(); 
        $this->load->view('backend/contactus/index', ['contactus' => $contactus]);
    }

  
    public function update()
    {
        $contactus = $this->ContactUsModel->get_single();
    
        $data = [
            'headoffice_address'    => $this->input->post('address'),
            'headoffice_phnumber'    => $this->input->post('headoffice_phnumber'),
            'headoffice_email'    => $this->input->post('email'),

            'eastern_region_phnumber'    => $this->input->post('eastern_region_phnumber'),
            'western_region_phnumber'    => $this->input->post('western_region_phnumber'),
            'south_region_phnumber'    => $this->input->post('south_region_phnumber'),

        ];
    
        $this->ContactUsModel->update($data);
    
        $this->session->set_flashdata('success', 'Data updated successfully!');
        redirect('/contact', 'refresh');
    }
    
}



