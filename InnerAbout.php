<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InnerAbout extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('InnerAboutModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $innerabout = $this->InnerAboutModel->get_single(); 
        $this->load->view('backend/innerabout/index', ['innerabout' => $innerabout]);
    }
    
   
    public function edit($id)
    {
        $innerabout = $this->InnerAboutModel->get_single();

        if (!$innerabout) {
            $this->session->set_flashdata('error', 'About section not found.');
            redirect('innerabout');
        }

        $this->load->view('backend/innerabout/edit', ['innerabout' => $innerabout]);
    }

  
    public function update()
    {
        $innerabout = $this->InnerAboutModel->get_single();
        
        $image = $_FILES['image'];
        $imagePath = $innerabout->image; 
    
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
                
                if ($width === 651 && $height === 475) {
                    if (!empty($innerabout->image)) {
                        $oldImagePath = 'assets/images/innerabout_image/' . $innerabout->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
    
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/innerabout_image/' . $newName);
                    $imagePath = $newName;
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 650x475 pixels!');
                    redirect('/innerabout', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid image file!');
                redirect('/innerabout', 'refresh');
                return;
            }
        }
    
        $data = [
            'content'    => $this->input->post('content'),
            'mission'    => $this->input->post('mission'),
            'vision'    => $this->input->post('vision'),
            'image'      => $imagePath
        ];
    
        $this->InnerAboutModel->update($data);
    
        $this->session->set_flashdata('success', 'About section updated successfully!');
        redirect('/innerAbout', 'refresh');
    }
    
}



