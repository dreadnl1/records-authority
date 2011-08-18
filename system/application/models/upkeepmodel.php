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
 	
}