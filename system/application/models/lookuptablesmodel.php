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


class LookUpTablesModel extends CI_Model 
{

	public function __contstruct() {
 		parent::__construct();
 		
 		$this->devEmail = $this->config->item('devEmail');
 	}
	
 	/**
    * invokes createFieldTypeDropDownQuery()
    * 
    * @access public 
    * @return $result
    */
	public function createFieldTypeDropDown() {
		$result = $this->createFieldTypeDropDownQuery();
 		return $result;
	}
	
	/**
    * gets field types and packages them up in an array 
    * used by admin forms
    *  
    * @access private 
    * @return $result
    */
	private function createFieldTypeDropDownQuery() {
		$fieldTypes = array();
	 	$this->db->select('fieldTypeID, fieldType');
	 	$this->db->from('rm_fieldTypes');
	 	$query = $this->db->get();
	 		 		 
	 	if ($query->num_rows() > 0) {		
	 		 foreach ($query->result() as $results) {
			 	$fieldTypes[$results->fieldTypeID] = $results->fieldType;
			 }
	 	}		
	 		return $fieldTypes;
	 }

	/**
    * invokes createDivisionDropDownQuery()
    * 
    * @access public 
    * @return $result
    */
	 public function createDivisionDropDown() {
		$result = $this->createDivisionDropDownQuery();
 		return $result;
	}
	
	/**
    * gets divisions and packages them into an array
    * used by admin forms
    * 
    * @access public 
    * @return $result
    */
	private function createDivisionDropDownQuery() {
		$divisions = array();
	 	$this->db->select('divisionID, divisionName');
	 	$this->db->from('rm_divisions');
	 	$this->db->order_by('divisionName', 'asc');
	 	$query = $this->db->get();
	 		 		 
	 	if ($query->num_rows() > 0) {		
	 		 foreach ($query->result() as $results) {
			 	$divisions[$results->divisionID] = $results->divisionName;
			 }
			 
	 		if ($results->divisionName == "Generating Unit") {
				$findIndex = $results->divisionName; // remove this value - used in admin only
				$indexKey = array_search($findIndex, $divisions);
				unset($divisions[$indexKey]);
			}
	 		return $divisions;
	 	}		
	 }

	 
	/**
    * invokes createRsDivisionDropDownQuery() / used by retention schedule form
    * 
    * @access public 
    * @return $result
    */
	 public function createRsDivisionDropDown() {
		$result = $this->createRsDivisionDropDownQuery();
 		return $result;
	}
	
	/**
    * gets divisions and packages them into an array
    * used by admin forms
    * 
    * @access public 
    * @return $result
    */
	private function createRsDivisionDropDownQuery() {
		$divisions = array();
	 	$this->db->select('divisionID, divisionName');
	 	$this->db->from('rm_divisions');
	 	$this->db->order_by('divisionName', 'asc');
	 	$query = $this->db->get();
	 		 		 
	 	if ($query->num_rows() > 0) {		
	 		 foreach ($query->result() as $results) {
			 	$divisions[$results->divisionID] = $results->divisionName;
			 }
	 		return $divisions;
	 	}			
	 } 

	 
	/**
    * invokes setDepartmentsQuery()
    * 
    * @access public 
    * @param $divisionID
    * @return $departments
    */
	 public function setDepartments($divisionID) {
	 	$departments = $this->setDepartmentsQuery($divisionID);
	 	return $departments;
	 }
	 
	/**
    * gets departments
    * used by admin forms
    * 
    * @access private
    * @param $divisionID 
    * @return $result
    */
	 private function setDepartmentsQuery($divisionID) {
	 	$this->db->select('departmentID, departmentName');
	 	$this->db->from('rm_departments');
	 	$this->db->order_by('departmentName', 'asc');
	 	$this->db->where('divisionID', $divisionID);
	
	 	$query = $this->db->get();
	 	$departments = array();
	 	if ($query->num_rows() > 0) {		
	 			 
	 		foreach ($query->result() as $results) {
			 	if ($results->departmentName !== "All Departments") {
	 				$departments[$results->departmentID] = $results->departmentName;
			 	}
			 }
	 		
			 return $departments;
			
	 	} else {
	 		//send_email('devEmail', 'RecordsAuthority_Error', 'database error: no departments found - setDepartmentsQuery()');
	 		return "No records found";
	 	}	
	 }
	 
	
	/**
    * invokes getDivisionQuery()
    * 
    * @access public 
    * @param $departmentID
    * @return $division
    */
	public function getDivision($departmentID) {
		$division = $this->getDivisionQuery($departmentID);
		return $division;
	}
	
	/**
    * gets division and department names
    * used by admin forms
    * 
    * @access private 
    * @param $departmentID
    * @return $divDeptArray (array contains both a department name and division name)
    */
	private function getDivisionQuery($departmentID) {
		$this->db->select('divisionID, departmentName');
		$this->db->from('rm_departments');
		$this->db->order_by('departmentName', 'asc');
		if($departmentID != 999999) {
			$this->db->where('departmentID', $departmentID);
		}
		$divisionIdQuery = $this->db->get();
		
		$divDeptArray = array();
		if ($divisionIdQuery->num_rows() > 0) {
			$divisionIdResult = $divisionIdQuery->row();
			$divDeptArray['departmentID'] = $departmentID;
			$divDeptArray['divisionID'] = $divisionIdResult->divisionID;  
			$divDeptArray['departmentName'] = $divisionIdResult->departmentName;
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			$this->db->order_by('divisionName', 'asc');
			$this->db->where('divisionID', $divDeptArray['divisionID']);
			
			$divisionNameQuery = $this->db->get();
			
			if ($divisionNameQuery->num_rows > 0) {
				$divisonNameResult = $divisionNameQuery->row();
				$divDeptArray['divisionName'] = $divisonNameResult->divisionName;
				
				return $divDeptArray;
			}
		}
	}
	
	/**
    * invokes getDivisionID()
    * 
    * @access public 
    * @param $divisionName
    * @return $division
    */
	public function getDivisionID($divisionName) {
		$division = $this->getDivisionIDQuery($divisionName);
		return $division;
	}
	
	/**
    * gets division ID
    * used by admin forms
    * 
    * @access private 
    * @param $divisionName
    * @return $division
    */
	private function getDivisionIDQuery($divisionName) {
		$this->db->select('divisionID');
		$this->db->from('rm_divisions');
		$this->db->where('divisionName', $divisionName);
		
		$divisionQuery = $this->db->get();
		
		if ($divisionQuery->num_rows() > 0) {
			$result = $divisionQuery->row();
			$division = $result->divisionID;  
			
			return $division;
		}
	}
	
	/**
    * invokes getDepartmentQuery()
    * 
    * @access public 
    * @param $departmentID
    * @return $department
    */
	public function getDepartment($departmentID) {
		$department = $this->getDepartmentQuery($departmentID);
		return $department;
	}
	
	/**
    * gets department name
    * used by admin forms
    * 
    * @access private 
    * @param $departmentID
    * @return $department
    */
	private function getDepartmentQuery($departmentID) {
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		$this->db->order_by('departmentName', 'asc');
		$this->db->where('departmentID', $departmentID);
		
		$departmentQuery = $this->db->get();
		
		if ($departmentQuery->num_rows() > 0) {
	   		$result = $departmentQuery->row();
			$department = $result->departmentName;  	
			
			return $department;
		}
	}
	
	/**
    * invokes getDepartmentID()
    * 
    * @access public 
    * @param $divisionName
    * @return $division
    */
	public function getDepartmentID($departmentName) {
		$department = $this->getDepartmentIDQuery($departmentName);
		return $department;
	}
	
	/**
    * gets division ID
    * used by admin forms
    * 
    * @access private 
    * @param $divisionName
    * @return $division
    */
	private function getDepartmentIDQuery($departmentName) {
		$this->db->select('departmentID');
		$this->db->from('rm_departments');
		$this->db->where('departmentName', $departmentName);
		
		$departmentQuery = $this->db->get();
		
		if ($departmentQuery->num_rows() > 0) {
			$result = $departmentQuery->row();
			$department = $result->departmentID;  
			
			return $department;
		}
	}
	
	/**
    * invokes getRecordCategoriesQuery()
    * 
    * @access public 
    * @return $recordCategories
    */
	public function getRecordCategories() {
		$recordCategories = $this->getRecordCategoriesQuery();
		return $recordCategories;
	}
	
	/**
    * gets record categories
    * 
    * @access private 
    * @return $recordCategories
    */
	private function getRecordCategoriesQuery() {
		$this->db->select('recordCategoryID, recordCategory');
		$this->db->from('rm_recordCategories');
		$this->db->order_by('recordCategory', 'asc');
		$recordCategoryQuery = $this->db->get();
		
		if ($recordCategoryQuery->num_rows() > 0) {		
	 			 
	 		foreach ($recordCategoryQuery->result() as $results) {
			 	$recordCategories[$results->recordCategoryID] = $results->recordCategory;
			 }
	 		
			 return $recordCategories;
			
	 	} else {
	 		//send_email('devEmail', 'RecordsAuthority_Error', 'database error: no record categories found - getRecordCategoriesQuery()');
	 		return "no record categories found";
	 	}	
	}
	
	/**
	 * invokes getDivisionNameQuery
	 *
	 * @param $divisionID
	 * @return $divisionName
	 */
	public function getDivisionName($divisionID) {
		$divisionName = $this->getDivisionNameQuery($divisionID);
		return $divisionName;
	}
	
	/**
	 * gets division name
	 *
	 * @param $divisionID
	 * @return $divisionName
	 */
	private function getDivisionNameQuery($divisionID) {
		$this->db->select('divisionID, divisionName');
	 	$this->db->from('rm_divisions');
	 	$this->db->order_by('divisionName', 'asc');
	 	$this->db->where('divisionID', $divisionID);
	 	
	 	$query = $this->db->get();
	 			 
	 	if ($query->num_rows() > 0) {		
	 		$result = $query->row();
			$divisionName = $result->divisionName;  
	 	}		
	 		return $divisionName;
	}
	
	/**
	 * invokes getAssociatedUnitsQuery
	 *
	 * @param $_POST
	 * @return $associatedUnits
	 */
	public function getAssociatedUnits($_POST) {
		$associatedUnits = $this->getAssociatedUnitsQuery($_POST);
		return $associatedUnits;
	}
	
	/** TODO: refactor
	 * gets associated units // add aus
	 *
	 * @param $_POST
	 * @return $associatedUnits
	 */
	private function getAssociatedUnitsQuery($_POST) {
		if (isset($_POST['divisionID'])) {
			//NEW RETENTION MODE
			$divisionID = $_POST['divisionID'];	
			$departments = $this->setDepartments($divisionID);
			$divisionName = $this->getDivisionName($divisionID);
			
			// get uuid from session
			$uuid = $this->session->userdata('uuid');
			// check if divisionID is in temp table
			$checked = $this->checkDivisionID($divisionID);
			
			////
			if ($uuid == FALSE && $checked == FALSE) {
				if (isset($_POST['retentionScheduleID'])) {
					$retentionScheduleID = $_POST['retentionScheduleID'];
					// load checked boxes for edit
					$associatedUnits = $this->editAssociatedUnits($divisionID, $retentionScheduleID);
				} else {
					// load unchecked boxes
					$associatedUnits = $this->loadCheckBoxes($divisionID, $departments, $divisionName);
				}
			} elseif ($uuid == TRUE && $checked == FALSE) {
				// load unchecked boxes 
				$associatedUnits = $this->loadCheckBoxes($divisionID, $departments, $divisionName);
			} elseif ($uuid == TRUE && $checked == TRUE) {
				// load checked options if departments have been checked for a particular department
				$associatedUnits = $this->loadCheckedAssociatedUnits($divisionID, $uuid);
			}
			////
		
		} elseif (isset($_POST['departmentID']) && !isset($_POST['retentionScheduleID']) && !isset($_POST['associatedUnitsID'])) {
			//INSERT MODE
			$departmentID = $_POST['departmentID'];
			if (isset($_POST['primaryRep'])) {
				$primaryRep = $_POST['primaryRep'];
			} else {
				$primaryRep = 0;
			}
			
			// get uuid from session
			$uuid = $this->session->userdata('uuid');
			// check if divisionID is in temp table
			$checked = $this->checkDepartmentID($departmentID);
			$getDivID = $this->getDivision($departmentID);
			$divisionID = $getDivID['divisionID'];
			
			if ($uuid == FALSE) {
				$uuid = $this->createUUID();	
			}
			if ($checked == FALSE) {
				$this->saveCheckedDepartment($uuid, $divisionID, $departmentID, $primaryRep);	
			
				$this->db->select('departmentID');
				$this->db->from('rm_departments');
				$this->db->where('divisionID', $divisionID);
				$aus = $this->db->get();	
				
				$this->db->select('departmentID');
				$this->db->from('rm_associatedUnits_temp');
				$this->db->where('divisionID', $divisionID);
				$this->db->where('uuid', $uuid);
				$checkedAus = $this->db->get();	
				
				$divisionName = $this->getDivisionName($divisionID);
				
				if ($checkedAus->num_rows > 0) {
					foreach ($checkedAus->result() as $checkedAu) {
						$checkedArray[] = $checkedAu->departmentID;
					}
				}
				
				$associatedUnits = "";
				if ($aus->num_rows > 0) {
					$associatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='checkAll($divisionID, \" $uuid \")' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
											
					foreach ($aus->result() as $result) {
						
						$department = $this->getDepartment($result->departmentID);
						
						if ($department !== "All Departments") {  // don't display All Departments label
							if (in_array($result->departmentID, $checkedArray)) {
								$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='uncheckDepartment($result->departmentID, \" $uuid \")' class='assocUnits' checked />&nbsp;" . $department . "<br />";
							} else {
								$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='checkDepartment($result->departmentID)' class='assocUnits' />&nbsp;" . $department . "<br />";
							}
						}
					}
				}
			}
			
			if ($checked == TRUE) {
				// remove department from table
				$this->db->where('departmentID', $departmentID);
				$this->db->delete('rm_associatedUnits_temp');
				// render check boxes
				$associatedUnits = $this->loadCheckedAssociatedUnits($divisionID, $uuid);
			}
		} /*EDIT MODE*/ elseif (!isset($_POST['associatedUnitsID']) && isset($_POST['retentionScheduleID']) && isset($_POST['departmentID'])) { // checks department
			$retentionScheduleID = $_POST['retentionScheduleID'];	
			$departmentID = $_POST['departmentID'];
			$this->saveCheckedDepartmentEdit($retentionScheduleID, $departmentID);
			$getDivID = $this->getDivision($departmentID);
			$divisionID = $getDivID['divisionID'];
			$associatedUnits = $this->editAssociatedUnits($divisionID, $retentionScheduleID);
		} elseif (isset($_POST['associatedUnitsID']) && isset($_POST['departmentID']) && isset($_POST['retentionScheduleID'])) { // unchecks department
			$associatedUnitsID = $_POST['associatedUnitsID'];
			$departmentID = $_POST['departmentID'];
			$retentionScheduleID = $_POST['retentionScheduleID'];
			// uncheck in edit mode
			$this->deleteCheckedDepartmentEdit($associatedUnitsID, $departmentID);
			$getDivID = $this->getDivision($departmentID);
			$divisionID = $getDivID['divisionID'];
			$associatedUnits = $this->editAssociatedUnits($divisionID, $retentionScheduleID);
		} else {
			$associatedUnits = "Please select a division.";
		}
		return $associatedUnits;
	}
		
	// add aus
	/**
	 * saves checked associated unit
	 *
	 * @param $uuid
	 * @param $divisionID
	 * @param $departmentID
	 * @param $primaryRep
	 * 
	 * @return void
	 */
	private function saveCheckedDepartment($uuid, $divisionID, $departmentID, $primaryRep) {
		// remove if isOfficeOfPrimaryResponsibility is 1  (1==TRUE)
		if ($primaryRep == 1) {
			$this->db->where('isOfficeOfPrimaryResponsibility', 1);
			$this->db->delete('rm_associatedUnits_temp');			
		}
		
		// insert into temp table here
		$checked = array();
		$checked['uuid'] = $uuid;
		$checked['divisionID'] = $divisionID;
		$checked['departmentID'] = $departmentID;
		$checked['isOfficeOfPrimaryResponsibility'] = $primaryRep;
		$this->db->insert('rm_associatedUnits_temp', $checked);			
	}
	
	// add aus
	/**
	 * load associated unit checkboxes
	 *
	 * @param $divisionID
	 * @param $departments
	 * @param $divisionName
	 * @return $associatedUnits
	 */
	private function loadCheckBoxes($divisionID, $departments, $divisionName) {
		$associatedUnits = "";
		$associatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='checkAll($divisionID)' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		foreach ($departments as $i => $department) {
			if ($department !== "All Departments") {  // don't display All Departments label
				$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$i' onClick='checkDepartment($i)' class='assocUnits' />&nbsp;" . $department . "<br />";
			}
		}
		return $associatedUnits;
	}
	
	// add aus
	/**
	 * load checked associated units
	 *
	 * @param $divisionID
	 * @param $uuid
	 * @return $associatedUnits
	 */
	private function loadCheckedAssociatedUnits($divisionID, $uuid) {
		$this->db->select('departmentID');
		$this->db->from('rm_departments');
		$this->db->where('divisionID', $divisionID);
		$aus = $this->db->get();	
		
		$this->db->select('departmentID');
		$this->db->from('rm_associatedUnits_temp');
		$this->db->where('uuid', $uuid);
		$this->db->where('divisionID', $divisionID);
		$checkedAus = $this->db->get();	
		
		$checkedArray = array();
		if ($checkedAus->num_rows > 0) {
			foreach ($checkedAus->result() as $checkedAu) {
				$checkedArray[] = $checkedAu->departmentID;
			}
			$total = count($checkedArray);
			$totalChecked = $total + 1;
		} else {
			$total = 0;
			$totalChecked = 0;
		}

		$totalDepartments = $aus->num_rows;
		$divisionName = $this->getDivisionName($divisionID);
				
		$associatedUnits = "";
		
		if ($totalDepartments == $totalChecked) { 
			$associatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='uncheckAll($divisionID, \" $uuid \")' checked />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		} else {
			$associatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='checkAll($divisionID)' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		}
				
		if ($aus->num_rows > 0) {
			
			foreach ($aus->result() as $result) {
				$department = $this->getDepartment($result->departmentID);
							
				if ($department !== "All Departments") {  // don't display All Departments label
					if (is_array($checkedArray) && in_array($result->departmentID, $checkedArray)) { // isset($checkArray) &&
						$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='uncheckDepartment($result->departmentID, \" $uuid \")' class='assocUnits' checked />&nbsp;" . $department . "<br />";
					} else {
						$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='checkDepartment($result->departmentID)' class='assocUnits' />&nbsp;" . $department . "<br />";
					}
				}
			}
		return $associatedUnits;
		}
	}
	
	// add aus
	/**
	 * checks if the divisionID of a..division is currently in the temp table
	 *
	 * @param $divisionID
	 * @return $checked
	 */
	private function checkDivisionID($divisionID) {
		$this->db->select('divisionID');
		$this->db->from('rm_associatedUnits_temp');
		$this->db->where('divisionID', $divisionID);
		$checkedAus = $this->db->get();	
		if ($checkedAus->num_rows > 0) {
			$checked = TRUE;
		} else {
			$checked = FALSE;
		}
		return $checked;
	}
	
	// add aus
	/**
	 * checks if the departmentID of a ..department is currently in the temp table
	 *
	 * @param $departmentID
	 * @return $checked
	 */
	
	private function checkDepartmentID($departmentID) {
		$this->db->select('departmentID');
		$this->db->from('rm_associatedUnits_temp');
		$this->db->where('departmentID', $departmentID);
		$checkedAus = $this->db->get();	
		if ($checkedAus->num_rows > 0) {
			$checked = TRUE;
		} else {
			$checked = FALSE;
		}
		return $checked;
	}
	
	// add aus - checkAll functionality
	/**
	 * invokes check_associatedUnitsQuery()
	 *
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
	public function check_associatedUnits($_POST) {
		$checkedAssociatedUnits = $this->check_associatedUnitsQuery($_POST);
		return $checkedAssociatedUnits;
	}
	
	// add aus
	/**
	 * adds checked associated units to temp table
	 *
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
	private function check_associatedUnitsQuery($_POST) {
		$divisionID = $_POST['divisionID'];	
		$departments = $this->setDepartments($divisionID);
		$divisionName = $this->getDivisionName($divisionID);
		// get uuid from session
		$uuid = $this->session->userdata('uuid');
		if ($uuid == FALSE) {
			$uuid = $this->createUUID();
		} 
				
		// check if value is already in the db
		$this->db->select('departmentID');
		$this->db->from('rm_associatedUnits_temp');
		$this->db->where('uuid', $uuid);
		$this->db->where('divisionID', $divisionID);
		$checkedAus = $this->db->get();	
		
		if ($checkedAus->num_rows > 0) {
			foreach ($checkedAus->result() as $checkedAu) {
				$checkedArray[] = $checkedAu->departmentID;
			}
		}

		$totalDeptartments = $checkedAus->num_rows;
		
		$checkedAssociatedUnits = "";
		$checkedAssociatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='uncheckAll($divisionID, \" $uuid \")' checked />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		foreach ($departments as $i => $department) {
			if ($department !== "All Departments") {  // don't display All Departments label
				$checkedAssociatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$i' onClick='uncheckDepartment($i, \" $uuid \")' class='assocUnits' checked />&nbsp;" . $department . "<br />";
				
				if ($totalDeptartments !== 0 && in_array($i, $checkedArray)) {
					// remove duplicates
					$this->db->where('departmentID', $i);
					$this->db->delete('rm_associatedUnits_temp');
				}
				
				// insert into temp table here
				$checked['uuid'] = $uuid;
				$checked['divisionID'] = $divisionID;
				$checked['departmentID'] = $i;
				$this->db->insert('rm_associatedUnits_temp', $checked);
			}
		}
		return $checkedAssociatedUnits; 
	}
	
	// add aus
	/**
	 * creates unique identifier
	 *
	 * @return $uuid
	 */
	private function createUUID() {
		// create uuid
		$newuuid = uniqid(rand(), true);
		// hash uuid
		$uuidhash = md5($newuuid);
		// place uuid in session
		$uuidarray = array('uuid'=>$uuidhash);
		$this->session->set_userdata($uuidarray);
		$uuid = $this->session->userdata('uuid');	
		return $uuid;
	}
	
	// add aus - uncheckall functionality
	/**
	 * invokes uncheck_associatedUnits()
	 *
	 * @param $_POST
	 * @return $unCheckedAssociatedUnits
	 */
	public function uncheck_associatedUnits($_POST) {
		$unCheckedAssociatedUnits = $this->uncheck_associatedUnitsQuery($_POST);
		return $unCheckedAssociatedUnits;
	}
	
	// add aus
	/**
	 * removes associated units from temp table
	 *
	 * @param $_POST
	 * @return $unCheckedAssociatedUnits
	 */
	private function uncheck_associatedUnitsQuery($_POST) {
		$divisionID = $_POST['divisionID'];	
		$uuid = $_POST['uuid'];
		$uuid = trim($uuid);
		$departments = $this->setDepartments($divisionID);
		$divisionName = $this->getDivisionName($divisionID);
		// remove values from temp table		
		$this->db->where('divisionID', $divisionID);
		$this->db->where('uuid', $uuid);
		$this->db->delete('rm_associatedUnits_temp');
		$unCheckedAssociatedUnits = "";
		$unCheckedAssociatedUnits .= "<input name='divisionID' type='checkbox' id='uncheckAllDepartments' value='$divisionID' onClick='checkAll($divisionID)' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		foreach ($departments as $i => $department) {
			if ($department !== "All Departments") {  // don't display All Departments label
				$unCheckedAssociatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$i' onClick='checkDepartment($i)' class='assocUnits' />&nbsp;" . $department . "<br />";
			}
		}	
		return $unCheckedAssociatedUnits; 
	}
	
	// edit aus
	/**
	 * gets associated units while in edit mode
	 *
	 * @param $divisionID
	 * @param $retentionScheduleID
	 * @return $associatedUnits
	 */
	private function editAssociatedUnits($divisionID, $retentionScheduleID) {
		$this->db->select('departmentID');
		$this->db->from('rm_departments');
		$this->db->where('divisionID', $divisionID);
		$aus = $this->db->get();	
		
		$this->db->select('departmentID');
		$this->db->from('rm_associatedUnits');
		$this->db->where('retentionScheduleID', $retentionScheduleID);
		$checkedAus = $this->db->get();	
		
		$checkedArray = array();
		if ($checkedAus->num_rows > 0) {
			foreach ($checkedAus->result() as $checkedAu) {
				$checkedArray[] = $checkedAu->departmentID;
			}
			$total = count($checkedArray);
			$totalChecked = $total + 1;
		} else {
			$total = 0;
			$totalChecked = 0;
		}

		$totalDepartments = $aus->num_rows;
		$divisionName = $this->getDivisionName($divisionID);
				
		$associatedUnits = "";
		$associatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='editCheckAll($divisionID)' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
								
		if ($aus->num_rows > 0) {
			foreach ($aus->result() as $result) {
				$department = $this->getDepartment($result->departmentID);
							
				if ($department !== "All Departments") {  // don't display All Departments label
					if (is_array($checkedArray) && in_array($result->departmentID, $checkedArray)) { 
						$this->db->select('associatedUnitsID');
						$this->db->from('rm_associatedUnits');
						$this->db->where('departmentID', $result->departmentID);
						$this->db->where('retentionScheduleID', $retentionScheduleID);
						$auID = $this->db->get();	
						$id = $auID->row();
						$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='uncheckDepartment($result->departmentID, $id->associatedUnitsID)' class='assocUnits' checked />&nbsp;" . $department . "<br />";
					} else {
						$associatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$result->departmentID' onClick='checkDepartment($result->departmentID, $retentionScheduleID)' class='assocUnits' />&nbsp;" . $department . "<br />";
					}
				}
			}
		return $associatedUnits;
		}
	}
	
	// edit aus
	/**
	 * saves checked associated units while in edit mode
	 *
	 * @param $retentionScheduleID
	 * @param $departmentID
	 */
	private function saveCheckedDepartmentEdit($retentionScheduleID, $departmentID) {
		$checked['retentionScheduleID'] = $retentionScheduleID;
		$checked['departmentID'] = $departmentID;
		$this->db->insert('rm_associatedUnits', $checked);
	}
	
	// edit aus
	/**
	 * removes checked associated units from database
	 *
	 * @param $associatedUnitsID
	 * @param $departmentID
	 */
	private function deleteCheckedDepartmentEdit($associatedUnitsID, $departmentID) {
		$this->db->where('associatedUnitsID', $associatedUnitsID);
		$this->db->where('departmentID', $departmentID);
		$this->db->delete('rm_associatedUnits');
	}
	
	// edit aus
	/**
	 * invokes editCheckAllAssociatedUnitsQuery()
	 *
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
	public function editCheckAllAssociatedUnits($_POST) {
		$checkedAssociatedUnits = $this->editCheckAllAssociatedUnitsQuery($_POST);
		return $checkedAssociatedUnits;
	}
	
	// edit aus
	/**
	 * gets all checked associated units while in edit mode
	 *
	 * @param $_POST
	 * @return $checkedAssociatedUnits
	 */
	private function editCheckAllAssociatedUnitsQuery($_POST) {
		
		$divisionID = $this->input->post('divisionID', TRUE);	
		$retentionScheduleID = $this->input->post('retentionScheduleID', TRUE);
		
		$departments = $this->setDepartments($divisionID); // gets departments
		$divisionName = $this->getDivisionName($divisionID);
								
		// check if value is already in the db
		$this->db->select('departmentID');
		$this->db->from('rm_associatedUnits');
		$this->db->where('retentionScheduleID', $retentionScheduleID);
		//$this->db->where('departmentID', $departmentID);
		$checkedAus = $this->db->get();	
		
		if ($checkedAus->num_rows > 0) {
			foreach ($checkedAus->result() as $checkedAu) {
				$checkedArray[] = $checkedAu->departmentID;
			}
		}

		$totalDeptartments = $checkedAus->num_rows;
		
		$checkedAssociatedUnits = "";
		$checkedAssociatedUnits .= "<input name='divisionID' type='checkbox' id='selectAllDepartments' value='$divisionID' onClick='editUncheckAll($divisionID)' checked />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		foreach ($departments as $i => $department) {
			if ($department !== "All Departments") {  // don't display All Departments label
								
				if ($totalDeptartments !== 0 && in_array($i, $checkedArray)) {
					// remove duplicates
					$this->db->where('departmentID', $i);
					$this->db->where('retentionScheduleID', $retentionScheduleID);
					$this->db->delete('rm_associatedUnits');
				}
												
				$departmentID = $i;
				$this->saveCheckedDepartmentEdit($retentionScheduleID, $departmentID);			
				
				$this->db->select('associatedUnitsID');
				$this->db->from('rm_associatedUnits');
				$this->db->where('departmentID', $i);
				$this->db->where('retentionScheduleID', $retentionScheduleID);
				$auID = $this->db->get();	
				$id = $auID->row();
				
				$checkedAssociatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$i' onClick='uncheckDepartment($i, $id->associatedUnitsID)' class='assocUnits' checked />&nbsp;" . $department . "<br />";
			}
		}
		return $checkedAssociatedUnits; 
	}
	
	/**
	 * invokes editUnCheckAllAssociatedUnits()
	 *
	 * @param $_POST
	 * @return $unCheckedAssociatedUnits
	 */
	public function editUnCheckAllAssociatedUnits($_POST) {
		$unCheckedAssociatedUnits = $this->editUnCheckAllAssociatedUnitsQuery($_POST);	
		return $unCheckedAssociatedUnits;
	}
	
	/**
	 * unchecks associated units while in edit mode 
	 *
	 * @param $_POST
	 * @return $unCheckedAssociatedUnits
	 */
	private function editUnCheckAllAssociatedUnitsQuery($_POST) {
		$divisionID = $this->input->post('divisionID', TRUE);
		$retentionScheduleID = $this->input->post('retentionScheduleID', TRUE);
		
		$departments = $this->setDepartments($divisionID);
		$divisionName = $this->getDivisionName($divisionID);
		
		$unCheckedAssociatedUnits = "";
		$unCheckedAssociatedUnits .= "<input name='divisionID' type='checkbox' id='uncheckAllDepartments' value='$divisionID' onClick='editCheckAll($divisionID)' />&nbsp;<strong>$divisionName</strong>&nbsp;(selects all departments)<br />";
		foreach ($departments as $i => $department) {
			if ($department !== "All Departments") {  // don't display All Departments label
				
				// remove values from table		
				$this->db->where('retentionScheduleID', $retentionScheduleID);
				$this->db->where('departmentID', $i);
				$this->db->delete('rm_associatedUnits');
		
				$unCheckedAssociatedUnits .= "<input name='associatedUnits[]' type='checkbox' value='$i' onClick='checkDepartment($i)' class='assocUnits' />&nbsp;" . $department . "<br />";
			}
		}	
		return $unCheckedAssociatedUnits; 
	
	}
}
 
?>