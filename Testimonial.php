<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('TestimonialModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $testimonials = $this->TestimonialModel->get_all();
            echo json_encode($testimonials);
            return;
        }

        $this->load->view('backend/testimonial/index');
    }


    public function create()
    {
        $this->load->view('backend/testimonial/create');
    }

    public function save()
    {    
                    $data = [
                        'name' => $this->input->post('title'),
                        'designation' => $this->input->post('designation'),
                        'description' => $this->input->post('long_description'),
                    ];
    
                    $this->TestimonialModel->save($data);
    
                    $this->session->set_flashdata('success', 'Testimonial created successfully!');
                    redirect('/testimonial', 'refresh');
    }
    

    public function edit($id)
    {
        $testimonial = $this->TestimonialModel->get($id);
        $this->load->view('backend/testimonial/edit', ['testimonial' => $testimonial]);
    }

    public function update($id)
    {
        $testimonial = $this->TestimonialModel->get($id);

        $data = [
            'name' => $this->input->post('title'),
            'designation' => $this->input->post('designation'),
            'description' => $this->input->post('long_description'),
        ];

        $this->TestimonialModel->update($id, $data);

        $this->session->set_flashdata('success', 'Testimonial updated successfully!');
        redirect('/testimonial', 'refresh');
    }


    public function delete($id)
    {
        $testimonial = $this->TestimonialModel->get($id);

        if ($testimonial) {
            $this->TestimonialModel->delete($id);

            $this->session->set_flashdata('success', 'Career deleted successfully!');
            redirect('/testimonial', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/testimonial', 'refresh');
        }
    }


}