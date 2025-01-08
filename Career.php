<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Career extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('CareerModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $careers = $this->CareerModel->get_all();
            echo json_encode($careers);
            return;
        }

        $this->load->view('backend/career/index');
    }


    public function create()
    {
        $this->load->view('backend/career/create');
    }

    public function save()
    {    
                    $data = [
                        'name' => $this->input->post('title'),
                        'description' => $this->input->post('long_description'),
                        'start_date' => $this->input->post('start_date'),
                        'end_date' => $this->input->post('end_date'),
                        'noof_openings' => $this->input->post('noof_openings'),
                    ];
    
                    $this->CareerModel->save($data);
    
                    $this->session->set_flashdata('success', 'Career created successfully!');
                    redirect('/career', 'refresh');
    }
    

    public function edit($id)
    {
        $career = $this->CareerModel->get($id);
        $this->load->view('backend/career/edit', ['career' => $career]);
    }

    public function update($id)
    {
        $career = $this->CareerModel->get($id);

        $data = [
            'name' => $this->input->post('title'),
            'description' => $this->input->post('long_description'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'noof_openings' => $this->input->post('noof_openings'),
        ];

        $this->CareerModel->update($id, $data);

        $this->session->set_flashdata('success', 'Career updated successfully!');
        redirect('/career', 'refresh');
    }


    public function delete($id)
    {
        $career = $this->CareerModel->get($id);

        if ($career) {
            $this->CareerModel->delete($id);

            $this->session->set_flashdata('success', 'Career deleted successfully!');
            redirect('/career', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/career', 'refresh');
        }
    }


}