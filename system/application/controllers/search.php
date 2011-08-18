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

class Search extends Controller {

	public function __construct() {
		parent::Controller();
	} 

	/*
	public function index() {
	       
        $url = "http://lib-devrecmgmt.cair.du.edu/kepi/newFacetSearchIndex.htm";		
		$data['refresh'] = header("Refresh: 0; url=$url");
		$data['message'] = "<p><strong>Loading...</strong></p>";
		$this->load->view('includes/redirect', $data);	                                                                       
	}
	*/
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function index() { 
		
		$this->load->model('LookUpTablesModel');
		
		// gets departments for selected division
		if (!empty($_POST['divisionID'])) {
			$url = site_url();
			$divisionID = $_POST['divisionID'];
			$data['departmentData'] = $this->LookUpTablesModel->setDepartments($divisionID);
		}
					
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchForm', $data);		
	}
	
	/**
    * performs search by department 
    *
    * @access public
    * @return $recordTypes / search results
    */
	public function searchByDepartment() {
		if (!empty($_POST['departmentID'])) { 
			$departmentID = $_POST['departmentID'];
			$divisionID = $_POST['divisionID'];
			$recordTypes = $this->SearchModel->getRecordTypes($departmentID, $divisionID);
			echo $recordTypes; // result used by jQuery	
		} 
	}
	
	/**
    * performs global search for record types 
    *
    * @access public
    * @return $globalRecordTypes / search results
    */
	public function globalSearch() {
		if (!empty($_POST['keyword'])) {
			$keyword = $_POST['keyword'];
			$globalRecordTypes = $this->SearchModel->getGlobalRecordTypes($keyword);
			echo $globalRecordTypes; // result used by jQuery		
		}
	}
}

?>
