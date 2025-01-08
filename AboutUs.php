<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutUs extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');
        $this->load->model('InnerAboutModel');
        $this->load->model('CoreValueModel');
        $this->load->model('CounterAreaModel');
        $this->load->model('PartnerModel'); 




    }

    public function index()
    {
        $data['services'] = $this->ServiceModel->get_all_services();
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);
        $data['about'] = $this->InnerAboutModel->get_single();
        $data['core_values'] = $this->CoreValueModel->get_all();
        $data['partners'] = $this->PartnerModel->get_all();


        $counter1 = $this->CounterAreaModel->get(1); 
        $counter2 = $this->CounterAreaModel->get(2); 
        $counter3 = $this->CounterAreaModel->get(3);
        $counter4 = $this->CounterAreaModel->get(4); 

        $data['counter1'] = $counter1;
        $data['counter2'] = $counter2;
        $data['counter3'] = $counter3;
        $data['counter4'] = $counter4;

        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/aboutus',$data);
        $this->load->view('frontend/footer', $data);


    }

    public function service_details($name)
    {
        $name = str_replace('-', ' ', urldecode($name)); 
        $data['service'] = $this->ServiceModel->get_service_details_by_name($name);
    
        if ($data['service']) {
            $this->load->view('frontend/service_details', $data);
        } else {
            show_404();
        }
    }

}




