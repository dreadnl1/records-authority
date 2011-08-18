<?php
/**
 * Copyright 2008 University of Denver--Penrose Library--University Records Management Program
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


class DashboardModel extends Model {

	public function __construct() {
 		parent::Model();
 	}
	
	/**
    * invokes getAllSurveysQuery() 
    *
    * @access public
    * @return  $surveys
    */
	public function getAllSurveys() {
	 	$surveys = $this->getAllSurveysQuery();
	 	return $surveys;
	 }
	 
	 /**
    * gets all surveys from database if any exist 
    *
    * @access  private
    * @return  $surveys
    */
	 private function getAllSurveysQuery() {
	 	$siteUrl = site_url();
	 	$this->db->select('surveyID, surveyName, surveyDescription');
	 	$this->db->from('rm_surveys');
	 	$this->db->order_by('surveyName','asc');
	 	$query = $this->db->get();
	 	
	 	$surveys = "";
	 	if ($query->num_rows() > 0) {
	 		foreach ($query->result() as $results) {
	 			$surveys .= "<li id='$results->surveyID'><a href='#' onClick='return areYouSure($results->surveyID);'>[Delete]</a>&nbsp;&nbsp;&nbsp;&nbsp;" . anchor("dashboard/addSurveyQuestions/$results->surveyID", trim(strip_tags($results->surveyName))) . "</li><br />";	
	 		}
	 		
	 		// ajax
	 		$surveys .= "<script type='text/javascript'>";
			$surveys .= "function areYouSure(surveyID) { ";
			$surveys .= "if (confirm('Are you sure you want to DELETE this Question?')) {";
			$surveys .= "$.post('$siteUrl/dashboard/deleteSurvey/', {surveyID: surveyID, ajax: 'true'}, function(results){ ";
			$surveys .= "$('#' + surveyID).html(results); ";
			$surveys .= "}); "; // post
			$surveys .= "} "; // end if
			$surveys .= "} "; // js
			$surveys .= "</script>";
	 		
	 		return $surveys;
	 	} else {
	 		$surveys = "No surveys found";
	 		return $surveys;
	 	}	
	 }
			 	 
	/**
    * invokes getSurveyResponsesQuery()
    *
    * @access public
    * @param $departmentID
    * @return $surveyResponses 
    */
	public function getSurveyResponses($departmentID) {
		$surveyResponses = $this->getSurveyResponsesQuery($departmentID);
 		return $surveyResponses;
	}
	
	/**
    * gets survey resopnses if any exists for "survey notes form"
    *
    * @access private
    * @param $departmentID
    * @return $surveyResponses
    */
	private function getSurveyResponsesQuery($departmentID) {
		
		$surveyResponses = array();
						
		// begin packaging available data to render survey notes form
		$departmentContact = $this->getDepartmentContact($departmentID);
		
		if (!is_array($departmentContact)) {
			return $departmentContact;
		} else {
			$surveyContacts = $this->getSurveyContacts($departmentID);
			$surveyResponses['departmentContact'] = $departmentContact;
			$contactID = $departmentContact['contactID'];
			
			$contactNotes = $this->getContactNotes($contactID);
			
			if ($contactNotes !="") {
				$surveyContacts[] = $contactNotes;
			}
						
			$surveyResponses['surveyContacts'] = $surveyContacts;
						
		}
							
		$responses = $this->getResponses($contactID, $departmentID);
		
		if (!is_array($responses)) {
			return $responses; 
		} else {
			$surveyResponses['responses'] = $responses;
		}
						
		$surveyResponses = $this->createSurveyNotesForm($surveyResponses, $departmentID);	
				
		return $surveyResponses;
	}

	/**
    * gets contact general notes
    *
    * @access private
    * @param $contactID
    * @return $contactNotes
    */
	private function getContactNotes($contactID) {
		$this->db->select('contactNotesID, contactNotes');
	 	$this->db->from('rm_surveyContactNotes');
	 	$this->db->where('contactID', $contactID);
	 	$this->db->order_by('contactNotes');
	 	$query = $this->db->get();
	 	
	 	$contactNotes = array();
	 	if ($query->num_rows > 0) {
	 		foreach ($query->result() as $notes) {
	 			$contactNotes['contactNotesID'] = $notes->contactNotesID;
	 			$contactNotes['contactNotes'] = $notes->contactNotes;			
	 		}
	 	
	 	return $contactNotes;
	 	}
	}
	
	/**
    * generates "survey notes form" 
    * TODO: refactor
    * @access private
    * @param $surveyResponses
    * @return $surveyFormHtml
    */
	private function createSurveyNotesForm($surveyResponses, $departmentID) {
		
		if (!empty($surveyResponses) && is_array($surveyResponses)) { 
				
			$siteUrl = site_url();
			$actionUrl = $siteUrl . '/dashboard/surveyNotesForm';
		
			$surveyFormHtml = "";
	 		foreach ($surveyResponses as $i => $surveyData) {
	 		if ($i == "departmentContact") {
	 			$division = $surveyData['division'];
	 			$department = $surveyData['department'];
	 			
	 			$surveyFormHtml .= "<h1 style='font-size:20px;'>" . trim(strip_tags($division)) . "<h1>";
	 			$surveyFormHtml .= "<h2 style='font-size:15px;'>" . trim(strip_tags($department)) . "</h2>";
	 			$surveyFormHtml .= "<br />";
	 			$surveyFormHtml .= "<div class='ui-accordion-sections'>";
	 			$surveyFormHtml .= "<strong>Department Contact</strong>";
	 			$surveyFormHtml .= "<br />";
	 			
	 			foreach ($surveyData as $j => $contact) {
		 			if (!is_array($contact)) {
		 				if ($j == "firstName") {
					 		$surveyFormHtml .= "<strong>First Name:</strong> ";
					 	}
					 	if ($j == "lastName") {
			 				$surveyFormHtml .= "<strong>Last Name:</strong> ";
			 			}
			 			if ($j == "jobTitle") {
			 				$surveyFormHtml .= "<strong>Job Title:</strong> ";
			 			}
			 			if ($j == "phoneNumber") {
			 				$surveyFormHtml .= "<strong>Phone Number:</strong> ";
			 			}
			 			if ($j == "emailAddress") {
			 				$surveyFormHtml .= "<strong>Email:</strong> ";
			 			}
			 			if ($j !== "contactID" && $j !== "division" && $j !== "department") {
			 				$surveyFormHtml .= trim(strip_tags($contact)) . "<br /><br />";	
			 			}
			 			if ($j == "contactID") {
			 				$contact = trim(strip_tags($contact));
			 				$surveyFormHtml .= "<input name='contactID' type='hidden' value='$contact' />";
			 			}	
		 			}
		 		
		 		}
	 		}
				 			 			 		
			if ($i == "surveyContacts") {
				
				$surveyFormHtml .= "<div class='ui-accordion-sections'>";
				$surveyFormHtml .= "<strong>Survey Contacts</strong>";
				
				$surveyFormHtml .= "<br />";
				
				$surveyContacts = array();
				$arraySize = count($surveyData);
				$contactNoteTrigger = $arraySize - 1; // used to make sure the notes field only gets rendered at the end of the loop cycle
				$f = 0;
				while ($f < $arraySize) {
					$value = $surveyData[$f];
					if ($value == 'First Name') {
						$surveyFormHtml .= "<strong>" . trim(strip_tags($value)) . ":</strong> ";
					}
					if ($value == 'Last Name') {
						$surveyFormHtml .= "<strong>" . trim(strip_tags($value)) . ":</strong> ";
					}
					if ($value == 'Phone') {
						$surveyFormHtml .= "<strong>" . trim(strip_tags($value)) . ":</strong>";
					}
					if ($value == 'Email') {
						$surveyFormHtml .= "<strong>" . trim(strip_tags($value)) . ":</strong>";
					}
					if ($value == 'Notes') {
						$surveyFormHtml .= "<strong>test" . trim(strip_tags($value)) . ":</strong>";
					}
					if ($value !== 'First Name' && $value!== 'Last Name' && $value !== 'Phone' && $value !== 'Email' && $value !== 'Notes') {
						if (is_array($value)) {
							$surveyFormHtml .= ""; // don't display Array
						} else {
							$surveyFormHtml .= trim(strip_tags($value)) . "<br /><br />"; 
						}
					}
					if ($f == $contactNoteTrigger) {  
						if (is_array($value)) {
							$surveyFormHtml .= "<strong>Contact Notes:</strong><br />";
							foreach ($value as $e => $contactNotes) {
								if ($e == "contactNotesID") {
									$surveyFormHtml .= "<input name='contactNotesID' type='hidden' value='$contactNotes' />";
								}
								if ($e == "contactNotes") {
									$contactNotes = trim(strip_tags($contactNotes));
									$surveyFormHtml .= "<textarea name='contactNotes' rows='3' cols='50' wrap='hard'>$contactNotes</textarea><br />";				
								}
							}
						} else {
							$surveyFormHtml .= "<strong>Contact Notes:</strong><br />";
							$surveyFormHtml .= "<textarea name='contactNotes' rows='3' cols='50' wrap='hard'></textarea><br />";	
						} 
					}
				++$f;
				}	
				$surveyFormHtml .= "</div>";			
			}

			$qCount = 1; // sets question count
	 		if ($i == "responses") {
	 			$surveyFormHtml .= "<div class='ui-accordion-sections'>";
	 			$surveyFormHtml .= "<strong>Survey Responses</strong>";
	 			//$surveyFormHtml .= "</div>";
	 			 				 			
	 			foreach ($surveyData as $count => $responseData) {
	 			
	 				if (is_array($responseData)) {
	 						 						 					
	 					foreach ($responseData as $k => $responses) {	
							
	 						if ($k !== "questionFieldType" && !is_array($responses)) {
								
	 							if ($k == "questionID") {
	 								$questionID = $responseData['questionID'];
	 								$surveyFormHtml .= "<input name='questionID[]' type='hidden' value='$questionID' /><br />";
	 							}
	 						
	 							// extract file extension from filename
								$fileExtension = str_replace(".", "", strrchr($responses, "."));
		
								// TODO: allowed file types..refactor to pull from database
								$fileTypes = array('pdf', 'docx', 'doc', 'txt', 'gif', 'jpg', 'jpeg', 'vsd', 'xls', 'xlsx', 'ppt', 'pptx');
																																													
								if ($k == "question") {
									$surveyFormHtml .= "<hr /><br />"; 
									$surveyFormHtml .= "<strong>$qCount .) Question:</strong><br />";
	 								$qCount++; // increments question count by 1
									$surveyFormHtml .= trim(strip_tags($responses)) . "<br /><br />";		 	
	 							}
	 							if ($k == "subQuestion" && !empty($responseData['response'])) { 
	 								$surveyFormHtml .= "<strong>Sub Question:</strong><br />";
	 								$surveyFormHtml .= trim(strip_tags($responses)) . "<br /><br />";		
	 							}
	 							if ($k == "subChoiceQuestion" && !empty($responseData['response'])) { 
	 								$surveyFormHtml .= "<strong>Sub Choice Question:</strong><br />";
	 								$surveyFormHtml .= trim(strip_tags($responses)) . "<br /><br />";		
	 							}	 								 							
	 							if ($k == "response" && !empty($responseData['response'])) { 
	 								 								
	 								$surveyFormHtml .= "<strong>Response:</strong><br />";
	 								// check if filename extension is in extension list TODO: refactor / don't need to check for file extentiosn
									if (in_array($fileExtension, $fileTypes, TRUE)) {
										$siteURL = base_url();
										$responses = trim(strip_tags($responses));
										$controller = "index.php/dashboard/forceDownload/$responses";
										$url = $siteURL . $controller;
										$surveyFormHtml .= "<a href='$url' target='_blank'>$responses</a><br /><br />"; // triggers download	
									} elseif ($responseData['response'] == "null") {
										$surveyFormHtml .= "-<br /><br />";
									} else {
										$surveyFormHtml .= trim(strip_tags($responses)) . "<br /><br />";	
									}
	 							} 
	 								 							
	 							// survey notes
	 							if ($k == "surveyNotesID" && $responses !== "") {
	 								$responses = trim(strip_tags($responses));
	 								$surveyFormHtml .= "<input name='surveyNotesID[]' type='hidden' value='$responses' /><br />";
	 							}
		 						if ($k == "deptSurveyNotes") {
		 							$responses = trim(strip_tags($responses));
		 							$surveyFormHtml .= "(Department Answer)<br />";
			 						$surveyFormHtml .= "<textarea name='deptSurveyNotes[]' rows='3' cols='50' wrap='hard'>$responses</textarea><br /><br />";
		 						}
		 						if ($k == "rmSurveyNotes") {
		 							$responses = trim(strip_tags($responses));
		 							$surveyFormHtml .= "(Records Management)<br />";
		 							$surveyFormHtml .= "<textarea name='rmSurveyNotes[]' rows='3' cols='50' wrap='hard'>$responses</textarea><br /><br />";
		 						}				
							}			
	 					}
	 				}
	 			}
	 			$surveyFormHtml .= "</div>";
	 		} 	
	 		$surveyFormHtml .= "</div>";
	 		$surveyFormHtml .= "<br /><br />"; 	

	 		
	 		}
	 } elseif (!empty($surveyResponses)) {
	 	$surveyFormHtml .= $surveyResponses; // returns no responses message ?
	 } 	
		
	// display save/update buttons
	 if (!empty($surveyResponses) && is_array($surveyResponses)) {  
		if (isset($responseData['update']) && $responseData['update'] == TRUE) { 
			$surveyFormHtml .= "<input name='submit' type='submit' value='Update Notes' onClick='return confirm(\"Are you sure you want to update this record?\")' /><br /><br />";
	 	} else { 
			$surveyFormHtml .= "<input name='submit' type='submit' value='Save Notes'  /><br /><br />";
		} 
	 } 	
	return $surveyFormHtml;		
	}
	
	/**
    * generates random string
    *
    * @access private
    * @return $randomString
    */
	private function genRandomString() {
		// http://www.bytemycode.com/snippets/snippet/579/
		$randomString = "";
    	$length = 10;
    	for ($i = 0; $i < $length; $i++) {
    		$randomString .= rand(0,1) ? chr(rand(48, 57)) : chr(rand(97, 122));
    	} 
        return $randomString;
	}
	
	/**
    * gets survey notes if any exist from database
    *
    * @access private
    * @param $departmentID
    * @return $surveyNotes
    */
	private function getSurveyNotes($questionID, $departmentID) {
		
		$surveyNotesArray = array();
		$deptSurveyNotesArray = array();
		$rmSurveyNotesArray = array();
		
		$this->db->select('*');
		$this->db->from('rm_surveyNotes');
		$this->db->where('departmentID', $departmentID);
		$this->db->where('questionID', $questionID);
		$idNotes = $this->db->get();	
		
		if ($idNotes->num_rows > 0) {
			foreach ($idNotes->result() as $notes) {
				$surveyNotesArray['surveyNotesID'] = $notes->surveyNotesID;
				$surveyNotesArray['questionID'] = $notes->questionID;
				$surveyNotesArray['deptSurveyNotes'] = $notes->deptSurveyNotes;
				$surveyNotesArray['rmSurveyNotes'] = $notes->rmSurveyNotes;
				$surveyNotesArray['update'] = TRUE; // used to render update button on form
			}

		} else {
			$surveyNotesArray['surveyNotesID'] = "";
			$surveyNotesArray['questionID'] = "";
			$surveyNotesArray['deptSurveyNotes'] = "";
			$surveyNotesArray['rmSurveyNotes'] = "";
		}
		return $surveyNotesArray;
	}
	
	/**
    * checks if any notes are currently in the database
    *
    * @access private
    * @param $departmentID
    * @return TRUE/FALSE
    */		 
	private function checkSurveyNotes($departmentID) {
		$this->db->select('departmentID');
		$this->db->from('rm_surveyNotes');
		$this->db->where('departmentID', $departmentID);
		$departmentNotes = $this->db->get();
		
		if ($departmentNotes->num_rows > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
    * gets survey notes if any exist from database
    *
    * @access private
    * @param $departmentID
    * @return $surveyContacts
    */
	private function getSurveyContacts($departmentID) {
		$surveyContacts = array();
				
		$this->db->select('departmentID, contactFieldID, contactResponse');
	 	$this->db->from('rm_surveyContactResponses');
	 	$this->db->where('departmentID', $departmentID);
 		$surveyContact = $this->db->get();
 		
 		if ($surveyContact->num_rows > 0) {
 			foreach ($surveyContact->result() as $surveyContactResults) {
 				// contact field labels			
 				$this->db->select('contactField');
 				$this->db->from('rm_surveyContactFields');
 				$this->db->where('contactFieldID', $surveyContactResults->contactFieldID);
 				$contactFields = $this->db->get();
 				if ($contactFields->num_rows() >0) {
 					foreach ($contactFields->result() as $contactFieldsResults) {
 						$surveyContacts[] = $contactFieldsResults->contactField;
 						$surveyContacts[] = $surveyContactResults->contactResponse;
 					}	
 				} 				
 			
 			}
 			
 			return $surveyContacts;
 		}
	}
	
	/**
    * gets department contact from database
    *
    * @access private
    * @param $departmentID
    * @return $departmentContact / $noResults
    */ 
	private function getDepartmentContact($departmentID) {
		$departmentContact = array();
		
		$this->db->select('contactID, firstName, lastName, jobTitle, phoneNumber, emailAddress');
	 	$this->db->from('rm_departmentContacts');
	 	$this->db->where('departmentID', $departmentID);
 		$contact = $this->db->get();
 		
 		if ($contact->num_rows() > 0) {
 			foreach ($contact->result() as $contactResult) {
 				$departmentContact['contactID'] = $contactResult->contactID;
 				$departmentContact['firstName'] = $contactResult->firstName;
 				$departmentContact['lastName'] = $contactResult->lastName;
 				$departmentContact['jobTitle'] = $contactResult->jobTitle;
 				$departmentContact['phoneNumber'] = $contactResult->phoneNumber;
 				$departmentContact['emailAddress'] = $contactResult->emailAddress;
 			}
 			
 			$this->db->select('departmentName, divisionID');
 			$this->db->from('rm_departments');
 			$this->db->where('departmentID', $departmentID);
 			$department = $this->db->get();
 			
 			if ($department->num_rows() > 0) {
 				foreach ($department->result() as $departmentResult) {
 					$this->db->select('divisionName');
 					$this->db->from('rm_divisions');
 					$this->db->where('divisionID', $departmentResult->divisionID);
 					$division = $this->db->get();
 					if ($division->num_rows() > 0) {
 						foreach ($division->result() as $divisionResult) {
 							$departmentContact['division'] = $divisionResult->divisionName; 
 						}
 					}
 					
 					$departmentContact['department'] = $departmentResult->departmentName;		
 				}
 			}
 			return $departmentContact;
 		} else {
 			$noResults = "<br />No surveys found for the selected department";
 			return $noResults;
 		}
	}
	
	/**
    * gets survey question responses from database
    *
    * @access private
    * @param $contactID
    * @return $responses / $noResponses
    */ 
	private function getResponses($contactID, $departmentID) {
		
		$this->db->select('surveyID, questionID, question, response');
		$this->db->from('rm_surveyQuestionResponses');
		$this->db->where('contactID', $contactID);
		$questionResponses = $this->db->get();
			
		$responses = array();
		
		$questionResponsesArray = array();
				
		if ($questionResponses->num_rows() > 0) {
			foreach ($questionResponses->result() as $questionResponseResults) {
								
				$questionID = $questionResponseResults->questionID;
				
				$fieldType = $this->getFieldType($questionID);
						
				$questionResponsesArray['questionFieldType'] = $fieldType;
				$questionResponsesArray['question'] = $questionResponseResults->question;
				
				if ($questionResponseResults->response == "") {
					$questionResponsesArray['response'] = "null";
				} else {
					$questionResponsesArray['response'] = $questionResponseResults->response;
				}
				
				$responses[] = $questionResponsesArray;
				
				// get sub questions if any
				$this->db->select('subQuestionID, subQuestion, subQuestion, response');
				$this->db->from('rm_surveySubQuestionResponses');
				$this->db->where('questionID', $questionResponseResults->questionID);
				$this->db->where('contactID', $contactID);
				$subQuestionResponses = $this->db->get();
				
				$subQuestionResponsesArray = array();
					
				if ($subQuestionResponses->num_rows() > 0) {
					foreach ($subQuestionResponses->result() as $subQuestionResponseResults) {
						
						$subQuestionResponsesArray['subQuestion'] = $subQuestionResponseResults->subQuestion;
						$subQuestionResponsesArray['response'] = $subQuestionResponseResults->response;
						
						$responses[] = $subQuestionResponsesArray;
						
						// get sub choice questions if any
						$this->db->select('subChoiceQuestionID, subChoiceQuestion, response');
						$this->db->from('rm_surveySubChoiceQuestionResponses');
						$this->db->where('subQuestionID', $subQuestionResponseResults->subQuestionID);
						$this->db->where('contactID', $contactID);
						$subChoiceQuestionResponses = $this->db->get();
						
						$subChoiceQuestionResponsesArray = array();
							
						if ($subChoiceQuestionResponses->num_rows > 0) {
							foreach ($subChoiceQuestionResponses->result() as $subChoiceQuestionResponseResults) {
																
								$subChoiceQuestionResponsesArray['subChoiceQuestion'] = $subChoiceQuestionResponseResults->subChoiceQuestion;
								$subChoiceQuestionResponsesArray['response'] = $subChoiceQuestionResponseResults->response;
								
								$responses[] = $subChoiceQuestionResponsesArray;
							}
						}
					}
				}
				
				$surveyNotesArray = $this->getSurveyNotes($questionID, $departmentID);	
				$surveyNotesArray['questionID'] = $questionID;
				$responses[] = $surveyNotesArray;
			}
		return $responses;
		} else {
			$noResponses = "No responses found for the selected department";
 			return $noResponses;
		}
			
	}
	
	/**
    * gets field types from database  **USED BUT NOT BE NEEDED**
    *
    * @access private
    * @param $questionID
    * @return $fieldType
    */ 
	private function getFieldType($questionID) {
		// get field type id
		$this->db->select('fieldTypeID');
		$this->db->from('rm_surveyQuestions');
		$this->db->where('questionID', $questionID);
		$questionfieldTypeID = $this->db->get();
		if ($questionfieldTypeID->num_rows() > 0) {
			$fieldTypeIdResult = $questionfieldTypeID->row();
			$fieldTypeID = $fieldTypeIdResult->fieldTypeID;
		
			// get field type 
			$this->db->select('fieldType');
			$this->db->from('rm_fieldTypes');
			$this->db->where('fieldTypeID', $fieldTypeID);
			$questionfieldType = $this->db->get();
			if ($questionfieldType->num_rows() > 0) {
				$fieldTypeResult = $questionfieldType->row();
				$fieldType = $fieldTypeResult->fieldType;
			
				return $fieldType;
			}
		}
	}
	
	/**
    * invokes saveNotesQuery()
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
	public function saveNotes($_POST) {
		$this->saveNotesQuery($_POST);	
	}
	
	/**
    * saves survey response notes into database
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
	private function saveNotesQuery($_POST) {
		
		$surveyNotes = array();
		$surveyContactNotes = array();
			
		$arraySize = count($_POST['questionID']);
		$i = 0;
		while ($i < $arraySize) {
			$surveyNotes['departmentID'] = $_POST['departmentID'];
			$surveyNotes['contactID'] = $_POST['contactID'];
			$surveyNotes['questionID'] = $_POST['questionID'][$i];
			$surveyNotes['deptSurveyNotes'] = $_POST['deptSurveyNotes'][$i];
			$surveyNotes['rmSurveyNotes'] = $_POST['rmSurveyNotes'][$i];
			$this->db->insert('rm_surveyNotes', $surveyNotes);
		++$i;
		}
		
		$surveyContactNotes['contactID'] = $_POST['contactID'];
		$surveyContactNotes['contactNotes'] = $_POST['contactNotes'];
		$this->db->insert('rm_surveyContactNotes', $surveyContactNotes);
	}

	/**
    * invokes updateNotesQuery
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
	public function updateNotes($_POST) {
		$this->updateNotesQuery($_POST);
	}
	
	/**
    * updates survey response notes
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
	private function updateNotesQuery($_POST) {
		
		$updatedSurveyNotes = array();
		$updateSurveyContactNotes = array();
		
		$updateSurveyContactNotes['contactNotes'] = $_POST['contactNotes'];
		$contactNotesID = $_POST['contactNotesID'];
		$this->db->where('contactNotesID', $contactNotesID);
		$this->db->update('rm_surveyContactNotes', $updateSurveyContactNotes);
				
		$arraySize = count($_POST['surveyNotesID']);
		$i = 0;
		while ($i < $arraySize) {	
			$updatedSurveyNotes['deptSurveyNotes'] = $_POST['deptSurveyNotes'][$i];
			$updatedSurveyNotes['rmSurveyNotes'] = $_POST['rmSurveyNotes'][$i];
			$surveyNotesID = $_POST['surveyNotesID'][$i];
			$this->db->where('surveyNotesID', $surveyNotesID);
			$this->db->update('rm_surveyNotes', $updatedSurveyNotes);
		++$i;	
		}
	}

	/**
    * invokes getRecordTypeQuery()
    *
    * @access public
    * @param $departmentID
    * @return $recordTypeResults
    */ 
	public function getRecordType($recordInformationID) {
		$recordTypeResults = $this->getRecordTypeQuery($recordInformationID);
		return $recordTypeResults;
	}
	
	/**
    * gets existing record type records from database based on departmentID
    *
    * @access private
    * @param $departmentID
    * @return $recordTypeResults
    */ 
	private function getRecordTypeQuery($recordInformationID) {
		
		$this->db->select('*');
	 	$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->where('recordInformationID', $recordInformationID);
 		$recordInformation = $this->db->get();
		
		$this->db->select('*');
	 	$this->db->from('rm_recordTypeFormatAndLocation');
	 	$this->db->where('recordInformationID', $recordInformationID);
 		$formatAndLocation = $this->db->get();
	
		$this->db->select('*');
	 	$this->db->from('rm_recordTypeManagement');
	 	$this->db->where('recordInformationID', $recordInformationID);
 		$management = $this->db->get();
		
		$recordTypeResults = array();
				
		if ($recordInformation->num_rows > 0) {
			foreach ($recordInformation->result() as $recordInformationResults) {
				$recordTypeResults['recordInformationID'] = $recordInformationResults->recordInformationID;
				$recordTypeResults['recordTypeDepartment'] = $recordInformationResults->recordTypeDepartment;
				$recordTypeResults['recordName'] = $recordInformationResults->recordName;
				$recordTypeResults['recordDescription'] = $recordInformationResults->recordDescription;
				$recordTypeResults['recordCategory'] = $recordInformationResults->recordCategory;
				$recordTypeResults['recordNotesDeptAnswer'] = $recordInformationResults->recordNotesDeptAnswer;
				$recordTypeResults['recordNotesRmNotes'] = $recordInformationResults->recordNotesRmNotes;
				
			}	
		}
		
		if ($formatAndLocation->num_rows > 0) {
			foreach ($formatAndLocation->result() as $formatAndLocationResults) {
				$systemChoices = explode(",", $formatAndLocationResults->system); 
				$location = explode(",", $formatAndLocationResults->location);
				$recordLocation = explode(",", $formatAndLocationResults->recordLocation);
								
				$recordTypeResults['formatAndLocationID'] = $formatAndLocationResults->formatAndLocationID;
				$recordTypeResults['electronicRecord'] = $formatAndLocationResults->electronicRecord;
				$recordTypeResults['system'] = $systemChoices;
				$recordTypeResults['otherText'] = $formatAndLocationResults->otherText;
				$recordTypeResults['paperVersion'] = $formatAndLocationResults->paperVersion;
				$recordTypeResults['location'] = $location;
				$recordTypeResults['otherBuilding'] = $formatAndLocationResults->otherBuilding;
				$recordTypeResults['otherStorage'] = $formatAndLocationResults->otherStorage;
				$recordTypeResults['finalRecordExist'] = $formatAndLocationResults->finalRecordExist;
				$recordTypeResults['backupMedia'] = $formatAndLocationResults->backupMedia;
				$recordTypeResults['recordLocation'] = $recordLocation;
				$recordTypeResults['otherRecordLocation'] = $formatAndLocationResults->otherRecordLocation;
				$recordTypeResults['fileFormat'] = $formatAndLocationResults->fileFormat;
				$recordTypeResults['formatAndLocationDeptAnswer'] = $formatAndLocationResults->formatAndLocationDeptAnswer;
				$recordTypeResults['formatAndLocationRmNotes'] = $formatAndLocationResults->formatAndLocationRmNotes; 
				
			}
		} else {
			$recordTypeResults['newFormatAndLocation'] = "insertMode";  // used to generate an insert form if no record exists.
		}
		
		if ($management->num_rows > 0) {
			foreach ($management->result() as $managementResults) {
				$recordTypeResults['managementID'] = $managementResults->managementID;	
				$recordTypeResults['accessAndUseDeptAnswer'] = $managementResults->accessAndUseDeptAnswer;
				$recordTypeResults['accessAndUseRmNotes'] = $managementResults->accessAndUseRmNotes;
				$recordTypeResults['yearsActive'] = $managementResults->yearsActive;
				$recordTypeResults['yearsAvailable'] = $managementResults->yearsAvailable;
				$recordTypeResults['activePeriodDeptAnswer'] = $managementResults->activePeriodDeptAnswer;
				$recordTypeResults['activePeriodRmNotes'] = $managementResults->activePeriodRmNotes;
				$recordTypeResults['yearsKept'] = $managementResults->yearsKept;
				$recordTypeResults['retentionPeriodDeptAnswer'] = $managementResults->retentionPeriodDeptAnswer;
				$recordTypeResults['retentionPeriodRmNotes'] = $managementResults->retentionPeriodRmNotes;
				
				// query division and departments
				$recordTypeResults['managementDivisionID'] = $managementResults->managementDivisionID;
				$recordTypeResults['managementDepartmentID'] = $managementResults->managementDepartmentID;
				
				$recordTypeResults['custodianDeptAnswer'] = $managementResults->custodianDeptAnswer;
				$recordTypeResults['custodianRmNotes'] = $managementResults->custodianRmNotes;
				$recordTypeResults['legislationGovernRecords'] = $managementResults->legislationGovernRecords;
				$recordTypeResults['legislation'] = $managementResults->legislation;
				$recordTypeResults['legislationHowLong'] = $managementResults->legislationHowLong;
				$recordTypeResults['legalRequirmentsDeptAnswer'] = $managementResults->legalRequirmentsDeptAnswer;
				$recordTypeResults['legalRequirmentsRmNotes'] = $managementResults->legalRequirmentsRmNotes;
				$recordTypeResults['standardsAndBestPracticesDeptAnswer'] = $managementResults->standardsAndBestPracticesDeptAnswer;
				$recordTypeResults['standardsAndBestPracticesRmNotes'] = $managementResults->standardsAndBestPracticesRmNotes;
				$recordTypeResults['destroyRecords'] = $managementResults->destroyRecords;
				$recordTypeResults['howOftenDestruction'] = $managementResults->howOftenDestruction;
				$recordTypeResults['howAreRecordsDestroyed'] = $managementResults->howAreRecordsDestroyed;
				$recordTypeResults['destructionDeptAnswer'] = $managementResults->destructionDeptAnswer;
				$recordTypeResults['destructionRmNotes'] = $managementResults->destructionRmNotes;
				$recordTypeResults['transferToArchives'] = $managementResults->transferToArchives;
				$recordTypeResults['howOftenArchive'] = $managementResults->howOftenArchive;
				$recordTypeResults['transferToArchivesDeptAnswer'] = $managementResults->transferToArchivesDeptAnswer;
				$recordTypeResults['transferToArchivesRmNotes'] = $managementResults->transferToArchivesRmNotes;
				$recordTypeResults['vitalRecords'] = $managementResults->vitalRecords;
				$recordTypeResults['manageVitalRecords'] = $managementResults->manageVitalRecords;
				$recordTypeResults['vitalRecordsDeptAnswer'] = $managementResults->vitalRecordsDeptAnswer;
				$recordTypeResults['vitalRecordsRmNotes'] = $managementResults->vitalRecordsRmNotes;
				$recordTypeResults['sensitiveInformation'] = $managementResults->sensitiveInformation;
				$recordTypeResults['describeInformation'] = $managementResults->describeInformation;
				$recordTypeResults['sensitiveInformationDeptAnswer'] =$managementResults->sensitiveInformationDeptAnswer;
				$recordTypeResults['sensitiveInformationRmNotes'] = $managementResults->sensitiveInformationRmNotes;
				$recordTypeResults['secureRecords'] = $managementResults->secureRecords;
				$recordTypeResults['describeSecurityRecords'] = $managementResults->describeSecurityRecords;
				$recordTypeResults['securityDeptAnswer'] = $managementResults->securityDeptAnswer;
				$recordTypeResults['securityRmNotes'] = $managementResults->securityRmNotes;
				$recordTypeResults['duplication'] = $managementResults->duplication;
				
				// place division ids into array
				$divisionIDs = explode(",", $managementResults->duplicationDivisionID); 
				$recordTypeResults['duplicationDivisionID'] = $divisionIDs;
				
				// place department ids into array
				$departmentIDs = explode(",", $managementResults->duplicationDepartmentID); 
				$recordTypeResults['duplicationDepartmentID'] = $departmentIDs;
				
				// query to get division and department
				$recordTypeResults['masterCopyDivisionID'] = $managementResults->masterCopyDivisionID;
				$recordTypeResults['masterCopyDepartmentID'] = $managementResults->masterCopyDepartmentID;
				
				$recordTypeResults['duplicationDeptAnswer'] = $managementResults->duplicationDeptAnswer;
				$recordTypeResults['duplicationRmNotes'] = $managementResults->duplicationRmNotes;
				
			}
		} else {
			$recordTypeResults['newManagement'] = "insertMode"; // used to generate an insert form if no record exists.
		}
		return $recordTypeResults;
	}
			
	/**
    * invokes updateRecordTypeRecordInformationQuery()
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
	public function updateRecordTypeRecordInformation($_POST) {
		$this->updateRecordTypeRecordInformationQuery($_POST);
	}
	
	/**
    * updates record Type "record Information"
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
	private function updateRecordTypeRecordInformationQuery($_POST) {
		
		$recordInformation = array();
		$recordInformation['recordName'] = $_POST['recordName'];
		$recordInformation['recordDescription']	= $_POST['recordDescription'];
		$recordInformation['recordCategory'] = $_POST['recordCategory'];
		$recordInformation['recordNotesDeptAnswer'] = $_POST['recordNotesDeptAnswer'];	
		$recordInformation['recordNotesRmNotes'] = $_POST['recordNotesRmNotes'];
		$recordInformationID = $_POST['recordInformationID'];
		
		$this->db->where('recordInformationID', $recordInformationID);
		$this->db->update('rm_recordTypeRecordInformation', $recordInformation);
	}
	
	/**
    * invokes updateFormatAndLocationQuery()
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
	public function updateFormatAndLocation($_POST) {
		$this->updateFormatAndLocationQuery($_POST);
	}
	
	/**
    * updates record Type "format and location"
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
    private function updateFormatAndLocationQuery($_POST) {
    	
    	// turn posted arrays (checkbox options) into lists
		$system = implode(",", $_POST['system']);
		$location = implode(",", $_POST['location']);
		$recordLocation = implode(",", $_POST['recordLocation']);
		
		$formatAndLocation = array(
								'recordTypeDepartment'=>$this->input->post('recordTypeDepartment'),
								'electronicRecord'=>$this->input->post('electronicRecord'),
								'system'=>$system,
								'otherText'=>$this->input->post('otherText'),										
								'paperVersion'=>$this->input->post('paperVersion'),
								'location'=>$location,
								'otherBuilding'=>$this->input->post('otherBuilding'),
								'otherStorage'=>$this->input->post('otherStorage'),
								'finalRecordExist'=>$this->input->post('finalRecordExist'),
								'backupMedia'=>$this->input->post('backupMedia'),
								'recordLocation'=>$recordLocation,
								'otherRecordLocation'=>$this->input->post('otherRecordLocation'),
								'fileFormat'=>$this->input->post('fileFormat'),
								'formatAndLocationDeptAnswer'=>$this->input->post('formatAndLocationDeptAnswer'),
								'formatAndLocationRmNotes'=>$this->input->post('formatAndLocationRmNotes')
								);
		
		$formatAndLocationID = $_POST['formatAndLocationID'];					
		$this->db->where('formatAndLocationID', $formatAndLocationID);
		$this->db->update('rm_recordTypeFormatAndLocation', $formatAndLocation);
    
    } 
    
    /**
    * invokes updateManagementQuery()
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
    public function updateManagement($_POST) {
    	$this->updateManagementQuery($_POST);
    }
    
    /**
    * updates record Type "management"
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
    private function updateManagementQuery($_POST) {
    	
    	$duplicationDepartmentID = implode(",", $_POST['duplicationDepartmentID']);
    	$duplicationDivisionID = implode(",", $_POST['duplicationDivisionID']);
    	$management = array(
						'recordTypeDepartment'=>$this->input->post('recordTypeDepartment'),
						'accessAndUseDeptAnswer'=>$this->input->post('accessAndUseDeptAnswer'),
						'accessAndUseRmNotes'=>$this->input->post('accessAndUseRmNotes'),
						'yearsActive'=>$this->input->post('yearsActive'),										
						'yearsAvailable'=>$this->input->post('yearsAvailable'),
						'activePeriodDeptAnswer'=>$this->input->post('activePeriodDeptAnswer'),
						'activePeriodRmNotes'=>$this->input->post('activePeriodRmNotes'),
						'yearsKept'=>$this->input->post('yearsKept'),
						'retentionPeriodDeptAnswer'=>$this->input->post('retentionPeriodDeptAnswer'),
						'retentionPeriodRmNotes'=>$this->input->post('retentionPeriodRmNotes'),
						'managementDivisionID'=>$this->input->post('managementDivisionID'),
						'managementDepartmentID'=>$this->input->post('managementDepartmentID'),
						'custodianDeptAnswer'=>$this->input->post('custodianDeptAnswer'),
						'custodianRmNotes'=>$this->input->post('custodianRmNotes'),
						'legislationGovernRecords'=>$this->input->post('legislationGovernRecords'),
						'legislation'=>$this->input->post('legislation'),
						'legislationHowLong'=>$this->input->post('legislationHowLong'),
						'legalRequirmentsDeptAnswer'=>$this->input->post('legalRequirmentsDeptAnswer'),
						'legalRequirmentsRmNotes'=>$this->input->post('legalRequirmentsRmNotes'),
						'standardsAndBestPracticesDeptAnswer'=>$this->input->post('standardsAndBestPracticesDeptAnswer'),
						'standardsAndBestPracticesRmNotes'=>$this->input->post('standardsAndBestPracticesRmNotes'),
						'destroyRecords'=>$this->input->post('destroyRecords'),
						'howOftenDestruction'=>$this->input->post('howOftenDestruction'),
						'howAreRecordsDestroyed'=>$this->input->post('howAreRecordsDestroyed'),
						'destructionDeptAnswer'=>$this->input->post('destructionDeptAnswer'),
						'destructionRmNotes'=>$this->input->post('destructionRmNotes'),
						'transferToArchives'=>$this->input->post('transferToArchives'),
						'howOftenArchive'=>$this->input->post('howOftenArchive'),
						'transferToArchivesDeptAnswer'=>$this->input->post('transferToArchivesDeptAnswer'),
						'transferToArchivesRmNotes'=>$this->input->post('transferToArchivesRmNotes'),
						'vitalRecords'=>$this->input->post('vitalRecords'),
						'manageVitalRecords'=>$this->input->post('manageVitalRecords'),
						'vitalRecordsDeptAnswer'=>$this->input->post('vitalRecordsDeptAnswer'),
						'vitalRecordsRmNotes'=>$this->input->post('vitalRecordsRmNotes'),
						'sensitiveInformation'=>$this->input->post('sensitiveInformation'),
						'describeInformation'=>$this->input->post('describeInformation'),
						'sensitiveInformationDeptAnswer'=>$this->input->post('sensitiveInformationDeptAnswer'),
						'sensitiveInformationRmNotes'=>$this->input->post('sensitiveInformationRmNotes'),
						'secureRecords'=>$this->input->post('secureRecords'),
						'describeSecurityRecords'=>$this->input->post('describeSecurityRecords'),
						'securityDeptAnswer'=>$this->input->post('securityDeptAnswer'),
						'securityRmNotes'=>$this->input->post('securityRmNotes'),
						'duplication'=>$this->input->post('duplication'),
						'duplicationDivisionID'=>$duplicationDivisionID,
    					'duplicationDepartmentID'=>$duplicationDepartmentID,
						'masterCopyDivisionID'=>$this->input->post('masterCopyDivisionID'),
    					'masterCopyDepartmentID'=>$this->input->post('masterCopyDepartmentID'),
    					'duplicationDeptAnswer'=>$this->input->post('duplicationDeptAnswer'),
						'duplicationRmNotes'=>$this->input->post('duplicationRmNotes'),
					);
    	
    	$managementID = $_POST['managementID'];					
		$this->db->where('managementID', $managementID);
		$this->db->update('rm_recordTypeManagement', $management);
    }
		
	/**
    * invokes authenticateQuery()
    *
    * @access public
    * @param $_POST
    * @return $auth TRUE/FALSE
    */ 
	public function authenticate($_POST) {
		$auth = $this->authenticateQuery($_POST);
		return $auth;
	}
	
	/**
    * authenticates admin user
    *
    * @access private
    * @param $_POST
    * @return TRUE / FALSE
    */ 
	private function authenticateQuery($_POST) {
				
		$username = $_POST['uname'];
		$passcode = $_POST['pcode'];
		
		// hash password TODO: build method to allow admin to reset password
		$this->load->library('encrypt');
		$passcodeHash = $this->encrypt->sha1($passcode);
				
		$this->db->select('username, passcode');
	 	$this->db->from('rm_users');
	 	$this->db->where('username', $username);
	 	$this->db->where('passcode', $passcodeHash);
 		$authQuery = $this->db->get();
 		
 		if ($authQuery->num_rows == 1) {
 			return TRUE;
 		} else {
 			return FALSE;
 		}
	}
	
	/**
    * invokes saveRecordTypeRecordInformationQuery()
    *
    * @access public
    * @param $recordInformation
    * @return $recordInformationID
    */
	public function saveRecordTypeRecordInformation($recordInformation) {
		$recordInformationID = $this->saveRecordTypeRecordInformationQuery($recordInformation);
		return $recordInformationID;
	}	
	
	/**
    * saves record type information to database
    *
    * @access private
    * @param $recordInformation
    * @return $recordInformationID
    */
	private function saveRecordTypeRecordInformationQuery($recordInformation) {
		$this->db->insert('rm_recordTypeRecordInformation', $recordInformation);
		$this->db->select_max('recordInformationID');
		$recordInformationIDquery = $this->db->get('rm_recordTypeRecordInformation');						
		if ($recordInformationIDquery->num_rows() > 0) {
	   		$result = $recordInformationIDquery->row();
			$recordInformationID = $result->recordInformationID; 
			return $recordInformationID; 	
		}				
	} 	
	
	/** TODO: refactor...
    * invokes saveRecordTypeFormatAndLocationQuery()
    *
    * @access public
    * @param $formatAndLocation
    * @return void
    */
	public function saveRecordTypeFormatAndLocation($formatAndLocation) {
		$this->saveRecordTypeFormatAndLocationQuery($formatAndLocation);
	}	
	
	/**
    * saves format and location information to database
    *
    * @access private
    * @param $formatAndLocation
    * @return void
    */
	private function saveRecordTypeFormatAndLocationQuery($formatAndLocation) {
		$this->db->insert('rm_recordTypeFormatAndLocation', $formatAndLocation);
	} 	
	
	/**
    * invokes saveRecordTypeManagementQuery()
    *
    * @access public
    * @param $management
    * @return void
    */
	public function saveRecordTypeManagement($management) {
		$this->saveRecordTypeManagementQuery($management);
	}
	
	/**
    * saves management information to database
    *
    * @access private
    * @param $management
    * @return void
    */
	private function saveRecordTypeManagementQuery($management) {
		$this->db->insert('rm_recordTypeManagement', $management);
	} 
}
?>