<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('PartnerModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $partnerLogos = $this->PartnerModel->get_all();
            echo json_encode($partnerLogos);
            return;
        }

        $this->load->view('backend/partner/index');
    }


    public function create()
    {
        $this->load->view('backend/partner/create');
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
                    
                    move_uploaded_file($image['tmp_name'], 'assets/images/partner_images/' . $newName);
    
                    $data = [
                        'image' => $newName,
                    ];
    
                    $this->PartnerModel->save($data);
    
                    $this->session->set_flashdata('success', 'Image uploaded successfully!');
                    redirect('/partner', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 216x70 pixels!');
                    redirect('/partner', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/partner', 'refresh');
                return;
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/partner', 'refresh');
        }
    }
    

    public function edit($id)
    {
        $partner = $this->PartnerModel->get($id);
        $this->load->view('backend/partner/edit', ['partner' => $partner]);
    }

    public function update($id)
    {
        $partner = $this->PartnerModel->get($id);
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;

                if ($width === 216 && $height === 70) {

                    if (!empty($partner->image)) {
                        $oldImagePath = 'assets/images/partner_images/' . $partner->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }

                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/partner_images/' . $newName);
                    $imagePath = $newName;

                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 216x70 pixels!');
                    redirect('/partnerpartner', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/partner', 'refresh');
                return;
            }
        } else {
            $imagePath = $partner->image; 
        }

        $data = [
            'image' => $imagePath,
        ];

        $this->PartnerModel->update($id, $data);

        $this->session->set_flashdata('success', 'Image updated successfully!');
        redirect('/partner', 'refresh');
    }


    public function delete($id)
    {
        $partner = $this->PartnerModel->get($id);

        if ($partner) {
            if (!empty($partner->image)) {
                $imagePath = 'assets/images/partner_images/' . $partner->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->PartnerModel->delete($id);

            $this->session->set_flashdata('success', 'Image deleted successfully!');
            redirect('/partner', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/partner', 'refresh');
        }
    }

}