<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SBGroupNews extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');
        $this->load->model('NewsModel');

    }

    public function index()
    {
        $data['services'] = $this->ServiceModel->get_all_services();
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        $data['news'] = $this->NewsModel->get_all();
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/news', $data);
        $this->load->view('frontend/footer', $data);
    }


    public function service_details($name)
    {
        $name = str_replace('-', ' ', urldecode($name)); 
        $data['service'] = $this->ServiceModel->get_service_details_by_name($name);
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['services'] = $this->ServiceModel->get_all_services();


    
        if ($data['service']) {
            $this->load->view('frontend/service_details', $data);
        } else {
            show_404();
        }
    }

    public function news_details($id)
    {
        $data['news_item'] = $this->NewsModel->get_news_by_id($id);

        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['services'] = $this->ServiceModel->get_all_services();

        if ($data['news_item']) {
            $this->load->view('frontend/news_details', $data); 
            $this->load->view('frontend/header', $data);
            $this->load->view('frontend/footer', $data);
        } else {
            show_404();  
        }
    }



}




