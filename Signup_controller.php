<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup_controller extends CI_Controller {

	function __construct(){

		parent::__construct();
		
    $this->load->helper('security');
    $this->load->model('Signup_model');
   
    $this->load->library('pagination');
    $this->load->library('facebook');
    $this->load->library('google');
	}
  function remote_login_google(){
    $google_data=$this->google->validate();
    // print_r($google_data); die;
    $name_array = explode(' ', $google_data['name']);
    $userData=array(
      'oauth_provider'=> 'google',
      'oauth_uid' => $google_data['id'],
      'first_name'=>$name_array[0],
      'last_name'=>$name_array[1],
      'email'=>$google_data['email']
    );
    $userID = $this->Signup_model->checkUser($userData);
    if(!empty($userID)){ 
      $data['userData'] = $userData; 
      // Store the user profile info into session
      $this->session->set_userdata('customer_id',$userID); 
      $this->session->set_userdata('userdata', $userData); 
    }else{ 
      $data['userData'] = array(); 
    }
    redirect(base_url());
  }


  function logout(){
    unset($_SESSION['access_token']);
    $this->google->get_logout_url();
    $this->session->unset_userdata('customer_id');
    $this->session->unset_userdata('userdata');
    delete_cookie('unique_id',$_SERVER['SERVER_NAME'],'/'); 
    return redirect(base_url());
  }
    
}
?>