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
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */	
	public function searchRetentionSchedules() {
		$siteUrl = site_url();
		$data['sortByScript'] = $this->JsModel->sortByWidgetJs($siteUrl);
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRetentionScheduleForm', $data);		
	}

	/**
    * renders search form
    *
    * @access public
    * @return void
    */	
	public function searchRetentionSchedulesDeleted() {
		$siteUrl = site_url();
		$data['sortByScript'] = $this->JsModel->sortByWidgetJs($siteUrl);
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRetentionScheduleFormDeleted', $data);		
	}

	/**
    * renders search form
    *
    * @access public
    * @return void
    */	
	public function searchRecordTypes() {
		$siteUrl = site_url();
		//$data['sortByScript'] = $this->JsModel->sortByWidgetRecordTypeJs($siteUrl);
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRecordTypeForm', $data);		
	}
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */	
	public function searchRecordTypesDeleted() {
		$siteUrl = site_url();
		//$data['sortByScript'] = $this->JsModel->sortByWidgetRecordTypeJs($siteUrl);
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchRecordTypeFormDeleted', $data);		
	}
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function searchSurveys() {
		$siteUrl = site_url();
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/searchSurveyForm', $data);	
	}
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function globalAuditSearch() {
		$this->load->view('admin/forms/searchAuditForm');
	}
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function recordTypeGlobalSearch() {
		$this->load->view('admin/forms/searchGlobalRecordTypeForm');			
	}
	
	/**
    * renders search form
    *
    * @access public
    * @return void
    */
	public function retentionScheduleGlobalSearch() {
		$this->load->view('admin/forms/searchGlobalRetentionScheduleForm');
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
    * performs search by department for deleted material
    *
    * @access public
    * @return $recordTypes / search results
    */
	public function getRecordTypesDeleted() {
		if (!empty($_POST['departmentID'])) { 
			$departmentID = $_POST['departmentID'];
			$divisionID = $_POST['divisionID'];
			$recordTypes = $this->SearchModel->getRecordTypesDeleted($departmentID, $divisionID);
			echo $recordTypes; // result used by jQuery	
		} 
	}
	
	/**
    * performs search by keyword
    *
    * @access public
    * @return $globalRecordTypes / search results
    */
	public function getGlobalRecordTypes() {
		if (!empty($_POST['keyword'])) { 
			$keyword = $_POST['keyword'];
			$globalRecordTypes = $this->SearchModel->getGlobalRecordTypes($keyword);
			echo $globalRecordTypes; // result used by jQuery	
		} 
	}
	
	/**
    * performs search by keyword
    *
    * @access public
    * @return $globalRetentionSchedules / search results
    */
	public function getGlobalRetentionSchedules() {
		if (!empty($_POST['keyword'])) { 
			$keyword = $_POST['keyword'];
			$globalRetentionSchedules = $this->SearchModel->getGlobalRetentionSchedules($keyword);
			echo $globalRetentionSchedules; // result used by jQuery	
		} 
	}
	
	/**
    * performs search by department 
    *
    * @access public
    * @return $surveys / search results
    */
	public function getSurveys() {
		if (!empty($_POST['departmentID'])) { 
			$departmentID = $_POST['departmentID'];
			$divisionID = $_POST['divisionID'];
			$surveys = $this->SearchModel->getSurveys($departmentID, $divisionID);
			echo $surveys; // result used by jQuery	
		} 
	}
	
	/**
    * performs search for audit 
    *
    * @access public
    * @return $audit / search results
    */
	public function auditSearch() {
		if (!empty($_POST['keyword'])) {
			$keyword = $_POST['keyword'];
			$audit = $this->SearchModel->getAudit($keyword);
			echo $audit; // result used by jQuery		
		}
	}
	
	/**
    * performs search for audit 
    *
    * @access public
    * @return $audit / search results
    */
	public function auditDateSearch() {
		if (!empty($_POST['beginDate']) && !empty($_POST['endDate'])) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
			$audit = $this->SearchModel->getDateAudit($beginDate,$endDate);
			echo $audit; // result used by jQuery		
		}
	}
	
	/**
    * performs global search for record types 
    *
    * @access public
    * @return $globalRecordTypes / search results
    */
	public function globalRecordTypeSearch() {
		if (!empty($_POST['keyword'])) {
			$keyword = $_POST['keyword'];
			$globalRecordTypes = $this->SearchModel->getGlobalRecordTypes($keyword);
			echo $globalRecordTypes; // result used by jQuery		
		}
	}
	
	/**
    * performs global search for deleted record types 
    *
    * @access public
    * @return $globalRecordTypesDeleted / search results
    */
	public function globalRecordTypeDeletedSearch() {
		$recordTypes = $this->SearchModel->getGlobalRecordTypesDeleted();
		echo $recordTypes; // result used by jQuery		
	}
	
	/**
	 * performs global seach for retention schedules
	 * 
	 * @access pulic
	 * @return $retentionSchedules / search results
	 */
	public function globalRetentionScheduleSearch() {
		if(!empty($_POST['keyword'])) {
			$keyword = $_POST['keyword'];
			$globalRetentionSchedules = $this->SearchModel->getGlobalRetentionSchedules($keyword);
			echo $globalRetentionSchedules;
		}
	}
	
	/**
	 * performs global seach for deleted retention schedules
	 * 
	 * @access pulic
	 * @return $retentionSchedules / search results
	 */
	public function globalRetentionScheduleDeletedSearch() {
		if(!empty($_POST['keyword'])) {
			$keyword = $_POST['keyword'];
			$globalRetentionSchedulesDeleted = $this->SearchModel->getGlobalRetentionSchedulesDeleted($keyword);
			echo $globalRetentionSchedulesDeleted;
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
	
		/**
    * performs search by department 
    *
    * @access public
    * @return $retentionSchedules / search results
    */
	public function getRetentionSchedulesDeleted() {
		if (!empty($_POST['departmentID'])) {
			$retentionSchedules = $this->SearchModel->getRetentionSchedulesDeleted($_POST);
			echo $retentionSchedules;
		} 
	}
	
	/**
    * allows user to perform a full text search on existing retention schedules
    *
    * @access public
    * @return void
    */
	public function fullTextSearch() {
		$searchResults = $this->SearchModel->doFullTextSearch($_POST);
		echo $searchResults;
	}
}

?>