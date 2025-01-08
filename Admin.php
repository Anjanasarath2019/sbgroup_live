<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model'); 
        $this->load->library('form_validation');  
        $this->load->library('session');  
        $this->load->helper('url');
        
    }

    public function login()
    {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard');  
        }

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('backend/admin/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $admin = $this->Admin_model->check_login($username, $password);
            
            if ($admin) {
                $this->session->set_userdata('admin_logged_in', TRUE);
                $this->session->set_userdata('admin_id', $admin['id']);
                $this->session->set_userdata('admin_username', $admin['username']);

                redirect('dashboard/index');
            } else {
                $data['error_message'] = 'Invalid username or password';
                $this->load->view('backend/admin/login', $data);
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');  
    }
}
