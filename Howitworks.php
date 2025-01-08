<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Howitworks extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('HowitworksModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $howitworks = $this->HowitworksModel->get_single(); 
        $this->load->view('backend/howitworks/index', ['howitworks' => $howitworks]);
    }
    
   
    public function edit($id)
    {
        $howitworks = $this->HowitworksModel->get_single();

        if (!$howitworks) {
            $this->session->set_flashdata('error', 'Data not found.');
            redirect('howitworks');
        }

        $this->load->view('backend/howitworks/edit', ['howitworks' => $howitworks]);
    }

  
    public function update()
    {
        $howitworks = $this->HowitworksModel->get_single();
        
        $image = $_FILES['image'];
        $imagePath = $howitworks->image; 
    
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
                
                if ($width === 689 && $height === 510) {
                    if (!empty($howitworks->image)) {
                        $oldImagePath = 'assets/images/howitworks_image/' . $howitworks->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
    
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/howitworks_image/' . $newName);
                    $imagePath = $newName;
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 689 x 510 pixels!');
                    redirect('/howitworks', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid image file!');
                redirect('/howitworks', 'refresh');
                return;
            }
        }
    
        $data = [
            'content'    => $this->input->post('content'),
            'image'      => $imagePath
        ];
    
        $this->HowitworksModel->update($data);
    
        $this->session->set_flashdata('success', 'How it works section updated successfully!');
        redirect('/howitworks', 'refresh');
    }
    
}



