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

class SessionManager extends Model {

	/**
	 * checks if admin user is logged in
	 *
	 */
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
		
	/**
	 * checks if the admin account is logged in
	 * 
	 */
	public function isAdmin() {
		$this->load->library('session');
		$isAdmin = $this->session->userdata('username');
		if($isAdmin == "admin") {
			return TRUE;
		} else {
			return FALSE;
		}
	}
 }
 
?>