<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('MapModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $map = $this->MapModel->get_single(); 
        $this->load->view('backend/map/index', ['map' => $map]);
    }
    
   
    public function edit($id)
    {
        $map = $this->MapModel->get_single();

        if (!$map) {
            $this->session->set_flashdata('error', 'Data not found.');
            redirect('map');
        }

        $this->load->view('backend/map/edit', ['map' => $map]);
    }

  
    public function update()
    {
        $map = $this->MapModel->get_single();
        
        $image = $_FILES['image'];
        $imagePath = $map->image; 
    
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
                
                if ($width === 1698 && $height === 1501) {
                    if (!empty($map->image)) {
                        $oldImagePath = 'assets/images/map_image/' . $map->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
    
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/map_image/' . $newName);
                    $imagePath = $newName;
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 1698 x 1501 pixels!');
                    redirect('/map', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid image file!');
                redirect('/map', 'refresh');
                return;
            }
        }
    
        $data = [
            'image'      => $imagePath
        ];
    
        $this->MapModel->update($data);
    
        $this->session->set_flashdata('success', 'Map updated successfully!');
        redirect('/map', 'refresh');
    }
    
}