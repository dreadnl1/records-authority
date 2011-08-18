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

class Survey extends Controller {

	public function __construct() {
		parent::Controller();
		
		$this->devEmail = $this->config->item('devEmail');
		$this->prodEmail = $this->config->item('prodEmail');
	} 
	
	/**
    * processes incoming $_POST array from survey form
    *
    * @access  public
    * @return  void 
    */
	public function index() {

		if (empty($_POST)) {
			echo "An error has occured. The survey was not submitted.";	
		}
						
		// remove smart quotes
		$this->load->library('convertsmartquotes');
		$_POST = $this->convertsmartquotes->convert($_POST);
						
		// check if department has already submitted a survey
		$checkDepartmentQuery = $this->db->get_where('rm_departmentContacts', array('departmentID'=>$_POST['departmentID']));
		
		if ($checkDepartmentQuery->num_rows() > 0) {
			echo "Your department has already submitted a survey. Please contact the Records Management Department at 303-871-3334 or email records-mgmt@du.edu";
		} else {
			$this->SurveyModel->saveSurveyResponses($_POST, $_FILES);
			$this->sendEmail($_POST);
			
			$this->load->library('session');
			$this->session->sess_destroy();
			
			$url =  base_url() . "success/";		
			$data['refresh'] = header("Refresh: 1; url=$url");
			$data['message'] = "<p><strong>Saving Responses...</strong></p>";
			$this->load->view('includes/redirect', $data);	
		} 
	} 
	
	/**
    * sends email when survey is successfully submitted
    *
    * @access  private
    * @param $_POST
    * @return  void 
    */
	private function sendEmail($_POST) {
		
		$this->load->library('email');
		$this->load->model('LookUpTablesModel');
		
		$division = trim($_POST['divisionID']);
		$department = trim($_POST['departmentID']);
		// place this email in config database/table
		$toEmail = $this->prodEmail; 
		$fromEmail = trim($_POST['emailAddress']);
		
		$firstName = trim($_POST['firstName']);
		$lastName = trim($_POST['lastName']);
		$phoneNumber = trim($_POST['phoneNumber']);
				
		$results = $this->LookUpTablesModel->getDivision($department);
		
		$divResult = $results['divisionName'];
		$deptResult = $results['departmentName'];
		
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);
	
		$this->email->from($fromEmail, 'Records Management');
		$this->email->to($toEmail);
		$this->email->bcc($this->devEmail);
		
		$this->email->subject('Records Management Survey');
		$this->email->message('A survey has just been submitted by:<br /><br /> ' .
				'<strong>Division:</strong> ' . $divResult . '<br />' .
				'<strong>Department:</strong> ' . $deptResult . '<br /><br />' .
				$firstName . ' ' . $lastName . '<br />' .
				$phoneNumber 
				);
		
		$this->email->send();
	} 
	
	/**
    * gets departments (used by survey)
    *
    * @access public
    * @param  $_POST['divisionID']
    * @return model returns JSON package containing departments  
    */
	public function getDepartments() {
		if (!empty($_POST['divisionID'])) {
			$divisionID = $_POST['divisionID'];
			$this->SurveyModel->getDepartments($divisionID);
		} else {
			echo "an error has occurred";
		} 
	}
} 

?>
