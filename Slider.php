<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('SliderModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $sliderImages = $this->SliderModel->get_all();
            echo json_encode($sliderImages);
            return;
        }

        $this->load->view('backend/slider/index');
    }


    public function create()
    {
        $this->load->view('backend/slider/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
    
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            $width = $imageInfo[0];
            $height = $imageInfo[1];
    
            if ($width === 1920 && $height === 1080) {
                $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    
                move_uploaded_file($image['tmp_name'], 'assets/images/slider_images/' . $newName);
    
                $data = [
                    'slider_text' => $this->input->post('text'),
                    'slider_image' => $newName,
                ];
    
                $this->SliderModel->save($data);
    
                $this->session->set_flashdata('success', 'Image uploaded successfully!');
                redirect('/slider', 'refresh');
            } else {
                $this->session->set_flashdata('upload_error', 'Image dimensions must be 1920x1080px.');
                redirect('slider', 'refresh');
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Image upload failed. Please try again.');
            redirect('slider', 'refresh');
        }
    }
    
    public function edit($id)
    {
        $slider = $this->SliderModel->get($id);
        $this->load->view('backend/slider/edit', ['slider' => $slider]);
    }

    public function update($id)
    {
        $slider = $this->SliderModel->get($id);
    
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            $width = $imageInfo[0];
            $height = $imageInfo[1];
    
            if ($width === 1920 && $height === 1080) {
                if (!empty($slider->slider_image)) {
                    $oldImagePath = 'assets/images/slider_images/' . $slider->slider_image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); 
                    }
                }
    
                $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                move_uploaded_file($image['tmp_name'], 'assets/images/slider_images/' . $newName);
                $imagePath = $newName;
            } else {
                $this->session->set_flashdata('upload_error', 'Image dimensions must be 1920x1080px.');
                redirect('/slider', 'refresh');
                return;
            }
        } else {
            $imagePath = $slider->slider_image;
        }
    
        $data = [
            'slider_text' => $this->input->post('text'),
            'slider_image' => $imagePath,
        ];
    
        $this->SliderModel->update($id, $data);
    
        $this->session->set_flashdata('success', 'Image updated successfully!');
        redirect('/slider', 'refresh');
    }
    
    public function delete($id)
    {
        $slider = $this->SliderModel->get($id);

        if ($slider) {
            if (!empty($slider->slider_image)) {
                $imagePath = 'assets/images/slider_images/' . $slider->slider_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->SliderModel->delete($id);

            $this->session->set_flashdata('success', 'Image deleted successfully!');
            redirect('/slider', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Image not found.');
            redirect('/slider', 'refresh');
        }
    }

}