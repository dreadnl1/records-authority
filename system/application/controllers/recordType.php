<?php

/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu
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
 
 class RecordType extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		// admin user must be loggedin 
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
		$this->load->model('UpkeepModel');
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
		$this->load->model('AuditModel');
	}
	
	/**
    * displays record type form
    *
    * @access public
    * @return void
    */
	public function view() {
		
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
		
		$departmentID = $this->uri->segment(3, 0);
		if ($departmentID !== 0) {
			$data['division'] = $this->LookUpTablesModel->getDivision($departmentID);
		} elseif (!empty($_POST['divisionID'])) {
			$divisionID = $_POST['divisionID'];
			$data['departmentData'] = $this->LookUpTablesModel->setDepartments($divisionID);
		}
		
		$siteUrl = site_url();
		$jQuery = $this->JsModel->departmentWidgetJs($siteUrl);
		$jQueryDeptWidget = $this->JsModel->managementDepartmentWidgetJs($siteUrl);
		//$jQueryDeptWidget = $this->JsModel->departmentRadioButtonWidgetJs($siteUrl);
		//$jQueryDeptMasterCopyWidget = $this->JsModel->managementMasterCopyDepartmentWidgetJs($siteUrl);
		//$jQueryDeptDuplicationWidget = $this->JsModel->managementDuplicationDepartmentWidgetJs($siteUrl);
		$smallPopUp = $this->JsModel->smallPopUp(); 
		$mediumPopUp = $this->JsModel->mediumPopUp();
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['smallPopUp'] = $smallPopUp;
		$data['mediumPopUp'] = $mediumPopUp;
		$data['jQuery'] = $jQuery;
		$data['jQueryDeptWidget'] = $jQueryDeptWidget;
		//$data['jQueryDeptMasterCopyWidget'] = $jQueryDeptMasterCopyWidget;
		//$data['jQueryDeptDuplicationWidget'] = $jQueryDeptDuplicationWidget;
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();

		$this->load->view('admin/forms/addRecordTypeForm', $data);	
	}
	
	/**
    * displays record type edit form 
    *
    * @access public
    * @return void
    */
	public function edit() {
		
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
		
		$recordInformationID = $this->uri->segment(3);
		
		$siteUrl = site_url();
		$jQuery = $this->JsModel->departmentWidgetJs($siteUrl);
		$jQueryDeptWidget = $this->JsModel->managementDepartmentWidgetJs($siteUrl);
		//$jQueryDeptWidget = $this->JsModel->departmentRadioButtonWidgetJs($siteUrl);
		//$jQueryDeptMasterCopyWidget = $this->JsModel->managementMasterCopyDepartmentWidgetJs($siteUrl);
		//$jQueryDeptDuplicationWidget = $this->JsModel->managementDuplicationDepartmentWidgetJs($siteUrl);
		$popUp = $this->JsModel->retentionSchedulePopUp();
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['jQuery'] = $jQuery;
		$data['jQueryDeptWidget'] = $jQueryDeptWidget;
		//$data['jQueryDeptMasterCopyWidget'] = $jQueryDeptMasterCopyWidget;
		//$data['jQueryDeptDuplicationWidget'] = $jQueryDeptDuplicationWidget;
		$data['popUp'] = $popUp;
		
		$data['recordTypeData'] = $this->RecordTypeModel->getRecordType($recordInformationID);
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		
		$this->load->view('admin/forms/editRecordTypeForm', $data);		
	}
	
	/**
	 * saves data from recordTypeInformation form
	 * 
	 * @access public
	 * @return $recordInformationID / used by jQuery, record type forms
	 */
	public function saveRecordType() {
		// turn posted arrays (checkbox options) into lists
		if (isset($_POST['recordRegulations'])) {
			$recordRegulations = implode(",", $_POST['recordRegulations']);
		} else {
			$recordRegulations = "";
		}
		
		$recordInformation = array(
								'recordTypeDepartment'=>trim(strip_tags($this->input->post('recordTypeDepartment'))),
								'recordInformationDivisionID'=>trim(strip_tags($this->input->post('recordInformationDivisionID'))), //'recordInformationDepartmentID'=>$this->input->post('recordInformationDepartmentID', TRUE)
								'recordName'=>trim(strip_tags($this->input->post('recordName'))),										
								'recordDescription'=>trim(strip_tags($this->input->post('recordDescription'))),
								'recordCategory'=>trim(strip_tags($this->input->post('recordCategory'))),
								'managementDivisionID'=>trim(strip_tags($this->input->post('managementDivisionID'))),
								'managementDepartmentID'=>trim(strip_tags($this->input->post('managementDepartmentID'))),
								'recordNotesDeptAnswer'=>trim(strip_tags($this->input->post('recordNotesDeptAnswer'))),
								'recordNotesRmNotes'=>trim(strip_tags($this->input->post('recordNotesRmNotes'))),
								'recordFormat'=>trim(strip_tags($this->input->post('recordFormat'))),
								'otherPhysicalText'=>trim(strip_tags($this->input->post('otherPhysicalText'))),
								'otherElectronicText'=>trim(strip_tags($this->input->post('otherElectronicText'))),
								'recordStorage'=>trim(strip_tags($this->input->post('recordStorage'))),
								'otherDUBuildingText'=>trim(strip_tags($this->input->post('otherDUBuildingText'))),
								'otherOffsiteStorageText'=>trim(strip_tags($this->input->post('otherOffsiteStorageText'))),
								'otherElectronicSystemText'=>trim(strip_tags($this->input->post('otherElectronicSystemText'))),
								'formatAndLocationDeptAnswer'=>trim(strip_tags($this->input->post('formatAndLocationDeptAnswer'))),
								'formatAndLocationRmNotes'=>trim(strip_tags($this->input->post('formatAndLocationRmNotes'))),
								'recordRetentionAnswer'=>trim(strip_tags($this->input->post('recordRetentionAnswer'))),
								'usageNotesAnswer'=>trim(strip_tags($this->input->post('usageNotesAnswer'))),
								'retentionAuthoritiesAnswer'=>trim(strip_tags($this->input->post('retentionAuthoritiesAnswer'))),
								'vitalRecord'=>trim(strip_tags($this->input->post('vitalRecord'))),
								'vitalRecordNotesAnswer'=>trim(strip_tags($this->input->post('vitalRecordNotesAnswer'))),
								'recordRegulations'=>$recordRegulations,
								'personallyIdentifiableInformationAnswer'=>trim(strip_tags($this->input->post('personallyIdentifiableInformationAnswer'))),
								'personallyIdentifiableInformationRmNotes'=>trim(strip_tags($this->input->post('personallyIdentifiableInformationRmNotes'))),
								'otherDepartmentCopiesAnswer'=>trim(strip_tags($this->input->post('otherDepartmentCopiesAnswer')))
		);
	
		$this->AuditModel->audit($_POST);	
		$recordInformationID = $this->RecordTypeModel->saveRecordType($recordInformation);
		echo $recordInformationID; //  result used by jQuery
	}
	
	/**
    * updates record type information 
    *
    * @access public
    * @return void
    */
	public function updateRecordTypeEditForm() {
		
		if (isset($_POST['recordInformationID'])) {
			$this->AuditModel->audit($_POST);
			$this->RecordTypeModel->updateRecordType($_POST);
		}
	}
	
	/**
    * echo's departmentID / used by jQuery, record type forms
    *
    * @access public
    * @return void
    */
	public function setRecordTypeFormDepartment() {
		if (!empty($_POST['departmentID'])) {
			echo $_POST['departmentID'];
		}
	}
	
	/**
	 * deletes record type
	 * 
	 * @access public
	 * @return void
	 */
	public function delete() {
		$recordInformationID = $this->uri->segment(3);
		$this->RecordTypeModel->deleteRecordType($recordInformationID);
		$data['recordUpdated'] = "Record Deleted.";
		$this->load->view('admin/displays/success', $data);
	}

	/**
	 * restores Record Type
	 * 
	 * @access public
	 * @return void
	 */
	public function restore(){
		$recordInformationID = $this->uri->segment(3);
		$this->RecordTypeModel->restoreRecordType($recordInformationID);
		$data['recordUpdated'] = "Record Restored.";
		$this->load->view('admin/displays/success', $data);
	}
	
	/**
	 * deletes record type from deleted table
	 * 
	 * @access public
	 * @return void
	 */
 	public function permanentDelete() {
		$recordInformationID = $this->uri->segment(3);
		$this->RecordTypeModel->permanentDeleteRecordType($recordInformationID);
		$data['recordUpdated'] = "Record Deleted.";
		$this->load->view('admin/displays/success', $data);
	}
}
?>