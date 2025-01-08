<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('GalleryModel'); 
        $this->load->library('upload');

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
 
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $gallery = $this->GalleryModel->get_all();
            echo json_encode($gallery);
            return;
        }
        $this->load->view('backend/gallery/index');
    }

    public function create()
    {
        $this->load->view('backend/gallery/create');
    }

    public function save()
    {    
        $config['upload_path'] = './assets/images/gallery_images/';  
        $config['allowed_types'] = 'jpg|jpeg|png|gif';  
        $config['max_size'] = 2048;  
    
        $this->upload->initialize($config);
    
        $files = $_FILES['images']; 
        $uploadedImages = [];
    
        for ($i = 0; $i < count($files['name']); $i++) {
            $_FILES['image[]']['name'] = $files['name'][$i];
            $_FILES['image[]']['type'] = $files['type'][$i];
            $_FILES['image[]']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['image[]']['error'] = $files['error'][$i];
            $_FILES['image[]']['size'] = $files['size'][$i];
    
            if ($this->upload->do_upload('image[]')) {
                $data = $this->upload->data();
                $uploadedImages[] = $data['file_name']; 
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('upload_error', $error); 
                redirect('gallery'); 
            }
        }
    
        if (!empty($uploadedImages)) {
            $data = [
                'title' => $this->input->post('title'),
                'images' => json_encode($uploadedImages), 
            ];
    
            $this->GalleryModel->save($data);
            $this->session->set_flashdata('success', 'Images saved successfully!');
            redirect('gallery');
        }
    }

   
    public function edit($id)
    {
        $gallery = $this->GalleryModel->get($id);
        $this->load->view('backend/gallery/edit', ['gallery' => $gallery]);
    }

    public function update($id)
    {
        $config['upload_path'] = './assets/images/gallery_images/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048;
    
        $this->upload->initialize($config);
    
        $files = $_FILES['gallery_images'];
        $uploadedImages = [];
    
        $gallery = $this->GalleryModel->get($id);
        if (!$gallery) {
            $this->session->set_flashdata('upload_error', 'Gallery not found.');
            redirect('gallery');
        }
    
        // Check if there are new files uploaded
        if (!empty($files['name'][0])) { // Ensure there's at least one file uploaded
            $oldImages = json_decode($gallery['images']);
            $upload_path = './assets/images/gallery_images/';
    
            // Delete old images
            foreach ($oldImages as $oldImage) {
                $oldImagePath = $upload_path . $oldImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); 
                }
            }
    
            // Upload new images
            for ($i = 0; $i < count($files['name']); $i++) {
                $_FILES['image[]']['name'] = $files['name'][$i];
                $_FILES['image[]']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['image[]']['error'] = $files['error'][$i];
    
                if ($this->upload->do_upload('image[]')) {
                    $data = $this->upload->data();
                    $uploadedImages[] = $data['file_name']; 
                } else {
                    $error = $this->upload->display_errors();   
                    $this->session->set_flashdata('upload_error', $error);
                    redirect('gallery/edit/' . $id);
                }
            }
        }
    
        // If no new images were uploaded, retain old images
        if (empty($uploadedImages)) {
            $uploadedImages = json_decode($gallery['images']);
        }
    
        $data = [
            'title' => $this->input->post('title'),
            'images' => json_encode($uploadedImages), 
        ];
    
        $this->GalleryModel->update($id, $data);
    
        $this->session->set_flashdata('success', 'Gallery updated successfully!');
        redirect('gallery');
    }
    

    

    public function delete($id)
    {
        $gallery = $this->GalleryModel->get($id);
    
        if ($gallery) {
            $upload_path = './assets/images/gallery_images/';
            $images = json_decode($gallery['images']);
    
            foreach ($images as $image) {
                $image_path = $upload_path . $image;
                if (file_exists($image_path)) {
                    unlink($image_path); 
                }
            }
    
            $this->GalleryModel->delete($id);
    
            $this->session->set_flashdata('success', 'Image deleted successfully.');
        } else {
            $this->session->set_flashdata('upload_error', 'Image not found or already deleted.');
        }
    
        redirect('gallery');
    }
    
}