<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ServiceModel');
        $this->load->model('BannerModel');
        $this->load->model('AboutModel');
        $this->load->model('CoreValueModel');
        $this->load->model('ServiceModel');
        $this->load->model('HowitworksModel');
        $this->load->model('CounterAreaModel');
        $this->load->model('TestimonialModel');
        $this->load->model('MapModel');
        $this->load->model('ClientModel'); 
        $this->load->model('CareerModel'); 


    }

    public function index()
    {
        $data['services'] = $this->ServiceModel->get_all_services();
        $data['banner'] = $this->BannerModel->get_banner_data();
        $data['about'] = $this->AboutModel->get_single();
        $data['core_values'] = $this->CoreValueModel->get_all();
        $data['home_services']= $this->ServiceModel->get_latest_home_services(4);
        $data['howitworks'] = $this->HowitworksModel->get_single();
        $data['testimonials'] = $this->TestimonialModel->get_all();
        $data['map'] = $this->MapModel->get_single();
        $data['clients'] = $this->ClientModel->get_all();
        $data['footer_services']= $this->ServiceModel->get_latest_footer_services(4);


        $half_count = ceil(count($data['core_values']) / 2);
        $data['core_values_first_half'] = array_slice($data['core_values'], 0, $half_count);
        $data['core_values_second_half'] = array_slice($data['core_values'], $half_count);

        $counter1 = $this->CounterAreaModel->get(1); 
        $counter2 = $this->CounterAreaModel->get(2); 
        $counter3 = $this->CounterAreaModel->get(3);
        $counter4 = $this->CounterAreaModel->get(4); 

        $data['counter1'] = $counter1;
        $data['counter2'] = $counter2;
        $data['counter3'] = $counter3;
        $data['counter4'] = $counter4;

        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/home', $data);
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


    public function submit_enquiry()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'message' => $this->input->post('message')
        );

        $this->CareerModel->insert_enquiry($data);

        $this->session->set_flashdata('success', 'Your message has been sent!');
        redirect('home');
    }

    
    


}




