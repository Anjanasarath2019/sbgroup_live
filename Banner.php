<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('BannerModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');  
        }
    }

    public function index()
    {
        $banner = $this->BannerModel->get_single(); 
        $this->load->view('backend/banner/index', ['banner' => $banner]);
    }
    
   
    public function edit($id)
    {
        $banner = $this->BannerModel->get_single();

        if (!$banner) {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('banner');
        }

        $this->load->view('backend/banner/edit', ['banner' => $banner]);
    }

  
    public function update()
    {
        $banner = $this->BannerModel->get_single();
    
        if (!$banner) {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/banner', 'refresh');
        }
    
        $data = [
            'top_heading' => $this->input->post('top_heading'),
            'heading'     => $this->input->post('heading'),
        ];
    
        if (!empty($_FILES['video']['name'])) {
            $config['upload_path'] = FCPATH . 'assets/videos/banner/';
            $config['allowed_types'] = 'mp4|avi|mov|wmv';
            $config['max_size']      = 102400; 
            $config['file_name']     = time() . '_' . $_FILES['video']['name'];
    
            $this->load->library('upload', $config);
    
            if ($this->upload->do_upload('video')) {
                $existingVideoPath = FCPATH . 'assets/videos/banner/' . $banner->video;
                if (!empty($banner->video) && file_exists($existingVideoPath)) {
                    unlink($existingVideoPath);
                }
    
                $uploadData = $this->upload->data();
                $data['video'] = $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('upload_error', $this->upload->display_errors());
                redirect('/banner', 'refresh');
            }
        }
    
        $this->BannerModel->update($data);
    
        $this->session->set_flashdata('success', 'Banner updated successfully!');
        redirect('/banner', 'refresh');
    }
    
    
}



