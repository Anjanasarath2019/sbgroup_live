<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('NewsModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $news = $this->NewsModel->get_all();
            echo json_encode($news);
            return;
        }

        $this->load->view('backend/news/index');
    }


    public function create()
    {
        $this->load->view('backend/news/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
    
                if ($width === 1000 && $height === 1000) {
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    
                    move_uploaded_file($image['tmp_name'], 'assets/images/news_images/' . $newName);
    
                    $data = [
                        'name' => $this->input->post('title'),
                        'image' => $newName,
                        'long_description' => $this->input->post('long_description'),
                        'date' => $this->input->post('date'),

                    ];
    
                    $this->NewsModel->save($data);
    
                    $this->session->set_flashdata('success', 'News created successfully!');
                    redirect('/news', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 1000 x 1000 pixels!');
                    redirect('/news', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/news', 'refresh');
                return;
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/news', 'refresh');
        }
    }
    

    public function edit($id)
    {
        $news = $this->NewsModel->get($id);
        $this->load->view('backend/news/edit', ['news' => $news]);
    }

    public function update($id)
    {
        $news = $this->NewsModel->get($id);
        
        $imagePath = $news['image'];  
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;

                if ($width === 1000 && $height === 1000) {

                    if (!empty($news['image'])) {
                        $oldImagePath = 'assets/images/news_images/' . $news['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }

                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/news_images/' . $newName);
                    $imagePath = $newName; 

                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 1000 x 1000 pixels!');
                    redirect('/news', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/news', 'refresh');
                return;
            }
        }

        $data = [
            'name' => $this->input->post('title'),
            'image' => $imagePath,  
            'long_description' => $this->input->post('long_description'),
            'date' => $this->input->post('date'),
        ];

        $this->NewsModel->update($id, $data);

        $this->session->set_flashdata('success', 'News updated successfully!');
        redirect('/news', 'refresh');
    }


    public function delete($id)
    {
        $news = $this->NewsModel->get($id);

        if ($news) {
            $imagePath = 'assets/images/news_images/' . $news['image']; 

            if (!empty($news['image'])) {  
                if (file_exists($imagePath)) {
                    unlink($imagePath);  
                }
            }

            $this->NewsModel->delete($id);

            $this->session->set_flashdata('success', 'News deleted successfully!');
            redirect('/news', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/news', 'refresh');
        }
    }


}