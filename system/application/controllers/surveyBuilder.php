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

class SurveyBuilder extends Controller {

	public function __construct() {
		parent::Controller();
	} 
	
	/**
    * adds survey name to database
    *
    * @access public
    * @param $_POST
    * @return void
    */
	public function addSurveyName() {
		
		// sanitize data
		$_POST = $this->input->xss_clean($_POST);
		
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
		
		$surveyQuestion = array('surveyID'=>$this->input->post('surveyID', TRUE),
								'question'=>$this->input->post('question', TRUE),
								'fieldTypeID'=>$this->input->post('fieldTypeID', TRUE),
								'subQuestion'=>$this->input->post('subQuestion', TRUE),
								'required'=>$this->input->post('required', TRUE),										
								'questionType'=>$this->input->post('questionType', TRUE)
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
		
		$surveySubQuestion = array('questionID'=>$this->input->post('questionID', TRUE),
								'fieldTypeID'=>$this->input->post('fieldTypeID', TRUE),
								'subQuestion'=>$this->input->post('subQuestion', TRUE),
								'subChoiceQuestionCheck'=>$this->input->post('subChoiceQuestionCheck', TRUE),
								'toggle'=>$this->input->post('toggle', TRUE)										
								);
		$surveySubChoiceQuesiton = array('subChoiceQuestion'=>$this->input->post('subChoiceQuestion', TRUE),
										'fieldTypeID'=>$this->input->post('subFieldTypeID', TRUE),
										'toggle'=>$this->input->post('toggle', TRUE)
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
		$surveyContactQuestion = array('surveyID'=>$this->input->post('surveyID', TRUE),
									'contactQuestion'=>$this->input->post('contactQuestion', TRUE),
									'questionType'=>$this->input->post('questionType', TRUE),
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
		$surveyContactField = array('contactQuestionID'=>$this->input->post('contactQuestionID', TRUE),
								'contactField'=>$this->input->post('contactField', TRUE),
								'fieldTypeID'=>$this->input->post('fieldTypeID', TRUE),
								'required'=>$this->input->post('required', TRUE)
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
	
	public function editSurveyQuestions() {
		
		$this->load->model('JsModel');
		$popUpParams = $this->JsModel->popUp();
			
		$surveyID = $this->uri->segment(3, 0); 
		$formHtml = $this->SurveyBuilderModel->editSurveyQuestions($surveyID, $popUpParams);
		echo $formHtml; // results pulled into view via jQuery AJAX
	} 
	
	public function editSurveySubQuestions() {
					
		$questionID = $this->uri->segment(3, 0);
		$formHtml = $this->SurveyBuilderModel->editSurveySubQuestions($questionID);
		echo $formHtml; // results pulled into view via jQuery AJAX
	}
	
	public function editSurveySubChoiceQuestions() {
		$subQuestionID = $this->uri->segment(3, 0);
		$formHtml = $this->SurveyBuilderModel->editSurveySubChoiceQuestions($subQuestionID);
		echo $formHtml; // results pulled into view via jQuery AJAX
	}
	
	public function updateSurvey() {
	
		$_POST = $this->input->xss_clean($_POST);
		
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
}

?>