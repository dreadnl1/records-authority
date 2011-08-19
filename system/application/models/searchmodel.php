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
 
 class SearchModel extends CI_Model 
{

	public function __construct() {
 		parent::__construct();
 		
 		$this->solr = $this->config->item('solr');
 	}
	
	/**
 	 * Invokes getSurveysQuery()
 	 *
 	 * @param $departmentID
 	 * @param $divisionID
 	 * @return $surveyResults;
 	 */
	public function getSurveys($departmentID, $divisionID) {
		$surveyResults = $this->getSurveysQuery($departmentID, $divisionID);
		return $surveyResults;
	}
	
/**
	 * gets surveys by division/department
	 *
	 * @param $departmentID
	 * @param $divisionID
	 * @return $surveyResults
	 */
	private function getSurveysQuery($departmentID, $divisionID) {
				
		$attributes = array(
			'width'      => '700',
			'height'     => '700',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		$CI =& get_instance();
		$CI->load->model('LookUpTablesModel', 'getDivAndDept', true);
		$surveyDepartment = $departmentID;
		
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		if ($departmentID != 999999) {
			$this->db->where('departmentID', $departmentID);
		}
		$this->db->order_by('departmentName','asc');
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		
		$this->db->select('contactID');
	 	$this->db->from('rm_departmentContacts');
	 	if ($departmentID != 999999) {
	 		$this->db->where('departmentID', $departmentID);
	 	}
 		$surveyIDs = $this->db->get();
		if ($surveyIDs->num_rows() > 0) {
			
			// package id's in an array
 			$ids = array();
			foreach ($surveyIDs->result() as $id) {
				$ids[] = $id->contactID;				
			}
			 			
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "firstName";
			} elseif (isset($_POST['field']) && $_POST['field'] == 2) {
				$field = "lastName"; 
			} elseif (isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "jobTitle"; 
			} elseif (isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "emailAddress"; 
			} else {
				$field = "submitDate";
			}
			
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}
			
			$baseUrl = base_url();
			$siteUrl = site_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			if ($divisionID != 999999) {
				$this->db->where('divisionID', $divisionID);
			}
			$this->db->order_by('divisionName','asc');
			$divisionNameQuery = $this->db->get();
				if ($divisionNameQuery->num_rows > 0) {
					$divisonNameResult = $divisionNameQuery->row();
					$divisionName = $divisonNameResult->divisionName;
				 }
				 
			$surveyResults = "";
			$surveyResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$surveyResults .= "<script type='text/javascript'>";
			$surveyResults .= "function sortBy(departmentID, divisionID, sortBy, field) { ";
			$surveyResults .= "$('#sorting').show(); ";
			$surveyResults .= "$.post('$siteUrl/search/getSurveys',{ departmentID: departmentID, divisionID: divisionID, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$surveyResults .= "$('#surveySearchResults').html(results); ";
			$surveyResults .= "$('#sorting').hide(); ";
			$surveyResults .= "}); "; // post
			$surveyResults .= "} "; // js
			$surveyResults .= "</script>";
			
			$sd = 0; // 0 = submitDate	
			$fn = 1; // 1 = firstName
			$ln = 2; // 2 = lastName
			$jt = 3; // 3 = jobTitle
			$ea = 4; // 4 = emailAddress
			
			$this->db->select('contactID, firstName, lastName, jobTitle, departmentID, phoneNumber, emailAddress, submitDate');
	 		$this->db->from('rm_departmentContacts');
	 		if ($departmentID != 999999) {
	 			$this->db->where('departmentID', $departmentID);
	 		}
	 		$this->db->order_by($field, $sort);
 			$surveys = $this->db->get();
 			
			if ($departmentID != 999999) {
				$surveyResults .= "<h2>" . trim(strip_tags($divisionName)) . "</h2>";
				$surveyResults .= "<h2>" . trim(strip_tags($departmentName)) . "</h2>";
			} else {
				$surveyResults .= "<h2>Display All</h2>";
			}
			
			$surveyResults .= "<a href=''>New Search</a>";
			$surveyResults .= "<table id='searchResultsTable'>";
			$surveyResults .= "<tr>"; 
			$surveyResults .= "<th><strong>Division</strong></th>";
			$surveyResults .= "<th><strong>Department</strong></th>";
   			$surveyResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $fn);'>First Name</a></strong></th>";
   			$surveyResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $ln);'>Last Name</a></strong></th>";
   			$surveyResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $jt);'>Job Title</a></strong></th>";
   			$surveyResults .= "<th><strong>Phone Number</strong></th>";
   			$surveyResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $ea);'>Email Address</a></strong></th>";
   			$surveyResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $sd);'>Submit Date</a></strong></th>";		
   			$surveyResults .= "</tr>";
			
			foreach ($surveys->result() as $results) {
 		
				// get divisionID
	 			$this->db->select('departmentName','divisionID','departmentID');
	 			$this->db->from('rm_departments');
	 			$this->db->where('departmentID', $results->departmentID);
	 			$this->db->order_by('departmentName','asc');
	 			$departmentQuery = $this->db->get();

				foreach ($departmentQuery->result() as $department) {
	 				$department = $department->departmentName;
	 			}
	 			$departmentID = $results->departmentID;
	 			$divDeptArray = $CI->getDivAndDept->getDivision($departmentID); 
	 			
			 	$surveyResults .= "<tr>";
				$surveyResults .= "<td>";
				$surveyResults .= trim(strip_tags($divDeptArray['divisionName']));
				$surveyResults .= "</td>";
				$surveyResults .= "<td>";
			 	if (!empty($department)) {
					$surveyResults .= anchor_popup('dashboard/surveyNotesForm/' . $results->departmentID, trim(strip_tags($department)), $attributes);
			 	} else {
			 		$surveyResults .= anchor_popup('dashboard/surveyNotesForm/' . $results->departmentID, trim(strip_tags($results->departmentID)), $attributes);
			 	}
				$surveyResults .= "</td>";
			 	$surveyResults .= "<td>";
			 	//$surveyResults .= anchor_popup('recordType/edit/' . $results->recordInformationID, trim(strip_tags($results->recordName)), $attributes);
			 	$surveyResults .= trim(strip_tags($results->firstName));
				$surveyResults .= "</td>";
			 	$surveyResults .= "<td>";
			 	$surveyResults .= trim(strip_tags($results->lastName));
			 	$surveyResults .= "</td>";
			 	$surveyResults .= "<td>";
			 	$surveyResults .= trim(strip_tags($results->jobTitle));
				$surveyResults .= "</td>";
				$surveyResults .= "<td>";
			 	$surveyResults .= trim(strip_tags($results->phoneNumber));
				$surveyResults .= "</td>";    
				$surveyResults .= "<td>";
			 	$surveyResults .= trim(strip_tags($results->emailAddress));
				$surveyResults .= "</td>";
				$surveyResults .= "<td>";
			 	$surveyResults .= trim(strip_tags($results->submitDate));
				$surveyResults .= "</td>";
				$surveyResults .= "</tr>";
			}
			$surveyResults .= "</table>";
			return $surveyResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
 	/**
 	 * Invokes getRecordTypesQuery()
 	 *
 	 * @param $departmentID
 	 * @param $divisionID
 	 * @return $recordTypeResults;
 	 */
	public function getRecordTypes($departmentID, $divisionID) {
		$recordTypeResults = $this->getRecordTypesQuery($departmentID, $divisionID);
		return $recordTypeResults;
	}
	
	/**
	 * gets record types by division/department
	 *
	 * @param $departmentID
	 * @param $divisionID
	 * @return $recordTypeResults
	 */
	private function getRecordTypesQuery($departmentID, $divisionID) {
				
		$attributes = array(
			'width'      => '700',
			'height'     => '700',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		$recordTypeDepartment = $departmentID;
		$this->db->select('recordInformationID, recordInformationDivisionID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	//$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->from('rm_recordType');
	 	if ($recordTypeDepartment != 999999) {
	 		$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 	}
	 	$this->db->order_by('recordName', 'asc');
 		$recordTypes = $this->db->get();
		
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		if ($departmentID != 999999) {
			$this->db->where('departmentID', $departmentID);
		}
		$this->db->order_by('departmentName','asc');
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		
		$this->db->select('recordInformationID');
		$this->db->from('rm_recordType');
		if ($recordTypeDepartment != 999999) {
	 		$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 	}
 		$recordTypeIDs = $this->db->get();
		if ($recordTypeIDs->num_rows() > 0) {
			
			// package id's in an array
 			$ids = array();
			foreach ($recordTypeIDs->result() as $id) {
				$ids[] = $id->recordInformationID;				
			}
			 			
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "recordName";
			} else {
				$field = "recordCategory";
			}
			
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}
			
			$baseUrl = base_url();
			$siteUrl = site_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			if ($divisionID != 999999) {
				$this->db->where('divisionID', $divisionID);
			}
			$this->db->order_by('divisionName','asc');
			$divisionNameQuery = $this->db->get();
				if ($divisionNameQuery->num_rows > 0) {
					$divisonNameResult = $divisionNameQuery->row();
					$divisionName = $divisonNameResult->divisionName;
				 }

			$recordTypeResults = "";
			$recordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$recordTypeResults .= "<script type='text/javascript'>";
			$recordTypeResults .= "function sortBy(departmentID, divisionID, sortBy, field) { ";
			$recordTypeResults .= "$('#sorting').show(); ";
			$recordTypeResults .= "$.post('$siteUrl/search/getRecordTypes',{ departmentID: departmentID, divisionID: divisionID, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$recordTypeResults .= "$('#recordTypeSearchResults').html(results); ";
			$recordTypeResults .= "$('#sorting').hide(); ";
			$recordTypeResults .= "}); "; // post
			$recordTypeResults .= "} "; // js
			$recordTypeResults .= "</script>";
			
			$rc = 0; // 0 = recordCategory
			$rn = 1; // 1 = recordName
			
			$this->db->select('recordInformationID, recordInformationDivisionID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 		$this->db->from('rm_recordType');
	 		if ($recordTypeDepartment != 999999) {
	 			$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 		}
	 		$this->db->order_by($field, $sort);
 			$recordTypes = $this->db->get();
 		
			if ($recordTypeDepartment != 999999) {
				$recordTypeResults .= "<h2>" . trim(strip_tags($divisionName)) . "</h2>";
				$recordTypeResults .= "<h2>" . trim(strip_tags($departmentName)) . "</h2>";
			} else {
				$recordTypeResults .= "<h2>Display All</h2>";
			}

			$recordTypeResults .= "<a href=''>New Search</a>";
			$recordTypeResults .= "<table id='searchResultsTable'>";
			$recordTypeResults .= "<tr>";
			$recordTypeResults .= "<th><strong>Division</strong></th>";
			$recordTypeResults .= "<th><strong>Department</strong></th>";
			$recordTypeResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rc);'>Functional Category</a></strong></th>";
   			$recordTypeResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rn);'>Record Name</a></strong></th>";
   			$recordTypeResults .= "<th><strong>Description</strong></th>";
   			$recordTypeResults .= "</tr>";
			
			foreach ($recordTypes->result() as $results) {
				// get division name
				$this->db->select('divisionName');
				$this->db->from('rm_divisions');
				$this->db->where('divisionID', $results->recordInformationDivisionID);
				$this->db->order_by('divisionName','asc');
				$divisionQuery = $this->db->get();
				foreach ($divisionQuery->result() as $division) {
					$division = $division->divisionName;
				}
				
				// get department name
	 			$this->db->select('departmentName');
	 			$this->db->from('rm_departments');
	 			$this->db->where('departmentID', $results->recordTypeDepartment);
	 			$this->db->order_by('departmentName','asc');
	 			$departmentQuery = $this->db->get();			
	 			foreach ($departmentQuery->result() as $department) {
	 				$department = $department->departmentName;
	 			}
	 			
			 	$recordTypeResults .= "<tr>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($division));
			 	$recordTypeResults .= "</td>";
	 			$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($department));
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordCategory));
			 	$recordTypeResults .= "</td>";
	 			$recordTypeResults .= "<td>";
			 	$recordTypeResults .= anchor_popup('recordType/edit/' . $results->recordInformationID, trim(strip_tags($results->recordName)), $attributes);
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordDescription));
				$recordTypeResults .= "</td>"; 
				$recordTypeResults .= "</tr>";
			}
			$recordTypeResults .= "</table>";
			return $recordTypeResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
	 /**
 	 * Invokes getRecordTypesDeletedQuery()
 	 *
 	 * @param $departmentID
 	 * @param $divisionID
 	 * @return $recordTypeResults;
 	 */
	public function getRecordTypesDeleted($departmentID, $divisionID) {
		$recordTypeResults = $this->getRecordTypesDeletedQuery($departmentID, $divisionID);
		return $recordTypeResults;
	}
	
		/**
	 * gets record types by division/department
	 *
	 * @param $departmentID
	 * @param $divisionID
	 * @return $recordTypeResults
	 */
	private function getRecordTypesDeletedQuery($departmentID, $divisionID) {
				
		$attributes = array(
			'width'      => '700',
			'height'     => '700',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		$siteUrl = site_url();
		$restoreUrl= $siteUrl . "/recordType/restore";
		$deleteUrl = $siteUrl . "/recordType/permanentDelete";
		
		$recordTypeDepartment = $departmentID;
		$this->db->select('recordInformationID, recordInformationDivisionID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	$this->db->from('rm_recordTypeDeleted');
	 	if ($recordTypeDepartment != 999999) {
	 		$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 	}
	 	$this->db->order_by('recordName', 'asc');
 		$recordTypes = $this->db->get();
		
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		if ($recordTypeDepartment != 999999) {
			$this->db->where('departmentID', $recordTypeDepartment);
		}
		$this->db->order_by('departmentName','asc');
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		
		$this->db->select('recordInformationID');
		$this->db->from('rm_recordTypeDeleted');
		if ($recordTypeDepartment != 999999) {
	 		$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 	}
 		$recordTypeIDs = $this->db->get();
 		
		if ($recordTypeIDs->num_rows() > 0) {
			// package id's in an array
 			$ids = array();
			foreach ($recordTypeIDs->result() as $id) {
				$ids[] = $id->recordInformationID;				
			}
			
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "recordName";
			} else {
				$field = "recordCategory";
			}
			
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}
			
			$baseUrl = base_url();
			$siteUrl = site_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			if ($divisionID != 999999) {
				$this->db->where('divisionID', $divisionID);
			}
			$this->db->order_by('divisionName','asc');
			$divisionNameQuery = $this->db->get();
				if ($divisionNameQuery->num_rows > 0) {
					$divisonNameResult = $divisionNameQuery->row();
					$divisionName = $divisonNameResult->divisionName;
				 }
				 
			$recordTypeResults = "";
			$recordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$recordTypeResults .= "<script type='text/javascript'>";
			$recordTypeResults .= "function sortBy(departmentID, divisionID, sortBy, field) { ";
			$recordTypeResults .= "$('#sorting').show(); ";
			$recordTypeResults .= "$.post('$siteUrl/search/getRecordTypesDeleted',{ departmentID: departmentID, divisionID: divisionID, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$recordTypeResults .= "$('#recordTypeDeletedSearchResults').html(results); ";
			$recordTypeResults .= "$('#sorting').hide(); ";
			$recordTypeResults .= "}); "; // post
			$recordTypeResults .= "} "; // js
			$recordTypeResults .= "</script>";
			
			$rc = 0; // 0 = recordCategory
			$rn = 1; // 1 = recordName
			
			$this->db->select('recordInformationID, recordInformationDivisionID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 		$this->db->from('rm_recordTypeDeleted');
			if ($recordTypeDepartment != 999999) {
	 			$this->db->where('recordTypeDepartment', $recordTypeDepartment);
	 		}
	 		$this->db->order_by($field, $sort);
 			$recordTypes = $this->db->get();
 			
			if ($recordTypeDepartment != 999999) {
				$recordTypeResults .= "<h2>" . trim(strip_tags($divisionName)) . "</h2>";
				$recordTypeResults .= "<h2>" . trim(strip_tags($departmentName)) . "</h2>";
			} else {
				$recordTypeResults .= "<h2>Display All</h2>";
			}
			
			$recordTypeResults .= "<a href=''>New Search</a>";
			$recordTypeResults .= "<table id='searchResultsTable'>";
			$recordTypeResults .= "<tr>";
			$recordTypeResults .= "<th><strong>Restore</strong></th>";
			$recordTypeResults .= "<th><strong>Division</strong></th>";
			$recordTypeResults .= "<th><strong>Department</strong></th>";
			$recordTypeResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rc);'>Functional Category</a></strong></th>";
   			$recordTypeResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rn);'>Record Name</a></strong></th>";
   			$recordTypeResults .= "<th><strong>Description</strong></th>";
   			$recordTypeResults .= "<th><strong>Purge</strong></th>";
   			$recordTypeResults .= "</tr>";
			

			foreach ($recordTypes->result() as $results) {

				// get division name
				$this->db->select('divisionName');
				$this->db->from('rm_divisions');
				$this->db->where('divisionID', $results->recordInformationDivisionID);
				$this->db->order_by('divisionName','asc');
				$divisionQuery = $this->db->get();
				foreach ($divisionQuery->result() as $division) {
					$division = $division->divisionName;
				}
				
				// get department name
	 			$this->db->select('departmentName');
	 			$this->db->from('rm_departments');
	 			$this->db->where('departmentID', $results->recordTypeDepartment);
	 			$this->db->order_by('departmentName','asc');
	 			$departmentQuery = $this->db->get();			
	 			foreach ($departmentQuery->result() as $department) {
	 				$department = $department->departmentName;
	 			}
	 				
			 	$recordTypeResults .= "<tr>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= "<form method='link' action='$restoreUrl/$results->recordInformationID'  onClick='return confirm(\"Are you sure you want to Restore this record?\")'>";
			 	$recordTypeResults .= "<input type='submit' value='Restore'>";
			 	$recordTypeResults .= "</form>";
			 	$recordTypeResults .= "</td>"; 
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($division));
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($department));
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordCategory));
			 	$recordTypeResults .= "</td>";
				$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordName));
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordDescription));
				$recordTypeResults .= "</td>";
				$recordTypeResults .= "<td>";
			 	$recordTypeResults .= "<form method='link' action='$deleteUrl/$results->recordInformationID'  onClick='return confirm(\"Are you sure you want to PURGE this record?\")'>";
			 	$recordTypeResults .= "<input type='submit' value='Purge'>";
			 	$recordTypeResults .= "</form>";
			 	$recordTypeResults .= "</td>"; 
				$recordTypeResults .= "</tr>";
			}
			$recordTypeResults .= "</table>";
			return $recordTypeResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
	/**
	 * invokes getAuditQuery()
	 *
	 * @param $keyword
	 * @return $audit
	 */
	public function getAudit($keyword) {
 		$audit = $this->getAuditQuery($keyword);
 		return $audit;
 	}
 	
 	/**
	 * gets any audit based on search keyword
	 *
	 * @param $keyword
	 * @return $auditResults
	 */
 	private function getAuditQuery($keyword) {
		$baseUrl = base_url();
		$siteUrl = site_url();
		$siteName = $this->config->item('site_name');
		
		$js = "js/searchResults.js";
		$jsPath = $baseUrl . $js;
		
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "username";
		} else {
			$field = "updateDate";
		}
		
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
		
		$this->db->select('auditID, username, updateDate, previousData, currentData');
	 	$this->db->from('rm_audit');
	 	if($keyword != '*') {
		 	$this->db->where('MATCH(
		 						username,
		 						updateDate,
		 						previousData,
		 						currentData) 
		 						AGAINST ("*' . $keyword . '*" IN BOOLEAN MODE)');
	 	}

	 	$this->db->order_by($field,$sort);
	 	$auditQuery = $this->db->get();
	 	
	 	if ($auditQuery->num_rows() > 0) {
			
			$auditResults= "";
			$auditResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$auditResults .= "<script type='text/javascript'>";
			$auditResults .= "function sortBy(keyword, sortBy, field) { ";
			$auditResults .= "$('#sorting').show(); ";
			$auditResults .= "$.post('$siteUrl/search/auditSearch',{keyword: keyword, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$auditResults .= "$('#auditSearchResults').html(results); ";
			$auditResults .= "$('#sorting').hide(); ";
			$auditResults .= "}); "; // post
			$auditResults .= "} "; // js
			$auditResults .= "</script>";
			
			$dt = 0; // date
			$un = 1; // username
			
			$auditResults .= "<a href='$siteUrl/export/transformAudit/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
			//$auditResults .= "<a href='$siteUrl/export/transformAudit/pdf'><img src='/$siteName/images/page_white_acrobat.png' alt='Export to PDF' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/csv'><img src='/$siteName/images/page_csv.png' alt='Export to CSV' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/html'><img src='/$siteName/images/page_html.png' alt='Export to HTML' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/auditXml'><img src='/$siteName/images/page_xml.png' alt='Export to XML' border='0' /></a>&nbsp;&nbsp;";
			
			$auditResults .= "<table id='searchResultsTable'>";
			$auditResults .= "<tr>"; 
			$auditResults .= "<th><strong>User</a></strong></th>";
			$auditResults .= "<th><strong>Date</a></strong></th>";
	   		$auditResults .= "<th><strong>Previous Data</strong></th>";
	   		$auditResults .= "<th><strong>Current Data</strong></th>";
	   		$auditResults .= "</tr>";
			
			foreach ($auditQuery->result() as $results) {
				$auditID = $results->auditID;
								
				$auditResults .= "<tr>";
				$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->username));
			 	$auditResults .= "</td>";
				$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->updateDate));
			 	$auditResults .= "</td>";
			 	$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->previousData));
				$auditResults .= "</td>"; 
			 	$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->currentData));
				$auditResults .= "</td>"; 
				$auditResults .= "</tr>";
			}
		
			$auditResults .= "</table>";
			return $auditResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
 	}
 	
 	/**
	 * invokes getDateAuditQuery()
	 *
	 * @param $beginDate, $endDate
	 * @return $audit
	 */
 	public function getDateAudit($beginDate,$endDate) {
 		$audit = $this->getDateAuditQuery($beginDate,$endDate);
 		return $audit;
 	}
 	
 	 /**
	 * gets any audit based on two dates
	 *
	 * @param $beginDate,$endDate
	 * @return $auditResults
	 */
 	private function getDateAuditQuery($beginDate,$endDate) {
		$baseUrl = base_url();
		$siteUrl = site_url();
		$siteName = $this->config->item('site_name');
		
		$js = "js/searchResults.js";
		$jsPath = $baseUrl . $js;
		
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "username";
		} else {
			$field = "updateDate";
		}
		
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
		$this->db->select("* FROM rm_audit WHERE timestamp BETWEEN '$beginDate' AND '$endDate'", false);  
	 	$auditQuery = $this->db->get();
	 	
	 	if ($auditQuery->num_rows() > 0) {
			
			$auditResults= "";
			$auditResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$auditResults .= "<script type='text/javascript'>";
			$auditResults .= "function sortBy(keyword, sortBy, field) { ";
			$auditResults .= "$('#sorting').show(); ";
			$auditResults .= "$.post('$siteUrl/search/auditSearch',{beginDate: beginDate, endDate: endDate, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$auditResults .= "$('#auditSearchResults').html(results); ";
			$auditResults .= "$('#sorting').hide(); ";
			$auditResults .= "}); "; // post
			$auditResults .= "} "; // js
			$auditResults .= "</script>";
			
			$dt = 0; // date
			$un = 1; // username
			
			$auditResults .= "<a href='$siteUrl/export/transformAudit/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
			//$auditResults .= "<a href='$siteUrl/export/transformAudit/pdf'><img src='/$siteName/images/page_white_acrobat.png' alt='Export to PDF' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/csv'><img src='/$siteName/images/page_csv.png' alt='Export to CSV' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/html'><img src='/$siteName/images/page_html.png' alt='Export to HTML' border='0' /></a>&nbsp;&nbsp;";
			$auditResults .= "<a href='$siteUrl/export/transformAudit/xml'><img src='/$siteName/images/page_xml.png' alt='Export to XML' border='0' /></a>&nbsp;&nbsp;";
			
			$auditResults .= "<table id='searchResultsTable'>";
			$auditResults .= "<tr>"; 
			$auditResults .= "<th><strong>User</a></strong></th>";
			$auditResults .= "<th><strong>Date</a></strong></th>";
	   		$auditResults .= "<th><strong>Previous Data</strong></th>";
	   		$auditResults .= "<th><strong>Current Data</strong></th>";
	   		$auditResults .= "</tr>";
			
			foreach ($auditQuery->result() as $results) {
				$auditID = $results->auditID;
								
				$auditResults .= "<tr>";
				$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->username));
			 	$auditResults .= "</td>";
				$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->updateDate));
			 	$auditResults .= "</td>";
			 	$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->previousData));
				$auditResults .= "</td>"; 
			 	$auditResults .= "<td>";
			 	$auditResults .= trim(strip_tags($results->currentData));
				$auditResults .= "</td>"; 
				$auditResults .= "</tr>";
			}
		
			$auditResults .= "</table>";
			return $auditResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
 	}
 	
	/**
	 * invokes getGlobalRecordTypesQuery()
	 *
	 * @param $keyword
	 * @return $globalRecordTypeResults
	 */
	public function getGlobalRecordTypes($keyword) {
		$globalRecordTypeResults = $this->getGlobalRecordTypesQuery($keyword);
		return $globalRecordTypeResults;
	}

	/**
	 * gets any record type based on search keyword
	 *
	 * @param $keyword
	 * @return $globalRecordTypeResults
	 */
	private function getGlobalRecordTypesQuery($keyword) {
		
		$attributes = array(
			'width'      => '700',
			'height'     => '700',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		$baseUrl = base_url();
		$siteUrl = site_url();
		$js = "js/searchResults.js";
		$jsPath = $baseUrl . $js;
				 			
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "recordName";
		} else {
			$field = "recordCategory";
		}
		
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
		
		// load model (loading a model within a model is not typical)
		$CI =& get_instance();
		$CI->load->model('LookUpTablesModel', 'getDivAndDept', true);
		$this->db->select('recordInformationID, recordInformationDivisionID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	$this->db->from('rm_recordType');
	 
	 	//Check for search all with *
	 	if($keyword != '*') {
		 	$this->db->where('MATCH(
						 		recordDescription, 
						 		recordNotesDeptAnswer, 
						 		recordNotesRmNotes, 
						 		otherPhysicalText, 
						 		otherElectronicText, 
						 		otherDUBuildingText, 
						 		otherOffsiteStorageText, 
						 		otherElectronicSystemText, 
						 		formatAndLocationDeptAnswer, 
						 		formatAndLocationRmNotes, 
						 		usageNotesAnswer, 
						 		retentionAuthoritiesAnswer, 
						 		vitalRecordNotesAnswer, 
						 		personallyIdentifiableInformationAnswer, 
						 		personallyIdentifiableInformationRmNotes, 
						 		otherDepartmentCopiesAnswer) 
						 		AGAINST ("*' . $keyword . '*" IN BOOLEAN MODE) 
						 			OR MATCH(
						 				recordName, 
						 				recordCategory, 
						 				recordFormat, 
						 				recordStorage, 
						 				vitalRecord, 
						 				recordRegulations,
						 				recordRetentionAnswer) 
						 				AGAINST ("*' . $keyword . '*" IN BOOLEAN MODE)');
	 	}
	 	$this->db->order_by($field,$sort);
	 	$globalRecordTypes = $this->db->get();
	 	
	 	if ($globalRecordTypes->num_rows() > 0) {
			
			$globalRecordTypeResults = "";
			$globalRecordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$globalRecordTypeResults .= "<script type='text/javascript'>";
			$globalRecordTypeResults .= "function sortBy(keyword, sortBy, field) { ";
			$globalRecordTypeResults .= "$('#sorting').show(); ";
			$globalRecordTypeResults .= "$.post('$siteUrl/search/getGlobalRecordTypes',{keyword: keyword, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$globalRecordTypeResults .= "$('#globalRecordTypeSearchResults').html(results); ";
			$globalRecordTypeResults .= "$('#sorting').hide(); ";
			$globalRecordTypeResults .= "}); "; // post
			$globalRecordTypeResults .= "} "; // js
			$globalRecordTypeResults .= "</script>";
			
			$rc = 0; // recordCategory
			$rn = 1; // recordName
			
			
			$globalRecordTypeResults .= "<table id='searchResultsTable'>";
			$globalRecordTypeResults .= "<tr>"; 
			$globalRecordTypeResults .= "<th><strong>Division</strong></th>";
			$globalRecordTypeResults .= "<th><strong>Department</strong></th>";
			$globalRecordTypeResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $rc);'>Functional Category</a></strong></th>";
			$globalRecordTypeResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $rn);'>Record Name</a></strong></th>";
	   		$globalRecordTypeResults .= "<th><strong>Description</strong></th>";
	   		$globalRecordTypeResults .= "</tr>";
			
			foreach ($globalRecordTypes->result() as $results) {
				$divisionID = $results->recordInformationDivisionID;
				$departmentID = $results->recordTypeDepartment;
				$divDeptArray = $CI->getDivAndDept->getDivision($departmentID); 		
								
				$globalRecordTypeResults .= "<tr>";
				$globalRecordTypeResults .= "<td>";
				$globalRecordTypeResults .= trim(strip_tags($divDeptArray['divisionName']));
				$globalRecordTypeResults .= "</td>";
				$globalRecordTypeResults .= "<td>";
				$globalRecordTypeResults .= trim(strip_tags($divDeptArray['departmentName']));
				$globalRecordTypeResults .= "</td>";
				$globalRecordTypeResults .= "<td>";
			 	$globalRecordTypeResults .= trim(strip_tags($results->recordCategory));
			 	$globalRecordTypeResults .= "</td>";
				$globalRecordTypeResults .= "<td>";
			 	$globalRecordTypeResults .= anchor_popup('recordType/edit/' . $results->recordInformationID, trim(strip_tags($results->recordName)), $attributes);
			 	$globalRecordTypeResults .= "</td>";
			 	$globalRecordTypeResults .= "<td>";
			 	$globalRecordTypeResults .= trim(strip_tags($results->recordDescription));
				$globalRecordTypeResults .= "</td>"; 
				$globalRecordTypeResults .= "</tr>";
			}
		
			$globalRecordTypeResults .= "</table>";
			return $globalRecordTypeResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
	/**
 	 * Invokes getRetentionScheduleQuery()
 	 *
 	 * @param $departmentID
 	 * @param $divisionID
 	 * @return $retentionScheduleResults;
 	 */
	public function getRetentionSchedules($_POST) {
		$retentionScheduleResults = $this->getRetentionSchedulesQuery($_POST);
		return $retentionScheduleResults;
	}
	
	
	/**
	 * gets retention schedules by division/department (admin)
	 *
	 * @param $departmentID
	 * @param $divisionID
	 * @return $retentionScheduleResults
	 */
	private function getRetentionSchedulesQuery($_POST) {
		
		$departmentID = $_POST['departmentID'];
		$divisionID = $_POST['divisionID'];
		
		$attributes = array(
			'width'      => '965',
			'height'     => '1000',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		
		// get department name
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		if ($departmentID != 999999) {
			$this->db->where('departmentID', $departmentID);
		}
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		 		
		// get division name
		$this->db->select('divisionName');
		$this->db->from('rm_divisions');
		if ($divisionID != 999999) {
			$this->db->where('divisionID', $divisionID);
		}
		$divisionNameQuery = $this->db->get();
		if ($divisionNameQuery->num_rows > 0) {
			$divisonNameResult = $divisionNameQuery->row();
			$divisionName = $divisonNameResult->divisionName;
		}
			
		
		// get retention schedule ids
		$this->db->select('retentionScheduleID');
		//$this->db->from('rm_associatedUnits');
	 	$this->db->from('rm_retentionSchedule');
	 	if ($departmentID != 999999) {
	 		$this->db->where('officeOfPrimaryResponsibility', $departmentID);
	 	}
 		$retentionScheduleIDs = $this->db->get();
 		
 		if ($retentionScheduleIDs->num_rows() > 0) {
			
 			// package id's in an array
 			$ids = array();
			foreach ($retentionScheduleIDs->result() as $id) {
				$ids[] = $id->retentionScheduleID;				
			}
			 			
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "recordCategory";
			} elseif (isset($_POST['field']) && $_POST['field'] == 2) {
				$field = "officeOfPrimaryResponsibility";
			} elseif (isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "disposition";
			} elseif (isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "recordName";
			} else {
				$field = "recordCode";
			}
			
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}

			// generate output
			$siteUrl = site_url();		 		
	 		$baseUrl = base_url();
			$siteName = $this->config->item('site_name');
			
	 		$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			$retentionScheduleResults = "";
			//$retentionScheduleResults .= "<h2>$divisionName</h2>";
			//$retentionScheduleResults .= "<h2>$departmentName</h2>";
			$retentionScheduleResults .= "<script src='$jsPath' type='text/javascript'></script>";
						
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(departmentID, divisionID, sortBy, field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/search/getRetentionSchedules',{ departmentID: departmentID, divisionID: divisionID, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
			
			$rec = 0; // 0 = recordCode
			$rn = 4; // 4 = recordName
			$rc = 1; // 1 = recordCategory
			$opr = 2; // 2 = officeOfPrimaryResponsibility
			$dis = 3; // 3 = disposition
			
			$retentionScheduleResults .= "<br /><br /><br /><br />";
			$retentionScheduleResults .= "<div id='sorting'>Sorting...</div>";
			
			$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
			//$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/pdf'><img src='/$siteName/images/page_white_acrobat.png' alt='Export to PDF' border='0' /></a>&nbsp;&nbsp;";
			$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/csv'><img src='/$siteName/images/page_csv.png' alt='Export to CSV' border='0' /></a>&nbsp;&nbsp;";
			$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/html'><img src='/$siteName/images/page_html.png' alt='Export to HTML' border='0' /></a>&nbsp;&nbsp;";
			$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/xml'><img src='/$siteName/images/page_xml.png' alt='Export to XML' border='0' /></a>&nbsp;&nbsp;";
			
			$retentionScheduleResults .= "<table id='searchResultsTable'>";
			$retentionScheduleResults .= "<tr>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rec);'>Record Code</a></strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rn);'>Record Name</a></strong></th>";
			$retentionScheduleResults .= "<th><strong>Description</strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $opr);'>Primary Owner</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong>Retention Period</strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $dis);'>Retention Rules</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rc);'>Functional Category</a></strong></th>";
			$retentionScheduleResults .= "</tr>";
		  			 	 								
			$this->db->select('retentionScheduleID, recordCode, recordName, recordCategory, retentionPeriod, disposition, officeOfPrimaryResponsibility, recordDescription');
			$this->db->from('rm_retentionSchedule');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$this->db->order_by($field, $sort);
						
			$retentionScheduleQuery = $this->db->get();
		 		
		 		if ($retentionScheduleQuery->num_rows() > 0) {
		 					 			
		 			foreach ($retentionScheduleQuery->result() as $results) {
		 						 						 				
		 				$retentionScheduleResults .= "<tr>";
		 				$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->recordCode));
						$retentionScheduleResults .= "</td>";
		 				$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= anchor_popup('retentionSchedule/edit/' . $results->retentionScheduleID, trim(strip_tags($results->recordName)), $attributes);
					 	$retentionScheduleResults .= "</td>";
					 	
					 	$recordDescription = $results->recordDescription;
						$description = $this->getDescriptionLength($retentionScheduleID="", $recordDescription);
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($description));
						$retentionScheduleResults .= "</td>";
												
						// get divisionID
	 					$this->db->select('departmentName');
	 					$this->db->from('rm_departments');
	 					$this->db->where('departmentID', $results->officeOfPrimaryResponsibility);
	 					$this->db->order_by('departmentName','asc');
	 					$divisionQuery = $this->db->get();
	 					
	 					foreach ($divisionQuery->result() as $officeOfPrimaryResponsibility) {
	 						$officeOfPrimaryResponsibility = $officeOfPrimaryResponsibility->departmentName;
	 					}	
					 	
					 	$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($officeOfPrimaryResponsibility));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->retentionPeriod));
						$retentionScheduleResults .= "</td>";
												
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->disposition));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->recordCategory));
					 	$retentionScheduleResults .= "</td>";
					 	$retentionScheduleResults .= "</tr>";
		 			}
		 		}
			$retentionScheduleResults .= "</table>";
			return $retentionScheduleResults;
			
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
		/**
 	 * Invokes getRetentionScheduleQuery()
 	 *
 	 * @param $departmentID
 	 * @param $divisionID
 	 * @return $retentionScheduleResults;
 	 */
	public function getRetentionSchedulesDeleted($_POST) {
		$retentionScheduleResults = $this->getRetentionSchedulesDeletedQuery($_POST);
		return $retentionScheduleResults;
	}
	
	
	/**
	 * gets retention schedules by division/department (admin)
	 *
	 * @param $departmentID
	 * @param $divisionID
	 * @return $retentionScheduleResults
	 */
	private function getRetentionSchedulesDeletedQuery($_POST) {
		
		$departmentID = $_POST['departmentID'];
		$divisionID = $_POST['divisionID'];
		
		$attributes = array(
			'width'      => '965',
			'height'     => '1000',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		
		
		// get department name
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		if($departmentID != 999999) {
	 		$this->db->where('departmentID', $departmentID);
	 	}
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		 		
		// get division name
		$this->db->select('divisionName');
		$this->db->from('rm_divisions');
		if ($divisionID != 999999) {
			$this->db->where('divisionID', $divisionID);
		}
		$divisionNameQuery = $this->db->get();
		if ($divisionNameQuery->num_rows > 0) {
			$divisonNameResult = $divisionNameQuery->row();
			$divisionName = $divisonNameResult->divisionName;
		}
				
		
		// get retention schedule ids
		$this->db->select('retentionScheduleID');
	 	//$this->db->from('rm_associatedUnits');
	 	$this->db->from('rm_retentionScheduleDeleted');
	 	if($departmentID != 999999) {
	 		$this->db->where('departmentID', $departmentID);
	 	}
 		$retentionScheduleIDs = $this->db->get();
 		
 		if ($retentionScheduleIDs->num_rows() > 0) {
			
 			// package id's in an array
 			$ids = array();
			foreach ($retentionScheduleIDs->result() as $id) {
				$ids[] = $id->retentionScheduleID;				
			}
			 			
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "recordCategory";
			} elseif (isset($_POST['field']) && $_POST['field'] == 2) {
				$field = "officeOfPrimaryResponsibility";
			} elseif (isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "disposition";
			} elseif (isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "recordName";
			} else {
				$field = "recordCode";
			}
			
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}

			// generate output
			$siteUrl = site_url();		 		
	 		$baseUrl = base_url();
	 		$siteName = $this->config->item('site_name');
	 		
	 		$restoreUrl= $siteUrl . "/retentionSchedule/restore";
			$deleteUrl = $siteUrl . "/retentionSchedule/permanentDelete";
			
	 		$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			$retentionScheduleResults = "";
			//$retentionScheduleResults .= "<h2>$divisionName</h2>";
			//$retentionScheduleResults .= "<h2>$departmentName</h2>";
			$retentionScheduleResults .= "<script src='$jsPath' type='text/javascript'></script>";
						
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(departmentID, divisionID, sortBy, field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/search/getRetentionSchedulesDeleted',{ departmentID: departmentID, divisionID: divisionID, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleDeletedSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
			
			$rec = 0; // 0 = recordCode
			$rc = 1; // 1 = recordCategory
			$opr = 2; // 2 = officeOfPrimaryResponsibility
			$dis = 3; // 3 = disposition
			$rn = 4; // 4 = recordName
			
			$retentionScheduleResults .= "<br /><br /><br /><br />";
			$retentionScheduleResults .= "<div id='sorting'>Sorting...</div>";
			//$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
			$retentionScheduleResults .= "<table id='searchResultsTable'>";
			$retentionScheduleResults .= "<tr>";
			$retentionScheduleResults .= "<th><strong>Restore</strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rec);'>Record Code</a></strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rn);'>Record Name</a></strong></th>";
			$retentionScheduleResults .= "<th><strong>Description</strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $opr);'>Primary Owner</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong>Retention Period</strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $dis);'>Retention Rules</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rc);'>Functional Category</a></strong></th>";
			$retentionScheduleResults .= "<th><strong>Purge</strong></th>";
	   		$retentionScheduleResults .= "</tr>";
		  			 	 								
			$this->db->select('retentionScheduleID, recordCode, recordName, recordCategory, retentionPeriod, disposition, officeOfPrimaryResponsibility, recordDescription');
			$this->db->from('rm_retentionScheduleDeleted');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$this->db->order_by($field, $sort);
						
			$retentionScheduleQuery = $this->db->get();
		 		
		 		if ($retentionScheduleQuery->num_rows() > 0) {
		 					 			
		 			foreach ($retentionScheduleQuery->result() as $results) {
		 						 						 				
		 				$retentionScheduleResults .= "<tr>";
		 				
		 				$retentionScheduleResults .= "<td>";
			 			$retentionScheduleResults .= "<form method='link' action='$restoreUrl/$results->retentionScheduleID'  onClick='return confirm(\"Are you sure you want to Restore this record?\")'>";
			 			$retentionScheduleResults .= "<input type='submit' value='Restore'>";
			 			$retentionScheduleResults .= "</form>";
			 			$retentionScheduleResults .= "</td>"; 
			 			
			 			$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->recordCode));
						$retentionScheduleResults .= "</td>";
						
		 				$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->recordName));
					 	$retentionScheduleResults .= "</td>";
					 	
					 	$recordDescription = $results->recordDescription;
						$description = $this->getDescriptionLength($retentionScheduleID="", $recordDescription);
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($description));
						$retentionScheduleResults .= "</td>";
												
						// get divisionID
	 					$this->db->select('departmentName');
	 					$this->db->from('rm_departments');
	 					$this->db->where('departmentID', $results->officeOfPrimaryResponsibility);
	 					$this->db->order_by('departmentName','asc');
	 					$divisionQuery = $this->db->get();
	 					
	 					foreach ($divisionQuery->result() as $officeOfPrimaryResponsibility) {
	 						$officeOfPrimaryResponsibility = $officeOfPrimaryResponsibility->departmentName;
	 					}	
					 	
					 	$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($officeOfPrimaryResponsibility));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->retentionPeriod));
						$retentionScheduleResults .= "</td>";
												
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->disposition));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td>";
					 	$retentionScheduleResults .= trim(strip_tags($results->recordCategory));
					 	$retentionScheduleResults .= "</td>";
					 	
					 	$retentionScheduleResults .= "<td>";
			 			$retentionScheduleResults .= "<form method='link' action='$deleteUrl/$results->retentionScheduleID'  onClick='return confirm(\"Are you sure you want to PURGE this record?\")'>";
			 			$retentionScheduleResults .= "<input type='submit' value='Purge'>";
			 			$retentionScheduleResults .= "</form>";
			 			$retentionScheduleResults .= "</td>"; 
			 	
					 	$retentionScheduleResults .= "</tr>";
		 			}
		 		}
			$retentionScheduleResults .= "</table>";
			return $retentionScheduleResults;
			
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
	
	/**
	 * invokes getGlobalRetentionSchedulesQuery()
	 *
	 * @param $keyword
	 * @return $globalRetentionSchedulesResults
	 */
	public function getGlobalRetentionSchedules($keyword) {
		$globalRetentionSchedulesResults = $this->getGlobalRetentionSchedulesQuery($keyword);
		return $globalRetentionSchedulesResults;
	}
	
	/**
	 * gets any retention schedule based on search keyword
	 *
	 * @param $keyword
	 * @return $globalRetentionScheduleResults
	 */
	private function getGlobalRetentionSchedulesQuery($keyword) {
		
		$attributes = array(
			'width'      => '965',
			'height'     => '1000',
			'scrollbars' => 'yes',
            'status'     => 'yes',
            'resizable'  => 'yes',
            'screenx'    => '0',
            'screeny'    => '0'
		);
		$siteUrl = site_url();
		$publishUrl = $siteUrl . "/retentionSchedule/publish/";
		$siteName = $this->config->item('site_name');
		
		$js = 'onclick="jqCheckAll( this.id, "approvedByCounsel" )"';
		
		// load model (loading a model within a model is not typical)
		$CI =& get_instance();
		//Load LookUptablesModel as getDivAndDept Alias
		$CI->load->model('LookUpTablesModel', 'getDivAndDept', true);
		
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "recordCategory";
		} elseif (isset($_POST['field']) && $_POST['field'] == 2) {
			$field = "recordName";
		} elseif (isset($_POST['field']) && $_POST['field'] == 3) {
			$field = "approvedByCounsel";
		} else {
			$field = "recordCode";
		}
		
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
				
		$this->db->select('retentionScheduleID, recordCode, recordName, recordCategory, recordDescription, keywords, officeOfPrimaryResponsibility, uuid, approvedByCounsel');
	 	$this->db->from('rm_retentionSchedule');
	 	//Check for Search all with *
	 	if($keyword != '*') {
		 	$this->db->where('MATCH(
		 						uuid,
		 						recordCode,
		 						recordName,
		 						recordDescription,
		 						recordCategory,
		 						keywords,
		 						retentionPeriod,
		 						primaryAuthorityRetention,
		 						retentionNotes,
		 						retentionDecisions,
		 						disposition,
		 						primaryAuthority,
		 						notes,
		 						vitalRecord,
		 						approvedByCounsel,
		 						approvedByCounselDate) 
		 						AGAINST ("*' . $keyword . '*" IN BOOLEAN MODE)');
	 	}
	 	$this->db->order_by($field,$sort);
	 	$globalRetentionSchedules = $this->db->get();
	 	
	 	if ($globalRetentionSchedules->num_rows() > 0) {
			$baseUrl = base_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			$globalRetentionSchedulesResults = "";
			$globalRetentionSchedulesResults .= "<script src='$jsPath' type='text/javascript'></script>";
			
			$globalRetentionSchedulesResults .= "<script type='text/javascript'>";
			$globalRetentionSchedulesResults .= "function sortBy(keyword, sortBy, field) { ";
			$globalRetentionSchedulesResults .= "$('#sorting').show(); ";
			$globalRetentionSchedulesResults .= "$.post('$siteUrl/search/getGlobalRetentionSchedules',{keyword: keyword, sortBy: sortBy, field: field, ajax: 'true'}, function(results){ ";
			$globalRetentionSchedulesResults .= "$('#globalRetentionScheduleSearchResults').html(results); ";
			$globalRetentionSchedulesResults .= "$('#sorting').hide(); ";
			$globalRetentionSchedulesResults .= "}); "; // post
			$globalRetentionSchedulesResults .= "} "; // js
			$globalRetentionSchedulesResults .= "</script>";
			
			$cd = 0; // recordCode
			$rn = 2; // recordName
			$rc = 1; // recordCategory
			$pb = 3; // publication
			
			if($keyword != '*') {
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transformText/$keyword/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
				//$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transformText/$keyword/pdf'><img src='/$siteName/images/page_white_acrobat.png' alt='Export to PDF' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transformText/$keyword/csv'><img src='/$siteName/images/page_csv.png' alt='Export to CSV' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transformText/$keyword/html'><img src='/$siteName/images/page_html.png' alt='Export to HTML' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transformText/$keyword/xml'><img src='/$siteName/images/page_xml.png' alt='Export to XML' border='0' /></a>&nbsp;&nbsp;";
			} else {
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transform/999999/excel'><img src='/$siteName/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
				//$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transform/999999/pdf'><img src='/$siteName/images/page_white_acrobat.png' alt='Export to PDF' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transform/999999/csv'><img src='/$siteName/images/page_csv.png' alt='Export to CSV' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transform/999999/html'><img src='/$siteName/images/page_html.png' alt='Export to HTML' border='0' /></a>&nbsp;&nbsp;";
				$globalRetentionSchedulesResults .= "<a href='$siteUrl/export/transform/999999/xml'><img src='/$siteName/images/page_xml.png' alt='Export to XML' border='0' /></a>&nbsp;&nbsp;";
			}
			$globalRetentionSchedulesResults .= "<table id='searchResultsTable'>";
			$globalRetentionSchedulesResults .= "<tr>"; 

			$globalRetentionSchedulesResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $cd);'>Record Code</a></strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $rn);'>Record Name</a></strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $rc);'>Functional Category</a></strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong>Record Description</strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong>Keywords</strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong>Division</strong></th>";
			$globalRetentionSchedulesResults .= "<th><strong>Department</strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong>UUID</strong></th>";
	   		$globalRetentionSchedulesResults .= "<th><strong><a href='#' onClick='sortBy(\"$keyword\", $sortBy, $pb);'>Publication</a></strong><br />";
	   		$globalRetentionSchedulesResults .= "</tr>";
			
		foreach ($globalRetentionSchedules->result() as $results) {
			
			$departmentID = $results->officeOfPrimaryResponsibility;
			$divDeptArray = $CI->getDivAndDept->getDivision($departmentID); 		
							
			$globalRetentionSchedulesResults .= "<tr>";
			$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= trim(strip_tags($results->recordCode));
		 	$globalRetentionSchedulesResults .= "</td>";
			$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= anchor_popup('retentionSchedule/edit/' . $results->retentionScheduleID, trim(strip_tags($results->recordName)), $attributes);
		 	$globalRetentionSchedulesResults .= "</td>";
		 	$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= trim(strip_tags($results->recordCategory));
		 	$globalRetentionSchedulesResults .= "</td>";
		 	$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= trim(strip_tags($results->recordDescription));
			$globalRetentionSchedulesResults .= "</td>";
			$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= trim(strip_tags($results->keywords));
			$globalRetentionSchedulesResults .= "</td>"; 
			$globalRetentionSchedulesResults .= "<td>";
			$globalRetentionSchedulesResults .= trim(strip_tags($divDeptArray['divisionName']));
			$globalRetentionSchedulesResults .= "</td>";
			$globalRetentionSchedulesResults .= "<td>";
			$globalRetentionSchedulesResults .= trim(strip_tags($divDeptArray['departmentName']));
			$globalRetentionSchedulesResults .= "</td>"; 
			$globalRetentionSchedulesResults .= "<td>";
		 	$globalRetentionSchedulesResults .= trim(strip_tags($results->uuid));
			$globalRetentionSchedulesResults .= "</td>";
			$globalRetentionSchedulesResults .= "<td>";
			$globalRetentionSchedulesResults .= "<form name='approveAndPublish' method='post' action='$publishUrl'>";
			
			if($results->approvedByCounsel == "yes") {
				$globalRetentionSchedulesResults .= "<input type='checkbox' name='approvedByCounsel' value='yes' checked />publish<br />";
				$globalRetentionSchedulesResults .= "<input type='hidden' name='retentionScheduleID' value='$results->retentionScheduleID' />";
			} else {
				$globalRetentionSchedulesResults .= "<input type='checkbox' name='approvedByCounsel' value='yes' />publish<br />";
				$globalRetentionSchedulesResults .= "<input type='hidden' name='retentionScheduleID' value='$results->retentionScheduleID' />";
			}
			$globalRetentionSchedulesResults .= "<input type='hidden' name='keyword' value='$keyword'>";
			$globalRetentionSchedulesResults .= "<input type='submit' value='submit'>";
			$globalRetentionSchedulesResults .= "</form>";
			$globalRetentionSchedulesResults .= "</td>";
			$globalRetentionSchedulesResults .= "</tr>";
		}
		
		$globalRetentionSchedulesResults .= "</table>";
		$globalRetentionSchedulesResults .= "<form name='publishAllRetentionSchedules' method='post' action='$siteUrl/retentionSchedule/publishAll'>";
		$globalRetentionSchedulesResults .= "<input type='checkbox' name='publishAll' value='yes' />Publish All&nbsp&nbsp&nbsp";
		$globalRetentionSchedulesResults .= "<input type='hidden' name='keyword' value='$keyword'>";
		$globalRetentionSchedulesResults .= "<input type='submit' value='submit'>";
		
			return $globalRetentionSchedulesResults;
		} else {
			$noResults = "No results found<br /><br />";
			return $noResults;
		}
	}
		
	/**
	 * performs full text search
	 *
	 * @param $_POST
	 * @return $retentionScheduleResults
	 */
	public function doFullTextSearch($_POST) {
		
		$solrHost = $this->solr;
		
		if (isset($_POST['keyword']) && $_POST['keyword'] !== "") {
			// check if post is done via ajax TODO: do I really need this?
			//if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
				$k = trim($_POST['keyword']);
				$keyword = str_replace(" ", "+", $k);
			//} else {
			//	echo "Access Denied.";
			//	exit();
			//}
		} else {
			return "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
		}
		
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 2) {
			$field = "disposition";
		} elseif(isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "officeOfPrimaryResponsibility";
		} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
			$field = "recordCategory";
		} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
			$field = "retentionPeriod";
		} elseif(isset($_POST['field']) && $_POST['field'] == 5) {
			$field = "recordName";
		} elseif(isset($_POST['field']) && $_POST['field'] == 6) {
			$field = "keywords";
		} else {
			$field = "recordCode";
		}	
			
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
										
		// set sorting string
		$sortString = $field . 'Sort%20' . $sort;
		// set to php so array is returned			
		$resultString = 'wt=php';
		// build solr search string  TODO: add paging functionality to full-text search result page. 
		// Solr will NOT return all documents by default.  Asking solr to return 1000 records should suffice in the short-term.
		$searchString = $solrHost . '/select?q=' . $keyword . '&sort=' . $sortString . '&' . $resultString . '&start=0&rows=1000'; // dev only &indent=on
												
		// check if solr is running
		if (!file_get_contents($searchString)) {
			return "&nbsp;&nbsp;&nbsp;&nbsp;<strong>Full-text search server is currently not available.</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";	
			exit();
		} else {
			$solrResponse = file_get_contents($searchString); 
			eval("\$result = " . $solrResponse . ";");
		}
																				
		if (isset($result)) {
			// get number of documents found
			foreach ($result['response'] as $response) {
				if (is_array($response)) {
					$recordCount = count($response);
				}
			}
					
		if ($recordCount > 0) {
																
			$solrResults = $this->generateSolrResults($keyword, $result, $sortBy, $field, $recordCount);
			$retentionScheduleResults = "";
			$retentionScheduleResults .= $solrResults;
					
		} else {
			return "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
		}
			return $retentionScheduleResults;
		} 
	}
	
	/**
	 * generates results based on data returned from the solr search server
	 * @param array $results
	 * @return $retentionScheduleResults
	 */
	private function generateSolrResults($keyword, $result, $sortBy, $field, $recordCount) {
						
		// generate output
		$siteUrl = site_url();		 		
		$baseUrl = base_url();
		$siteName = $this->config->item('site_name');			
		 						
		$jsSearch = "js/searchResults.js";
	 	$jsThickBox = "js/thickBox.js";
		$jsPathSearch = $baseUrl . $jsSearch;
		$jsPathThickBox = $baseUrl . $jsThickBox;
								
		$retentionScheduleResults = "";
		$retentionScheduleResults .= "<script src='$jsPathSearch' type='text/javascript'></script>";
		$retentionScheduleResults .= "<script src='$jsPathThickBox' type='text/javascript'></script>";
		
		if ($keyword !== "") {
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/du/fullTextSearch',{ keyword: '$keyword', sortBy: $sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		} 
						
		$rco = 0; // 0 = recordCode
		$opr = 1; // 1 = officeOfPrimaryResponsibility
		$dis = 2; // 2 = disposition
		$rc =  3; // 3 = recordCategory
		$rp =  4; // 4 = retentionPeriod
		$rn =  5; // 5 = recordName
		$kw = 6;  // 6 = keywords
		
		//Assign regular image
		$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image6 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		
		//Assign Sort arrow images
		if($field == "recordCode") {
			if($sortBy == 2) {
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "officeOfPrimaryResponsibility") {
			if($sortBy == 2) {
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "disposition") {
			if($sortBy == 2) {
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "recordCategory") {
			if($sortBy == 2) {
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "retentionPeriod") {
			if($sortBy == 2) {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "recordName") {
			if($sortBy == 2) {
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "keywords") { 
			if($sortBy == 2) {
				$image6 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image6 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image6 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		}
		
		//$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/site/about/urmp/retentionFAQ.php' target='_blank'>About the Records Retention Schedule</a><br />";
		//$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/site/about/urmp/glossaryURMP.php' target='_blank'>What do these codes mean?</a><br /><br />";
		
		$retentionScheduleResults .= "&nbsp;&nbsp;Records Found:&nbsp;" . $recordCount;
		$retentionScheduleResults .= "<table id='searchResultsTable' width='100%'>";
		
		$retentionScheduleResults .= "<tr>";
		$retentionScheduleResults .= "<th width='6%'><strong><a href='#' title='Click to sort' onClick='sortBy($rco);'>Record Code$image0</a></strong></th>";
		$retentionScheduleResults .= "<th width='10%'><strong><a href='#' title='Click to sort' onClick='sortBy($rc);'>Functional Category$image3</a></strong></th>";
		$retentionScheduleResults .= "<th width='13%'><strong><a href='#' title='Click to sort' onClick='sortBy($rn);'>Record Group$image5</a></strong></th>";
		$retentionScheduleResults .= "<th width='19%'><strong>Description</strong></th>";
		$retentionScheduleResults .= "<th width='19%'><strong><a href='#' title='Click to sort' onClick='sortBy($kw);'>Search Terms$image6</a></strong></th>";
		$retentionScheduleResults .= "<th width='16%'><strong><a href='#' title='Click to sort' onClick='sortBy($rp);'>Retention Period$image4</a></strong></th>";
		$retentionScheduleResults .= "<th width='16%'><strong><a href='#' title='Click to sort' onClick='sortBy($dis);'>Retention Rules$image2</a></strong></th>";
		//$retentionScheduleResults .= "<th width='10%'><strong><a href='#' title='Click to sort' onClick='sortBy($opr);'>Primary Owner$image1</a></strong></th>";
		$retentionScheduleResults .= "<th width='1%'><strong></strong></th>";
		$retentionScheduleResults .= "</tr>";
		$retentionScheduleResults .= "</table>";
		
		$retentionScheduleResults .= "<div id='loadingContainer' style='height:630px; overflow: auto;'><span id='sorting'><em>Sorting...</em></span><br />";
		$retentionScheduleResults .= "<table id='searchResultsTable' width='100%'>";
		// display records
		foreach ($result['response'] as $response) {
			if (is_array($response)) {
				foreach ($response as $docs) {
					
					$retentionScheduleID = $docs['retentionScheduleID'];
					
					// set vars
					foreach ($docs['recordCode'] as $record) {
						$recordCode = $record;
					}
					
					foreach ($docs['recordName'] as $record) {
						$recordName = $record;
					}
					
					foreach ($docs['recordDescription'] as $record) {
						$recordDescription = $record;
					}
					
					foreach ($docs['officeOfPrimaryResponsibility'] as $record) {
						$officeOfPrimaryResponsibility = $record;
					}
					
					foreach ($docs['retentionPeriod'] as $record) {
						$retentionPeriod = $record;
					}
					
					foreach ($docs['disposition'] as $record) {
						$disposition = $record;
					}
					
					foreach ($docs['recordCategory'] as $record) {
						$recordCategory = $record;
					}
					
					foreach ($docs['keywords'] as $record) {
						$keywords = $record;
					}
					
					$retentionScheduleResults .= "<tr>";
					
					$retentionScheduleResults .= "<td width='6%'>";
					$retentionScheduleResults .= trim(strip_tags($recordCode));
					$retentionScheduleResults .= "</td>";
					
					$retentionScheduleResults .= "<td width=10%'>";
					$retentionScheduleResults .= trim(strip_tags($recordCategory));
					$retentionScheduleResults .= "</td>";
	
					$retentionScheduleResults .= "<td width='13%'>";
					$retentionScheduleResults .= "<a href='/$siteName/index.php/du/getRetentionSchedule/" . $retentionScheduleID . "?height=435&width=450' class='thickbox' title='Click to view details'>$recordName</a>";
					$retentionScheduleResults .= "</td>";
					
					$retentionScheduleResults .= "<td width='19%'>";
					$descResults = $this->getDescriptionLength($retentionScheduleID, $recordDescription);
					$retentionScheduleResults .= trim($descResults);
					$retentionScheduleResults .= "</td>";
					
					$retentionScheduleResults .= "<td width='19%'>";
					$keywordsResults = $this->getDescriptionLength($retentionScheduleID, $keywords);
					$retentionScheduleResults .= trim($keywordsResults);
					$retentionScheduleResults .= "</td>";
										
					$retentionScheduleResults .= "<td width='16%'>";
					$retentionPeriodResults	= $this->getDescriptionLength($retentionScheduleID, $retentionPeriod);
					$retentionScheduleResults .= trim($retentionPeriodResults);
					$retentionScheduleResults .= "</td>";
							
					$retentionScheduleResults .= "<td width='16%'>";
					$retentionScheduleResults .= trim(strip_tags($disposition));
					$retentionScheduleResults .= "</td>";
					
					/*
					 
					$retentionScheduleResults .= "<td width='10%'>";
					$retentionScheduleResults .= trim(strip_tags($officeOfPrimaryResponsibility)); 
					$retentionScheduleResults .= "</td>";
					*/			
					$retentionScheduleResults .= "</tr>";	
							
				}
			} 
		}
		
		$retentionScheduleResults .= "</table></div>";
		$retentionScheduleResults .= "<br /><br />";
		
		return $retentionScheduleResults;
	}
	
	/**
	 * performs search by record category
	 *
	 * @param $_POST
	 * @return $retentionScheduleResults
	 */
	public function doRecordCategorySearch($_POST) {
	
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 2) {
			$field = "disposition";
		} elseif(isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "keywords";
		} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
			$field = "recordCategory";
		} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
			$field = "retentionPeriod";
		} elseif(isset($_POST['field']) && $_POST['field'] == 5) {
			$field = "recordName";
		} else {
			$field = "recordCode";
		}
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
		$this->db->select(); // "*" is assumed by codeigniter
		$this->db->from('rm_fullTextSearch'); 
		//$this->db->where('recordCategory', $rc[0]);
		$categories = "";
		
		//Check for ajax call back
		if (isset($_POST['recordCategory'])) {
			// parse string with explode()
			$recordCategories = explode("|", $_POST['recordCategory']);
			foreach($recordCategories as $recordCategory) {
				$categories .= $recordCategory . "|";
				$rc = trim($recordCategory);
				$this->db->or_where('recordCategory', $rc);
			}
		} else {
			foreach($_POST as $recordCategory) {
				$categories .= $recordCategory . "|";
				$rc = trim($recordCategory);
				$this->db->or_where('recordCategory', $rc);
			}
		}
		$this->db->order_by($field, $sort);
		$retentionScheduleQuery = $this->db->get();

		if ($retentionScheduleQuery->num_rows() > 0) {
				
	 			$retentionScheduleResults = "";
	 			$results = $this->generateSearchResults($divisionID="", $departmentID="", $sortBy, $retentionScheduleQuery, $keyword="", $categories, $field);
				$retentionScheduleResults .= $results;
	 						
				return $retentionScheduleResults;
	 		} else {
				$retentionScheduleResults = "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
				return $retentionScheduleResults;
			}			
	}
	
	/**
	 * performs search by department
	 *
	 * @param $_POST
	 * @return $retentionScheduleResults
	 */
	public function doDepartmentSearch($_POST) {
						
		$divisionID = $_POST['divisionID'];
		$departmentID = $_POST['departmentID'];
								
		// get retention schedule ids
		$this->db->select('retentionScheduleID');
	 	$this->db->from('rm_associatedUnits');
	 	$this->db->where('departmentID', $departmentID);
 		$retentionScheduleIDs = $this->db->get();
 		
 		if ($retentionScheduleIDs->num_rows() > 0) {
					
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 2) {
				$field = "disposition";
			} elseif(isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "keywords";
			} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "recordCategory";
			} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "retentionPeriod";
			} elseif(isset($_POST['field']) && $_POST['field'] == 5) {
				$field = "recordName";
			} else {
				$field = "recordCode";
			}
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}
 			 			
 			// package id's in an array
 			$ids = array();
			foreach ($retentionScheduleIDs->result() as $id) {
				$ids[] = $id->retentionScheduleID;				
			}
			 		 		
 			$this->db->select(); // "*" is assumed by codeigniter
			$this->db->from('rm_fullTextSearch'); 
			$this->db->where_in('retentionScheduleID', $ids);
		 	$this->db->order_by($field, $sort);
			$retentionScheduleQuery = $this->db->get();
			
	 		if ($retentionScheduleQuery->num_rows() > 0) {
				
	 			$retentionScheduleResults = "";
	 			$results = $this->generateSearchResults($divisionID, $departmentID, $sortBy, $retentionScheduleQuery, $keyword="", $recordCategory="", $field);
				$retentionScheduleResults .= $results;
	 						
				return $retentionScheduleResults;
	 		} else {
				$retentionScheduleResults = "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
				return $retentionScheduleResults;
			}			
	 	
		} else {
			$retentionScheduleResults = "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
			return $retentionScheduleResults;
		}
	}
	
	/**
	 * generates search result view
	 *
	 * @param $divisionID, $departmentID, $retentionScheduleQuery, $keyword
	 * @return $retentionScheduleResults
	 */
	private function generateSearchResults($divisionID="", $departmentID="", $sortBy, $retentionScheduleQuery, $keyword="", $recordCategory="", $field) {
		
		$recordCount = $retentionScheduleQuery->num_rows();
		// generate output
		$siteUrl = site_url();		 		
		$baseUrl = base_url();
		$siteName = $this->config->item('site_name');
						 						
		$jsSearch = "js/searchResults.js";
	 	$jsThickBox = "js/thickBox.js";
		$jsPathSearch = $baseUrl . $jsSearch;
		$jsPathThickBox = $baseUrl . $jsThickBox;
								
		$retentionScheduleResults = "";
		$retentionScheduleResults .= "<script src='$jsPathSearch' type='text/javascript'></script>";
		$retentionScheduleResults .= "<script src='$jsPathThickBox' type='text/javascript'></script>";
		
		if ($divisionID !== "") {
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/du/searchByDepartment',{ divisionID: $divisionID, departmentID: $departmentID, sortBy: $sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleSearchResults').html(results); ";
			//$retentionScheduleResults .= "$('#divDeptRetentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		} elseif ($keyword !== "") {
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/du/fullTextSearch',{ keyword: '$keyword', sortBy: $sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		} elseif ($recordCategory !== "") {
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/du/searchByRecordCategory',{ recordCategory: '$recordCategory', sortBy: $sortBy, field: field, ajax: 'true'}, function(results){ ";
			$retentionScheduleResults .= "$('#retentionScheduleSearchResults').html(results); ";
			//$retentionScheduleResults .= "$('#recordCategoryRetentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		}
				
		$rco = 0; // 0 = record code
		$kw = 1; // 1 = keywords
		$dis = 2; // 2 = disposition
		$rc =  3; // 3 = recordCategory
		$rp =  4; // 4 = retentionPeriod
		$rn =  5; // 5 = recordName
		$opr = 6; // 6 = officeOfPrimaryResponsibility

		
		//Assign regular image
		$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		$image6 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
		
		//Assign Sort arrow images
		if($field == "recordCode") {
			if($sortBy == 2) {
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image0 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "keywords") {
			if($sortBy == 2) {
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image1 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "disposition") {
			if($sortBy == 2) {
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image2 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "recordCategory") {
			if($sortBy == 2) {
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image3 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "retentionPeriod") {
			if($sortBy == 2) {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else {
				$image4 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} elseif($field == "recordName") {
			if($sortBy == 2) {
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_up_white.gif' /></img>";
			} elseif ($sortBy == 1) {
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_down_white.gif' /></img>";
			} else { 
				$image5 = "<img src='" . $baseUrl . "images/222222_7x7_arrow_updown_white.gif' /></img>";
			}
		} 
		
		//$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/site/about/urmp/retentionFAQ.php' target='_blank'>About the Records Retention Schedule</a><br />";
		//$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/site/about/urmp/glossaryURMP.php' target='_blank'>What do these codes mean?</a><br /><br />";
		
		$retentionScheduleResults .= "<div id='loadingContainer'><span id='sorting'><em>Sorting...</em></span></div><br />";
		$retentionScheduleResults .= "&nbsp;&nbsp;Records Found:&nbsp;" . $recordCount;
		
		$retentionScheduleResults .= "<table id='searchResultsTable' width='100%'>";
		$retentionScheduleResults .= "<tr>";
		$retentionScheduleResults .= "<th width='6%'><strong><a href='#' title='Click to sort' onClick='sortBy($rco);'>Record Code$image0</a></strong></th>";
		$retentionScheduleResults .= "<th width='10%'><strong><a href='#' title='Click to sort' onClick='sortBy($rc);'>Functional Category$image3</a></strong></th>";
		$retentionScheduleResults .= "<th width='13%'><strong><a href='#' title='Click to sort' onClick='sortBy($rn);'>Record Group$image5</a></strong></th>";
		$retentionScheduleResults .= "<th width='19%'><strong>Description</strong></th>";
		$retentionScheduleResults .= "<th width='19%'><strong><a href='#' title='Click to sort' onClick='sortBy($kw);'>Search Terms$image1</a></strong></th>";
		$retentionScheduleResults .= "<th width='16%'><strong><a href='#' title='Click to sort' onClick='sortBy($rp);'>Retention Period$image4</a></strong></th>";
		$retentionScheduleResults .= "<th width='16%'><strong><a href='#' title='Click to sort' onClick='sortBy($dis);'>Retention Rules$image2</a></strong></th>";
		//$retentionScheduleResults .= "<th width='10%'><strong><a href='#' title='Click to sort' onClick='sortBy($opr);'>Primary Owner$image6</a></strong></th>";
		$retentionScheduleResults .= "<th width='1%'><strong></strong></th>";
		$retentionScheduleResults .= "</tr>";
		$retentionScheduleResults .= "</table>";
		
		$retentionScheduleResults .= "<div id='loadingContainer' style='height:630px; overflow: auto;'><span id='sorting'><em>Sorting...</em></span><br />";
		$retentionScheduleResults .= "<table id='searchResultsTable' width='100%'>";
		
		foreach ($retentionScheduleQuery->result() as $results) {
			
			$retentionScheduleID = $results->retentionScheduleID;
			$recordDescription = $results->recordDescription;
			$keywords = $results->keywords;
			$retentionPeriod = $results->retentionPeriod;
			
			$retentionScheduleResults .= "<tr>";
			
			$retentionScheduleResults .= "<td width='6%'>";
			$retentionScheduleResults .= trim(strip_tags($results->recordCode));
			$retentionScheduleResults .= "</td>";
			
			$retentionScheduleResults .= "<td width='10%'>";
			$retentionScheduleResults .= trim(strip_tags($results->recordCategory));
			$retentionScheduleResults .= "</td>";
			
			$retentionScheduleResults .= "<td width='13%'>";
			$retentionScheduleResults .= "<a href='/$siteName/index.php/du/getRetentionSchedule/$results->retentionScheduleID?height=420&width=450' class='thickbox' title='Click to view details'>$results->recordName</a>";
			$retentionScheduleResults .= "</td>";

			$retentionScheduleResults .= "<td width='19%'>";
			$descResults = $this->getDescriptionLength($retentionScheduleID, $recordDescription);
			$retentionScheduleResults .= trim($descResults);
			$retentionScheduleResults .= "</td>";
			
			$retentionScheduleResults .= "<td width='19%'>";
			$keywordsResults = $this->getDescriptionLength($retentionScheduleID, $keywords);
			$retentionScheduleResults .= trim($keywordsResults);
			$retentionScheduleResults .= "</td>";

			$retentionScheduleResults .= "<td width='16%'>";
			$retentionPeriodResults	= $this->getDescriptionLength($retentionScheduleID, $retentionPeriod);
			$retentionScheduleResults .= trim($retentionPeriodResults);
			$retentionScheduleResults .= "</td>";
						
			$retentionScheduleResults .= "<td width='16%'>";
			$retentionScheduleResults .= trim(strip_tags($results->disposition));
			$retentionScheduleResults .= "</td>";
			

			$retentionScheduleResults .= "</tr>";	
		}
		 	
		$retentionScheduleResults .= "</table></div>";
		$retentionScheduleResults .= "<br /><br />";
		
		return $retentionScheduleResults;
	}
		
     /**
	 * deterines the length of the text in the description field and truncates it to the last space if it is more than 100 characters
	 * adds more url to full description
	 * 
	 * @param $retentionScheduleID
	 * @param $recordDescription
	 * @return $results
	 */
	private function getDescriptionLength($retentionScheduleID, $recordDescription) {
		$siteName = $this->config->item('site_name');
		$results = "";
		if ( strlen($recordDescription) <= 100 ) {
			return $recordDescription;
		}
		
		$newstr = substr($recordDescription, 0, 100);
		
		if ( substr($newstr,-1,1) != ' ' ) {
			$newstr = substr($newstr, 0, strrpos($newstr, " "));	
		}
		if ($retentionScheduleID !== "") {
			$results .= $newstr . "&nbsp;<a href='/$siteName/index.php/du/getRetentionSchedule/$retentionScheduleID?height=420&width=450' class='thickbox' title='University Records Management Program'>more...</a>";	
		} else {
			$results .= $newstr . "&nbsp;...";		
		}
		return $results;
	}

	/**
 	 * Invokes getDepartmentQuery()
 	 *
 	 * @param $departmentID
 	 * @return $departmentResults
 	 */
	public function getDepartment($departmentID) {
		$departmentResults = $this->getDepartmentQuery($departmentID);
		return $departmentResults;
	}
	
	/**
	 * gets department information
	 *
	 * @param $departmentID
	 * @return $departmentResults
	 */
	private function getDepartmentQuery($departmentID) {
		$this->db->select('departmentID, divisionID, departmentName, timestamp');
		$this->db->from('rm_departments');
		$this->db->where('dpartmentID', $departmentID);
		$this->db->order_by('departmentName','asc');
		$departmentResults = $this->db->get();
		
		return $departmentResults;
	}
}
?>