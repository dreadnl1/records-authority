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

 
 class Dashboard extends Controller {

	public function __construct() {
		parent::Controller();
		
		// admin user must be loggedin in order to use dashboard methods
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
	} 

	/**
    * displays dashboard
    *
    * @access public
    * @return void
    */
	public function index() {
					
		$this->load->model('JsModel');
		$data['popUpParams'] = $this->JsModel->popUp();
		$data['searchPopUpParams'] = $this->JsModel->searchPopUp();
		$data['mediumPopUpParams'] = $this->JsModel->mediumPopUp();
		$this->load->view('admin/displays/dashboard', $data);	
	}
	
	/**
    * displays add survey form
    *
    * @access public
    * @return void
    */
	public function addSurveyName() {
		$this->load->view('admin/forms/addSurveyNameForm');	
	}
	
	/**
    * displays add survey questions form
    *
    * @access public
    * @return void
    */
	public function addSurveyQuestions() {
		
		$surveyID = $this->uri->segment(3);
		$this->load->model('LookUpTablesModel');
		$data['surveyName'] = $this->SurveyModel->getSurveyName($surveyID);
		$data['fieldTypeData'] = $this->LookUpTablesModel->createFieldTypeDropDown();
		$data['surveyID'] = $surveyID;
		$this->load->view('admin/forms/addSurveyQuestionsForm', $data);	
	}
	
	/**
    * gets all surveys in the system 
    *
    * @access public
    * @return $surveyResults
    */
	public function listSurveys() {
		$data['surveyResults'] = $this->DashboardModel->getAllSurveys();
		$this->load->view('admin/displays/listSurveys', $data);	
	}
	
	/**
    * displays record type form
    *
    * @access public
    * @return void
    */
	public function recordTypeForm() {
		
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
		$jQueryDeptMasterCopyWidget = $this->JsModel->managementMasterCopyDepartmentWidgetJs($siteUrl);
		$jQueryDeptDuplicationWidget = $this->JsModel->managementDuplicationDepartmentWidgetJs($siteUrl);
		$smallPopUp = $this->JsModel->smallPopUp(); 
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['smallPopUp'] = $smallPopUp;
		$data['jQuery'] = $jQuery;
		$data['jQueryDeptWidget'] = $jQueryDeptWidget;
		$data['jQueryDeptMasterCopyWidget'] = $jQueryDeptMasterCopyWidget;
		$data['jQueryDeptDuplicationWidget'] = $jQueryDeptDuplicationWidget;
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/recordTypeForm', $data);	
	}
	
	/**
    * displays record type edit form 
    *
    * @access public
    * @return void
    */
	public function editRecordTypeForm() {
		
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
		
		$recordInformationID = $this->uri->segment(3);
		
		$siteUrl = site_url();
		$jQuery = $this->JsModel->departmentWidgetJs($siteUrl);
		$jQueryDeptWidget = $this->JsModel->managementDepartmentWidgetJs($siteUrl);
		$jQueryDeptMasterCopyWidget = $this->JsModel->managementMasterCopyDepartmentWidgetJs($siteUrl);
		$jQueryDeptDuplicationWidget = $this->JsModel->managementDuplicationDepartmentWidgetJs($siteUrl);
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['jQuery'] = $jQuery;
		$data['jQueryDeptWidget'] = $jQueryDeptWidget;
		$data['jQueryDeptMasterCopyWidget'] = $jQueryDeptMasterCopyWidget;
		$data['jQueryDeptDuplicationWidget'] = $jQueryDeptDuplicationWidget;
		
		$data['recordTypeData'] = $this->DashboardModel->getRecordType($recordInformationID);
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/editRecordTypeForm', $data);		
	}
	
	/**
    * updates record type information 
    *
    * @access public
    * @return void
    */
	public function updateRecordTypeEditForm() {
		
		$_POST = $this->input->xss_clean($_POST);
		if (isset($_POST['recordInformationID'])) {
			$this->DashboardModel->updateRecordTypeRecordInformation($_POST);	
		}
		
		if (isset($_POST['formatAndLocationID'])) {
			$this->DashboardModel->updateFormatAndLocation($_POST);
		}
		
		if (isset($_POST['managementID'])) {
			$this->DashboardModel->updateManagement($_POST);	
		}
	}
	
	/**
    * displays survey notes form
    *
    * @access public
    * @return void
    */
	public function surveyNotesForm() {
		
		$_POST = $this->input->xss_clean($_POST);
		$this->load->model('JsModel');
		$this->load->model('LookUpTablesModel');
		$data['popUpParams'] = $this->JsModel->popUp();
		
		// saves notes
		if (!empty($_POST['departmentID']) && !empty($_POST['contactID']) && !isset($_POST['surveyNotesID'])) {
			$this->DashboardModel->saveNotes($_POST);
		}
		
		// update survey notes
		if (isset($_POST['surveyNotesID'])) {
			$this->DashboardModel->updateNotes($_POST);
		}
		
		// gets departments for dropdown
		if (!empty($_POST['divisionID'])) {
			$divisionID = $_POST['divisionID'];
			$data['departmentData'] = $this->LookUpTablesModel->setDepartments($divisionID);
		}
		
		// gets questions and responses
		if (!empty($_POST['departmentID'])) { 
			$departmentID = $_POST['departmentID'];
			$surveyResponses = $this->DashboardModel->getSurveyResponses($departmentID);
			$data['surveyResponses'] = $surveyResponses;	
		} 
		
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$data['title'] = "Admin Department Form";
		$this->load->view('admin/forms/surveyNotesForm', $data);
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
	 * saves data from recordTypeInformation form
	 * 
	 * @access public
	 * @return $recordInformationID / used by jQuery, record type forms
	 */
	public function saveRecordTypeRecordInformation() {
		$recordInformation = array(
								'recordTypeDepartment'=>$this->input->post('recordTypeDepartment', TRUE),
								'recordInformationDivisionID'=>$this->input->post('recordInformationDivisionID', TRUE), //'recordInformationDepartmentID'=>$this->input->post('recordInformationDepartmentID', TRUE)
								'recordName'=>$this->input->post('recordName', TRUE),										
								'recordDescription'=>$this->input->post('recordDescription', TRUE),
								'recordCategory'=>$this->input->post('recordCategory', TRUE),
								'recordNotesDeptAnswer'=>$this->input->post('recordNotesDeptAnswer', TRUE),
								'recordNotesRmNotes'=>$this->input->post('recordNotesRmNotes', TRUE)
								);
	
		$recordInformationID = $this->DashboardModel->saveRecordTypeRecordInformation($recordInformation);
		echo $recordInformationID; //  result used by jQuery	
	}
	
	/**
	 * saves data from recordTypeFormatAndLocation form
	 * 
	 * @access public
	 * @return void
	 */
	public function saveRecordTypeFormatAndLocation() {
		
		// turn posted arrays (checkbox options) into lists
		if (isset($_POST['system'])) {
			$system = implode(",", $_POST['system']);
		} else {
			$system = "";
		}
		if (isset($_POST['location'])) {
			$location = implode(",", $_POST['location']);
		} else {
			$location = "";
		}
		if (isset($_POST['recordLocation'])) {
			$recordLocation = implode(",", $_POST['recordLocation']);
		} else {
			$recordLocation = "";
		}
						
		$formatAndLocation = array(
								'recordTypeDepartment'=>$this->input->post('recordTypeDepartment', TRUE),
								'electronicRecord'=>$this->input->post('electronicRecord', TRUE),
								'system'=>$system,
								'otherText'=>$this->input->post('otherText', TRUE),										
								'paperVersion'=>$this->input->post('paperVersion', TRUE),
								'location'=>$location,
								'otherBuilding'=>$this->input->post('otherBuilding', TRUE),
								'otherStorage'=>$this->input->post('otherStorage', TRUE),
								'finalRecordExist'=>$this->input->post('finalRecordExist', TRUE),
								'backupMedia'=>$this->input->post('backupMedia', TRUE),
								'recordLocation'=>$recordLocation,
								'otherRecordLocation'=>$this->input->post('otherRecordLocation', TRUE),
								'fileFormat'=>$this->input->post('fileFormat', TRUE),
								'formatAndLocationDeptAnswer'=>$this->input->post('formatAndLocationDeptAnswer', TRUE),
								'formatAndLocationRmNotes'=>$this->input->post('formatAndLocationRmNotes', TRUE),
								'recordInformationID' =>$this->input->post('recordInformationID', TRUE)
								);
								
		$this->DashboardModel->saveRecordTypeFormatAndLocation($formatAndLocation);
	} 
	
	/**
	 * saves data from recordTypeManagement form
	 * 
	 * @access public
	 * @return void
	 */
	public function saveRecordTypeManagement() {
		// turn posted arrays (checkbox options) into lists
		if (isset($_POST['duplicationDivisionID'])) {
			$duplicationDivisionID = implode(",", $_POST['duplicationDivisionID']);
		} else {
			$duplicationDivisionID = "";
		}
		if (isset($_POST['duplicationDepartmentID'])) {
			$duplicationDepartmentID = implode(",", $_POST['duplicationDepartmentID']);
		} else {
			$duplicationDepartmentID = "";
		}
							
		$management = array(
						'recordTypeDepartment'=>$this->input->post('recordTypeDepartment', TRUE),
						'accessAndUseDeptAnswer'=>$this->input->post('accessAndUseDeptAnswer', TRUE),
						'accessAndUseRmNotes'=>$this->input->post('accessAndUseRmNotes', TRUE),
						'yearsActive'=>$this->input->post('yearsActive', TRUE),										
						'yearsAvailable'=>$this->input->post('yearsAvailable', TRUE),
						'activePeriodDeptAnswer'=>$this->input->post('activePeriodDeptAnswer', TRUE),
						'activePeriodRmNotes'=>$this->input->post('activePeriodRmNotes', TRUE),
						'yearsKept'=>$this->input->post('yearsKept', TRUE),
						'retentionPeriodDeptAnswer'=>$this->input->post('retentionPeriodDeptAnswer', TRUE),
						'retentionPeriodRmNotes'=>$this->input->post('retentionPeriodRmNotes', TRUE),
						'managementDivisionID'=>$this->input->post('managementDivisionID', TRUE),
						'managementDepartmentID'=>$this->input->post('managementDepartmentID', TRUE),
						'custodianDeptAnswer'=>$this->input->post('custodianDeptAnswer', TRUE),
						'custodianRmNotes'=>$this->input->post('custodianRmNotes', TRUE),
						'legislationGovernRecords'=>$this->input->post('legislationGovernRecords', TRUE),
						'legislation'=>$this->input->post('legislation', TRUE),
						'legislationHowLong'=>$this->input->post('legislationHowLong', TRUE),
						'legalRequirmentsDeptAnswer'=>$this->input->post('legalRequirmentsDeptAnswer', TRUE),
						'legalRequirmentsRmNotes'=>$this->input->post('legalRequirmentsRmNotes', TRUE),
						'standardsAndBestPracticesDeptAnswer'=>$this->input->post('standardsAndBestPracticesDeptAnswer', TRUE),
						'standardsAndBestPracticesRmNotes'=>$this->input->post('standardsAndBestPracticesRmNotes', TRUE),
						'destroyRecords'=>$this->input->post('destroyRecords', TRUE),
						'howOftenDestruction'=>$this->input->post('howOftenDestruction', TRUE),
						'howAreRecordsDestroyed'=>$this->input->post('howAreRecordsDestroyed', TRUE),
						'destructionDeptAnswer'=>$this->input->post('destructionDeptAnswer', TRUE),
						'destructionRmNotes'=>$this->input->post('destructionRmNotes', TRUE),
						'transferToArchives'=>$this->input->post('transferToArchives', TRUE),
						'howOftenArchive'=>$this->input->post('howOftenArchive', TRUE),
						'transferToArchivesDeptAnswer'=>$this->input->post('transferToArchivesDeptAnswer', TRUE),
						'transferToArchivesRmNotes'=>$this->input->post('transferToArchivesRmNotes', TRUE),
						'vitalRecords'=>$this->input->post('vitalRecords', TRUE),
						'manageVitalRecords'=>$this->input->post('manageVitalRecords', TRUE),
						'vitalRecordsDeptAnswer'=>$this->input->post('vitalRecordsDeptAnswer', TRUE),
						'vitalRecordsRmNotes'=>$this->input->post('vitalRecordsRmNotes', TRUE),
						'sensitiveInformation'=>$this->input->post('sensitiveInformation', TRUE),
						'describeInformation'=>$this->input->post('describeInformation', TRUE),
						'sensitiveInformationDeptAnswer'=>$this->input->post('describeInformation', TRUE),
						'sensitiveInformationRmNotes'=>$this->input->post('sensitiveInformationRmNotes', TRUE),
						'secureRecords'=>$this->input->post('secureRecords', TRUE),
						'describeSecurityRecords'=>$this->input->post('describeSecurityRecords', TRUE),
						'securityDeptAnswer'=>$this->input->post('securityDeptAnswer', TRUE),
						'securityRmNotes'=>$this->input->post('securityRmNotes', TRUE),
						'duplication'=>$this->input->post('duplication', TRUE),
						'duplicationDivisionID'=>$duplicationDivisionID,
						'duplicationDepartmentID'=>$duplicationDepartmentID,
						'masterCopyDivisionID'=>$this->input->post('masterCopyDivisionID', TRUE),
						'masterCopyDepartmentID'=>$this->input->post('masterCopyDepartmentID', TRUE),
						'duplicationDeptAnswer'=>$this->input->post('duplicationDeptAnswer', TRUE),
						'duplicationRmNotes'=>$this->input->post('duplicationRmNotes', TRUE),
						'recordInformationID' =>$this->input->post('recordInformationID', TRUE)
					);
								
		$this->DashboardModel->saveRecordTypeManagement($management);
	}
	
 }
 
?>