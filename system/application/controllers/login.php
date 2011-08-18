<?php
/**
 * Copyright 2008 University of Denver--Penrose Library--University Records Management Program
 * Author fernando.reyes@du.edu
 * 
 * This file is part of Records Authority.
 * 
 * Records Authority is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Records Authority is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Records Authority.  If not, see <http://www.gnu.org/licenses/>.
 **/
 
 
 class login extends Controller {

	public function __construct() {
		parent::Controller();
	} 
	
	/**
	 * loads login form
	 * 
	 * @access public
	 * @return void
	 */
	public function index() {
		$this->load->view("public/forms/surveyLoginForm");		
	}
	
	/**
	 * authenticates users using LDAP database
	 * 
	 * @access public 
	 * @return void
	 */
	public function authenticateLDAP() {
 		 		 				
 		//authenticate the user
		if (!empty($_POST["uname"]) && !empty($_POST["pcode"])) {
			
			$ldapUser = trim($_POST["uname"]);
   			$ldapPswd = trim($_POST["pcode"]);	
   			
			$this->load->library("duldap"); 	
			$authResult = $this->duldap->authenticate($ldapUser, $ldapPswd);
			
			if ($authResult['result'] == TRUE) {
				// set session here
				$this->load->library('session');
				$this->session->set_userdata('isLoggedIn', TRUE);
				$url = site_url() . "/surveyBuilder/generateSurvey/1/publish";		
				$data['refresh'] = header("Refresh: 0; url=$url");
				$data['message'] = "<p><strong>Authenticating...</strong></p>";
				$this->load->view('includes/redirect', $data);	
			} elseif ($authResult['result'] == FALSE) {
				$data['uname'] = $ldapUser;
				$data['pcode'] = $ldapPswd;
				$data['error'] = $authResult['error'];
				$this->load->view("public/forms/surveyLoginForm", $data);
			}
	
		} else {
			$this->load->view("public/forms/surveyLoginForm");
		}
 	} 

	/**
	 * authenticates users not using LDAP
	 * 
	 * @access public 
	 * @return void
	 */ 	
  	public function authenticate() {
 		// authenticate access to admin screen
 		$auth = $this->DashboardModel->authenticate($_POST);
 		 		
 		if ($auth) {
 			// set session here
 			$this->load->library('session');
			$this->session->set_userdata('isLoggedIn', TRUE);
 			$url = site_url() . "/surveyBuilder/generateSurvey/1/publish";		
			$data['refresh'] = header("Refresh: 0; url=$url"); // TODO: not functional in Opera browser
			$data['message'] = "<p><strong>Authenticating...</strong></p>";
			$this->load->view('includes/redirect', $data);	
 		} else {
 			$data['uname'] = trim(strip_tags($_POST['uname']));
 			$data['pcode'] = trim(strip_tags($_POST['pcode']));
 			$data['error'] = "Please Try Again.";
 			$this->load->view("public/forms/surveyLoginForm", $data);		
 		}
 	}
 	
 	/**
 	 * loads dashboard login screen
 	 * 
 	 * @access public
 	 * @return void
 	 */
 	public function dashboard() {
		$this->load->view("public/forms/dashboardLoginForm");	
	}
	
	/**
	 * authenticates admin users
	 * 
	 * @access public
	 * @return void
	 */
 	public function authdashboard() {
 		// authenticate access to admin screen
 		$auth = $this->DashboardModel->authenticate($_POST);
 		
 		$now = time();
 		$date = unix_to_human($now, TRUE, 'us');
		
 		if ($auth) {
 			// set session here
 			$this->load->library('session');
			$this->session->set_userdata('isAdminLoggedIn', TRUE);
			$this->session->set_userdata('username', $_POST['uname']);
			$this->session->set_userdata('loginTime', $date);
 			$url = site_url() . "/dashboard";		
			$data['refresh'] = header("Refresh: 0; url=$url"); // TODO: not functional in Opera browser
			$data['message'] = "<p><strong>Authenticating...</strong></p>";
			$this->load->view('includes/redirect', $data);	
 		} else {
 			$data['uname'] = trim(strip_tags($_POST['uname']));
 			$data['pcode'] = trim(strip_tags($_POST['pcode']));
 			$data['error'] = "Please Try Again.";
 			$this->load->view("public/forms/dashboardLoginForm", $data);		
 		}
 	}
 	
 	/**
 	 * logs out admin users
 	 * 
 	 * @access public
 	 * @return void
 	 */
 	public function logout() {
 		$this->load->library('session');
		$this->session->sess_destroy();
		$siteUrl = site_url();
		$loginUrl = $siteUrl . "/login/dashboard";
    	$data['refresh'] = header("Refresh: 0; url=$loginUrl");
    	$data['message'] = "<p><strong>Logging out...</strong></p>";
		$this->load->view('includes/redirect', $data);	
 	}
 }
 
?>