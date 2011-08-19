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


class SurveyModel extends CI_Model 
{

	public function __construct() {
 		parent::__construct();
		
 		$this->devEmail = $this->config->item('devEmail');
 		$this->uploadDir = $this->config->item('uploadDirectory');
	}
			
	/**
    * invokes getSurveyNameQuery()
    *
    * @access public
    * @param $surveyID
    * @return $results / surveyName 
    */
	public function getSurveyName($surveyID) {
		$result = $this->getSurveyNameQuery($surveyID);
 		return $result;
	}
	
	/**
    * gets survey name
    *
    * @access private
    * @param $surveyID
    * @return $surveyName / surveyName 
    */
	private function getSurveyNameQuery($surveyID) {
	 	$this->db->select('surveyName');
	 	$this->db->where('surveyID', $surveyID);
	 	$this->db->from('rm_surveys');
	 	$query = $this->db->get();
	 	$surveyName = "";
	 	if ($query->num_rows() > 0) {		
	 		 foreach ($query->result() as $results) {
			 	$surveyName .= $results->surveyName;
			 }
			 return $surveyName; 
	 	} else {
	 		return "No Surveys available";
	 	}	
	 }
	 
	/**
    * invokes getDepartmentsQuery()
    *
    * @access public
    * @param $divisionID
    * @return void 
    */
	 public function getDepartments($divisionID) {
	 	$this->getDepartmentsQuery($divisionID);
	 }
	 
	/**
    * gets departments
    *
    * @access private
    * @param $divisionID
    * @return echo's JSON package containing departments / used by jQuery 
    */
	 private function getDepartmentsQuery($divisionID) {
	 	
	 	$this->db->select('departmentID, departmentName');
		$this->db->where('divisionID', $divisionID);
		$this->db->from('rm_departments');
		$query = $this->db->get();
		$departments = array();
		if ($query->num_rows() > 0) {		
	
			foreach ($query->result() as $results) {
				if ($results->departmentName !== "All Departments") { // added f.r.  6.8.09 - stakeholder no longer wants all departments option
					$departments[] = '{' . 'departmentID: ' . $results->departmentID . ', ' . 'departmentName: ' . '"' . $results->departmentName . '"' . '}';
				}
			}
			
			echo '[' . implode(',', $departments) . ']';
				
		} else {
			//send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: no departments found - getDepartmentsQuery()');
			echo "no departments found";
		}
	 }
		 
	/**
    * invokes saveSurveyResponseQuery
    *
    * @access public
    * @param $_POST, $_FILES
    * @return void 
    */
	public function saveSurveyResponses($_POST, $_FILES) {
		$this->saveSurveyResponsesQuery($_POST, $_FILES);
	}
	
	/**
    * saves survey responses to database
    *
    * @access private
    * @param $_POST, $_FILES
    * @return void 
    */
	private function saveSurveyResponsesQuery($_POST, $_FILES) {
		
		// pull department contact values from $_POST array and place into $departmentContact array 
		$departmentContact = array('firstName'=>trim(strip_tags($_POST['firstName'])),
								'lastName'=>trim(strip_tags($_POST['lastName'])),
								'jobTitle'=>trim(strip_tags($_POST['jobTitle'])),
								'departmentID'=>trim(strip_tags($_POST['departmentID'])),
								'phoneNumber'=>trim(strip_tags($_POST['phoneNumber'])),
								'emailAddress'=>trim(strip_tags($_POST['emailAddress'])),
								'submitDate'=>trim(strip_tags($_POST['submitDate']))									
								); 
		// insert $departmentContact array into database 
		$this->db->insert('rm_departmentContacts', $departmentContact);
				
		// pull newly created contactID from database.  It will be used to give the questions a contact.						
		$this->db->select_max('contactID');
		$maxID = $this->db->get('rm_departmentContacts');						
		if ($maxID->num_rows() > 0) {
	   		$result = $maxID->row();
			$contactID = $result->contactID;  						
			
			// surveyID will allow us to pull questions based on the survey they belong to.
			$surveyID = trim(strip_tags($_POST['surveyID']));
			$departmentID = trim(strip_tags($_POST['departmentID']));
		
			// inserts survey contacts into database
			$surveyContact = array();
			foreach ($_POST['surveyContacts'] as $contactFieldID =>$surveyContacts) {
				$surveyContact['surveyID'] = $surveyID;
				$surveyContact['departmentID'] = $departmentID;
				$surveyContact['contactFieldID'] = $contactFieldID;
				$surveyContact['contactResponse'] = $surveyContacts;
				if (!empty($surveyContact['contactResponse'])) {
					//insert $surveyContact array into database
					$this->db->insert('rm_surveyContactResponses', $surveyContact);
				}
			}
		
			// get survey questions from database
			$getSurveyQuestionsSql = "SELECT questionID, question " .
									"FROM rm_surveyQuestions " .
									"WHERE surveyID = ? ";
			$getSurveyQuestionsQuery = $this->db->query($getSurveyQuestionsSql, array($surveyID));
			
			// array will be used to "package" questionID, question, contactID, surveyID, and response and insert into database 
			$surveyQuestionResponses = array();	
			
			// parse questions out of query object and place into $surveyQuestionResponses array
			foreach ($getSurveyQuestionsQuery->result() as $questions) {
				$surveyQuestionResponses['questionID'] = $questions->questionID;
				$surveyQuestionResponses['question'] = $questions->question;
									
				// get responses from post array and match to question from database
				foreach ($_POST['question'] as $i => $responses) {
					if (!empty($responses) && $i == $questions->questionID) { 
						$surveyQuestionResponses['response'] = $responses;
					}		
				}
			
			// append contactID and surveyID from previous queries to array	
			$surveyQuestionResponses['contactID'] = $contactID;
			$surveyQuestionResponses['surveyID'] = $surveyID;
			
			// insert array values into database 
			$this->db->insert('rm_surveyQuestionResponses', $surveyQuestionResponses);
			
			// clear array after values are inserted into database
			$surveyQuestionResponses['questionID'] = "";
			$surveyQuestionResponses['question'] = "";
			$surveyQuestionResponses['response'] = "";
			}
			
			// ceate two arrays to "package" questionID, subQuestionID, subQuestion and response
			$surveySubQuestionResponsesOuter = array();
			$surveySubQuestionResponsesInner = array();
			
			// parse out the results from the query object generated by the $getSurveyQuestionsQuery
			foreach ($getSurveyQuestionsQuery->result() as $questions) {
				// get survey sub questions from database based on questionID
				$getSurveySubQuestionsQuery = $this->db->get_where('rm_surveySubQuestions', array('questionID'=>$questions->questionID));
				
				if ($getSurveySubQuestionsQuery->num_rows() > 0) {
									
				// parse out sub question results from query object
				foreach ($getSurveySubQuestionsQuery->result() as $subResponses) {
					
					// place query results into array
					$surveySubQuestionResponsesOuter['questionID'] = $subResponses->questionID;
					$surveySubQuestionResponsesOuter['subQuestionID'] = $subResponses->subQuestionID;
					$surveySubQuestionResponsesOuter['subQuestion'] = $subResponses->subQuestion;
										
					// get sub responses from post array and match to sub question from database 
					foreach ($_POST['subQuestion'] as $j => $subQuestionResponses) {
						if (!empty($subQuestionResponses) && $j == $subResponses->subQuestionID) {
							
							// if question has more than one sub question (question type 3) package up the values and insert into database 	
							if ($questions->questionID == $subResponses->questionID) { 
								$surveySubQuestionResponsesInner['questionID'] = $subResponses->questionID;
								$surveySubQuestionResponsesInner['subQuestionID'] = $subResponses->subQuestionID;
								$surveySubQuestionResponsesInner['subQuestion'] = $subResponses->subQuestion;
								$surveySubQuestionResponsesInner['response'] = $subQuestionResponses;
								
								$surveySubQuestionResponsesInner['surveyID'] = $surveyID;
								$surveySubQuestionResponsesInner['contactID'] = $contactID;
								
								// insert array values into database
								$this->db->insert('rm_surveySubQuestionResponses', $surveySubQuestionResponsesInner);
							} 
								// place response into array
								$surveySubQuestionResponsesOuter['response'] = $subQuestionResponses;
						}
					}
					
					// if the array has duplicate values, clear it
					if (!empty($surveySubQuestionResponsesOuter) && !empty($surveySubQuestionResponsesInner)) {
						if ($surveySubQuestionResponsesOuter['subQuestionID'] == $surveySubQuestionResponsesInner['subQuestionID']) {
							$surveySubQuestionResponsesOuter['questionID'] = "";
							$surveySubQuestionResponsesOuter['subQuestionID'] = "";
							$surveySubQuestionResponsesOuter['subQuestion'] = "";
							$surveySubQuestionResponsesOuter['response'] = "";
						} 
					}
					
				}
											
				
				// if array package is empty, don't insert into database
				if ($surveySubQuestionResponsesOuter['questionID'] !== "" || $surveySubQuestionResponsesOuter['subQuestionID'] !== "" || $surveySubQuestionResponsesOuter['subQuestion'] !== "" || $surveySubQuestionResponsesOuter['response'] !== "") {
					$surveySubQuestionResponsesOuter['surveyID'] = $surveyID;
					$surveySubQuestionResponsesOuter['contactID'] = $contactID;
					// insert array values into database
					$this->db->insert('rm_surveySubQuestionResponses', $surveySubQuestionResponsesOuter);
				}
				// clear array after values inserted into database
				$surveySubQuestionResponsesOuter['surveyID'] = "";
				$surveySubQuestionResponsesOuter['questionID'] = "";
				$surveySubQuestionResponsesOuter['subQuestionID'] = "";
				$surveySubQuestionResponsesOuter['subQuestion'] = "";
				$surveySubQuestionResponsesOuter['response'] = "";
				
				} // closes if statement > 0 records
			}
			
			// create array to "package" subQuestionID, subChoiceQuestionID, subChoiceQuestion, and response
			$surveySubChoiceQuestionResponses = array();
			
			// parse value questionID out of query object
			foreach ($getSurveyQuestionsQuery->result() as $questions) {
				
				// get subQuestionID's based on questionID
				$getSurveySubQuestionsQuery = $this->db->get_where('rm_surveySubQuestions', array('questionID'=>$questions->questionID));
				
				// parse results out of query object
				foreach ($getSurveySubQuestionsQuery->result() as $subResponses) {
					
					// get sub choice questions based on subQuestionID
					$getSurveySubChoiceQuestonsQuery = $this->db->get_where('rm_surveySubChoiceQuestions', array('subQuestionID'=>$subResponses->subQuestionID));
					
					// parse results out of query object
					foreach ($getSurveySubChoiceQuestonsQuery->result() as $subChoices) {
							
						// place values into array
						$surveySubChoiceQuestionResponses['subQuestionID'] = $subResponses->subQuestionID;
						$surveySubChoiceQuestionResponses['subChoiceQuestionID'] = $subChoices->subChoiceQuestionID;
						$surveySubChoiceQuestionResponses['subChoiceQuestion'] = $subChoices->subChoiceQuestion;
									
							// get sub choice question responses from post array and match to sub choice questions
							foreach ($_POST['subChoiceQuestion'] as $k => $subChoiceResponses) {
								if (!empty($subChoiceResponses) && $k == $subResponses->subQuestionID) {
									$surveySubChoiceQuestionResponses['response'] = $subChoiceResponses;	
								}		
							}
					
					$surveySubChoiceQuestionResponses['surveyID'] = $surveyID;
					$surveySubChoiceQuestionResponses['contactID'] = $contactID;
					
					// insert array values into database
					$this->db->insert('rm_surveySubChoiceQuestionResponses', $surveySubChoiceQuestionResponses);
					
					// clear array values after database insertion
					$surveySubChoiceQuestionResponses['surveyID'] = "";
					$surveySubChoiceQuestionResponses['subQuestionID'] = "";
					$surveySubChoiceQuestionResponses['subChoiceQuestionID'] = "";
					$surveySubChoiceQuestionResponses['subChoiceQuestion'] = "";
					$surveySubChoiceQuestionResponses['response'] = "";
					}
				} 
			} 
			
			// handle file uploads from survey form 
			$this->doUpload($_FILES, $contactID);
					
		} else {
			//send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: key not retrieved successfully. unable to save survey responses');
			echo "database error: key not retrieved successfully. unable to save survey responses";
		}
	} // closes method
	
	
	/**
    * handles survey file uploads
    *
    * @access private
    * @param $_FILES
    * @param $contactID
    * @return void 
    */
	private function doUpload($_FILES, $contactID) {
			
		// package question types into array
		$uploadValues = array('question', 'subQuestion', 'subChoiceQuestion');
		
		// create array that will package new upload file name
		$surveyQuestionUploads = array();
		
		foreach ($uploadValues as $values) {
			if (isset($_FILES[$values])) {
				foreach ($_FILES[$values]['name'] as $i => $file) {
					if (!empty($file)) {
						// get file name from array
						$fileName = $_FILES[$values]['name'][$i];
						
						// make file name lowercase
						$lowerCaseFileName = strtolower($fileName);
						
						$fileSize = $_FILES[$values]['size'][$i];
											
						// check file size
						$fileSizeOk = $this->checkFileSize($fileSize);
						
						// check file extension
						$fileTypeOk = $this->checkFileType($lowerCaseFileName);
															
					if ($fileTypeOk == TRUE && $fileSizeOk == TRUE)  { 
						
						// add underscores if spaces are found in the file name
						$questionNewFileName = str_replace(" ", "_", $lowerCaseFileName);
						
						// append id to file name
						$newFileName = $contactID . "_" . $questionNewFileName;
						
						// concatenate path and file name
						$uploadFile = $this->uploadDir . basename($newFileName); 
						
						// save file to disk	
						if (move_uploaded_file($_FILES[$values]['tmp_name'][$i], $uploadFile)) { 
							$surveyQuestionUploads['response'] = $newFileName;
						} else {
							//send_email($this->devEmail, 'RecordsAuthority_Error', 'Upload Failed: (could not move file to file system) contactID:' . $contactID); 
							$surveyQuestionUploads['response'] = "File not uploaded";	
						}
						
							// update record with new file name
							switch ($values) {
								case "question";
									$this->db->where('contactID', $contactID);
									$this->db->where('questionID', $i);
									$this->db->update('rm_surveyQuestionResponses', $surveyQuestionUploads);
									break;
								case "subQuestion";
									$this->db->where('contactID', $contactID);
									$this->db->where('subQuestionID', $i);
									$this->db->update('rm_surveySubQuestionResponses', $surveyQuestionUploads);
									break;
								case "subChoiceQuestion";
									$this->db->where('contactID', $contactID);
									$this->db->where('subChoiceQuestionID', $i);
									$this->db->update('rm_surveySubChoiceQuestionResponses', $surveyQuestionUploads);
									break;
							}
							
						} else {
							
							if ($fileTypeOk !== TRUE) {
								//send_email($this->devEmail, 'RecordsAuthority_Error', 'Upload Failed: (file type not supported) contactID:' . $contactID);
								echo "file type not supported";
							} elseif ($fileSizeOk !== TRUE) {
								//send_email($this->devEmail, 'RecordsAuthority_Error', 'Upload Failed: (file size too big) contactID:' . $contactID); 
								echo "file size too big";
							} else { 
								// show error here
								//send_email($this->devEmail, 'RecordsAuthority_Error', 'Upload Failed: (unknown reason) contactID:' . $contactID); 
								echo "an error occurred..file not uploaded";
							}
						}
					}
				} 
			}  
		}
	} 
	
	/**
    * checks file size
    *
    * @access private
    * @param $fileSize
    * @return TRUE/FALSE
    */
	private function checkFileSize($fileSize) {
		
		$maxFileSize = 25000000; 
		if ($fileSize != 0  AND  $fileSize > $maxFileSize) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	/**
    * checks file type
    *
    * @access private
    * @param $lowerCaseFileName
    * @return TRUE/FALSE
    */
	private function checkFileType($lowerCaseFileName) {
		
		// extract file extension from filename
		$fileExtension = str_replace(".", "", strrchr($lowerCaseFileName, "."));
		
		// get docTypes
		$fileTypes = array();
		$this->db->select('docType');
	 	$this->db->from('rm_docTypes');
	 	$docTypeQuery = $this->db->get();
	 		 		 
	 	if ($docTypeQuery->num_rows() > 0) {		
	 		//place docType results into array 
	 		foreach ($docTypeQuery->result() as $results) {
			 	$fileTypes[] = $results->docType;
			 }
	 	}		
	 					
		// check if filename extension is in extension list
		if (in_array($fileExtension, $fileTypes, TRUE)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
} // closes class

?>