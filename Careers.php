<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');
        $this->load->model('CareerModel');
        $this->load->library('upload');

    }

    public function index()
    {
        $data['services'] = $this->ServiceModel->get_all_services();
        $data['careers'] = $this->CareerModel->get_all();
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/careers', $data);
        $this->load->view('frontend/footer', $data);
    }


    public function submit_application()
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone_number = $this->input->post('phone_number');
        $position = $this->input->post('position');
        $qualification = $this->input->post('qualification');
        $message = $this->input->post('message');
        $option = $this->input->post('option');
    
        $config['upload_path'] = './uploads/career_applications/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
        $config['max_size'] = 2048; 
    
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
    
        $this->upload->initialize($config);
    
        $filePaths = [];
        if (!empty($_FILES['my_files']['name'][0])) {
            $filesCount = count($_FILES['my_files']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name'] = $_FILES['my_files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['my_files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['my_files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['my_files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['my_files']['size'][$i];
    
                if ($this->upload->do_upload('file')) {
                    $fileData = $this->upload->data();
                    $filePaths[] = $fileData['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('upload_error', $error);
                    redirect('careers');
                }
            }
        }
    
        $data = [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'position' => $position,
            'experience' => $option,
            'qualification' => $qualification,
            'message' => $message,
            'resume' => json_encode($filePaths),
        ];
    
        if ($this->CareerModel->submit_application($data)) {
            
            $this->session->set_flashdata('upload_error', 'Failed to submit application. Please try again.');
            redirect('careers'); 
        } else {

            $this->session->set_flashdata('success', 'Application submitted successfully!');
            redirect('careers'); 
           
        }
    }
    




}    




