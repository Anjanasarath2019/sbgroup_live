<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContactUs extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');
        $this->load->model('ContactUsModel');

    }

    public function index()
    {
        $data['services'] = $this->ServiceModel->get_all_services();
        $data['contactus'] = $this->ContactUsModel->get_single();
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);

        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/contactus', $data);
        $this->load->view('frontend/footer', $data);

    }


    public function service_details($name)
    {
        $name = str_replace('-', ' ', urldecode($name)); 
        $data['service'] = $this->ServiceModel->get_service_details_by_name($name);
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['services'] = $this->ServiceModel->get_all_services();


    
        if ($data['service']) {
            $this->load->view('frontend/service_details', $data);
        } else {
            show_404();
        }
    }

    public function submit_message() {
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone_number' => $this->input->post('phone_number'),
            'msg_subject' => $this->input->post('msg_subject'),
            'message' => $this->input->post('message'),
        );

        $this->ContactUsModel->insert_data($data);

        $this->session->set_flashdata('success', 'Your message has been sent!');
        redirect('contactus');
    }


}




