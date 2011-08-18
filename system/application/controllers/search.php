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

class Search extends Controller {

	public function __construct() {
		parent::Controller();
		
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
	} 
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function index() { 
		$siteUrl = site_url();
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchForm', $data);		
	}
	
	
	public function searchRetentionSchedules() {
		$siteUrl = site_url();
		//$data['sortByScript'] = $this->JsModel->sortByWidgetJs($siteUrl);
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRetentionScheduleForm', $data);		
	}
	
	public function searchRecordTypes() {
		$siteUrl = site_url();
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRecordTypeForm', $data);		
	}
	
	public function recordTypeGlobalSearch() {
		$this->load->view('admin/forms/searchGlobalRecordTypeForm');			
	}
	
	/**
    * performs search by department 
    *
    * @access public
    * @return $recordTypes / search results
    */
	public function getRecordTypes() {
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
	
	/**
    * performs search by department 
    *
    * @access public
    * @return $retentionSchedules / search results
    */
	public function getRetentionSchedules() {
		if (!empty($_POST['departmentID'])) {
			$retentionSchedules = $this->SearchModel->getRetentionSchedules($_POST);
			echo $retentionSchedules;
		} 
	}
}

?>