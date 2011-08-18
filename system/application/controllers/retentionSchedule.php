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

 
 class RetentionSchedule extends Controller {

	public function __construct() {
		parent::Controller();
		
		// admin user must be loggedin 
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
		$this->load->model('UpkeepModel');
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
	}
	
	/**
	 * renders retention schedule view
	 * 
	 * @return void  
	 */
	public function view() {
		$siteUrl = site_url();
		//$data['dispositions'] = $this->RetentionScheduleModel->getDispositions();
		$data['divisions'] = $this->LookUpTablesModel->createRsDivisionDropDown();
		// js
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['unitRadioButtonScript'] = $this->JsModel->departmentRadioButtonWidgetJs($siteUrl);
		$data['unitRadioButtonCheckScript'] = $this->JsModel->officeOfPrimaryResponsibilitydepartmentCheckWidgetJs($siteUrl);
		//$data['dispositionScript'] = $this->JsModel->dispositionWidgetJs($siteUrl);
		$data['assocUnitsScript'] = $this->JsModel->associatedUnitWidgetJs($siteUrl);
		$data['checkAllScript'] = $this->JsModel->associatedUnitCheckAllWidgetJs($siteUrl);
		$data['uncheckAllScript'] = $this->JsModel->associatedUnitUnCheckAllWidgetJs($siteUrl);
		$data['checkDeptScript'] = $this->JsModel->associatedUnitDeptCheck($siteUrl);
		$data['uncheckDeptScript'] = $this->JsModel->associatedUnitDeptUnCheck($siteUrl);
		
		// when form is triggered from record type form
		if ($this->uri->segment(3)) {
			$recordInformationID = $this->uri->segment(3);
			$data['recordInformation'] = $this->RetentionScheduleModel->getRecordInformation($recordInformationID);	
		} else {
			$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		}
		$this->load->view('admin/forms/retentionSchedule/addRetentionScheduleForm', $data);	
	}
	
	/**
	 * saves retention schedule
	 * 
	 * @return void  
	 */
	public function save() {
		if (isset($_POST['retentionPeriod'])) {
			$retentionScheduleID = $this->RetentionScheduleModel->saveRetentionSchedule($_POST);	
			// go into edit mode immediately after save
			$this->edit($retentionScheduleID);
		}
	}
	
	/**
	 * updates retention schedule
	 * 
	 * @return void  
	 */
	public function update() {
		if (isset($_POST['retentionScheduleID'])) {
			$retentionScheduleID = $_POST['retentionScheduleID'];
			$this->RetentionScheduleModel->updateRetentionSchedule($_POST);	
			$this->edit($retentionScheduleID);
		}
	}
	
	/**
	 * pulls retention schedule record into edit mode
	 * 
	 * @param $retentionScheduleID
	 * @return void  
	 */
	public function edit($retentionScheduleID="") {
		$siteUrl = site_url();
		//$data['dispositions'] = $this->RetentionScheduleModel->getDispositions();
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		//js
		$data['unitScript'] = $this->JsModel->departmentWidgetJs($siteUrl);
		$data['unitRadioButtonScript'] = $this->JsModel->departmentRadioButtonWidgetJs($siteUrl);
		$data['unitRadioButtonCheckScript'] = $this->JsModel->officeOfPrimaryResponsibilitydepartmentCheckWidgetJs($siteUrl);
		//$data['dispositionScript'] = $this->JsModel->dispositionWidgetJs($siteUrl);
		$data['assocUnitsScript'] = $this->JsModel->associatedUnitWidgetJs($siteUrl);
		$data['checkAllScript'] = $this->JsModel->associatedUnitCheckAllWidgetJs($siteUrl);
		$data['uncheckAllScript'] = $this->JsModel->associatedUnitUnCheckAllWidgetJs($siteUrl);
		$data['checkDeptScript'] = $this->JsModel->associatedUnitDeptCheck($siteUrl);
		$data['uncheckDeptScript'] = $this->JsModel->associatedUnitDeptUnCheck($siteUrl);		
		
		if (isset($_POST['retentionScheduleID'])) {
			$retentionScheduleID = $_POST['retentionScheduleID'];
		}
		if ($retentionScheduleID !== "") {
			$retentionScheduleResults = $this->RetentionScheduleModel->editRetentionSchedule($retentionScheduleID);
			// get divisionID out of array to get associated departments
			$divisionID = $retentionScheduleResults['divisionID'];
			$data['retentionSchedule'] = $retentionScheduleResults;
			$data['oprDepartments'] = $this->LookUpTablesModel->setDepartments($divisionID);
			$this->load->view('admin/forms/retentionSchedule/editRetentionScheduleForm', $data);
		} 
	}
	
	/**
	 * updates office of primary responsibility
	 * 
	 * @param $_POST
	 * @return void  
	 */
	public function updateOfficeOfPrimaryResponsibility() {
		if (isset($_POST['departmentID'])) {
			$this->RetentionScheduleModel->updateOfficeOfPrimaryResponsibility($_POST);
		}
	}
	
	/**
	 * retrieves checked associated units
	 * 
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
	public function editCheckAllAssociatedUnits() {
		$checkedAssociatedUnits = $this->LookUpTablesModel->editCheckAllAssociatedUnits($_POST);
		echo $checkedAssociatedUnits;
	}
	
	/**
	 * retrieves unchecked associated units
	 * 
	 * @param $_POST
	 * @return $unCheckedAssociatedUnits
	 */
 	public function editUnCheckAllAssociatedUnits() {
		$unCheckedAssociatedUnits = $this->LookUpTablesModel->editUnCheckAllAssociatedUnits($_POST);
		echo $unCheckedAssociatedUnits;
	}
	
	/** not used **
	 * gets disposition details
	 * 
	 * @param $dispositionID
	 * @return $dispositionDetails
	 */
	public function getDispositionDetails() {
		$dispositionID = $_POST['dispositionID'];
		$dispositionDetails = $this->RetentionScheduleModel->getDispositionDetails($dispositionID);
				
		if (is_array($dispositionDetails)) {
			foreach ($dispositionDetails as $details) {
				echo $details;	
			}
		} else {
			echo $dispositionDetails;
		}	
	}
	
	/**
	 * gets associated units
	 * 
	 * @param $_POST
	 * @return $associatedUnits
	 */
	public function getAssociatedUnits() {
		$associatedUnits = $this->LookUpTablesModel->getAssociatedUnits($_POST);
		echo $associatedUnits;
	}
	
	/**
	 * gets checked associated units
	 * 
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
 	public function check_associatedUnits() {
		$checkedAssociatedUnits = $this->LookUpTablesModel->check_associatedUnits($_POST);
		echo $checkedAssociatedUnits;
 	}
	
 	/**
	 * gets unchecked associated units
	 * 
	 * @param $_POST
	 * @return $uncheckedAssociatedUnits
	 */
 	public function uncheck_associatedUnits() {
 		$unCheckedAssociatedUnits = $this->LookUpTablesModel->uncheck_associatedUnits($_POST);
 		echo $unCheckedAssociatedUnits;
 	}
 	
 	/**
	 * provides auto suggest functionality for primary authorities
	 * 
	 * @param $_POST
	 * @return $uncheckedAssociatedUnits
	 */
	public function autoSuggest_primaryAuthorities() {
		$primaryAuthority = $_POST['q'];
		$primaryAuthorities = $this->UpkeepModel->autoSuggest_primaryAuthorities($primaryAuthority);
		if (is_array($primaryAuthorities)) {
			foreach ($primaryAuthorities as $results) {
				echo $results . "\n";
			}
		} else {
			echo $primaryAuthorities; // displays "no results found"
		}
	}

	/**
	 * provides auto suggest functionality for primary authorities
	 * 
	 * @param $_POST
	 * @return $uncheckedAssociatedUnits
	 */
	public function autoSuggest_primaryAuthorityRetentions() {
		$primaryAuthorityRetention = $_POST['q'];
		$primaryAuthorityRetentions = $this->UpkeepModel->autoSuggest_primaryAuthorityRetentions($primaryAuthorityRetention);
		if (is_array($primaryAuthorityRetentions)) {
			foreach ($primaryAuthorityRetentions as $results) {
				echo $results . "\n";
			} 
		} else {
			echo $primaryAuthorityRetentions;
		}
	}
	
	/**
	 * provides auto suggest functionality for primary authorities
	 * 
	 * @param $_POST
	 * @return $uncheckedAssociatedUnits
	 */
 	public function autoSuggest_relatedAuthorities() {
		$relatedAuthority = $_POST['q'];
 		$relatedAuthorities = $this->UpkeepModel->autoSuggest_relatedAuthorities($relatedAuthority);
		if (is_array($relatedAuthorities)) {
			foreach ($relatedAuthorities as $results) {
				echo $results . "\n";
			}
		} else {
			echo $relatedAuthorities;
		}
	}
	
	/**
	 * provides auto suggest functionality for retention periods
	 * 
	 * @param $retentionPeriod
	 * @return $results / $retentionPeriods
	 */
 	public function autoSuggest_retentionPeriods() {
		$retentionPeriod = $_POST['q'];
 		$retentionPeriods = $this->UpkeepModel->autoSuggest_retentionPeriods($retentionPeriod);
		if (is_array($retentionPeriods)) {
			foreach ($retentionPeriods as $results) {
				echo $results . "\n";
			}
		} else {
			echo $retentionPeriods; // displays "no results found"
		}
	}
	
	/**
	 * provides auto suggest functionality for related authority retentions
	 * 
	 * @param $relatedAuthorityRetention
	 * @return $results / $relatedAuthorityRetentions
	 */
	public function autoSuggest_relatedAuthorityRetention() {
		$relatedAuthorityRetention = $_POST['q'];
		$relatedAuthorityRetentions = $this->UpkeepModel->autoSuggest_relatedAuthorityRetention($relatedAuthorityRetention);
		if (is_array($relatedAuthorityRetentions)) {
			foreach ($relatedAuthorityRetentions as $results) {
				echo $results . "\n";
			}
		} else {
			echo $relatedAuthorityRetentions;
		}
	}
	
	/**
	 * provides auto suggest functionality for record names
	 * 
	 * @param $relatedAuthorityRetention
	 * @return $results / $relatedAuthorityRetentions
	 */
	public function autoSuggest_recordName() {
		$recordName = $_POST['q'];
		$recordNames = $this->UpkeepModel->autoSuggest_recordName($recordName);
		if (is_array($recordNames)) {
			foreach ($recordNames as $results) {
				echo $results . "\n";
			}
		} else {
			echo $recordNames;
		}
	}
	
 	/**
	 * provides auto suggest functionality for record codes
	 * 
	 * @param $relatedAuthorityRetention
	 * @return $results / $relatedAuthorityRetentions
	 */
	public function autoSuggest_recordCode() {
		$recordCode = $_POST['q'];
		$recordCodes = $this->UpkeepModel->autoSuggest_recordCode($recordCode);
		if (is_array($recordCodes)) {
			foreach ($recordCodes as $results) {
				echo $results . "\n";
			}
		} else {
			echo $recordCodes;
		}
	}
	
 	/**
    * indexes retention schedules
    *
    * @access public
    * @return void
    */
	public function indexRetentionSchedules() {
		$data['title'] = 'Index Retention Schedule - Records Authority';
		$this->load->view('includes/adminHeader', $data); 
		$this->RetentionScheduleModel->indexRs();
		$this->load->view('includes/adminFooter');
	}
	
	/**
    * publishes individual retention schedules
    *
    * @access public
    * @return void
    */
	public function publish() {
		$this->load->model('SearchModel');
		if(isset($_POST['approvedByCounsel']) && $_POST['approvedByCounsel'] == "yes") {
			$publish = "yes";
		} else {
			$publish = "no";
		}
		$keyword = $_POST['keyword'];
		$retentionScheduleID = $_POST['retentionScheduleID'];
		$this->RetentionScheduleModel->publishRetentionSchedule($retentionScheduleID, $publish);
		$data['recordUpdated'] = "Record Published.";
		$data['previousSearch'] = $this->SearchModel->getGlobalRetentionSchedules($keyword);
		$this->load->view('admin/forms/searchGlobalRetentionScheduleForm',$data);
	}
	
	/**
    * publishes all searched retention schedules
    *
    * @access public
    * @return void
    */
	public function publishAll() {
		$this->load->model('SearchModel');
		if(isset($_POST['publishAll']) && $_POST['publishAll'] == "yes") {
			$publish = "yes";
		} else {
			$publish = "no";
		}
		$keyword = $_POST['keyword'];
		$query = $this->RetentionScheduleModel->getRetentionInformationKeyword($keyword);
		
		if($query->num_rows() > 0) {
			foreach ($query->result() as $results) {
				$retentionScheduleID = $results->retentionScheduleID;
				$this->RetentionScheduleModel->publishRetentionSchedule($retentionScheduleID, $publish);
			}
		}
		$data['recordUpdated'] = "All Records Published.";
		$data['previousSearch'] = $this->SearchModel->getGlobalRetentionSchedules($keyword);
		$this->load->view('admin/forms/searchGlobalRetentionScheduleForm',$data);
	}
	
	
 	/**
	 * deletes retention schedule
	 * 
	 * @access public
	 * @return void
	 */
	public function delete() {
		$retentionScheduleID = $this->uri->segment(3);
		$this->RetentionScheduleModel->deleteRetentionSchedule($retentionScheduleID);
		$data['recordUpdated'] = "Record Deleted.";
		$this->load->view('admin/displays/success', $data);
	}
	
 	/**
	 * restores retention rchedule
	 * 
	 * @access public
	 * @return void
	 */
	public function restore(){
		$retentionScheduleID = $this->uri->segment(3);
		$this->RetentionScheduleModel->restoreRetentionSchedule($retentionScheduleID);
		$data['recordUpdated'] = "Record Restored.";
		$this->load->view('admin/displays/success', $data);
	}
	
	/**
	 * deletes retention schedule from deleted table
	 * 
	 * @access public
	 * @return void
	 */
 	public function permanentDelete() {
		$retentionScheduleID = $this->uri->segment(3);
		$this->RetentionScheduleModel->permanentDeleteRetentionSchedule($retentionScheduleID);
		$data['recordUpdated'] = "Record Deleted.";
		$this->load->view('admin/displays/success', $data);
	}
 }