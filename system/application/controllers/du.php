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

class Du extends Controller {

	public function __construct() {
		parent::Controller();
		
		$this->load->library('iputility');
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
	} 
	
		
	/**
    * loads public search form(s)
    *
    * @access public
    * @return void
    */
	public function retentionSchedules() {
		//enable this code to lock to DU IP only
		/*$duIP = $this->iputility->checkDuIp();
		if ($duIP == FALSE) { 
	    	$this->load->view("public/displays/accessDenied");
			return; 
	    }*/
	    $this->load->model('JsModel');
		$data['searchPopUp'] = $this->JsModel->searchPopUp();
		
		// handles search forms
		if ($this->uri->segment(3) == "recordCategory") {
			$siteUrl = site_url();
			$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
			$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
	    	$this->load->view('public/forms/retentionScheduleRCSearchForm', $data);
		} elseif ($this->uri->segment(3) == "browseByDepartment") {
	    	$siteUrl = site_url();
			$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
	    	$this->load->view('public/forms/retentionScheduleDDSearchForm', $data);
	    } else {
	        $this->load->view('public/forms/retentionScheduleFTSearchForm', $data);                                                              
	    }
	}
	
	/**
    * allows user to perform a search for retention schedules by record category
    *
    * @access public
    * @return void
    */
	public function searchByRecordCategory() {
		$searchResults = $this->SearchModel->doRecordCategorySearch($_POST); 
		echo $searchResults;
	}
			
	/**
    * allows user to perform a search for retention schedules by department
    *
    * @access public
    * @return void
    */
	public function searchByDepartment() {
		$searchResults = $this->SearchModel->doDepartmentSearch($_POST); 
		echo $searchResults; 
	}
	
	/**
    * allows user to perform a full text search on existing retention schedules
    *
    * @access public
    * @return void
    */
	public function fullTextSearch() {
		/*if($this->input->post('submit') == 'Search All') {
			echo "Search All was selected";
			$_POST['keyword'] = "*";
			print_r($_POST);
		}
		$_POST['keyword'] = "*";
		print_r($_POST);
		$action = $this->input->post('submit');
		echo "action is: " . $action;
		*/
		$searchResults = $this->SearchModel->doFullTextSearch($_POST);
		echo $searchResults;
	}
	
	/**
    * gets requested retention schedule
    *
    * @access public
    * @param retentionScheduleID
    * @return void
    */
	public function getRetentionSchedule() {
		if ($this->uri->segment(3)) {
			$retentionScheduleID = $this->uri->segment(3);
			$recordResults = $this->RetentionScheduleModel->getRetentionScheduleRecord($retentionScheduleID);
			echo $recordResults; // displayed via thickBox
		}
	}
}

?>