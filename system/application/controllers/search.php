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

	public function index() {
	       
                $url = "http://lib-devrecmgmt.cair.du.edu/kepi/newFacetSearchIndex.htm";		
		$data['refresh'] = header("Refresh: 0; url=$url");
		$data['message'] = "<p><strong>Loading...</strong></p>";
		$this->load->view('includes/redirect', $data);	
	}
	
	
	public function searchRecordTypes() {
		
		$this->load->model('LookUpTablesModel');
		
		if (!empty($_POST['divisionID'])) {
			$url = site_url();
			$divisionID = $_POST['divisionID'];
			$data['departmentData'] = $this->LookUpTablesModel->setDepartments($divisionID);
		}
		
		if (!empty($_POST['departmentID'])) { 
			print_r($_POST);
			$departmentID = $_POST['departmentID'];
			$recordTypes = $this->SearchModel->getRecordTypes($departmentID);
			$data['recordTypes'] = $recordTypes;	
		} 
		
		if (!empty($_POST['keyword'])) {
			echo "TEST!";	
		}
						
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchForm', $data);		
	}

	
}

?>
