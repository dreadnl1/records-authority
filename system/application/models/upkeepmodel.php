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


class UpkeepModel extends Model {

	public function __construct() {
 		parent::Model();
 	}
 	
 	/**
    * invokes saveRecordCategory()
    *
    * @access  public
    * @return  void
    */
	public function saveRecordCategory() {
		$this->saveRecordCategoryQuery($_POST);
	}
	
	/**
    * saves record category to database 
    *
    * @access  private
    * @return  void
    */
	private function saveRecordCategoryQuery($_POST) {
		$recordCategory = array();
		$recordCategory['recordCategory'] = $_POST['recordCategory'];
		$this->db->insert('rm_recordCategories', $recordCategory);
	}
	
	/**
    * invokes saveUser()
    *
    * @access  public
    * @return  void
    */
	public function saveUser() {
		$this->saveUserQuery($_POST);
	}
	
	/**
    * saves user to database 
    *
    * @access  private
    * @return  void
    */
	private function saveUserQuery($_POST) {
		$array = array();
		$user['username'] = $_POST['username'];
		
		$passcode = $_POST['passcode'];
		$this->load->library('encrypt');
		$passcodeHash = $this->encrypt->sha1($passcode);
		
		$user['passcode'] = $passcodeHash;
		
		$this->db->insert('rm_users', $user);
	}
	
	/**
    * invokes saveDivisionQuery()
    *
    * @access  public
    * @return  void
    */
	public function saveDivision() {
		$this->saveDivisionQuery($_POST);
	}
	
	/**
    * saves division to database 
    *
    * @access  private
    * @return  void
    */
	private function saveDivisionQuery($_POST) {
		$divisionName = array();
		$divisionName['divisionName'] = $_POST['divisionName'];
		$this->db->insert('rm_divisions', $divisionName);
	}
	
/**
    * invokes saveDepartmentQuery()
    *
    * @access  public
    * @return  void
    */
	public function saveDepartment() {
		$this->saveDepartmentQuery($_POST);
	}
	
	/**
    * saves department to database 
    *
    * @access  private
    * @return  void
    */
	private function saveDepartmentQuery($_POST) {
		$department = array();
		$department['divisionID'] = $_POST['divisionID'];
		$department['departmentName'] = $_POST['departmentName'];
		$this->db->insert('rm_departments', $department);
	}
	
	/**
    * invokes getDivisionQuery()
    *
    * @access  public
    * @return  void
    */
	public function getDivision() {
		$divisionName = $this->getDivisionQuery($_POST);
		return $divisionName;
	}
	
	/**
    * gets division from database 
    *
    * @access  private
    * @return  void
    */
	private function getDivisionQuery($_POST) {
		$this->db->select('divisionName');
		$this->db->from('rm_divisions');
		$this->db->where('divisionID', $_POST['divisionID']);
		$divisionNameQuery = $this->db->get();
			
		if ($divisionNameQuery->num_rows > 0) {
			$divisonNameResult = $divisionNameQuery->row();
			$divisionName = $divisonNameResult->divisionName;
			
			return $divisionName;
		}
	}
	
	/**
    * invokes updateDivisionQuery()
    *
    * @access  public
    * @return  void
    */
	public function updateDivision() {
		$this->updateDivisionQuery($_POST);
	}
	
	/**
    * updates division  
    *
    * @access  private
    * @return  void
    */
	private function updateDivisionQuery($_POST) {
		$division = array();
		$division['divisionName'] = $_POST['divisionName'];
		$this->db->where('divisionID', $_POST['divisionID']);
		$this->db->update('rm_divisions', $division);
	}
	
	/**
    * invokes getDepartmentsQuery()
    *
    * @access  public
    * @return  void
    */
	public function getDepartments($_POST) {
		$departments = $this->getDepartmentsQuery($_POST);
		return $departments;
	}
	
	/**
    * gets departments to edit  
    *
    * @access  private
    * @return  $departments
    */
	private function getDepartmentsQuery($_POST) {
		$this->db->select('departmentID, departmentName');
		$this->db->from('rm_departments');
		$this->db->where('divisionID', $_POST['divisionID']);
		$departmentsQuery = $this->db->get();
		
		if ($departmentsQuery->num_rows > 0) {
		
			foreach ($departmentsQuery->result() as $results) {
				$departments[$results->departmentID] = $results->departmentName;
			}
			return $departments;		
		}
	}
	
	/**
    * invokes getDepartmentQuery()
    *
    * @access  public
    * @return  void
    */
	public function getDepartment($_POST) {
		$departmentName = $this->getDepartmentQuery($_POST);
		return $departmentName;
	}
	
	/**
    * gets department to edit  
    *
    * @access  private
    * @return  $departmentName
    */
	private function getDepartmentQuery($_POST) {
		$this->db->select('departmentID, departmentName');
		$this->db->from('rm_departments');
		$this->db->where('departmentID', $_POST['departmentID']);
		$departmentQuery = $this->db->get();
		
		if ($departmentQuery->num_rows > 0) {
		
			foreach ($departmentQuery->result() as $results) {
				$departmentName = $results->departmentName;
			}
			return $departmentName;		
		}
	}
	
	/**
    * invokes updateDepartmentQuery()
    *
    * @access  public
    * @return  void
    */
	public function updateDepartment() {
		$this->updateDepartmentQuery($_POST);
	}
	
	/**
    * updates division  
    *
    * @access  private
    * @return  void
    */
	private function updateDepartmentQuery($_POST) {
		$department = array();
		$department['departmentName'] = $_POST['departmentName'];
		$this->db->where('departmentID', $_POST['departmentID']);
		$this->db->update('rm_departments', $department);
	}
	
	/**
    * invokes getRecordCategoriesQuery()
    *
    * @access  public
    * @return  $recordCategories
    */
	public function getRecordCategories() {
		$recordCategories = $this->getRecordCategoriesQuery();
		return $recordCategories;
	}
	
	/**
    * gets record categories to edit  
    *
    * @access  private
    * @return  $recordCategories
    */
	private function getRecordCategoriesQuery() {
		$recordCategories = array();
	 	$this->db->select('recordCategoryID, recordCategory');
	 	$this->db->from('rm_recordCategories');
	 	$this->db->order_by('recordCategory', 'asc');
	 	$recordCategoryQuery = $this->db->get();
	 		 		 
	 	if ($recordCategoryQuery->num_rows() > 0) {		
	 		 foreach ($recordCategoryQuery->result() as $results) {
			 	$recordCategories[$results->recordCategoryID] = $results->recordCategory;
			 }
	 	}		
	 		return $recordCategories;
	}
	
	/**
    * invokes getUsersQuery()
    *
    * @access  public
    * @return  $users
    */
	public function getUsers() {
		$users = $this->getUsersQuery();
		return $users;
	}
	
	/**
    * gets users to edit  
    *
    * @access  private
    * @return  $users
    */
	private function getUsersQuery() {
		$users = array();
	 	$this->db->select('userID, username');
	 	$this->db->from('rm_users');
	 	$this->db->order_by('username', 'asc');
	 	$usersQuery = $this->db->get();
	 		 		 
	 	if ($usersQuery->num_rows() > 0) {		
	 		 foreach ($usersQuery->result() as $results) {
			 	$users[$results->userID] = $results->username;
			 }
	 	}		
	 		return $users;
	}
	
	/**
    * invokes getRecordCategoryQuery()
    *
    * @access  public
    * @return  $recordCategory
    */
	public function getRecordCategory($_POST) {
		$recordCategory = $this->getRecordCategoryQuery($_POST);
		return $recordCategory;
	}
	
	/**
    * gets record category to edit  
    *
    * @access  private
    * @return  $recordCategory
    */
	private function getRecordCategoryQuery($_POST) {
		$this->db->select('recordCategoryID, recordCategory');
		$this->db->from('rm_recordCategories');
		$this->db->where('recordCategoryID', $_POST['recordCategoryID']);
		$recordCategoryQuery = $this->db->get();
		
		if ($recordCategoryQuery->num_rows > 0) {
			foreach ($recordCategoryQuery->result() as $results) {
				$recordCategory = $results->recordCategory;
			}
			return $recordCategory;		
		}
	}
	
	/**
    * invokes getUserQuery()
    *
    * @access  public
    * @return  $user
    */
	public function getUser($_POST) {
		$user = $this->getUserQuery($_POST);
		return $user;
	}
	
	/**
    * gets user to edit  
    *
    * @access  private
    * @return  $user
    */
	private function getUserQuery($_POST) {
		$this->db->select('userID, username');
		$this->db->from('rm_users');
		$this->db->where('userID', $_POST['userID']);
		$userQuery = $this->db->get();
		
		if ($userQuery->num_rows > 0) {
			foreach ($userQuery->result() as $results) {
				$user = $results->username;
			}
			return $user;		
		}
	}
	
	/**
    * invokes updateRecordCategoryQuery()
    *
    * @access  public
    * @return  void
    */
	public function updateRecordCategory() {
		$this->updateRecordCategoryQuery($_POST);
	}
	
	/**
    * updates record category  
    *
    * @access  private
    * @return  void
    */
	private function updateRecordCategoryQuery($_POST) {
		$recordCategory = array();
		$recordCategory['recordCategory'] = $_POST['recordCategory'];
		$this->db->where('recordCategoryID', $_POST['recordCategoryID']);
		$this->db->update('rm_recordCategories', $recordCategory);
	}
	
	/**
    * invokes updateUserQuery()
    *
    * @access  public
    * @return  void
    */
	public function updateUser() {
		$this->updateUserQuery($_POST);
	}
	
	/**
    * updates user 
    *
    * @access  private
    * @return  void
    */
	private function updateUserQuery($_POST) {
		$user = array();
		$user['username'] = $_POST['username'];
		$this->db->where('userID', $_POST['userID']);
		$this->db->update('rm_users', $user);
	}
	
	/**
	 * invokes checkUserNameQuery()
	 *
	 *	@access public
	 *	@return $check TRUE/FALSE
	 */
	public function checkUserName() {
		$check = $this->checkUserNameQuery($_POST);
		return $check;
	}
	
	/**
	 *	checks user name is taken
	 *	
	 *	@access private
	 *	@return boolean
	 */
	private function checkUserNameQuery($_POST) {
		$username = $_POST['username'];
		
		$this->db->select('username');
	 	$this->db->from('rm_users');
	 	$this->db->where('username', $username);

 		$isTaken = $this->db->get();
 		
	 	if ($isTaken->num_rows > 0) {
 			return FALSE;
 		} else {
 			return TRUE;
 		}
	}
	
	/**
	 * invokes checkOldPasswordQuery()
	 *
	 *	@access public
	 *	@return $check TRUE/FALSE
	 */
	public function checkOldPassword() {
		$check = $this->checkOldPasswordQuery($_POST);
		return $check;
	}
	
	/**
	 *	checks old password is known 
	 *	
	 *	@access private
	 *	@return boolean
	 */
	private function checkOldPasswordQuery($_POST) {
		$username = $this->session->userdata('username');
		$passcode = $_POST['oldcode'];
		
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
    * invokes updatePasswordQuery()
    *
    * @access  public
    * @return  void
    */
	public function updatePassword() {
		$this->updatePasswordQuery($_POST);
	}
	
	/**
    * updates password 
    *
    * @access  private
    * @return  void
    */
	private function updatePasswordQuery($_POST) {
		$user = array();
		$username = $this->session->userdata('username');
		
		$passcode = $_POST['passcode'];
		$this->load->library('encrypt');
		$passcodeHash = $this->encrypt->sha1($passcode);
		$user['passcode'] = $passcodeHash;
		
		$this->db->select('userID');
		$this->db->where('username', $username);
		$this->db->from('rm_users');
		$userIDQuery = $this->db->get();
		
		if ($userIDQuery->num_rows > 0) {
			foreach ($userIDQuery->result() as $results) {
				$userID = $results->userID;
			}
			$this->db->where('userID', $userID);
			$this->db->update('rm_users', $user);		
		}
	}
	
	/**
    * invokes deleteDepartmentQuery()
    *
    * @access  public
    * @return  void
    */
	public function deleteDepartment($departmentID) {
		$this->deleteDepartmentQuery($departmentID);
	}
	
	/**
    * deletes department  
    *
    * @access  private
    * @return  void
    */
	private function deleteDepartmentQuery($departmentID) {
		$this->db->where('departmentID', $departmentID);
		$this->db->delete('rm_departments');
	}
	
	/**
    * invokes deleteDivisionQuery()
    *
    * @access  public
    * @return  void
    */
	public function deleteDivision($divisionID) {
		$this->deleteDivisionQuery($divisionID);
	}
	
	/**
    * delete division  
    *
    * @access  private
    * @return  void
    */
	private function deleteDivisionQuery($divisionID) {
		
		// check to see if there are departments under selected divison 
		$this->db->select('rm_departments');
		$this->db->from('rm_departments');
		$this->db->where('divisionID', $divisionID);
		$results = $this->db->count_all_results();
		
		if ($results > 0) {
			// removes associated departments
			$this->db->where('divisionID',$divisionID);
			$this->db->delete('rm_departments');
		}
		
		// removes division
		$this->db->where('divisionID', $divisionID);
		$this->db->delete('rm_divisions');
		
	}
	
	/**
    * invokes deleteRecordCategoryQuery()
    *
    * @access  public
    * @return  void
    */
	public function deleteRecordCategory($recordCategoryID) {
		$this->deleteRecordCategoryQuery($recordCategoryID);
	}
	
	/**
    * delete record category  
    *
    * @access  private
    * @return  void
    */
	private function deleteRecordCategoryQuery($recordCategoryID) {
		$this->db->where('recordCategoryID', $recordCategoryID);
		$this->db->delete('rm_recordCategories');
	}
	
	/**
    * invokes deleteUserQuery()
    *
    * @access  public
    * @return  void
    */
	public function deleteUser($userID) {
		$this->deleteUserQuery($userID);
	}
	
	/**
    * delete user 
    *
    * @access  private
    * @return  void
    */
	private function deleteUserQuery($userID) {
		$this->db->where('userID', $userID);
		$this->db->delete('rm_users');
	}
	
	/**
    * invokes getDocTypeQuery()
    *
    * @access  public
    * @return  $recordCategories
    */
	public function getDocTypes() {
		$recordCategories = $this->getDocTypesQuery();
		return $recordCategories;
	}
	
	/**
    * gets document types
    *
    * @access  private
    * @return  $docTypes
    */
	private function getDocTypesQuery() {
		$this->db->select('docTypeID, docType');
		$this->db->from('rm_docTypes');
		
		$docTypeQuery = $this->db->get();
		
		if ($docTypeQuery->num_rows > 0) {
			foreach ($docTypeQuery->result() as $results) {
				$docTypes[$results->docTypeID] = $results->docType;
			}
			return $docTypes;		
		}
	}
	
	/**
    * invokes saveDocTypeQuery()
    *
    * @access  public
    * @return  void
    */
	public function saveDocType() {
		$this->saveDocTypeQuery($_POST);
	}
	
	/**
    * saves doc type to database 
    *
    * @access  private
    * @return  void
    */
	private function saveDocTypeQuery($_POST) {
		$docType = array();
		$docType['docType'] = $_POST['docType'];
		$this->db->insert('rm_docTypes', $docType);
	}
 	
	
	/**
    * invokes getDocTypeQuery()
    *
    * @access  public
    * @return  $docType
    */
	public function getDocType($_POST) {
		$docType = $this->getDocTypeQuery($_POST);
		return $docType;
	}
	
	/**
    * gets doc type to edit  
    *
    * @access  private
    * @return  $docType
    */
	private function getDocTypeQuery($_POST) {
		$this->db->select('docType');
		$this->db->from('rm_docTypes');
		$this->db->where('docTypeID', $_POST['docTypeID']);
		$docTypeQuery = $this->db->get();
		
		if ($docTypeQuery->num_rows > 0) {
		
			foreach ($docTypeQuery->result() as $results) {
				$docType = $results->docType;
			}
			return $docType;		
		}
	}
	
	/**
    * invokes updateDocTypeQuery()
    *
    * @access  public
    * @return  void
    */
	public function updateDocType() {
		$this->updateDocTypeQuery($_POST);
	}
	
	/**
    * updates doc type  
    *
    * @access  private
    * @return  void
    */
	private function updateDocTypeQuery($_POST) {
		$docType = array();
		$docType['docType'] = $_POST['docType'];
		$this->db->where('docTypeID', $_POST['docTypeID']);
		$this->db->update('rm_docTypes', $docType);
	}
	
	/**
    * invokes deleteDocTypeQuery()
    *
    * @access  public
    * @return  void
    */
	public function deleteDocType($docTypeID) {
		$this->deleteDocTypeQuery($docTypeID);
	}
	
	/**
    * delete doc type  
    *
    * @access  private
    * @return  void
    */
	private function deleteDocTypeQuery($docTypeID) {
		$this->db->where('docTypeID', $docTypeID);
		$this->db->delete('rm_docTypes');
	}
	
	/**
    * gets doc types for autosuggest display
    *
    * @access  public
    * @return  $docTypes
    */
	public function autoSuggest_getDocTypes() {
		$docTypes = $this->getDocTypesQuery();
		return $docTypes;
	}
	
	/**
    * gets primary authorities types for autosuggest display
    *
    * @access  public
    * @return  $primaryAuthorities
    */
	public function autoSuggest_primaryAuthorities($primaryAuthority) {
		$primaryAuthorities = $this->getPrimaryAuthoritiesQuery($primaryAuthority);
		return $primaryAuthorities;  
	}
	
	/**
    * gets primary authorities for autosuggest display
    *
    * @access  	private
    * @param	$primaryAuthority
    * @return  	$primaryAuthority
    */
	private function getPrimaryAuthoritiesQuery($primaryAuthority) {
		$sql = "SELECT DISTINCT primaryAuthority FROM rm_retentionSchedule WHERE primaryAuthority LIKE ? ";
		$primaryAuthorityQuery = $this->db->query($sql, array('%' . $primaryAuthority . '%'));
		$primaryAuthority = array();
		if ($primaryAuthorityQuery->num_rows > 0) {
			foreach ($primaryAuthorityQuery->result() as $results) {
				$primaryAuthority[] = $results->primaryAuthority;
			}
			return $primaryAuthority;		
		} //else {
			//return $primaryAuthority = "No results found.";
		//}
	}
		
	/**
    * gets primary authorities retentions for autosuggest display
    *
    * @access  	public
    * @param	$primaryAuthorityRetention
    * @return  	$primaryAuthorityRetentions
    */	
	public function autoSuggest_primaryAuthorityRetentions($primaryAuthorityRetention) {
		$primaryAuthorityRetentions = $this->getPrimaryAuthorityRetentionsQuery($primaryAuthorityRetention);
		return $primaryAuthorityRetentions;  
	}
	
	/**
    * gets primary authorities retentions
    *
    * @access  	private
    * @param	$primaryAuthorityRetention
    * @return  	$primaryAuthorityRetention
    */	
	private function getPrimaryAuthorityRetentionsQuery($primaryAuthorityRetention) {
		$sql = "SELECT DISTINCT primaryAuthorityRetention FROM rm_retentionSchedule WHERE primaryAuthorityRetention LIKE ? ";
		$primaryAuthorityRetentionQuery = $this->db->query($sql, array('%' . $primaryAuthorityRetention . '%'));
		$primaryAuthorityRetention = array();
		if ($primaryAuthorityRetentionQuery->num_rows > 0) {
			foreach ($primaryAuthorityRetentionQuery->result() as $results) {
				$primaryAuthorityRetention[] = $results->primaryAuthorityRetention;
			}
			return $primaryAuthorityRetention;		
		} //else {
			//return $primaryAuthorityRetention = "No results found.";
		//}
	}
	
	/**
    * gets related authorities for autosuggest display
    *
    * @access  	public
    * @param	$relatedAuthority
    * @return  	$relatedAuthority
    */	
	public function autoSuggest_relatedAuthorities($relatedAuthority) {
		$relatedAuthorities = $this->getRelatedAuthoritiesQuery($relatedAuthority);
		return $relatedAuthorities;  
	}
	
	/**
    * gets related authorities
    *
    * @access  	private
    * @param	$relatedAuthority
    * @return  	$relatedAuthority
    */	
	private function getRelatedAuthoritiesQuery($relatedAuthority) {
		$sql = "SELECT DISTINCT rsRelatedAuthority FROM rm_rsRelatedAuthorities WHERE rsRelatedAuthority LIKE ? ";
		$relatedAuthorityQuery = $this->db->query($sql, array('%' . $relatedAuthority . '%'));
		$relatedAuthority = array();
		if ($relatedAuthorityQuery->num_rows > 0) {
			foreach ($relatedAuthorityQuery->result() as $results) {
				$relatedAuthority[] = $results->rsRelatedAuthority;
			}
			return $relatedAuthority;		
		} //else {
			//return $relatedAuthority = "No results found.";
		//}
	}
	
	/**
    * gets retention Period for autosuggest display
    *
    * @access  	public
    * @param	$retentionPeriod
    * @return  	$retentionPeriod
    */	
	public function autoSuggest_retentionPeriods($retentionPeriod) {
		$retentionPeriods = $this->getRetentionPeriodQuery($retentionPeriod);
		return $retentionPeriods;  
	}
	
	/**
    * gets retention Period
    *
    * @access  	private
    * @param	$retentionPeriod
    * @return  	$retentionPeriod
    */
	private function getRetentionPeriodQuery($retentionPeriod) {
		$sql = "SELECT DISTINCT retentionPeriod FROM rm_retentionSchedule WHERE retentionPeriod LIKE ? ";
		$retentionPeriodQuery = $this->db->query($sql, array('%' . $retentionPeriod . '%'));
		$retentionPeriod = array();
		if ($retentionPeriodQuery->num_rows > 0) {
			foreach ($retentionPeriodQuery->result() as $results) {
				$retentionPeriod[] = $results->retentionPeriod;
			}
			return $retentionPeriod;		
		} //else {
			//return $retentionPeriod = "No results found.";
		//}
	}
	
	/**
    * gets related authority retention for autosuggest display
    *
    * @access  	public
    * @param	$relatedAuthorityRetention
    * @return  	$relatedAuthorityRetention
    */
	public function autoSuggest_relatedAuthorityRetention($relatedAuthorityRetention) {
		$relatedAuthorityRetentions = $this->getRelatedAuthorityRetentionQuery($relatedAuthorityRetention);
		return $relatedAuthorityRetentions;  
	}
	
	/**
    * gets related authority retention
    *
    * @access  	private
    * @param	$relatedAuthorityRetention
    * @return  	$relatedAuthorityRetention
    */
	private function getRelatedAuthorityRetentionQuery($relatedAuthorityRetention) {
		$sql = "SELECT DISTINCT rsRelatedAuthorityRetention FROM rm_rsRelatedAuthorities WHERE rsRelatedAuthorityRetention LIKE ? ";
		$relatedAuthorityRetentionQuery = $this->db->query($sql, array('%' . $relatedAuthorityRetention . '%'));
		
		$relatedAuthorityRetention = array();
		if ($relatedAuthorityRetentionQuery->num_rows > 0) {
			foreach ($relatedAuthorityRetentionQuery->result() as $results) {
				$relatedAuthorityRetention[] = $results->rsRelatedAuthorityRetention;
			}
			return $relatedAuthorityRetention;		
		} //else {
			//return $relatedAuthorityRetention = "No results found.";
		//}
	}
	
	/**
    * gets record names for autosuggest display
    *
    * @access  	private
    * @param	$recordName
    * @return  	$recordNames
    */
	public function autoSuggest_recordName($recordName) {
		$recordNames = $this->getRecordNamesQuery($recordName);
		return $recordNames;  
	}
	
	/**
    * gets record names
    *
    * @access  	private
    * @param	$recordName
    * @return  	$recordNames
    */
	private function getRecordNamesQuery($recordName) {
		$sql = "SELECT DISTINCT recordName FROM rm_retentionSchedule WHERE recordName LIKE ? ";
		$recordNamesQuery = $this->db->query($sql, array('%' . $recordName . '%'));
		
		$recordNames = array();
		if ($recordNamesQuery->num_rows > 0) {
			foreach ($recordNamesQuery->result() as $results) {
				$recordNames[] = $results->recordName;
			}
			return $recordNames;		
		} //else {
			//return $recordNames = "No results found.";
		//}
	}
	
	/**
    * gets record codes for autosuggest display
    *
    * @access  	private
    * @param	$recordCode
    * @return  	$recordCodes
    */
	public function autoSuggest_recordCode($recordCode) {
		$recordCodes = $this->getRecordCodesQuery($recordCode);
		return $recordCodes;  
	}
	
	/**
    * gets record names
    *
    * @access  	private
    * @param	$recordCode
    * @return  	$recordCodes
    */
	private function getRecordCodesQuery($recordCode) {
		$sql = "SELECT DISTINCT recordCode FROM rm_retentionSchedule WHERE recordCode LIKE ? ";
		$recordCodesQuery = $this->db->query($sql, array('%' . $recordCode . '%'));
		
		$recordCodes = array();
		if ($recordCodesQuery->num_rows > 0) {
			foreach ($recordCodesQuery->result() as $results) {
				$recordCodes[] = $results->recordCode;
			}
			return $recordCodes;		
		} //else {
			//return $recordCodes = "No results found.";
		//}
	}
	
}