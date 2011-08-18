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


class LookUpTablesModel extends Model 
{

	public function __contstruct() {
 		parent::Model();
 	}
	
	public function createFieldTypeDropDown() {
		$result = $this->createFieldTypeDropDownQuery();
 		return $result;
	}
	
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
	 
	 public function createDivisionDropDown() {
		$result = $this->createDivisionDropDownQuery();
 		return $result;
	}
	
	private function createDivisionDropDownQuery() {
		$divisions = array();
	 	$this->db->select('divisionID, divisionName');
	 	$this->db->from('rm_divisions');
	 	$query = $this->db->get();
	 		 		 
	 	if ($query->num_rows() > 0) {		
	 		 foreach ($query->result() as $results) {
			 	$divisions[$results->divisionID] = $results->divisionName;
			 }
	 	}		
	 		return $divisions;
	 }
	 
	 
	 public function setDepartments($divisionID) {
	 	$departments = $this->setDepartmentsQuery($divisionID);
	 	return $departments;
	 }
	 
	 private function setDepartmentsQuery($divisionID) {
	 	$this->db->select('departmentID, departmentName');
	 	$this->db->where('divisionID', $divisionID);
	 	$this->db->from('rm_departments');
	 	$query = $this->db->get();
	 	$departments = array();
	 	if ($query->num_rows() > 0) {		
	 			 
	 		foreach ($query->result() as $results) {
			 	$departments[$results->departmentID] = $results->departmentName;
			 }
	 		
			 return $departments;
			
	 	} else {
	 		return "database error";
	 	}	
	 }
	
	public function getDivision($departmentID) {
		$division = $this->getDivisionQuery($departmentID);
		return $division;
	}
	
	private function getDivisionQuery($departmentID) {
		$this->db->select('divisionID, departmentName');
		$this->db->from('rm_departments');
		$this->db->where('departmentID', $departmentID);
		$divisionIdQuery = $this->db->get();
		
		$divDeptArray = array();
		if ($divisionIdQuery->num_rows() > 0) {
			$divisionIdResult = $divisionIdQuery->row();
			$divDeptArray['departmentID'] = $departmentID;
			$divDeptArray['divisionID'] = $divisionIdResult->divisionID;  
			$divDeptArray['departmentName'] = $divisionIdResult->departmentName;
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			$this->db->where('divisionID', $divDeptArray['divisionID']);
			$divisionNameQuery = $this->db->get();
			
			if ($divisionNameQuery->num_rows > 0) {
				$divisonNameResult = $divisionNameQuery->row();
				$divDeptArray['divisionName'] = $divisonNameResult->divisionName;
				
				return $divDeptArray;
			}
		}
	}
	
	public function getDepartment($departmentID) {
		$department = $this->getDepartmentQuery($departmentID);
		return $department;
	}
	
	private function getDepartmentQuery($departmentID) {
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		$this->db->where('departmentID', $departmentID);
		$departmentQuery = $this->db->get();
		
		if ($departmentQuery->num_rows() > 0) {
	   		$result = $departmentQuery->row();
			$department = $result->departmentName;  	
			
			return $department;
		}
	}
	
	public function getRecordCategories() {
		$recordCategories = $this->getRecordCategoriesQuery();
		return $recordCategories;
	}
	
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
	 		return "database error";
	 	}	
	}
}
 
?>