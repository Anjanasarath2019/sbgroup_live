<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CounterArea extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('CounterAreaModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    
       public function index()
    {
        $counterArea = $this->CounterAreaModel->get_all();
    
        $data['counterArea'] = $counterArea;
        $this->load->view('backend/counterarea/index', $data);
    }



    public function create()
    {
        $this->load->view('backend/counterarea/create');
    }

    public function save()
    {    
                    $data = [
                        'title' => $this->input->post('title'),
                        'count' => $this->input->post('count'),
                    ];
    
                    $this->CounterAreaModel->save($data);
    
                    $this->session->set_flashdata('success', 'Data created successfully!');
                    redirect('/counterArea', 'refresh');
    }
    

    public function edit($id)
    {
        $counterarea = $this->CounterAreaModel->get($id);
        $this->load->view('backend/counterarea/edit', ['counterarea' => $counterarea]);
    }

    public function update($id)
    {
        $counterarea = $this->CounterAreaModel->get($id);

        $data = [
            'title' => $this->input->post('title'),
            'count' => $this->input->post('count'),
        ];

        $this->CounterAreaModel->update($id, $data);

        $this->session->set_flashdata('success', 'Data updated successfully!');
        redirect('/counterArea', 'refresh');
    }


    public function delete($id)
    {
        $counterarea = $this->CounterAreaModel->get($id);

        if ($counterarea) {
            $this->CounterAreaModel->delete($id);

            $this->session->set_flashdata('success', 'Data deleted successfully!');
            redirect('/counterArea', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/counterArea', 'refresh');
        }
    }


}