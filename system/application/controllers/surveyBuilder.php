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

class SurveyBuilder extends CI_Controller {

	public function __construct() {
		parent::__construct();
	} 
	
	/**
    * adds survey name to database
    *
    * @access public
    * @param $_POST
    * @return void
    */
	public function addSurveyName() {
				
		// remove smart quotes
		$this->load->library('convertsmartquotes');
		$_POST = $this->convertsmartquotes->convert($_POST);
						
		$surveyName = array('surveyName'=>$_POST['surveyName'], 
						'surveyDescription'=>$_POST['surveyDescription']
						);
						
		$surveyID['surveyID'] = $this->SurveyBuilderModel->addSurveyName($surveyName);
		$id = $surveyID['surveyID'];
		//redirect 
		$url = site_url();
		$data['refresh'] = header("Refresh: 0; url=$url/dashboard/addSurveyQuestions/$id");
		$data['message'] = "<p><strong>Creating Survey...</strong></p>";
		$this->load->view('includes/redirect', $data);	
	}
	
	/**
    * adds survey Question to database
    *
    * @access public
    * @param $_POST
    * @return $id / echo's questionID for use by jQuery
    */
	public function addSurveyQuestion() {	
		
		$surveyQuestion = array('surveyID'=>$this->input->post('surveyID'),
								'question'=>$this->input->post('question'),
								'fieldTypeID'=>$this->input->post('fieldTypeID'),
								'subQuestion'=>$this->input->post('subQuestion'),
								'required'=>$this->input->post('required'),										
								'questionType'=>$this->input->post('questionType')
								);
							
		$questionID['questionID'] = $this->SurveyBuilderModel->addSurveyQuestion($surveyQuestion);
		$id = $questionID['questionID'];
		echo $id;
	}
	
	/**
    * adds survey Sub Question to database
    *
    * @access public
    * @param $_POST
    * @return void
    */
	public function addSurveySubQuestion() {
		
		$surveySubQuestion = array('questionID'=>$this->input->post('questionID'),
								'fieldTypeID'=>$this->input->post('fieldTypeID'),
								'subQuestion'=>$this->input->post('subQuestion'),
								'subChoiceQuestionCheck'=>$this->input->post('subChoiceQuestionCheck'),
								'toggle'=>$this->input->post('toggle')										
								);
		$surveySubChoiceQuesiton = array('subChoiceQuestion'=>$this->input->post('subChoiceQuestion'),
										'fieldTypeID'=>$this->input->post('subFieldTypeID'),
										'toggle'=>$this->input->post('toggle')
										);											
		$this->SurveyBuilderModel->addSurveySubQuestion($surveySubQuestion, $surveySubChoiceQuesiton);
	}
	
	/**
    * saves survey Sub Question to database
    *
    * @access public
    * @param $_POST
    * @return void
    */
	public function addSurveyContactQuestion() {
		$surveyContactQuestion = array('surveyID'=>$this->input->post('surveyID'),
									'contactQuestion'=>$this->input->post('contactQuestion'),
									'questionType'=>$this->input->post('questionType'),
									);
		$contactQuestionID['contactQuestionID'] = $this->SurveyBuilderModel->addSurveyContactQuestion($surveyContactQuestion);
		$id = $contactQuestionID['contactQuestionID'];
		echo $id; // results pulled into view via jQuery AJAX
	}
	
	/**
    * saves survey contact field to database
    *
    * @access public
    * @param $_POST
    * @return void
    */
	public function addSurveyContactField() {
		$surveyContactField = array('contactQuestionID'=>$this->input->post('contactQuestionID'),
								'contactField'=>$this->input->post('contactField'),
								'fieldTypeID'=>$this->input->post('fieldTypeID'),
								'required'=>$this->input->post('required')
								);
		$this->SurveyBuilderModel->addSurveyContactField($surveyContactField);
	}
	
	/**
    * gets survey questions
    *
    * @access public
    * @param $surveyID
    * @return void
    */
	public function getSurveyQuestions() {
		$surveyID = $this->uri->segment(3, 0);
		if ($surveyID == 0) {
			echo 'An error occured. Unable to generate survey questions.';
		} else {
			$this->SurveyBuilderModel->getSurveyQuestions($surveyID);	
		}		
	}
	
	/**
    * re-orders survey question **NOT USED**
    *
    * @access public
    * @param $surveyID
    * @return void
    */
	public function reorderSurveyQuestions() {
		$surveyID = $this->uri->segment(3, 0);
		if ($surveyID == 0) {
			echo 'An error occured. Unable to generate survey questions.';
		} else {
			print_r($_POST);
			echo "<br /><br /><br /><br />";
			foreach ($_POST as &$field) {
				echo $field . "<br />";
			}
			
		}		
	}
	
	/**
    * generates survey
    *
    * @access public
    * @param $surveyID
    * @param $action / could be publish or preview
    * @return void
    */
	public function generateSurvey() {
		
		$surveyID = $this->uri->segment(3, 0);
		// action = preview / publish
		$action = $this->uri->segment(4, 0);
		if ($surveyID == 0) {
			echo 'An error occured. Unable to generate survey.';
		} elseif ($action == "publish") {
			// check session exists
			$siteUrl = site_url();
			$this->load->library('session');
			$loginUrl = $siteUrl;
			$isLoggedIn = $this->session->userdata('isLoggedIn');
			//if session does not exist...send user to login form
			if (!isset($isLoggedIn) || $isLoggedIn != TRUE){
				header("Location: $loginUrl");
				exit();
			}	
		}	
		
		$surveyHtml = $this->SurveyBuilderModel->generateSurvey($surveyID);	
				
		if ($action == "publish") {
			$data['surveyHtml'] = $surveyHtml;
			$this->load->view('public/forms/surveyForm', $data);
		} elseif ($action == "preview") {
			echo $surveyHtml; // results pulled into view via jQuery AJAX	
		}		
	}
	
	/**
	 * edits survey questions
	 * 
	 * @access public
	 * @return $formHtml
	 */
	public function editSurveyQuestions() {
		
		$this->load->model('JsModel');
		$popUpParams = $this->JsModel->popUp();
			
		$surveyID = $this->uri->segment(3, 0); 
		$formHtml = $this->SurveyBuilderModel->editSurveyQuestions($surveyID, $popUpParams);
		echo $formHtml; // results pulled into view via jQuery AJAX
	} 
	
	/**
	 * edits survey sub questions
	 * 
	 * @access public
	 * @return $formHtml
	 */
	public function editSurveySubQuestions() {
		$questionID = $this->uri->segment(3, 0);
		$formHtml = $this->SurveyBuilderModel->editSurveySubQuestions($questionID);
		echo $formHtml; // results pulled into view via jQuery AJAX
	}
	
	/**
	 * edits sub choice questions
	 * 
	 * @access public
	 * @return $formHtml
	 */
	public function editSurveySubChoiceQuestions() {
		$subQuestionID = $this->uri->segment(3, 0);
		$formHtml = $this->SurveyBuilderModel->editSurveySubChoiceQuestions($subQuestionID);
		echo $formHtml; // results pulled into view via jQuery AJAX
	}
	
	/**
	 * updates survey
	 * 
	 * @access public
	 * @return void
	 */
	public function updateSurvey() {
		
		// updates survey description
		if (isset($_POST['surveyID']) && isset($_POST['descriptionID'])) {
			$this->SurveyBuilderModel->updateSurveyDescription($_POST);
		}	
				
		// updates survey question
		if (isset($_POST['questionID']) && !isset($_POST['subQuestionID'])) {
			$this->SurveyBuilderModel->updateSurveyQuestion($_POST);
		}
		
		// updates survey sub question
		if (isset($_POST['subQuestionID'])  && isset($_POST['questionID'])) {
			$this->SurveyBuilderModel->updateSurveySubQuestion($_POST);
		}
		
		// updates survey sub choice question
		if (isset($_POST['subChoiceQuestionID'])) {
			$this->SurveyBuilderModel->updateSurveySubChoiceQuestion($_POST);
		}
	}
	
	/** 
	 * deletes survey question 
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteSurveyQuestion() {
		if ($_POST['questionID']) {
			$questionID = trim($_POST['questionID']);
			$this->SurveyBuilderModel->deleteSurveyQuestion($questionID);
		}
	}
	
	/**
	 * deletes survey sub question
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteSurveySubQuestion() {
		if ($_POST['subQuestionID']) {
			$subQuestionID = trim($_POST['subQuestionID']);
			$this->SurveyBuilderModel->deleteSurveySubQuestion($subQuestionID);	
		}
	}
	
	/**
	 * deletes survey sub choice question
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteSurveySubChoiceQuestion() {
		if ($_POST['subChoiceQuestionID']) {
			$subChoiceQuestionID = trim($_POST['subChoiceQuestionID']);
			$this->SurveyBuilderModel->deleteSurveySubChoiceQuestion($subChoiceQuestionID);	
		}
	}
}

?>