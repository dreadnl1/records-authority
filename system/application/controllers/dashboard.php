<?php
/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu and fernando.reyes@du.edu
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
 
 class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
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
		$data['shadowboxMediumPopUpParams'] = $this->JsModel->shadowboxMediumPopUp();
		$data['shadowboxPopUpParams'] = $this->JsModel->shadowboxPopUp();
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
    * displays survey notes form
    *
    * @access public
    * @return void
    */
	public function surveyNotesForm() {
		
		$this->load->model('JsModel');
		$this->load->model('LookUpTablesModel');
		$data['popUpParams'] = $this->JsModel->popUp();
		
		$viewID = $this->uri->segment(3);
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

		if (!empty($viewID)) {
			$departmentID = $viewID;
			$divDeptArray = array();
			$divDeptArray = $this->LookUpTablesModel->getDivision($departmentID);
			$divisionID = $divDeptArray['divisionID'];
			
			$_POST['departmentID'] = $departmentID;
			$_POST['divisionID'] = $divisionID;
			
			$data['departmentData'] = $this->LookUpTablesModel->setDepartments($divisionID);
			$surveyResponses = $this->DashboardModel->getSurveyResponses($departmentID);
			$data['surveyResponses'] = $surveyResponses;
		}
		
		$data['divisionData'] = $this->LookUpTablesModel->createDivisionDropDown();
		$data['title'] = "Admin Department Form";
		$this->load->view('admin/forms/surveyNotesForm', $data);
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
	
}
 
?>