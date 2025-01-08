<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('AboutModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $about = $this->AboutModel->get_single(); 
        $this->load->view('backend/about/index', ['about' => $about]);
    }
    
   
    public function edit($id)
    {
        $about = $this->AboutModel->get_single();

        if (!$about) {
            $this->session->set_flashdata('error', 'About section not found.');
            redirect('about');
        }

        $this->load->view('backend/about/edit', ['about' => $about]);
    }

  
    public function update()
    {
        $about = $this->AboutModel->get_single();
        
        $image = $_FILES['image'];
        $imagePath = $about->image; 
    
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
                
                if ($width === 651 && $height === 475) {
                    if (!empty($about->image)) {
                        $oldImagePath = 'assets/images/about_image/' . $about->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
    
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/about_image/' . $newName);
                    $imagePath = $newName;
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 650x475 pixels!');
                    redirect('/about', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid image file!');
                redirect('/about', 'refresh');
                return;
            }
        }
    
        $data = [
            'heading'    => $this->input->post('heading'),
            'content'    => $this->input->post('content'),
            'image'      => $imagePath
        ];
    
        $this->AboutModel->update($data);
    
        $this->session->set_flashdata('success', 'About section updated successfully!');
        redirect('/about', 'refresh');
    }
    
}



