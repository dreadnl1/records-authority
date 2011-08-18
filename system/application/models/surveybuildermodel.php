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

class SurveyBuilderModel extends Model {
	
	public function __construct() {
		parent::Model();	
		
		// TODO: change this...
		$this->id = "surveyID"; //pk 
		$this->surveys = "rm_surveys";//table name	
		$this->qid = "questionID";//pk
		$this->questions = "rm_surveyQuestions";//table name
		$this->subQuestions = "rm_surveySubQuestions";//table name
		
		$this->devEmail = $this->config->item('devEmail');
	}
	 
	/**
    * invokes addSurveyNameQuery()
    *
    * @access public
    * @param $surveyName
    * @return $result / survey name 
    */
	 public function addSurveyName($surveyName) {
 		$result = $this->addSurveyNameQuery($surveyName);
 		return $result;
 	}
 	
 	/**
    * saves survey name to database
    *
    * @access private
    * @param $surveyName
    * @return $result / survey name 
    */
 	private function addSurveyNameQuery($surveyName) {
 		
 		$this->db->insert($this->surveys, $surveyName);
 		$this->db->select_max($this->id);
		$query = $this->db->get($this->surveys);
		
		if ($query->num_rows() > 0) {
   			$results = $query->row();
				$result = $results->surveyID;
		 	return $result;  
		} else {
			send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: Survey name not returned. addSurveyNameQuery()');
			return "Unable to return survey name.";
		}	
 	}
 	
 	/**
    * invokes addSurveyQuestionQuery()
    *
    * @access public
    * @param $surveyQuestion
    * @return $result / survey question 
    */
 	public function addSurveyQuestion($surveyQuestion) {
 		$result = $this->addSurveyQuestionQuery($surveyQuestion);
 		return $result;
 	}
 	
 	/**
    * saves survey question to database
    *
    * @access private
    * @param $surveyQuestion
    * @return $result / survey question id (pk in database)
    */
 	private function addSurveyQuestionQuery($surveyQuestion) {
 		$this->db->insert($this->questions, $surveyQuestion);
 		$this->load->helper('array');
 		// if question has sub questions...return questionID
 		if (element('subQuestion', $surveyQuestion) == 1) {
	 		$this->db->select_max($this->qid);
			$query = $this->db->get($this->questions);
		
			if ($query->num_rows() > 0) {
	   			$results = $query->row();
					$result = $results->questionID;
			 	return $result;  
			} else {
				send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: question ID not returned. addSurveyQuestionQuery()');
				return "Question not saved correctly";
			}	
 		} else {
 			return 0;
 		}
 	}
 	
 	/**
    * invokes addSurveySubQuestionQuery()
    *
    * @access public
    * @param $surveySubQuestion, $surveySubChoiceQuesiton
    * @return void
    */
 	public function addSurveySubQuestion($surveySubQuestion, $surveySubChoiceQuesiton) {
 		$result = $this->addSurveySubQuestionQuery($surveySubQuestion, $surveySubChoiceQuesiton);
 	}
 	
 	/**
    * saves survey sub question to database
    *
    * @access private
    * @param $surveySubQuestion
    * @param $surveySubChoiceQuesiton
    * @return void
    */
 	private function addSurveySubQuestionQuery($surveySubQuestion, $surveySubChoiceQuesiton) {
 		
 		$this->load->helper('array');
 		$this->db->insert('rm_surveySubQuestions', $surveySubQuestion);
 		
 		if (element('subChoiceQuestionCheck', $surveySubQuestion) == 1) {
 			$this->db->select_max('subQuestionID');
			$query = $this->db->get('rm_surveySubQuestions');
			
			if ($query->num_rows() > 0) {
	   			$results = $query->row();
				$subQuestionID = $results->subQuestionID;  
				$surveySubChoiceQuesiton['subQuestionID'] = $subQuestionID;
				$this->db->insert('rm_surveySubChoiceQuestions', $surveySubChoiceQuesiton);	
					
			} else {
				send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: unable to retrieve subQuestionID addSurveySubQuestionQuery()');
				echo "Unable to add sub question.";
			}
 		} else {
 			unset($surveySubChoiceQuesiton); // is this really needed?
 		}
 	}
	
	/**
    * invokes addSurveyContactQuestion()
    *
    * @access public
    * @param $surveyContactQuestion
    * @return $contactQuestionID (pk from database)
    */
	public function addSurveyContactQuestion($surveyContactQuestion) {
		$contactQuestionID = $this->addSurveyContactQuestionQuery($surveyContactQuestion);
		return $contactQuestionID;	
	}
	
	/**
    * saves survey contact question to database
    *
    * @access private
    * @param $surveyContactQuestion
    * @return $result / (contact question id / pk from database)
    */
	private function addSurveyContactQuestionQuery($surveyContactQuestion) {
		$this->db->insert('rm_surveyContactQuestions', $surveyContactQuestion);
 		$this->db->select_max('contactQuestionID');
		$query = $this->db->get('rm_surveyContactQuestions');
		
			if ($query->num_rows() > 0) {
	   			$results = $query->row();
					$result = $results->contactQuestionID;
			 	return $result;  
			} else {
				return 0;
 			}
	}
	
	/**
    * invokes addSurveyContactField()
    *
    * @access public
    * @param $surveyContactField
    * @return void
    */
	public function addSurveyContactField($surveyContactField) {
		$this->addSurveyContactFieldQuery($surveyContactField);
	}
	
	/**
    * saves survey contact field to database
    *
    * @access private
    * @param $surveyContactField
    * @return void
    */
	private function addSurveyContactFieldQuery($surveyContactField) {
		$this->db->insert('rm_surveyContactFields', $surveyContactField);
	}
	
	/**
	*  invokes deleteSurvey()
	*  
	*  @access public
	*  @param $surveyID
	*  @return void
	*/ 
	public function deleteSurvey($surveyID) {
		$this->deleteSurveyQuery($surveyID);
	}
	 
	/**
	* deletes the survey and associated questions
	* 
	* @access private 
	* @param $surveyID
	* @return void 
	*/
	private function deleteSurveyQuery($surveyID) {
		
		// delete survey
		$this->db->where('surveyID', $surveyID);
		$this->db->delete('rm_surveys');
		
		// get questionIDs for survey being deleted
		$this->db->select('questionID');
		$this->db->from('rm_surveyQuestions');
		$this->db->where('surveyID', $surveyID);
		$getQuestionIDsQuery = $this->db->get();
		
		if ($getQuestionIDsQuery->num_rows() > 0) {
			
			$questionIDs = array();
			
			// collect questionIDs
			foreach ($getQuestionIDsQuery->result() as $result) {
				$questionIDs[] = $result->questionID;
			}
			
			// remove questions...
			foreach ($questionIDs as $questionID) {
				$this->deleteSurveyQuestionQuery($questionID, $survey=true);
			}
		}
		
		// send to text to user.
		echo "Survey deleted.";
	}
		
	/**
    * invokes getSurveyQuestionsQuery() **NOT CURRENTLY USED**
    *
    * @access public
    * @param $surveyID
    * @return void
    */
	public function getSurveyQuestions($surveyID) {
		$this->getSurveyQuestionsQuery($surveyID);	
	}
	
	/**
    * gets survey questions **NOT CURRENTLY USED**
    *
    * @access private
    * @param $surveyID
    * @return void
    */
	private function getSurveyQuestionsQuery($surveyID) {
		//get survey questions
		$getSurveyQuestionsSql = "SELECT * " .
									"FROM rm_surveyQuestions " .
									"WHERE surveyID = ? " .
									"ORDER BY questionOrder ASC";
		$getSurveyQuestionsQuery = $this->db->query($getSurveyQuestionsSql, array($surveyID));
		
		// start form
		$url = site_url();
		$surveyReorderHtml = "";
		$surveyReorderHtml .= "<form name='questionOrder' method='post' action='$url/surveyBuilder/reorderSurveyQuestions/$surveyID' id='questionOrder'>";
		
		// pull questions out of query object
		if ($getSurveyQuestionsQuery->num_rows() > 0) {
			foreach ($getSurveyQuestionsQuery->result() as $results) {
				// display question
				$surveyReorderHtml .= "<input name='questionOrder_$results->questionID' type='text' value='$results->questionOrder' size='2' /><input name='questionID_$results->questionID' type='hidden' value='$results->questionID' />&nbsp;&nbsp;";
				$surveyReorderHtml .= trim(strip_tags($results->question)); // add re-order option here for preview
				$surveyReorderHtml .= "<br /><br />";
			}
		$surveyReorderHtml .= "<br /><br />";
		$surveyReorderHtml .= "<input name='submit' type='submit' value='Re-order questions' />";	
		$surveyReorderHtml .= "</form>";
		echo $surveyReorderHtml;
		}
	}
	
	/**
    * invokes editSurveyQuestionsQuery()
    *
    * @access public
    * @param $surveyID
    * @return $editSurveyResults
    */
	public function editSurveyQuestions($surveyID, $popUpParams) {
		$formResults = $this->editSurveyQuestionsQuery($surveyID);	
		return $formResults;
	}
	
	/**
    * generates editSurveyForm 
    *
    * @access private
    * @param $surveyID
    * @return $editSurveyResults
    */
	private function editSurveyQuestionsQuery($surveyID) {
		
		$this->db->select('questionID, surveyID, question, required, questionOrder');
		$this->db->from('rm_surveyQuestions');
		$this->db->where('surveyID', $surveyID);
		$getQuestionsQuery = $this->db->get();
				
		if ($getQuestionsQuery->num_rows > 0) {	
			$formResults = $this->generateSurveyQuestionsEditForm($getQuestionsQuery, $surveyID);
			return $formResults;
		}
	}
	
	/**
    * generates survey edit form 
    *
    * @access private
    * @param $getQuestionsQuery
    * @return $formResults
    */
	private function generateSurveyQuestionsEditForm($getQuestionsQuery, $surveyID) {
		
		$siteUrl = site_url();	
		$popUpParams = array(
		      'width'      => '500',
              'height'     => '300',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
		);
				
		$editFormHtml = "";
		$questionIDs = array();
						
		foreach ($getQuestionsQuery->result() as $count => $questions) {
			
			$questionCount = $count + 1;				
			$editFormHtml .= "<form id='$questions->questionID' name='$questions->questionID' method='post' action='$siteUrl/surveyBuilder/updateSurvey' >";
			$editFormHtml .= "<input name='surveyID' type='hidden' value='$questions->surveyID' />";
			$editFormHtml .= "<input name='questionID' type='hidden' value='$questions->questionID'/>";
			
			// following used to handle the display of quotes/apostrohies in input fields...CodeIgniter framework specific
			$questionData = array('name'=>'question', 'size'=>'90', 'value'=>$questions->question);
			$editFormHtml .= $questionCount .".) " . form_input($questionData);
			
			$editFormHtml .= "<br />";
			$editFormHtml .= "Required: ";
			
			if ($questions->required == 1) {
				$editFormHtml .= "<input name='required' type='checkbox' value='$questions->required' checked />";
			} else {
				$editFormHtml .= "<input name='required' type='checkbox' value='$questions->required' />";
			}
			
			$editFormHtml .= "<br />";
			$editFormHtml .= "Question Order: ";
			$editFormHtml .= "<input name='questionOrder' type='text' size='5' value='$questions->questionOrder' />";
			$editFormHtml .= "<br />";

			// check for sub questions
			$this->db->where('questionID', $questions->questionID);
			$subQuestionCount = $this->db->count_all_results('rm_surveySubQuestions');
			if ($subQuestionCount > 0) {	
				$editFormHtml .= anchor_popup('surveyBuilder/editSurveySubQuestions/' . $questions->questionID, 'Edit sub question', $popUpParams) . "<br />";
			}
			
			$editFormHtml .= "<br /><input name='$questions->questionID' type='submit' value='Update Question' />";
			$editFormHtml .= "&nbsp;&nbsp; <a href='#' onClick='return areYouSure($questions->questionID);'>[Delete]</a>"; 
			$div = $count . $questions->questionID;
			$editFormHtml .= "<div id='$div' style='display: none;'>Updating...</div>";
			$editFormHtml .= "</form>";
			$editFormHtml .= "<br /><br />";
			
			$questionIDs[] = $questions->questionID; 
		}
				
		// ajax
		foreach ($questionIDs as $count => $formDiv) {
    	
	    	$div = $count . $formDiv;
	    	
	    	$editFormHtml .= "<script type='text/javascript'>";	
			$editFormHtml .= "$(document).ready(function() {  ";
	    	
	    	$editFormHtml .= "var editSurveyQuestionsOptions = { ";
	        //success:		functionName, // post-submit callback 
	        $editFormHtml .= "resetForm:		false, ";        // reset the form after successful submit 
	     	$editFormHtml .= "timeout:   		3000, "; 
	    	$editFormHtml .= "beforeSend: 	    function() { $('#$div').fadeIn('slow'); }, "; 
			$editFormHtml .= "complete: 		function() { $('#$div').fadeOut('slow'); } ";  
	    	
	    	$editFormHtml .= "}; ";  
	    	
	    	$editFormHtml .= "$('#$formDiv').submit(function() { ";
	    	// submit the form 
	    	$editFormHtml .= "$(this).ajaxSubmit(editSurveyQuestionsOptions); "; 
	    	// return false to prevent normal browser submit and page navigation 
	    	$editFormHtml .= "return false; "; 
			$editFormHtml .= "}); ";
	    	
	    	$editFormHtml .= "}); ";
	    	$editFormHtml .= "</script>";    	
		}
		
		$editFormHtml .= "<script type='text/javascript'>";
		$editFormHtml .= "function areYouSure(questionID) { ";
		$editFormHtml .= "if (confirm('Are you sure you want to DELETE this Question?')) {";
		$editFormHtml .= "$.post('$siteUrl/surveyBuilder/deleteSurveyQuestion/', {questionID: questionID, ajax: 'true'}, function(results){ ";
		$editFormHtml .= "$('#' + questionID).html(results); ";
		$editFormHtml .= "}); "; // post
		$editFormHtml .= "} "; // end if
		$editFormHtml .= "} "; // js
		$editFormHtml .= "</script>";
		
		return $editFormHtml;
	}
	
	/**
	 * invokes deleteSurveyQuestion()
	 * @return void
	 */
	public function deleteSurveyQuestion($questionID) {
		$this->deleteSurveyQuestionQuery($questionID);
	}
	
	/**
	 * deletes survey question. 
	 * 
	 * @param $questionID
	 * @param $survey
	 * @return void
	 */
	private function deleteSurveyQuestionQuery($questionID, $survey="") {

		$subQuestionIDs = array();
		
		$this->db->trans_start();
		// remove question
		$this->db->where('questionID', $questionID);
		$this->db->delete('rm_surveyQuestions');
				
		// check if sub questions exists
		$this->db->where('questionID', $questionID);
		$subQuestionCount = $this->db->count_all_results('rm_surveySubQuestions');
		
		if ($subQuestionCount > 0) {
			// get id(s) so subChoice questions can be removed
			$this->db->select('subQuestionID');
			$this->db->from('rm_surveySubQuestions');
			$this->db->where('questionID', $questionID);
			$getSubQuestionIdQuery = $this->db->get();
			
			// may contain multiple ids...
			foreach ($getSubQuestionIdQuery->result() as $results) {
				$subQuestionIDs[] = $results->subQuestionID;
			}
			
			// remove sub question(s)
			$this->db->where('questionID', $questionID);
			$this->db->delete('rm_surveySubQuestions');
											
			// check if subChoice questions exists 
			foreach ($subQuestionIDs as $subQuestionID) {
							
				$sql = "SELECT subQuestionID FROM rm_surveySubChoiceQuestions WHERE subQuestionID = ?";
				$results = $this->db->query($sql, array($subQuestionID));
				
				// remove sub choice questions
				if ($results->num_rows > 0) {
					$this->db->where('subQuestionID', $subQuestionID);
					$this->db->delete('rm_surveySubChoiceQuestions');
				}			
			}		
		}
		$this->db->trans_complete();
		if ($survey=="") {
			echo "Question deleted.";
		} 
	}
	
	/**
    * invokes editSurveySubQuestionsQuery()
    *
    * @access public
    * @param $questionID
    * @return void
    */
	public function editSurveySubQuestions($questionID) {
		$formResults = $this->editSurveySubQuestionsQuery($questionID);
		return $formResults;
	}
	
	/**
    * Queries database
    *
    * @access private
    * @param $questionID
    * @return $formResults / html form
    */
	private function editSurveySubQuestionsQuery($questionID) {
		
		$this->db->select('subQuestionID, questionID, subQuestion, subChoiceQuestionCheck, toggle');
		$this->db->from('rm_surveySubQuestions');
		$this->db->where('questionID', $questionID);
		$getSubQuestionsQuery = $this->db->get();
		
		if ($getSubQuestionsQuery->num_rows > 0) {	
			$formResults = $this->generateSurveySubQuestionsEditForm($getSubQuestionsQuery);
			return $formResults;
		} else {
			$formResults = "This question does not contain a sub question.";
			return $formResults;
		}
	}
	
	private function generateSurveySubQuestionsEditForm($getSubQuestionsQuery) {
		
		$siteUrl = site_url();	
		$baseUrl = base_url();
		
		$popUpParams = array(
		      'width'      => '500',
              'height'     => '300',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
		);
		
		$subQuestionEditFormHtml = "";
		$subQuestionIDs = array();
		
		// load jQuery library TODO: generate "view" to reuse adminHeader
		$subQuestionEditFormHtml .= "<html><head><script src='{$baseUrl}js/jquery-1.2.6.min.js' type='text/javascript'></script><script src='{$baseUrl}js/jqueryForm.js' type='text/javascript'></script></head>";
		$subQuestionEditFormHtml .= "<body>";
		
		foreach ($getSubQuestionsQuery->result() as $count => $subQuestions) {

			$questionCount = $count + 1;
			$subQuestionEditFormHtml .= "<form id='$subQuestions->subQuestionID' name='$subQuestions->subQuestionID' method='post' action='$siteUrl/surveyBuilder/updateSurvey' >";
			$subQuestionEditFormHtml .= "<input name='subQuestionID' type='hidden' value='$subQuestions->subQuestionID' />";
			$subQuestionEditFormHtml .= "<input name='questionID' type='hidden' value='$subQuestions->questionID'/>";
			
			// following used to handle the display of quotes/apostrohies in input fields
			$subQuestionData = array('name'=>'subQuestion', 'size'=>'60', 'value'=>$subQuestions->subQuestion);
			$subQuestionEditFormHtml .= $questionCount .".) " . form_input($subQuestionData);
			
			$subQuestionEditFormHtml .= "<br />";
			if ($subQuestions->subChoiceQuestionCheck == 1) {
				if ($subQuestions->toggle == 1) {
					$subQuestionEditFormHtml .= "Toggle: ";
					$subQuestionEditFormHtml .= "<br />";
					$subQuestionEditFormHtml .= "<input name='toggle' type='text' size='5' value='$subQuestions->toggle' /><br />";
					$subQuestionEditFormHtml .= "<input name='required' type='checkbox' value='$subQuestions->subChoiceQuestionCheck' checked />";
				}
				// TODO: check if there are any sub choice questions.  if not, dot display url
				$subQuestionEditFormHtml .= "<br />" . anchor_popup('surveyBuilder/editSurveySubChoiceQuestions/' . $subQuestions->subQuestionID, 'Edit sub choice question', $popUpParams) . "<br /><br />";
			} else {
				$subQuestionEditFormHtml .= "<br />Add sub choice question (not functional)<br />"; // TODO: add url to allow creation of sub choice questions
			}
			$subQuestionEditFormHtml .= "<br />";
			$subQuestionEditFormHtml .= "<input name='submit' type='submit' value='Update' />";
			$subQuestionEditFormHtml .= "&nbsp;&nbsp; <a href='#' onClick='return areYouSure($subQuestions->subQuestionID);'>[Delete]</a>"; 
			$div = $count . $subQuestions->subQuestionID;
			$subQuestionEditFormHtml .= "<div id='$div' style='display: none;'>Updating...</div>";
			$subQuestionEditFormHtml .= "</form>";
			$subQuestionEditFormHtml .= "<br /><br />";
			
			$subQuestionIDs[] = $subQuestions->subQuestionID;
		}
				
		// ajax
		$subQuestionEditFormHtml .= "<script type='text/javascript'>";
		foreach ($subQuestionIDs as $count => $formDiv) {
    		
			$div = $count . $formDiv;
	    								    	
	    	$subQuestionEditFormHtml .= "$(document).ready(function() {  ";
	    	
	    	$subQuestionEditFormHtml .= "var editSurveySubQuestionsOptions = { ";
	        //success:		functionName, // post-submit callback 
	        $subQuestionEditFormHtml .= "resetForm:		false, ";        // reset the form after successful submit 
	     	$subQuestionEditFormHtml .= "timeout:   	3000, "; 
	    	$subQuestionEditFormHtml .= "beforeSend: 	function() { $('#$div').fadeIn('slow'); }, "; 
			$subQuestionEditFormHtml .= "complete: 		function() { $('#$div').fadeOut('slow'); } ";  
	    	
	    	$subQuestionEditFormHtml .= "}; ";  
	    	
	    	$subQuestionEditFormHtml .= "$('#$formDiv').submit(function() { ";
	    	// submit the form 
	    	$subQuestionEditFormHtml .= "$(this).ajaxSubmit(editSurveySubQuestionsOptions); "; 
	    	// return false to prevent normal browser submit and page navigation 
	    	$subQuestionEditFormHtml .= "return false; "; 
			$subQuestionEditFormHtml .= "}); ";
	    	
	    	$subQuestionEditFormHtml .= "}); ";
 	
		}
		
		// deletes sub questions
		$subQuestionEditFormHtml .= "function areYouSure(subQuestionID) { ";
		$subQuestionEditFormHtml .= "if (confirm('Are you sure you want to DELETE this Sub Question?')) {";
		$subQuestionEditFormHtml .= "$.post('$siteUrl/surveyBuilder/deleteSurveySubQuestion/', {subQuestionID: subQuestionID, ajax: 'true'}, function(results){ ";
		$subQuestionEditFormHtml .= "$('#' + subQuestionID).html(results); ";
		$subQuestionEditFormHtml .= "}); "; // post
		$subQuestionEditFormHtml .= "} "; // end if
		$subQuestionEditFormHtml .= "} "; // js
				
		$subQuestionEditFormHtml .="</script>&nbsp;</body></html>";
				
		return $subQuestionEditFormHtml;
	}
	
	/**
	 * invokes deleteSurveySubQuestion()
	 * @access public
	 * @param $subQuestionID
	 * @return void
	 */
	public function deleteSurveySubQuestion($subQuestionID) {
		$this->deleteSurveySubQuestionQuery($subQuestionID);
	}
	
	/**
	 * deletes sub question
	 * 
	 * @access private
	 * @param $subQuestionID
	 * @return void
	 */
	private function deleteSurveySubQuestionQuery($subQuestionID) {
		$this->db->where('subQuestionID', $subQuestionID);
		$this->db->delete('rm_surveySubQuestions');
		echo "Sub Question deleted.";
	}
	
	
	/**
    * invokes editSurveySubChoiceQuestionsQuery()
    *
    * @access public
    * @param $subQuestionID
    * @return $formResults
    */
	public function editSurveySubChoiceQuestions($subQuestionID) {
		$formResults = $this->editSurveySubChoiceQuestionsQuery($subQuestionID);	
		return $formResults;
	}
	
	/**
	 * edits sub choice questions
	 * 
	 * @access private
	 * @param $subQuestionID
	 * @return $formResults
	 */
	private function editSurveySubChoiceQuestionsQuery($subQuestionID) {
		$this->db->select('subChoiceQuestionID, subChoiceQuestion, toggle');
		$this->db->from('rm_surveySubChoiceQuestions');
		$this->db->where('subQuestionID', $subQuestionID);
		$getSubChoiceQuestionsQuery = $this->db->get();
		
		if ($getSubChoiceQuestionsQuery->num_rows > 0) {	
			$formResults = $this->generateSurveySubChoiceQuestionsEditForm($getSubChoiceQuestionsQuery);
			return $formResults;
		} else {
			$formResults = "This question does not contain a sub choice question.";
			return $formResults;
		}
	}
	
	/**
	 * generates a edit form for sub choice questions
	 * 
	 * @access private
	 * @param $getSubChoiceQuestionsQuery
	 * @return $subChoiceQuestionEditFormHtml
	 */
	private function generateSurveySubChoiceQuestionsEditForm($getSubChoiceQuestionsQuery) {
		$siteUrl = site_url();
		$baseUrl = base_url();	
		$popUpParams = array(
		      'width'      => '500',
              'height'     => '300',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
		);
		
		$subChoiceQuestionEditFormHtml = "";
		$subChoiceQuestionIDs = array();
		// load jQuery library TODO: generate "view" to reuse adminHeader
		$subChoiceQuestionEditFormHtml .= "<html><head><script src='{$baseUrl}js/jquery-1.2.6.min.js' type='text/javascript'></script><script src='{$baseUrl}js/jqueryForm.js' type='text/javascript'></script></head>";
		$subChoiceQuestionEditFormHtml .= "<body>";
		
		foreach ($getSubChoiceQuestionsQuery->result() as $count => $subChoiceQuestions) {
				
			$subChoiceQuestionEditFormHtml .= "<form id='$subChoiceQuestions->subChoiceQuestionID' name='$subChoiceQuestions->subChoiceQuestionID' method='post' action='$siteUrl/surveyBuilder/updateSurvey' >";
			$subChoiceQuestionEditFormHtml .= "<input name='subChoiceQuestionID' type='hidden' value='$subChoiceQuestions->subChoiceQuestionID' />";
			
			$questionCount = $count + 1;
			// following used to handle the display of quotes/apostrohies in input fields
			$subChoiceQuestionData = array('name'=>'subChoiceQuestion', 'size'=>'60', 'value'=>$subChoiceQuestions->subChoiceQuestion);
			$subChoiceQuestionEditFormHtml .= $questionCount .".) " . form_input($subChoiceQuestionData);
			$subChoiceQuestionEditFormHtml .= "<br />";
			$subChoiceQuestionEditFormHtml .= "<br />";
			$subChoiceQuestionEditFormHtml .= "Toggle: ";
			$subChoiceQuestionEditFormHtml .= "<br />";
			if ($subChoiceQuestions->toggle == 1) {
				$subChoiceQuestionEditFormHtml .= "<input name='toggle' type='checkbox' value='$subChoiceQuestions->toggle' checked />";
			} else {
				$subChoiceQuestionEditFormHtml .= "<input name='toggle' type='checkbox' value='$subChoiceQuestions->toggle' />";
			}
			$subChoiceQuestionEditFormHtml .= "<br />";
			$subChoiceQuestionEditFormHtml .= "<br />";
			$subChoiceQuestionEditFormHtml .= "<input name='submit' type='submit' value='Update' />";
			$subChoiceQuestionEditFormHtml .= "&nbsp;&nbsp; <a href='#' onClick='return areYouSure($subChoiceQuestions->subChoiceQuestionID);'>[Delete]</a>"; 
			$div = $count . $subChoiceQuestions->subChoiceQuestionID;
			$subChoiceQuestionEditFormHtml .= "<div id='$div' style='display: none;'>Updating...</div>";
			$subChoiceQuestionEditFormHtml .= "</form>";
			$subChoiceQuestionEditFormHtml .= "<br /><br />";
			
			$subChoiceQuestionIDs[] =  $subChoiceQuestions->subChoiceQuestionID;
		}
		
		// ajax
		$subChoiceQuestionEditFormHtml .= "<script type='text/javascript'>";
		foreach ($subChoiceQuestionIDs as $count => $formDiv) {
    		
			$div = $count . $formDiv;
	    								    	
	    	$subChoiceQuestionEditFormHtml .= "$(document).ready(function() {  ";
	    	
	    	$subChoiceQuestionEditFormHtml .= "var editSurveySubChoiceQuestionsOptions = { ";
	        //success:		functionName, // post-submit callback 
	        $subChoiceQuestionEditFormHtml .= "resetForm:	false, ";        // reset the form after successful submit 
	     	$subChoiceQuestionEditFormHtml .= "timeout:   	3000, "; 
	    	$subChoiceQuestionEditFormHtml .= "beforeSend: 	function() { $('#$div').fadeIn('slow'); }, "; 
			$subChoiceQuestionEditFormHtml .= "complete: 	function() { $('#$div').fadeOut('slow'); } ";  
	    	
	    	$subChoiceQuestionEditFormHtml .= "}; ";  
	    	
	    	$subChoiceQuestionEditFormHtml .= "$('#$formDiv').submit(function() { ";
	    	// submit the form 
	    	$subChoiceQuestionEditFormHtml .= "$(this).ajaxSubmit(editSurveySubChoiceQuestionsOptions); "; 
	    	// return false to prevent normal browser submit and page navigation 
	    	$subChoiceQuestionEditFormHtml .= "return false; "; 
			$subChoiceQuestionEditFormHtml .= "}); ";
	    	
	    	$subChoiceQuestionEditFormHtml .= "}); ";
 	
		}
		
		// deletes sub questions
		$subChoiceQuestionEditFormHtml .= "function areYouSure(subChoiceQuestionID) { ";
		$subChoiceQuestionEditFormHtml .= "if (confirm('Are you sure you want to DELETE this Sub Choice Question?')) {";
		$subChoiceQuestionEditFormHtml .= "$.post('$siteUrl/surveyBuilder/deleteSurveySubChoiceQuestion/', {subChoiceQuestionID: subChoiceQuestionID, ajax: 'true'}, function(results){ ";
		$subChoiceQuestionEditFormHtml .= "$('#' + subChoiceQuestionID).html(results); ";
		$subChoiceQuestionEditFormHtml .= "}); "; // post
		$subChoiceQuestionEditFormHtml .= "} "; // end if
		$subChoiceQuestionEditFormHtml .= "} "; // js
				
		$subChoiceQuestionEditFormHtml .="</script>&nbsp;</body></html>";
				
		
		return $subChoiceQuestionEditFormHtml;
	}
	
	/**
	 * invokes deleteSurveySubChoiceQuestion()
	 * @access public
	 * @param $subChoiceQuestionID
	 * @return void
	 */
	public function deleteSurveySubChoiceQuestion($subChoiceQuestionID) {
		$this->deleteSurveySubChoiceQuestionQuery($subChoiceQuestionID);
	}
	
	/**
	 * deletes sub choice question
	 * 
	 * @access private
	 * @param $subChoiceQuestionID
	 * @return void
	 */
	private function deleteSurveySubChoiceQuestionQuery($subChoiceQuestionID) {
		$this->db->where('subChoiceQuestionID', $subChoiceQuestionID);
		$this->db->delete('rm_surveySubChoiceQuestions');
		echo "Sub Choice Question deleted.";
	}
	
	
	
	public function updateSurveyQuestion($_POST) {
		$this->updateSurveyQuestionQuery($_POST);
	}
	
	private function updateSurveyQuestionQuery($_POST) {
		
		$surveyQuestions = array();
		$surveyQuestions['surveyID'] = $_POST['surveyID'];
		$surveyQuestions['question'] = $_POST['question'];
		$surveyQuestions['required'] = $_POST['required'];
		$surveyQuestions['questionOrder'] = $_POST['questionOrder'];
		$questionID = $_POST['questionID'];
		$this->db->where('questionID', $questionID);
		$this->db->update('rm_surveyQuestions', $surveyQuestions);
	}
	
	public function updateSurveySubQuestion($_POST) {
		
		$surveySubQuesitons = array();
		$surveySubQuesitons['questionID'] = $_POST['questionID'];
		$surveySubQuesitons['subQuestion'] = $_POST['subQuestion'];
		$subQuestionID = $_POST['subQuestionID'];
		$this->db->where('subQuestionID', $subQuestionID);
		$this->db->update('rm_surveySubQuestions', $surveySubQuesitons);
		
		echo "Sub question updated.";
	}

	public function updateSurveySubChoiceQuestion($_POST) {
		
		$surveySubChoiceQuesitons = array();
		$surveySubChoiceQuesitons['subChoiceQuestion'] = $_POST['subChoiceQuestion'];
		
		if (!isset($_POST['toggle'])) {
			$surveySubChoiceQuesitons['toggle'] = 0;
		} else {
			$surveySubChoiceQuesitons['toggle'] = $_POST['toggle'];
		}
		
		$subChoiceQuestionID = $_POST['subChoiceQuestionID'];
		print_r($surveySubChoiceQuesitons);
		$this->db->where('subChoiceQuestionID', $subChoiceQuestionID);
		$this->db->update('rm_surveySubChoiceQuestions', $surveySubChoiceQuesitons);
		
		echo "Sub choice question updated.";
	}
	
	/**
    * invokes generateSurveyQuery()
    *
    * @access public
    * @param $surveyID
    * @return void
    */
	public function generateSurvey($surveyID) { 
		$surveyHtml = $this->generateSurveyQuery($surveyID);	
		return $surveyHtml;
	}
			
	/**
    * generates survey 
    *
    * @access private
    * @param $surveyID
    * @param $action (publish / preview)
    * @return $surveyHtml
    */
	private function generateSurveyQuery($surveyID) {
		$datestring = "%Y-%m-%d %H:%i:%s";
		$time = time();
		$submitDate = mdate($datestring, $time);
		
		$surveyHtml = "";
		$siteUrl = site_url();
		$baseUrl = base_url();
		$imagePath = $baseUrl . "images/";
		// gets departments based on the division selected.  uses AJAX / JSON
		$surveyHtml .= "<script type='text/javascript'>";
    	$surveyHtml .= "$(document).ready(function(){ "; 
		//validation
		$surveyHtml .= "$('#surveyForm').validate(); ";
		$surveyHtml .= "$('select#divisions').change(function(){ ";
        $surveyHtml .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$surveyHtml .= "var options = ''; " ;
      	$surveyHtml .= "for (var i = 0; i < j.length; i++) { ";
        $surveyHtml .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$surveyHtml .= "}" ;
      	$surveyHtml .= "$('select#departments').html(options); ";
    	$surveyHtml .= "}, 'json'); "; // post
  		$surveyHtml .= "}); "; // select ...
  		$surveyHtml .= "}); "; // document...
     	$surveyHtml .= "</script>";
		
		// generate HTML
		$surveyHtml .= "<div id='main-content'>";				
		//get survey name
		$getSurveyNameSql = "SELECT surveyName, surveyDescription " .
								"FROM rm_surveys " .
								"WHERE surveyID = ? ";
		$getSurveyNameQuery = $this->db->query($getSurveyNameSql, array($surveyID));
		
		// pull results out of query object
		if ($getSurveyNameQuery->num_rows() > 0) {
			foreach ($getSurveyNameQuery->result() as $surveyInformation) {
				$surveyHtml .= "<h1>";
				$surveyHtml .= $surveyInformation->surveyName;
				$surveyHtml .= "</h1>";
				$surveyHtml .= "<p>";
				$surveyHtml .= $surveyInformation->surveyDescription . "<br /><br />";
				$surveyHtml .= "</p>";
			}
		}
		
		// get division to populate drop down menu
		$getDivisionsSql = "SELECT divisionID, divisionName " .
								"FROM rm_divisions ";
		$getDivisionsQuery = $this->db->query($getDivisionsSql);
		
		// survey form begins TODO: write conditionals for contact info
		$surveyHtml .= "<form id='surveyForm' name='survey' method='post' action='$siteUrl/survey' enctype='multipart/form-data'>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "* indicates a required field.<br /><br />";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<hr />";
		$surveyHtml .= "<br />";
		$surveyHtml .= "<p>";
		$surveyHtml .= "Please fill out your contact information:<br /><br />";
		$surveyHtml .= "<input name='surveyID' type='hidden' value='$surveyID' />";
		$surveyHtml .= "</p>";
		
		// department contact form fields
		$surveyHtml .= "<p>";
		$surveyHtml .= " <label for='firstName'>First Name&nbsp;&nbsp;*</label>";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<input name='firstName' type='text' class='required' />";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<br />";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<label for='lastName'>Last Name&nbsp;&nbsp;*</label>";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<input name='lastName' type='text' class='required' />";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<br />";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<label for='jobTitle'>Job Title&nbsp;&nbsp;*</label>";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<input name='jobTitle' type='text' class='required' />";
		$surveyHtml .= "</p>";
		
		$surveyHtml .= "<br />";
		
		$surveyHtml .= "<p>";
		// divisions drop down menu
		$surveyHtml .= "<label for='divisions'></label>";
		$surveyHtml .= "<select id='divisions' name='divisionID' size='1' class='required'>"; 
		$surveyHtml .= "<option value='' selected='selected'>Select your division</option>";
		$surveyHtml .= "<option value=''>--------------------</option>";
		
		// pull division results out of query object
		if ($getDivisionsQuery->num_rows() > 0) {
			foreach ($getDivisionsQuery->result() as $division) {
				$surveyHtml .= "<option value='$division->divisionID'>$division->divisionName</option>";
			}
		}
		
		$surveyHtml .= "</select>&nbsp;&nbsp;*";
		$surveyHtml .= "&nbsp;&nbsp;&nbsp;&nbsp;";
		
		// departments drop down menu 
		$surveyHtml .= "<label for='departments'></label>";
		$surveyHtml .= "<select id='departments' name='departmentID' size='1' class='required'>";
		$surveyHtml .= "<option value=''>Select your department</option>";
		$surveyHtml .= "</select>&nbsp;&nbsp;*";
		$surveyHtml .= "</p>";
		
		$surveyHtml .= "<p>";
		$surveyHtml .= "<label for='phoneNumber'>Phone Number&nbsp;&nbsp;*</label>";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<input name='phoneNumber' type='text' class='required phone' />&nbsp;(xxx-xxx-xxxx)";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<label for='emailAddress'>Email Address&nbsp;&nbsp;*</label>";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<p>";
		$surveyHtml .= "<input name='emailAddress' type='text' class='required email' />";
		$surveyHtml .= "</p>";
		$surveyHtml .= "<input name='submitDate' type='hidden' value='$submitDate' />";
		
		$surveyHtml .= "<br />";
		$surveyHtml .= "<hr />";
		$surveyHtml .= "<br /><br />";
				
		//get survey questions
		$getSurveyQuestionsSql = "SELECT * " .
									"FROM rm_surveyQuestions " .
									"WHERE surveyID = ? " .
									"ORDER BY questionOrder ASC";
		$getSurveyQuestionsQuery = $this->db->query($getSurveyQuestionsSql, array($surveyID));
		
		// pull questions out of query object
		if ($getSurveyQuestionsQuery->num_rows() > 0) {
			
			foreach ($getSurveyQuestionsQuery->result() as $results) {
				
				// display question
				$surveyHtml .= "<p>";
				//label tag causes problems with checkFileExtension() js on webkit based browsers...ids conflict in label tag? eventhough actual ids are different.
				//$surveyHtml .= "<label for='$results->questionID'>" . $results->question . "<label>"; 
				$surveyHtml .= $results->question;   
				if ($results->required == 1) {
					$surveyHtml .= "&nbsp;&nbsp;*";
				}
				$surveyHtml .= "</p>";
												
				// if question type is 2 add yes/no radio buttons instead of fieldType
				if ($results->questionType == 2) {	
					
					// get sub questions
					$getSubQuestionToggleSql = "SELECT subQuestionID FROM rm_surveySubQuestions WHERE questionID = ?";
					$getSubQuestionToggleQuery = $this->db->query($getSubQuestionToggleSql, array($results->questionID));
						
						// pull subquestionID out of query object
						foreach ($getSubQuestionToggleQuery->result() as $toggleSubQuestionID) {
							
							//get subQuestionID to trigger jQuery toggle
							$surveyHtml .= "<p>";
							$surveyHtml .= "<label for='question[$results->questionID]'><input name='question[$results->questionID]' type='radio' value='yes' id='show$toggleSubQuestionID->subQuestionID' class='required' />&nbsp;&nbsp;Yes</label><br />";
							$surveyHtml .= "<label for='question[$results->questionID]'><input name='question[$results->questionID]' type='radio' value='no' id='hide$toggleSubQuestionID->subQuestionID' class='required' />&nbsp;&nbsp;No</label><br />";
							$surveyHtml .= "</p>";
						}
					
				} else {
					//$surveyHtml .= "<p>";
					// get fieldType
					$getFieldTypeSql = "SELECT fieldType FROM rm_fieldTypes WHERE fieldTypeID = ?";
					$getFieldTypeQuery = $this->db->query($getFieldTypeSql, array($results->fieldTypeID));
						
						// pull fieldType out of query object
						foreach ($getFieldTypeQuery->result() as $fieldType) {
							if ($fieldType->fieldType == 'textarea') {
								if ($results->required == 1) {
									$surveyHtml .= "<textarea name='question[$results->questionID]' rows='3' cols='50' wrap='hard' class='required'></textarea>";
								} else {
									$surveyHtml .= "<textarea name='question[$results->questionID]' rows='3' cols='50' wrap='hard'></textarea>";
								}
								
							} elseif ($fieldType->fieldType == 'file') { 
								$surveyHtml .= "<script>var questionFieldName = 'question[$results->questionID]';</script> "; 
								$surveyHtml .= "<script>var questionId = 'checkExtensionQuestion';</script> ";
								if ($results->required == 1) {
									$surveyHtml .= "<input name='question[$results->questionID]' type='$fieldType->fieldType' onBlur='checkExtension(document.survey[questionFieldName].value, questionId)' class='required' />&nbsp;&nbsp;";
								} else {
									$surveyHtml .= "<input name='question[$results->questionID]' type='$fieldType->fieldType' onBlur='checkExtension(document.survey[questionFieldName].value, questionId)' />&nbsp;&nbsp;";	
								}
								$surveyHtml .= "<span id='checkExtensionQuestion' class='fileTypeError'></span>";
							}else {
								if ($results->required == 1) {
									$surveyHtml .= "<input name='question[$results->questionID]' type='$fieldType->fieldType' class='required' />";
								} else {
									$surveyHtml .= "<input name='question[$results->questionID]' type='$fieldType->fieldType' />";	
								}
							}
						}	
				//$surveyHtml .= "</p>";
				}
				
				// if question has sub questions...get them	
				if ($results->subQuestion == 1) {
					// get sub questions
					$getSubQuestionsSql = "SELECT * FROM rm_surveySubQuestions WHERE questionID = ? ORDER BY subQuestionID";
					$getSubQuestionsQuery = $this->db->query($getSubQuestionsSql, array($results->questionID));
						
						//get sub questions out of query object
						foreach ($getSubQuestionsQuery->result() as $subQuestion) {
							
							// get sub field Type
							$getSubFieldTypeSql = "SELECT * FROM rm_fieldTypes WHERE fieldTypeID = ?";
							$getSubFieldTypeQuery = $this->db->query($getSubFieldTypeSql, array($subQuestion->fieldTypeID));
							
								// pull sub fieldType out of query object
								foreach ($getSubFieldTypeQuery->result() as $subFieldType) {
									if ($subFieldType->fieldType == 'textarea') {
										
										//check if toggle
										if ($subQuestion->toggle == 1) {
										
											// set JavaScript to toggle div
											$surveyHtml .= "<script> ";
										
											// build jQuery function for show/hide effect
											$surveyHtml .= "$(document).ready(function(){ "; 
												$surveyHtml .= "$('#show$subQuestion->subQuestionID').click(function () { ";
      											$surveyHtml .= "$('#$subQuestion->subQuestionID').show('fast'); ";	
    											$surveyHtml .= "}); "; 
    											$surveyHtml .= "$('#hide$subQuestion->subQuestionID').click(function () { ";
      											$surveyHtml .= "$('#$subQuestion->subQuestionID').hide('fast'); ";	
    											$surveyHtml .= "}); "; 
											$surveyHtml .= "}); ";
											$surveyHtml .= "</script>";
										
											// div begins
											$surveyHtml .= "<div id='$subQuestion->subQuestionID' style='display: none;'>";
										}
										
											$surveyHtml .= "<p>";
											$surveyHtml .= $subQuestion->subQuestion;
											//TODO: look into required sub questions / fix below so not hard coded
											if ($results->required) { //$subQuestion->subQuestion == 'Please list' 
												$surveyHtml .= "&nbsp;&nbsp;*";
											}
											$surveyHtml .= "<p>";
											$surveyHtml .= "<p>";
											// TODO: fix this so not hard coded
											if ($subQuestion->subQuestion == 'Please list') { // $results->required
												$surveyHtml .= "<textarea name='subQuestion[$subQuestion->subQuestionID]' rows='3' cols='50' wrap='hard' class='required'>list:</textarea>";
											} else {
												$surveyHtml .= "<textarea name='subQuestion[$subQuestion->subQuestionID]' rows='3' cols='50' wrap='hard'></textarea>";
											}
											$surveyHtml .= "</p>";
										
										if ($subQuestion->toggle == 1) {
											$surveyHtml .= "</div>";
										}
									} elseif ($subFieldType->fieldType == 'text' || $subFieldType->fieldType == 'file') { 
										
										// check if toggle
										if ($subQuestion->toggle == 1) {
											
											// set JavaScript to toggle div
											$surveyHtml .= "<script>";
											
											// build jQuery function for show/hide effect
											$surveyHtml .= "$(document).ready(function(){ "; 
												$surveyHtml .= "$('#show$subQuestion->subQuestionID').click(function () { ";
      											$surveyHtml .= "$('#$subQuestion->subQuestionID').show('fast'); ";	
    											$surveyHtml .= "}); "; 
    											$surveyHtml .= "$('#hide$subQuestion->subQuestionID').click(function () { ";
      											$surveyHtml .= "$('#$subQuestion->subQuestionID').hide('fast'); ";	
    											$surveyHtml .= "}); "; 
											$surveyHtml .= "}); ";
											$surveyHtml .= "</script>";
										
											// div begins
											$surveyHtml .= "<div id='$subQuestion->subQuestionID' style='display: none;'>";	
										}
										
										$surveyHtml .= "<p>";
										$surveyHtml .= $subQuestion->subQuestion;
										$surveyHtml .= "<br />";
										
										if ($subFieldType->fieldType == 'file') { 
											$surveyHtml .= "<script>var subQuestionFieldName = 'subQuestion[$subQuestion->subQuestionID]'</script> "; 
											$surveyHtml .= "<script>var subQuestionId = 'checkExtensionSubQuestion';</script> ";
											$surveyHtml .= "<input name='subQuestion[$subQuestion->subQuestionID]' type='$subFieldType->fieldType' onBlur='checkExtension(document.survey[subQuestionFieldName].value, subQuestionId)' />&nbsp;";
											$surveyHtml .= "<span id='checkExtensionSubQuestion' class='fileTypeError'></span>";
										} else {
											$surveyHtml .= "<input name='subQuestion[$subQuestion->subQuestionID]' type='$subFieldType->fieldType' />";
										}
										
										$surveyHtml .= "</p>";
										
										if ($subQuestion->toggle == 1) {
											$surveyHtml .= "</div>";
										}
									
									} else {
										$surveyHtml .= "<p>";
										$surveyHtml .= "<input id='toggle$subQuestion->subQuestionID' name='subQuestion[$subQuestion->subQuestionID]' type='checkbox' value='$subQuestion->subQuestion' />&nbsp;&nbsp;";
										$surveyHtml .= $subQuestion->subQuestion;
										$surveyHtml .= "</p>";
										
										// if sub question has a sub question...get it																									
										if ($subQuestion->subChoiceQuestionCheck == 1) {
										
											//get sub choice question
											$getSubChoiceQuestionSql = "SELECT * FROM rm_surveySubChoiceQuestions WHERE subQuestionID = ?";
											$getSubChoiceQuestionQuery = $this->db->query($getSubChoiceQuestionSql, array($subQuestion->subQuestionID));
											
											//pull sub choice question out of query object
											foreach ($getSubChoiceQuestionQuery->result() as $subChoiceQuestion) {
												
												// get sub choice field Type
												$getSubChoiceFieldTypeSql = "SELECT * FROM rm_fieldTypes WHERE fieldTypeID = ?";
												$getSubChoiceFieldTypeQuery = $this->db->query($getSubChoiceFieldTypeSql, array($subChoiceQuestion->fieldTypeID));
													
													// pull sub choice field type out of query object
													foreach ($getSubChoiceFieldTypeQuery->result() as $subChoiceFieldType) {
														
														if ($subChoiceQuestion->toggle == 1) {
															
																// set JavaScript to toggle div
																$surveyHtml .= "<script>";
											
																// build jQuery function for show/hide effect
																$surveyHtml .= "$(document).ready(function(){ "; 
																$surveyHtml .= "$('#toggle$subQuestion->subQuestionID').click(function () { ";
      															$surveyHtml .= "$('#$subQuestion->subQuestionID').toggle('fast'); ";	
    															$surveyHtml .= "}); "; 
																$surveyHtml .= "}); ";
																$surveyHtml .= "</script>";
										
																// div begins
																$surveyHtml .= "<div id='$subQuestion->subQuestionID' style='display: none;'>";	
														
															} 
														
														// may be a problem later...label only appears when field type is a textarea. refactor?
														if ($subChoiceFieldType->fieldType == 'textarea') {
															
															// if there is not sub choice label...don't print blank space to screen
															if (!empty($subChoiceQuestion->subChoiceQuestion)) {
																$surveyHtml .= "<p>";
																$surveyHtml .= $subChoiceQuestion->subChoiceQuestion;
																// TODO: look into subChoice required fields
																if ($results->required == 1) {
																	$surveyHtml .= "&nbsp;&nbsp;*";
																}
																$surveyHtml .= "</p>";
															}
															$surveyHtml .= "<p>";
															$surveyHtml .= "<textarea name='subChoiceQuestion[$subQuestion->subQuestionID]' rows='3' cols='50' wrap='hard'></textarea>";
															$surveyHtml .= "</p>";
														} else {
															$surveyHtml .= "<p>";
															$surveyHtml .= "<input name='subChoiceQuestion[$subQuestion->subQuestionID]' type='$subChoiceFieldType->fieldType' />";	
															$surveyHtml .= "</p>";
														}
														
														if ($subChoiceQuestion->toggle == 1) {
															$surveyHtml .= "</div>";
														}
										
													
													}
											}
										}
									}
								}
						}
				}
				$surveyHtml .= "<br /><br />";
			}
			
			//get survey contact question
			$getSurveyContactQuestionsSql = "SELECT contactQuestionID, surveyID, contactQuestion  " .
										"FROM rm_surveyContactQuestions " .
										"WHERE surveyID = ? ";
			$getSurveyContactQuestionsQuery = $this->db->query($getSurveyContactQuestionsSql, array($surveyID));
			
			if ($getSurveyContactQuestionsQuery->num_rows > 0) {
							
				foreach ($getSurveyContactQuestionsQuery->result() as $contactQuestion) {
					$surveyHtml .=  "<p>";
					$surveyHtml .= "$contactQuestion->contactQuestion";
					$surveyHtml .= "</p>";
					
					//get survey contact fields
					$getSurveyContactFieldSql = "SELECT contactFieldID, contactQuestionID, contactField, fieldTypeID, required  " .
											"FROM rm_surveyContactFields " .
											"WHERE contactQuestionID = ? ";
					$getSurveyContactFieldQuery = $this->db->query($getSurveyContactFieldSql, array($contactQuestion->contactQuestionID));
					
					$surveyHtml .= "<p>";
					
					$surveyHtml .= "<input name='copyData' type='checkbox' value='yes' onClick='copyFormData()' />Check to include yourself as an interviewee<br />";					
					
					foreach ($getSurveyContactFieldQuery->result() as $requiredContactField) {
						// get field types
						$getFieldTypeSql = "SELECT fieldType " .
										"FROM rm_fieldTypes " .
										"WHERE fieldTypeID = ? ";
						$getFieldTypeQuery = $this->db->query($getFieldTypeSql, array($requiredContactField->fieldTypeID));	
																								
							foreach ($getFieldTypeQuery->result() as $requiredFieldType) {
																								
								if ($requiredContactField->required == 1) {
							
									if ($requiredFieldType->fieldType == 'text') {
										$surveyHtml .= "<br />";
										$surveyHtml .= "<label for='$requiredContactField->contactFieldID'>$requiredContactField->contactField&nbsp;&nbsp;*</label>";
										$surveyHtml .= "<br />";
										if ($requiredContactField->contactField == 'Email') {
											$surveyHtml .= "<input name='surveyContacts[$requiredContactField->contactFieldID]' id='$requiredContactField->contactFieldID' type='$requiredFieldType->fieldType' class='required email' /><br /><br />";
											$contactFields['email'] = $requiredContactField->contactFieldID;
										} elseif ($requiredContactField->contactField == 'Phone') {
											$surveyHtml .= "<input name='surveyContacts[$requiredContactField->contactFieldID]' id='$requiredContactField->contactFieldID' type='$requiredFieldType->fieldType' class='required phone' />&nbsp;(xxx-xxx-xxxx)<br /><br />";
											$contactFields['phone'] = $requiredContactField->contactFieldID;
										} elseif ($requiredContactField->contactField == 'First Name') { 
											$surveyHtml .= "<input name='surveyContacts[$requiredContactField->contactFieldID]' id='$requiredContactField->contactFieldID' type='$requiredFieldType->fieldType' class='required' /><br /><br />";
											$contactFields['firstName'] = $requiredContactField->contactFieldID;
										} elseif ($requiredContactField->contactField == 'Last Name') {
											$surveyHtml .= "<input name='surveyContacts[$requiredContactField->contactFieldID]' id='$requiredContactField->contactFieldID' type='$requiredFieldType->fieldType' class='required' /><br /><br />";
											$contactFields['lastName'] = $requiredContactField->contactFieldID;
										}				
									
									} elseif ($requiredFieldType->fieldType == 'textarea') {
										$surveyHtml .= "<label for='$requiredContactField->contactFieldID'>$requiredContactField->contactField&nbsp;&nbsp;*</label>";
										$surveyHtml .= "<br />";
										$surveyHtml .= "<textarea name='surveyContacts[$requiredContactField->contactFieldID]' rows='3' cols='50' wrap='hard' class='required'></textarea><br /><br />";
										
									} 
								} 
							}
					}
				}
				
				$surveyHtml .= "</p>";
				// javaScript used to copy contact form fields
				$surveyHtml .= "<script type='text/javascript'>";
				$surveyHtml .= "function copyFormData() { ";
				
				if (is_array($contactFields) && !empty($contactFields)) {
					if ($contactFields['firstName']) {
						$fieldID = $contactFields['firstName']; 
						$surveyHtml .= "var fieldName = 'surveyContacts[$fieldID]'; ";
						$surveyHtml .= "if (document.survey.copyData.checked) { document.survey[fieldName].value=document.survey.firstName.value; } else { document.survey[fieldName].value=''; } ";
					}
					if ($contactFields['lastName']) {
						$fieldID = $contactFields['lastName'];
						$surveyHtml .= "var fieldName = 'surveyContacts[$fieldID]'; ";
						$surveyHtml .= "if (document.survey.copyData.checked) { document.survey[fieldName].value=document.survey.lastName.value; } else { document.survey[fieldName].value=''; } ";
					}
					if ($contactFields['phone']) {
						$fieldID = $contactFields['phone'];
						$surveyHtml .= "var fieldName = 'surveyContacts[$fieldID]'; ";
						$surveyHtml .= "if (document.survey.copyData.checked) { document.survey[fieldName].value=document.survey.phoneNumber.value; } else { document.survey[fieldName].value=''; } ";
					}
					if ($contactFields['email']) {
						$fieldID = $contactFields['email'];
						$surveyHtml .= "var fieldName = 'surveyContacts[$fieldID]'; ";
						$surveyHtml .= "if (document.survey.copyData.checked) { document.survey[fieldName].value=document.survey.emailAddress.value; } else { document.survey[fieldName].value=''; } ";
					}
				}
				
				$surveyHtml .= "} ";
				$surveyHtml .= "</script>";
				// end javaScript
			
			}
											
			// set JavaScript to toggle non-required contact div
			$surveyHtml .= "<script>";
			// build jQuery function for show/hide effect
			$surveyHtml .= "$(document).ready(function(){ "; 
			$surveyHtml .= "$('#addContacts').click(function () { ";
			$surveyHtml .= "$('#surveyContacts').toggle('fast'); ";	
			$surveyHtml .= "}); "; 
			$surveyHtml .= "}); ";
			$surveyHtml .= "</script>";
			
			$surveyHtml .= "<p><a id='addContacts'><img src='$imagePath/plus.gif' alt='Add More Contacts' />&nbsp;&nbsp;Add More Contacts</a></p>";				
			$surveyHtml .= "<div id='surveyContacts' style='display: none;'>";
			$surveyHtml .= "<p>";
			
			foreach ($getSurveyContactFieldQuery->result() as $contactField) {
				// get field types
				$getFieldTypeSql = "SELECT fieldType " .
								"FROM rm_fieldTypes " .
								"WHERE fieldTypeID = ? ";
				$getFieldTypeQuery = $this->db->query($getFieldTypeSql, array($contactField->fieldTypeID));	
							
				foreach ($getFieldTypeQuery->result() as $fieldType) {
					if ($contactField->required == 0) {
						if ($fieldType->fieldType == 'text') {
							$surveyHtml .= "<br />";
							$surveyHtml .= "<label for='$contactField->contactFieldID'>$contactField->contactField</label>";
							$surveyHtml .= "<br />";
							if ($contactField->contactField == 'Email') {
								$surveyHtml .= "<input name='surveyContacts[$contactField->contactFieldID]' id='$contactField->contactFieldID' type='$fieldType->fieldType' class='email' /><br /><br />";
							} elseif ($contactField->contactField == 'Phone') {	
								$surveyHtml .= "<input name='surveyContacts[$contactField->contactFieldID]' id='$contactField->contactFieldID' type='$fieldType->fieldType' class='phone' />&nbsp;(xxx-xxx-xxxx)<br /><br />";
							} else {
								$surveyHtml .= "<input name='surveyContacts[$contactField->contactFieldID]' id='$contactField->contactFieldID' type='$fieldType->fieldType' /><br /><br />";
							}
							
						} elseif ($fieldType->fieldType == 'textarea') {
							$surveyHtml .= "<label for='$contactField->contactFieldID'>$contactField->contactField </label>";
							$surveyHtml .= "<br />";
							$surveyHtml .= "<textarea name='surveyContacts[$contactField->contactFieldID]' rows='3' cols='50' wrap='hard'></textarea><br /><br />";
						} 
					}	
								
				}
			}
			
			$surveyHtml .= "</p>";
			$surveyHtml .= "</div>";
			$surveyHtml .= "<br /><br />";
		}
		
			$surveyHtml .= "<br /><p><input name='submit' type='submit' value='Submit your responses' /></form></p>";
			$surveyHtml .= "</div>"; // closes content
		
		 return $surveyHtml; 	 
	}
	
}
 
?>