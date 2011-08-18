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
 
 class Dashboard extends Controller {

	public function __construct() {
		parent::Controller();
		
		// admin user must be loggedin in order to use dashboard methods
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
		
		$this->uploadDir = $this->config->item('uploadDirectory');
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
		$data['retentionSchedulePopUp'] = $this->JsModel->retentionSchedulePopUp();
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
	* deletes survey
	* 
	* @access public
	* @param $surveyID
	* @return void 
	*/
	public function deleteSurvey() {
		$surveyID = trim($_POST['surveyID']);
		$this->SurveyBuilderModel->deleteSurvey($surveyID);
	}
	
	/**
    * displays record type form
    *
    * @access public
    * @return void
    *//*
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
		$mediumPopUp = $this->JsModel->mediumPopUp();
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['smallPopUp'] = $smallPopUp;
		$data['mediumPopUp'] = $mediumPopUp;
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
    *//*
	public function editRecordTypeForm() {
		
		$this->load->model('LookUpTablesModel');
		$this->load->model('JsModel');
		
		$recordInformationID = $this->uri->segment(3);
		
		$siteUrl = site_url();
		$jQuery = $this->JsModel->departmentWidgetJs($siteUrl);
		$jQueryDeptWidget = $this->JsModel->managementDepartmentWidgetJs($siteUrl);
		$jQueryDeptMasterCopyWidget = $this->JsModel->managementMasterCopyDepartmentWidgetJs($siteUrl);
		$jQueryDeptDuplicationWidget = $this->JsModel->managementDuplicationDepartmentWidgetJs($siteUrl);
		$popUp = $this->JsModel->retentionSchedulePopUp();
		
		$data['recordCategories'] = $this->LookUpTablesModel->getRecordCategories();
		$data['jQuery'] = $jQuery;
		$data['jQueryDeptWidget'] = $jQueryDeptWidget;
		$data['jQueryDeptMasterCopyWidget'] = $jQueryDeptMasterCopyWidget;
		$data['jQueryDeptDuplicationWidget'] = $jQueryDeptDuplicationWidget;
		$data['popUp'] = $popUp;
		
		$data['recordTypeData'] = $this->RecordTypeModel->getRecordType($recordInformationID);
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		
		$this->load->view('admin/forms/editRecordTypeForm', $data);		
	}
	
	/**
    * updates record type information 
    *
    * @access public
    * @return void
    *//*
	public function updateRecordTypeEditForm() {
		
		if (isset($_POST['recordInformationID'])) {
			$this->RecordTypeModel->updateRecordType($_POST);	
		}
	}
	
	/**
    * displays survey notes form
    *
    * @access public
    * @return void
    */
	public function surveyNotesForm() {
		
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
    *//*
	public function setRecordTypeFormDepartment() {
		if (!empty($_POST['departmentID'])) {
			echo $_POST['departmentID'];
		}
	}*/
	
	/**
	 * saves data from recordTypeInformation form
	 * 
	 * @access public
	 * @return $recordInformationID / used by jQuery, record type forms
	 *//*
	public function saveRecordTypeRecordInformation() {
		$recordInformation = array(
								'recordTypeDepartment'=>trim(strip_tags($this->input->post('recordTypeDepartment'))),
								'recordInformationDivisionID'=>trim(strip_tags($this->input->post('recordInformationDivisionID'))), //'recordInformationDepartmentID'=>$this->input->post('recordInformationDepartmentID', TRUE)
								'recordName'=>trim(strip_tags($this->input->post('recordName'))),										
								'recordDescription'=>trim(strip_tags($this->input->post('recordDescription'))),
								'recordCategory'=>trim(strip_tags($this->input->post('recordCategory'))),
								'recordNotesDeptAnswer'=>trim(strip_tags($this->input->post('recordNotesDeptAnswer'))),
								'recordNotesRmNotes'=>trim(strip_tags($this->input->post('recordNotesRmNotes')))
								);
	
		$recordInformationID = $this->DashboardModel->saveRecordTypeRecordInformation($recordInformation);
		echo $recordInformationID; //  result used by jQuery	
	}
	
	/**
	 * saves data from recordTypeFormatAndLocation form
	 * 
	 * @access public
	 * @return void
	 *//*
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
								'recordTypeDepartment'=>trim(strip_tags($this->input->post('recordTypeDepartment'))),
								'electronicRecord'=>trim(strip_tags($this->input->post('electronicRecord'))),
								'system'=>$system,
								'otherText'=>trim(strip_tags($this->input->post('otherText'))),										
								'paperVersion'=>trim(strip_tags($this->input->post('paperVersion'))),
								'location'=>$location,
								'otherBuilding'=>trim(strip_tags($this->input->post('otherBuilding'))),
								'otherStorage'=>trim(strip_tags($this->input->post('otherStorage'))),
								'finalRecordExist'=>trim(strip_tags($this->input->post('finalRecordExist'))),
								'backupMedia'=>trim(strip_tags($this->input->post('backupMedia'))),
								'recordLocation'=>$recordLocation,
								'otherRecordLocation'=>trim(strip_tags($this->input->post('otherRecordLocation'))),
								'fileFormat'=>trim(strip_tags($this->input->post('fileFormat'))),
								'formatAndLocationDeptAnswer'=>trim(strip_tags($this->input->post('formatAndLocationDeptAnswer'))),
								'formatAndLocationRmNotes'=>trim(strip_tags($this->input->post('formatAndLocationRmNotes'))),
								'recordInformationID' =>trim(strip_tags($this->input->post('recordInformationID')))
								);
								
		$this->DashboardModel->saveRecordTypeFormatAndLocation($formatAndLocation);
	} 
	
	/**
	 * saves data from recordTypeManagement form
	 * 
	 * @access public
	 * @return void
	 *//*
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

		// TODO: refactor...use loop
		$management = array(
						'recordTypeDepartment'=>trim(strip_tags($this->input->post('recordTypeDepartment'))),
						'accessAndUseDeptAnswer'=>trim(strip_tags($this->input->post('accessAndUseDeptAnswer'))),
						'accessAndUseRmNotes'=>trim(strip_tags($this->input->post('accessAndUseRmNotes'))),
						'yearsActive'=>trim(strip_tags($this->input->post('yearsActive'))),										
						'yearsAvailable'=>trim(strip_tags($this->input->post('yearsAvailable'))),
						'activePeriodDeptAnswer'=>trim(strip_tags($this->input->post('activePeriodDeptAnswer'))),
						'activePeriodRmNotes'=>trim(strip_tags($this->input->post('activePeriodRmNotes'))),
						'yearsKept'=>trim(strip_tags($this->input->post('yearsKept'))),
						'retentionPeriodDeptAnswer'=>trim(strip_tags($this->input->post('retentionPeriodDeptAnswer'))),
						'retentionPeriodRmNotes'=>trim(strip_tags($this->input->post('retentionPeriodRmNotes'))),
						'managementDivisionID'=>trim(strip_tags($this->input->post('managementDivisionID'))),
						'managementDepartmentID'=>trim(strip_tags($this->input->post('managementDepartmentID'))),
						'custodianDeptAnswer'=>trim(strip_tags($this->input->post('custodianDeptAnswer'))),
						'custodianRmNotes'=>trim(strip_tags($this->input->post('custodianRmNotes'))),
						'legislationGovernRecords'=>trim(strip_tags($this->input->post('legislationGovernRecords'))),
						'legislation'=>trim(strip_tags($this->input->post('legislation'))),
						'legislationHowLong'=>trim(strip_tags($this->input->post('legislationHowLong'))),
						'legalRequirmentsDeptAnswer'=>trim(strip_tags($this->input->post('legalRequirmentsDeptAnswer'))),
						'legalRequirmentsRmNotes'=>trim(strip_tags($this->input->post('legalRequirmentsRmNotes'))),
						'standardsAndBestPracticesDeptAnswer'=>trim(strip_tags($this->input->post('standardsAndBestPracticesDeptAnswer'))),
						'standardsAndBestPracticesRmNotes'=>trim(strip_tags($this->input->post('standardsAndBestPracticesRmNotes'))),
						'destroyRecords'=>trim(strip_tags($this->input->post('destroyRecords'))),
						'howOftenDestruction'=>trim(strip_tags($this->input->post('howOftenDestruction'))),
						'howAreRecordsDestroyed'=>trim(strip_tags($this->input->post('howAreRecordsDestroyed'))),
						'destructionDeptAnswer'=>trim(strip_tags($this->input->post('destructionDeptAnswer'))),
						'destructionRmNotes'=>trim(strip_tags($this->input->post('destructionRmNotes'))),
						'transferToArchives'=>trim(strip_tags($this->input->post('transferToArchives'))),
						'howOftenArchive'=>trim(strip_tags($this->input->post('howOftenArchive'))),
						'transferToArchivesDeptAnswer'=>trim(strip_tags($this->input->post('transferToArchivesDeptAnswer'))),
						'transferToArchivesRmNotes'=>trim(strip_tags($this->input->post('transferToArchivesRmNotes'))),
						'vitalRecords'=>trim(strip_tags($this->input->post('vitalRecords'))),
						'manageVitalRecords'=>trim(strip_tags($this->input->post('manageVitalRecords'))),
						'vitalRecordsDeptAnswer'=>trim(strip_tags($this->input->post('vitalRecordsDeptAnswer'))),
						'vitalRecordsRmNotes'=>trim(strip_tags($this->input->post('vitalRecordsRmNotes'))),
						'sensitiveInformation'=>trim(strip_tags($this->input->post('sensitiveInformation'))),
						'describeInformation'=>trim(strip_tags($this->input->post('describeInformation'))),
						'sensitiveInformationDeptAnswer'=>trim(strip_tags($this->input->post('describeInformation'))),
						'sensitiveInformationRmNotes'=>trim(strip_tags($this->input->post('sensitiveInformationRmNotes'))),
						'secureRecords'=>trim(strip_tags($this->input->post('secureRecords'))),
						'describeSecurityRecords'=>trim(strip_tags($this->input->post('describeSecurityRecords'))),
						'securityDeptAnswer'=>trim(strip_tags($this->input->post('securityDeptAnswer'))),
						'securityRmNotes'=>trim(strip_tags($this->input->post('securityRmNotes'))),
						'duplication'=>trim(strip_tags($this->input->post('duplication'))),
						'duplicationDivisionID'=>$duplicationDivisionID,
						'duplicationDepartmentID'=>$duplicationDepartmentID,
						'masterCopyDivisionID'=>trim(strip_tags($this->input->post('masterCopyDivisionID'))),
						'masterCopyDepartmentID'=>trim(strip_tags($this->input->post('masterCopyDepartmentID'))),
						'duplicationDeptAnswer'=>trim(strip_tags($this->input->post('duplicationDeptAnswer'))),
						'duplicationRmNotes'=>trim(strip_tags($this->input->post('duplicationRmNotes'))),
						'recordInformationID' =>trim(strip_tags($this->input->post('recordInformationID')))
					);
								
		$this->DashboardModel->saveRecordTypeManagement($management);
	}
	
	/**
	* ajax auto suggest function..gets documents types i.e. pdf, doc, docx etc...
	* @access public
	* @return void
	*/
	public function autoSuggest_docTypes() {
		$this->load->model('UpkeepModel');
		$docTypes = $this->UpkeepModel->autoSuggest_getDocTypes();
		
		foreach ($docTypes as $results) {
			echo strip_tags($results) . "\n";
		}
	}
	
	/**
	 * forces download of user specifed file
	 * @access public
	 * @return void
	 */
	public function forceDownload() {
		
		// http://w-shadow.com/blog/2007/08/12/how-to-force-file-download-with-php/
		/*
		 This function takes a path to a file to output ($file), 
		 the filename that the browser will see ($name) and 
		 the MIME type of the file ($mime_type, optional).
		 
		 If you want to do something on download abort/finish,
		 register_shutdown_function('function_name');
		 */

		if (!$this->uri->segment(3)) {
			return;			
		}
		
		$name = $this->uri->segment(3);
		
		$filePath = $this->uploadDir;
		
		$file = $filePath . $name;
		
		$mime_type ='';
		
		if(!is_readable($file)) die('File not found or inaccessible!');
		 
		 $size = filesize($file);
		 $name = rawurldecode($name);
		 		 		 
		 /* Figure out the MIME type (if not specified) */
		 $known_mime_types=array(
		 	"pdf" => "application/pdf",
		 	"txt" => "text/plain",
		 	"doc" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		 	"xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		    "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
			"vsd" => "application/vnd.visio",
		 	"tiff" => "image/tiff",
		    "gif" => "image/gif",
			"png" => "image/png",
			"jpeg"=> "image/jpg",
			"jpg" =>  "image/jpg"
		  );
		 
		 if($mime_type==''){
			 $file_extension = strtolower(substr(strrchr($file,"."),1));
			 if(array_key_exists($file_extension, $known_mime_types)){
				$mime_type=$known_mime_types[$file_extension];
			 } else {
				$mime_type="application/force-download";
			 };
		 };
		 
		 @ob_end_clean(); //turn off output buffering to decrease cpu usage
		 
		 // required for IE, otherwise Content-Disposition may be ignored
		 if(ini_get('zlib.output_compression'))
		  ini_set('zlib.output_compression', 'Off');
		 
		 header('Content-Type: ' . $mime_type);
		 header('Content-Disposition: attachment; filename="'.$name.'"');
		 header("Content-Transfer-Encoding: binary");
		 header('Accept-Ranges: bytes');
		 
		 /* The three lines below basically make the 
		    download non-cacheable */
		 header("Cache-control: private");
		 header('Pragma: private');
		 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		 
		 // multipart-download and download resuming support
		 if(isset($_SERVER['HTTP_RANGE']))
		 {
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			$range=intval($range);
			if(!$range_end) {
				$range_end=$size-1;
			} else {
				$range_end=intval($range_end);
			}
		 
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		 } else {
			$new_length=$size;
			header("Content-Length: ".$size);
		 }
		 
		 /* output the file itself */
		 $chunksize = 1*(1024*1024); // 1MB reduces cpu usage...
		 $bytes_send = 0;
		 if ($file = fopen($file, 'r'))
		 {
			if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
		 
			while(!feof($file) && 
				(!connection_aborted()) && 
				($bytes_send<$new_length)
			      )
			{
				$buffer = fread($file, $chunksize);
				echo($buffer); 
				flush();
				$bytes_send += strlen($buffer);
			}
		 fclose($file);
		 } else die('Error - can not open file.');
		 
		die();
		
	}
	
	public function adduser() {
		$this->load->view('admin/forms/addUserForm');
	}
	
 	public function newuser() {	
 		$auth = $this->DashboardModel->newUser($_POST);
 		$data['recordUpdated'] = 'User Added';
		$this->load->view('admin/displays/success',$data);
 	}
}
 
?>