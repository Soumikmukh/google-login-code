<?php 
// require_once('Google/autoload.php');
include_once APPPATH . "libraries/vendor/autoload.php";

class Google {
	protected $CI;

	public function __construct(){
		$this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->config->load('google_config');
        $this->client = new Google_Client();
		$this->client->setClientId($this->CI->config->item('google_client_id'));
		$this->client->setClientSecret($this->CI->config->item('google_client_secret'));
		$this->client->setRedirectUri($this->CI->config->item('google_redirect_url'));
		$this->client->setScopes(array(
			"https://www.googleapis.com/auth/plus.login",
			"https://www.googleapis.com/auth/plus.me",
			"https://www.googleapis.com/auth/userinfo.email",
			"https://www.googleapis.com/auth/userinfo.profile"
			)
		);
  

	}

	public function get_login_url(){
		return  $this->client->createAuthUrl();

	}

	public function get_logout_url(){
		return  $this->client->revokeToken();

	}

	public function validate(){		
		if (isset($_GET['code'])) {
		  $this->client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $this->client->getAccessToken();

		}
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  $this->client->setAccessToken($_SESSION['access_token']);
		  $google_oauth = new Google_Service_Oauth2($this->client);
			$google_account_info = $google_oauth->userinfo->get();
			$email =  $google_account_info->email;
			$name =  $google_account_info->name;
			$id =  $google_account_info->id;
			$info['id']=$google_account_info->id;
			$info['email']=$google_account_info->email;
			$info['name']=$google_account_info->name;

		   return  $info;
		}


	}

}