<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ProjectModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $projects = $this->ProjectModel->get_all();
            echo json_encode($projects);
            return;
        }

        $this->load->view('backend/project/index');
    }


    public function create()
    {
        $this->load->view('backend/project/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
    
                if ($width === 550 && $height === 400) {
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    
                    move_uploaded_file($image['tmp_name'], 'assets/images/project_images/' . $newName);
    
                    $data = [
                        'name' => $this->input->post('name'),
                        'image' => $newName,
                    ];
    
                    $this->ProjectModel->save($data);
    
                    $this->session->set_flashdata('success', 'Project created successfully!');
                    redirect('/project', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 550 x 400 pixels!');
                    redirect('/project', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/project', 'refresh');
                return;
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/project', 'refresh');
        }
    }
    

    public function edit($id)
    {
        $project = $this->ProjectModel->get($id);
        $this->load->view('backend/project/edit', ['project' => $project]);
    }

    public function update($id)
    {
        $project = $this->ProjectModel->get($id);
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;

                if ($width === 550 && $height === 400) {

                    if (!empty($project->image)) {
                        $oldImagePath = 'assets/images/project_images/' . $project->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }

                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/project_images/' . $newName);
                    $imagePath = $newName;

                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 550 x 400 pixels!');
                    redirect('/project', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/project', 'refresh');
                return;
            }
        } else {
            $imagePath = $project->image; 
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $imagePath,
        ];

        $this->ProjectModel->update($id, $data);

        $this->session->set_flashdata('success', 'Image updated successfully!');
        redirect('/project', 'refresh');
    }


    public function delete($id)
    {
        $project = $this->ProjectModel->get($id);

        if ($project) {
            if (!empty($project->image)) {
                $imagePath = 'assets/images/project_images/' . $project->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->ProjectModel->delete($id);

            $this->session->set_flashdata('success', 'Project deleted successfully!');
            redirect('/project', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/project', 'refresh');
        }
    }

}