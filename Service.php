<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $serviceData = $this->ServiceModel->get_all();
            echo json_encode($serviceData);
            return;
        }

        $this->load->view('backend/service/index');
    }

    public function create()
    {
        $this->load->view('backend/service/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageSize = getimagesize($image['tmp_name']);
            $width = $imageSize[0];
            $height = $imageSize[1];

            if ($width !== 600 || $height !== 600) {
                $this->session->set_flashdata('upload_error', 'Image must be 600 x 600 pixels.');
                redirect('/service', 'refresh');
                return;
            }

            $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            move_uploaded_file($image['tmp_name'], 'assets/images/service_images/' . $newName);

            $data = [
                'name' => $this->input->post('name'),
                'image' => $newName,
                'home_page' => $this->input->post('home_page') ? 1 : 0, 
                'footer_section' => $this->input->post('footer_section') ? 1 : 0, 
                'content' => $this->input->post('content'),
            ];

            $this->ServiceModel->save($data);

            $this->session->set_flashdata('success', 'Service created successfully!');
            redirect('/service', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/service', 'refresh');
        }
    }


    public function edit($id)
    {
        $service = $this->ServiceModel->get($id);
        $this->load->view('backend/service/edit', ['service' => $service]);
    }

    public function update($id)
    {
        $service = $this->ServiceModel->get($id);

        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageSize = getimagesize($image['tmp_name']);
            $width = $imageSize[0];
            $height = $imageSize[1];

            if ($width !== 600 || $height !== 600) {
                $this->session->set_flashdata('upload_error', 'Image must be 600 x 600 pixels.');
                redirect('/service', 'refresh');
                return;
            }

            if (!empty($service->image)) {
                $oldImagePath = 'assets/images/service_images/' . $service->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            move_uploaded_file($image['tmp_name'], 'assets/images/service_images/' . $newName);
            $imagePath = $newName;
        } else {
            $imagePath = $service->image;
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $imagePath,
            'home_page' => $this->input->post('home_page') ? 1 : 0, 
            'footer_section' => $this->input->post('footer_section') ? 1 : 0, 
            'content' => $this->input->post('content'),
        ];

        $this->ServiceModel->update($id, $data);

        $this->session->set_flashdata('success', 'Service updated successfully!');
        redirect('/service', 'refresh');
    }

    

    public function delete($id)
    {
        $service = $this->ServiceModel->get($id);

        if ($service) {
            if (!empty($service->image)) {
                $imagePath = 'assets/images/service_images/' . $service->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->ServiceModel->delete($id);

            $this->session->set_flashdata('success', 'Service deleted successfully!');
            redirect('/service', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Data not found.');
            redirect('/service', 'refresh');
        }
    }
}