<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  // Redirect to login if not logged in
        }
    }

    public function index()
    {
        $this->load->view('backend/dashboard/index');
    }
}
