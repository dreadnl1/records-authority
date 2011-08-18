<?php
/**
 * Copyright 2008 University of Denver--Penrose Library--University Records Management Program
 * Author fernando.reyes@du.edu
 * 
 * This file is part of Liaison.
 * 
 * Liaison is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Liaison is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Liaison.  If not, see <http://www.gnu.org/licenses/>.
 **/
 
 
 class login extends Controller {

	public function __construct() {
		parent::Controller();
	} 
	
	public function index() {
		$this->load->library('user_agent');
		if ($this->agent->is_browser()) {
    		$agent = $this->agent->browser();
			if ($agent == 'Mozilla') { // Mozilla is the user agent for safari TODO: need to fix CSS problems!!
				$this->load->view("public/displays/browserNotSupported.php");
			} else {
				$this->load->view("public/forms/surveyLoginForm");		
			}
		}
	}
	
	public function authenticate() {
 		 		 				
 		//authenticate the user
		if (!empty($_POST["uname"]) && !empty($_POST["pcode"])) {
			
			$ldapUser = $_POST["uname"];
   			$ldapPswd = $_POST["pcode"];	
   			
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
 	
 	public function dashboard() {
		$this->load->view("public/forms/dashboardLoginForm");	
	}
	
 	public function authdashboard() {
 		// authenticate access to admin screen
 		$auth = $this->DashboardModel->authenticate($_POST);
 		 		
 		if ($auth) {
 			// set session here
 			$this->load->library('session');
			$this->session->set_userdata('isAdminLoggedIn', TRUE);
 			$url = site_url() . "/dashboard";		
			$data['refresh'] = header("Refresh: 0; url=$url");
			$data['message'] = "<p><strong>Authenticating...</strong></p>";
			$this->load->view('includes/redirect', $data);	
 		} else {
 			$data['uname'] = $_POST['uname'];
 			$data['pcode'] = $_POST['pcode'];
 			$data['error'] = "Please Try Again.";
 			$this->load->view("public/forms/dashboardLoginForm", $data);		
 		}
 	}
 	
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