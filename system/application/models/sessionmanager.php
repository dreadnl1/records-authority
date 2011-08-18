<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 *	Class used to check if admin user is logged in
 *
 *
*/

class SessionManager extends Model {

	public function isAdminLoggedIn() {
			
			$this->load->library('session');
			$siteUrl = site_url();
			$loginUrl = $siteUrl . "/login/dashboard";
			
			$isLoggedIn = $this->session->userdata('isAdminLoggedIn');
			$url = base_url();
			
			//if session does not exist...send user to login form
			if (!isset($isLoggedIn) || $isLoggedIn != TRUE){
				header("Location: $loginUrl");
				exit();
			}
		}
 }
 
?>