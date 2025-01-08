<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('BrandModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $brandImages = $this->BrandModel->get_all();
            echo json_encode($brandImages);
            return;
        }

        $this->load->view('backend/brand/index');
    }


    public function create()
    {
        $this->load->view('backend/brand/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

            move_uploaded_file($image['tmp_name'], 'assets/images/brand_images/' . $newName);

            $data = [
                'name' => $this->input->post('name'),
                'image' => $newName,
            ];

            $this->BrandModel->save($data);

            $this->session->set_flashdata('success', 'Brand created successfully!');
            redirect('/brand', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Data upload failed. Please try again.');
            redirect('brand/create', 'refresh');
        }
    }

    public function edit($id)
    {
        $brand = $this->BrandModel->get($id);
        $this->load->view('backend/brand/edit', ['brand' => $brand]);
    }

    public function update($id)
    {
        $brand = $this->BrandModel->get($id);
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {

            if (!empty($brand->image)) {
                $oldImagePath = 'assets/images/brand_images/' . $brand->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); 
                }
            }

            $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            move_uploaded_file($image['tmp_name'], 'assets/images/brand_images/' . $newName);
            $imagePath = $newName;
        } else {
            $imagePath = $brand->image;
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $imagePath,
        ];

        $this->BrandModel->update($id, $data);

        $this->session->set_flashdata('success', 'Amenity updated successfully!');
        redirect('/brand', 'refresh');
    }

    public function delete($id)
    {
        $brand = $this->BrandModel->get($id);

        if ($brand) {
            if (!empty($brand->image)) {
                $imagePath = 'assets/images/brand_images/' . $brand->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->BrandModel->delete($id);

            $this->session->set_flashdata('success', 'Brand deleted successfully!');
            redirect('/brand', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Data not found.');
            redirect('/brand', 'refresh');
        }
    }

}