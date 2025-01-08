<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ProductModel');  

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login'); 
        }
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $products = $this->ProductModel->get_all();
            echo json_encode($products);
            return;
        }

        $this->load->view('backend/product/index');
    }


    public function create()
    {
        $this->load->view('backend/product/create');
    }

    public function save()
    {
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
    
                if ($width === 500 && $height === 600) {
                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    
                    move_uploaded_file($image['tmp_name'], 'assets/images/product_images/' . $newName);
    
                    $data = [
                        'name' => $this->input->post('name'),
                        'image' => $newName,
                        'featured_product' => $this->input->post('featured_product') ? 1 : 0, // Checkbox value

                    ];
    
                    $this->ProductModel->save($data);
    
                    $this->session->set_flashdata('success', 'Product created successfully!');
                    redirect('/product', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 500 x 600 pixels!');
                    redirect('/product', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/product', 'refresh');
                return;
            }
        } else {
            $this->session->set_flashdata('upload_error', 'Data upload failed. Please try again.');
            redirect('/product', 'refresh');
        }
    }
    

    public function edit($id)
    {
        $product = $this->ProductModel->get($id);
        $this->load->view('backend/product/edit', ['product' => $product]);
    }

    public function update($id)
    {
        $product = $this->ProductModel->get($id);
        
        $image = $_FILES['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;

                if ($width === 500 && $height === 600) {

                    if (!empty($product->image)) {
                        $oldImagePath = 'assets/images/product_images/' . $product->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }

                    $newName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    move_uploaded_file($image['tmp_name'], 'assets/images/product_images/' . $newName);
                    $imagePath = $newName;

                } else {
                    $this->session->set_flashdata('upload_error', 'Image must be 500 x 600 pixels!');
                    redirect('/product', 'refresh');
                    return;
                }
            } else {
                $this->session->set_flashdata('upload_error', 'Invalid image file!');
                redirect('/product', 'refresh');
                return;
            }
        } else {
            $imagePath = $product->image; 
        }

        $data = [
            'name' => $this->input->post('name'),
            'image' => $imagePath,
            'featured_product' => $this->input->post('featured_product') ? 1 : 0, // Checkbox value

        ];

        $this->ProductModel->update($id, $data);

        $this->session->set_flashdata('success', 'Image updated successfully!');
        redirect('/product', 'refresh');
    }


    public function delete($id)
    {
        $product = $this->ProductModel->get($id);

        if ($product) {
            if (!empty($product->image)) {
                $imagePath = 'assets/images/product_images/' . $product->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); 
                }
            }

            $this->ProductModel->delete($id);

            $this->session->set_flashdata('success', 'Product deleted successfully!');
            redirect('/product', 'refresh');
        } else {
            $this->session->set_flashdata('upload_error', 'Data not found.');
            redirect('/product', 'refresh');
        }
    }

}