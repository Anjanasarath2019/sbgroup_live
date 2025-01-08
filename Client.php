<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ClientModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $clientLogos = $this->ClientModel->get_all();
            echo json_encode($clientLogos);
            return;
        }

        $this->load->view('backend/client/index');
    }


    public function create()
    {
        $this->load->view('backend/client/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
    
                if ($width === 216 && $height === 70) {
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    
                    move_uploaded_file($image['tmp_name'], 'assets/images/client_images/' . $newName);
    
                    $data = [
                        'image' => $newName,
                    ];
    
                    $this->ClientModel->save($data);
    
                    $this->session->set_flashdata('success', 'Image uploaded successfully!');
                    redirect('/client', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 216x70 pixels!');
                    redirect('/client', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/client', 'refresh');
                return;
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/client', 'refresh');
        }
    }
    

    public function edit($id)
    {
        $client = $this->ClientModel->get($id);
        $this->load->view('backend/client/edit', ['client' => $client]);
    }

    public function update($id)
    {
        $client = $this->ClientModel->get($id);
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;

                if ($width === 216 && $height === 70) {

                    if (!empty($client->image)) {
                        $oldImagePath = 'assets/images/client_images/' . $client->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }

                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/client_images/' . $newName);
                    $imagePath = $newName;

                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 216x70 pixels!');
                    redirect('/client', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/client', 'refresh');
                return;
            }
        } else {
            $imagePath = $client->image; 
        }

        $data = [
            'image' => $imagePath,
        ];

        $this->ClientModel->update($id, $data);

        $this->session->set_flashdata('success', 'Image updated successfully!');
        redirect('/client', 'refresh');
    }


    public function delete($id)
    {
        $client = $this->ClientModel->get($id);

        if ($client) {
            if (!empty($client->image)) {
                $imagePath = 'assets/images/client_images/' . $client->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->ClientModel->delete($id);

            $this->session->set_flashdata('success', 'Image deleted successfully!');
            redirect('/client', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/client', 'refresh');
        }
    }

}