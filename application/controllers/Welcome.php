<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';


class Welcome extends REST_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Property_model");
        $this->load->database();
    	
        //$autoload['model'] = array('property_model');
    	
    }	

    //check email address
    public function Checkemail_get()
    {
    	header("Access-Control-Allow-Origin: *");
        $email= $this->get('email');

        if($email!="")
        {
            $d=  $this->db->query("select * from users where email='".$email."'")->num_rows();
            if($d==1)
            {
                $this->response(['status' => FALSE,'error' => 'Email is allready in database'],REST_Controller::HTTP_SERVICE_UNAVAILABLE); 
            }else{
                $this->response(['status' => FALSE,'message' => 'add email'],REST_Controller::HTTP_SERVICE_UNAVAILABLE); 
            }    
        }else{

            $this->response([
                'status' => FALSE,
                'message' => 'Email is black'
            ], REST_Controller::HTTP_SERVICE_UNAVAILABLE); 
        }
    }

    public function login_get()
    {
        header("Access-Control-Allow-Origin: *");
        $email= $this->get('email');
        $password= $this->get('password');
        
        if($email!="" && $password!="")
        {
            $d = $this->db->query("select * from users where email='".$email."' AND password = '".$password."'")->num_rows();
            if($d==1)
            {
                $result = $this->db->query("select * from users where email='".$email."' AND password = '".$password."'")->result();
                $this->response($result,200); 
            }else{
                $this->response(['status' => FALSE,'error' => 'Email and password not valid'],REST_Controller::HTTP_SERVICE_UNAVAILABLE); 
            }    
        }else{
            $this->response([
                'status' => FALSE,
                'error' => 'Email and password is black'
            ], REST_Controller::HTTP_SERVICE_UNAVAILABLE); 
        }
    }


    public function Property_get()
    {
        
        header("Access-Control-Allow-Origin: *");
    	$start= $this->get('start');
    	$limit =$this->get('limit');
    	
    	$data= $this->Property_model->get_property_listing($start,$limit);

    	if(count($data)>0)
    	{
	    	$this->response($data,200);
    	}else{

    		$this->response([
                'status' => FALSE,
                'message' => 'No Record Available'
            ], REST_Controller::HTTP_SERVICE_UNAVAILABLE); 

    	}
    }


    public function Quickview_get()
    {
    	header("Access-Control-Allow-Origin: *");
    	$property_id= $this->get('property_id');
    	$data = $this->Property_model->get_quick_view_detail($property_id);
    	if(count($data)>0)
    	{
    		$this->response($data,200);
    	}else{

    		$this->response([
                'status' => FALSE,
                'message' => 'No Record Available'
            ], REST_Controller::HTTP_SERVICE_UNAVAILABLE); 

    	}


    }

      
   

}
