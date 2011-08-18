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

class Upkeep extends Controller {
	
	public function __construct() {
		parent::Controller();
	
		// admin user must be loggedin in order to use dashboard methods
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
		$this->load->model('UpkeepModel');
		$this->load->model('LookUpTablesModel'); 
		$this->imagePath = base_url() . "images/ffd40f_11x11_icon_close.gif";
		
	} 
	
	/**
    * displays division form 
    *
    * @access public
    * @return void
    */
	public function divisionForm() {
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/addDivisionForm', $data);
	}
	
	/**
    * displays department form 
    *
    * @access public
    * @return void
    */
	public function departmentForm() {
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/addDepartmentForm', $data);
	}
	
	/**
    * displays record category form 
    *
    * @access public
    * @return void
    */
	public function recordCategoryForm() {
		$this->load->view('admin/forms/addRecordCategoryForm');
	}
		
	/**
    * displays user form 
    *
    * @access public
    * @return void
    */
	public function userForm() {	
		$this->load->view('admin/forms/addUserForm');
	}
	
	/**
    * displays edit division form 
    *
    * @access public
    * @return $divisions
    */
	public function editDivisionForm() {
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/editDivisionForm', $data);
	}
	
	/**
    * displays edit department form 
    *
    * @access public
    * @return $departments
    */
	public function editDepartmentForm() {
		$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
		$this->load->view('admin/forms/editDepartmentForm', $data);
	}
	
	/**
    * displays edit record categories form 
    *
    * @access public
    * @return $recordCategories
    */
	public function editRecordCategoryForm() {
		$data['recordCategories'] = $this->UpkeepModel->getRecordCategories();
		$this->load->view('admin/forms/editRecordCategoryForm', $data);
	}
	
	/**
    * displays edit user form 
    *
    * @access public
    * @return $users
    */
	public function editUserForm() {
		$data['users'] = $this->UpkeepModel->getUsers();
		$this->load->view('admin/forms/editUserForm', $data);
	}
	
	/**
    * displays edit password form 
    *
    * @access public
    * @return $users
    */
	public function editPasswordForm() {
		$data['user'] = $this->session->userdata('username');
		$this->load->view('admin/forms/editPasswordForm', $data);
	}
	
	/**
    * displays add doc types form
    *
    * @access public
    * @return $docTypes
    */
	public function addDocTypeForm() {
		$data['docTypes'] = $this->UpkeepModel->getDocTypes();
		$this->load->view('admin/forms/addDocTypeForm', $data);
	}
	
	/**
    * displays edit doc types form
    *
    * @access public
    * @return $docTypes
    */
	public function editDocTypeForm() {
		$data['docTypes'] = $this->UpkeepModel->getDocTypes();
		$this->load->view('admin/forms/editDocTypeForm', $data);
	}
	
	/**
    * saves values to database from upkeep forms
    *
    * @access public
    * @return void
    */
	public function save() {
						
		if (isset($_POST['recordCategory'])) {
			$this->UpkeepModel->saveRecordCategory($_POST);
			$data['recordSaved'] = "Record Saved";
			$data['imagePath'] = $this->imagePath;
			$this->load->view('admin/displays/recordSaved', $data);
		}
		if (isset($_POST['divisionName'])) {
			$this->UpkeepModel->saveDivision($_POST);
			$data['recordSaved'] = "Record Saved";
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$this->load->view('admin/forms/addDivisionForm', $data);
		}
		if (isset($_POST['departmentName'])) {
			$this->UpkeepModel->saveDepartment($_POST);
			$data['recordSaved'] = "Record Saved";
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$this->load->view('admin/forms/addDepartmentForm', $data);
		}
		if (isset($_POST['docType'])) {
			$this->UpkeepModel->saveDocType($_POST);
			$data['recordSaved'] = "Record Saved";
			$data['docTypes'] = $this->UpkeepModel->getDocTypes();
			$this->load->view('admin/forms/addDocTypeForm', $data);
		}
		if (isset($_POST['username'])) {
			$this->UpkeepModel->saveUser($_POST);
			$data['recordSaved'] = "User Added";
			$data['imagePath'] = $this->imagePath;
			$this->load->view('admin/displays/recordSaved', $data);
		}
	}

	/**
    * edits values to database from upkeep forms
    *
    * @access public
    * @return void
    */	
	public function edit() {
				
		if (isset($_POST['divisionID']) && !isset($_POST['getDept']) && !isset($_POST['editDept'])) {
			$data['divisionName'] = $this->UpkeepModel->getDivision($_POST);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$this->load->view('admin/forms/editDivisionForm', $data);
		}
		if (isset($_POST['divisionID']) && isset($_POST['getDept'])) {
			$data['departments'] = $this->UpkeepModel->getDepartments($_POST);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$this->load->view('admin/forms/editDepartmentForm', $data);
		}
		if (isset($_POST['departmentID']) && isset($_POST['editDept']) && isset($_POST['divisionID'])) {
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$data['departments'] = $this->UpkeepModel->getDepartments($_POST);
			$data['departmentName'] = $this->UpkeepModel->getDepartment($_POST);	
			$this->load->view('admin/forms/editDepartmentForm', $data);	
		}
		if (isset($_POST['recordCategoryID'])) {
			$data['recordCategory'] = $this->UpkeepModel->getRecordCategory($_POST);
			$data['recordCategories'] = $this->UpkeepModel->getRecordCategories();
			$this->load->view('admin/forms/editRecordCategoryForm', $data);	
		}
		if (isset($_POST['docTypeID'])) {
			$data['docTypes'] = $this->UpkeepModel->getDocTypes();
			$data['docType'] = $this->UpkeepModel->getDocType($_POST);
			$this->load->view('admin/forms/editDocTypeForm', $data);	
		}
		if (isset($_POST['userID'])) {
			$data['user'] = $this->UpkeepModel->getUser($_POST);
			$data['users'] = $this->UpkeepModel->getUsers();		
			$this->load->view('admin/forms/editUserForm', $data);	
		}
	}

	/**
    * update values to database from upkeep forms
    *
    * @access public
    * @return void
    */		
	public function update() {
				
		if (isset($_POST['divisionName'])) {
			$this->UpkeepModel->updateDivision($_POST);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$data['recordUpdated'] = "Record Updated.";
			$this->load->view('admin/forms/editDivisionForm', $data);
		}
		if (isset($_POST['departmentName'])) {
			$this->UpkeepModel->updateDepartment($_POST);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$data['recordUpdated'] = "Record Updated.";
			$this->load->view('admin/forms/editDepartmentForm', $data);
		}
		if (isset($_POST['recordCategory'])) {
			$this->UpkeepModel->updateRecordCategory($_POST);
			$data['recordCategories'] = $this->UpkeepModel->getRecordCategories();
			$data['recordUpdated'] = "Record Updated.";
			$this->load->view('admin/forms/editRecordCategoryForm', $data);	
		}
		if (isset($_POST['docType'])) {
			$this->UpkeepModel->updateDocType($_POST);
			$data['docTypes'] = $this->UpkeepModel->getDocTypes();
			$data['recordUpdated'] = "Record Updated.";
			$this->load->view('admin/forms/editDocTypeForm', $data);
		}
		if (isset($_POST['username'])) {
			$this->UpkeepModel->updateUser($_POST);
			$data['users'] = $this->UpkeepModel->getUsers();
			$data['recordUpdated'] = "User Updated.";
			$this->load->view('admin/forms/editUserForm', $data);
		}
		if (isset($_POST['passcode'])) {
			$passtest = $this->UpkeepModel->checkOldPassword($_POST);
			if($passtest == TRUE) {
				if($_POST['passcode'] == $_POST['passtest']) {
					$this->UpkeepModel->updatePassword($_POST);
					$data['recordUpdated'] = "Password Changed.";
				} else {
					$data['recordUpdated'] = "Passwords did not match.";
				}
			} else {
				$data['recordUpdated'] = "Old Password did not match.";
			}
			$this->load->view('admin/forms/editPasswordForm',$data);
		}
	}

	/**
    * delete values from the database based on url string
    *
    * @access public
    * @return void
    */		
	public function delete() {

		// deletes division
		if ($this->uri->segment(3,0) == "delDiv") {
			$divisionID = $this->uri->segment(4, 0);
			$this->UpkeepModel->deleteDivision($divisionID);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$data['recordUpdated'] = "Record Deleted.";
			$this->load->view('admin/forms/editDivisionForm', $data);
		}
		
		// deletes department
		if ($this->uri->segment(3,0) == "delDept") {
			$departmentID = $this->uri->segment(4, 0);
			$this->UpkeepModel->deleteDepartment($departmentID);
			$data['divisions'] = $this->LookUpTablesModel->createDivisionDropDown();
			$data['recordUpdated'] = "Record Deleted.";
			$this->load->view('admin/forms/editDepartmentForm', $data);
		}
		
		// deletes record category
		if ($this->uri->segment(3,0) == "delRecCat") {
			$recordCategoryID = $this->uri->segment(4, 0);
			$this->UpkeepModel->deleteRecordCategory($recordCategoryID);
			$data['recordCategories'] = $this->UpkeepModel->getRecordCategories();
			$data['recordUpdated'] = "Record Deleted.";
			$this->load->view('admin/forms/editRecordCategoryForm', $data);	
		}
		
		// deletes doc type
		if ($this->uri->segment(3,0) == "delDocType") {
			$docTypeID = $this->uri->segment(4, 0);
			$this->UpkeepModel->deleteDocType($docTypeID);
			$data['docTypes'] = $this->UpkeepModel->getDocTypes();
			$data['recordUpdated'] = "Record Deleted.";
			$this->load->view('admin/forms/editDocTypeForm', $data);
		}
		
		// deletes user
		if ($this->uri->segment(3,0) == "delUser") {
			$userID = $this->uri->segment(4, 0);
			if($userID == "1")
			{
				$data['users'] = $this->UpkeepModel->getUsers();
				$data['recordUpdated'] = "Cannot Delete the Admin account.";
				$this->load->view('admin/forms/editUserForm', $data);
			} else {
				$this->UpkeepModel->deleteUser($userID);
				$data['users'] = $this->UpkeepModel->getUsers();
				$data['recordUpdated'] = "User Deleted.";
				$this->load->view('admin/forms/editUserForm', $data);
			}
		}
	}

}

?>
