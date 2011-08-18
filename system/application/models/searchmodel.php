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
 
 class SearchModel extends Model 
{

	public function __construct() {
 		parent::Model();
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
		$this->db->select('recordInformationID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->where('recordTypeDepartment', $recordTypeDepartment);
 		$recordTypes = $this->db->get();
		
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		$this->db->where('departmentID', $recordTypeDepartment);
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		
		if ($recordTypes->num_rows() > 0) {
			
			$this->db->select('divisionName');
			$this->db->from('rm_divisions');
			$this->db->where('divisionID', $divisionID);
			$divisionNameQuery = $this->db->get();
				if ($divisionNameQuery->num_rows > 0) {
					$divisonNameResult = $divisionNameQuery->row();
					$divisionName = $divisonNameResult->divisionName;
				 }
			$baseUrl = base_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			$recordTypeResults = "";
			$recordTypeResults .= "<h2>" . trim(strip_tags($divisionName)) . "</h2>";
			$recordTypeResults .= "<h2>" . trim(strip_tags($departmentName)) . "</h2>";
			$recordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$recordTypeResults .= "<a href=''>New Search</a>";
			$recordTypeResults .= "<table id='searchResultsTable'>";
			$recordTypeResults .= "<tr>"; 
   			$recordTypeResults .= "<th><strong>Record Name</strong></th>";
   			$recordTypeResults .= "<th><strong>Record Category</strong></th>";
   			$recordTypeResults .= "<th><strong>Record Description</strong></th>";
   			$recordTypeResults .= "</tr>";
			
			foreach ($recordTypes->result() as $results) {
			 		
			 	$recordTypeResults .= "<tr>";
				$recordTypeResults .= "<td>";
			 	$recordTypeResults .= anchor_popup('dashboard/editRecordTypeForm/' . $results->recordInformationID, trim(strip_tags($results->recordName)), $attributes);
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= trim(strip_tags($results->recordCategory));
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
		
		// load model (loading a model within a model is not typical)
		$CI =& get_instance();
		$CI->load->model('LookUpTablesModel', 'getDivAndDept', true);
				
		$this->db->select('recordInformationID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->like('recordName', $keyword);
	 	$this->db->or_like('recordCategory', $keyword);
	 	$this->db->or_like('recordDescription', $keyword);
	 	$this->db->or_like('recordCategory', $keyword);
	 	$globalRecordTypes = $this->db->get();
	 	
	 	if ($globalRecordTypes->num_rows() > 0) {
			$baseUrl = base_url();
			$js = "js/searchResults.js";
			$jsPath = $baseUrl . $js;
			$globalRecordTypeResults = "";
			$globalRecordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$globalRecordTypeResults .= "<table id='searchResultsTable'>";
			$globalRecordTypeResults .= "<tr>"; 
			$globalRecordTypeResults .= "<th><strong>Division</strong></th>";
			$globalRecordTypeResults .= "<th><strong>Department</strong></th>";
	   		$globalRecordTypeResults .= "<th><strong>Record Name</strong></th>";
	   		$globalRecordTypeResults .= "<th><strong>Record Category</strong></th>";
	   		$globalRecordTypeResults .= "<th><strong>Record Description</strong></th>";
	   		$globalRecordTypeResults .= "</tr>";
			
		foreach ($globalRecordTypes->result() as $results) {
			
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
		 	$globalRecordTypeResults .= anchor_popup('dashboard/editRecordTypeForm/' . $results->recordInformationID, trim(strip_tags($results->recordName)), $attributes);
		 	$globalRecordTypeResults .= "</td>";
		 	$globalRecordTypeResults .= "<td>";
		 	$globalRecordTypeResults .= trim(strip_tags($results->recordCategory));
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
		
		/*
		// get department name
		$this->db->select('departmentName');
		$this->db->from('rm_departments');
		$this->db->where('departmentID', $departmentID);
		$departmentNameQuery = $this->db->get();
		if ($departmentNameQuery->num_rows() > 0) {
			$departmentNameResult = $departmentNameQuery->row();
			$departmentName = $departmentNameResult->departmentName;		 		 	
		}
		 		
		// get division name
		$this->db->select('divisionName');
		$this->db->from('rm_divisions');
		$this->db->where('divisionID', $divisionID);
		$divisionNameQuery = $this->db->get();
		if ($divisionNameQuery->num_rows > 0) {
			$divisonNameResult = $divisionNameQuery->row();
			$divisionName = $divisonNameResult->divisionName;
		}
		*/		
		
		// get retention schedule ids
		$this->db->select('retentionScheduleID');
	 	$this->db->from('rm_associatedUnits');
	 	$this->db->where('departmentID', $departmentID);
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
			} else {
				$field = "recordName";
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
			
			$rn = 0; // 0 = recordName
			$rc = 1; // 1 = recordCategory
			$opr = 2; // 2 = officeOfPrimaryResponsibility
			$dis = 3; // 3 = disposition
			
			$retentionScheduleResults .= "<br /><br /><br /><br />";
			$retentionScheduleResults .= "<div id='sorting'>Sorting...</div>";
			$retentionScheduleResults .= "<a href='$siteUrl/export/transform/$departmentID/excel'><img src='/liaison/images/page_excel.png' alt='Export to Excel' border='0' /></a>&nbsp;&nbsp;";
			$retentionScheduleResults .= "<table id='searchResultsTable'>";
			$retentionScheduleResults .= "<tr>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rn);'>Record Name</a></strong></th>";
			$retentionScheduleResults .= "<th><strong>Record Description</strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $opr);'>Office of Primary Responsibility</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong>retentionPeriod</strong></th>";
			$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $dis);'>Disposition</a></strong></th>";
	   		$retentionScheduleResults .= "<th><strong><a href='#' onClick='sortBy($departmentID, $divisionID, $sortBy, $rc);'>Record Category</a></strong></th>";
			$retentionScheduleResults .= "</tr>";
		  			 	 								
			$this->db->select('retentionScheduleID, recordName, recordCategory, retentionPeriod, disposition, officeOfPrimaryResponsibility, recordDescription');
			$this->db->from('rm_retentionSchedule');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$this->db->order_by($field, $sort);
						
			$retentionScheduleQuery = $this->db->get();
		 		
		 		if ($retentionScheduleQuery->num_rows() > 0) {
		 					 			
		 			foreach ($retentionScheduleQuery->result() as $results) {
		 						 						 				
		 				$retentionScheduleResults .= "<tr>";
		 				$retentionScheduleResults .= "<td width='20%'>";
					 	$retentionScheduleResults .= anchor_popup('retentionSchedule/edit/' . $results->retentionScheduleID, trim(strip_tags($results->recordName)), $attributes);
					 	$retentionScheduleResults .= "</td>";
					 	
					 	$recordDescription = $results->recordDescription;
						$description = $this->getDescriptionLength($retentionScheduleID="", $recordDescription);
						
						$retentionScheduleResults .= "<td width='25%'>";
					 	$retentionScheduleResults .= trim(strip_tags($description));
						$retentionScheduleResults .= "</td>";
												
						// get divisionID
	 					$this->db->select('departmentName');
	 					$this->db->from('rm_departments');
	 					$this->db->where('departmentID', $results->officeOfPrimaryResponsibility);
	 					$divisionQuery = $this->db->get();
	 					
	 					foreach ($divisionQuery->result() as $officeOfPrimaryResponsibility) {
	 						$officeOfPrimaryResponsibility = $officeOfPrimaryResponsibility->departmentName;
	 					}	
					 	
					 	$retentionScheduleResults .= "<td width='20%'>";
					 	$retentionScheduleResults .= trim(strip_tags($officeOfPrimaryResponsibility));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td width='20%'>";
					 	$retentionScheduleResults .= trim(strip_tags($results->retentionPeriod));
						$retentionScheduleResults .= "</td>";
												
						$retentionScheduleResults .= "<td width='5%'>";
					 	$retentionScheduleResults .= trim(strip_tags($results->disposition));
						$retentionScheduleResults .= "</td>";
						
						$retentionScheduleResults .= "<td width='15%'>";
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
	 * performs full text search
	 *
	 * @param $_POST
	 * @return $retentionScheduleResults
	 */
	public function doFullTextSearch($_POST) {
		
		$keyword = $_POST['keyword'];
		
			// set sort by field name
			if (isset($_POST['field']) && $_POST['field'] == 2) {
				$field = "disposition";
			} elseif(isset($_POST['field']) && $_POST['field'] == 1) {
				$field = "officeOfPrimaryResponsibility";
			} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "recordCategory";
			} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "retentionPeriod";
			} else {
				$field = "recordName";
			}	
		
			// set sort order
			if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
				$sortBy = 2; // desc : Z - A
				$sort = "desc";
			} else {
				$sortBy = 1; // asc : A - Z
				$sort = "asc";
			}
		
		// full-text search
		$sql = "SELECT retentionScheduleID, recordName, recordCategory, officeOfPrimaryResponsibility, disposition, retentionPeriod, recordDescription  FROM rm_fullTextSearch " .
						"WHERE MATCH (recordName, officeOfPrimaryResponsibility, disposition, retentionPeriod, recordDescription) AGAINST ( ? ) " .
						"ORDER BY $field $sort";
		$retentionScheduleQuery = $this->db->query($sql, array($keyword));
		 		
 		if ($retentionScheduleQuery->num_rows() > 0) {
			
 			$retentionScheduleResults = "";
	 		$results = $this->generateSearchResults($divisionID="", $departmentID="", $sortBy, $retentionScheduleQuery, $keyword, $recordCategory="");
			$retentionScheduleResults .= $results;
			 					
			return $retentionScheduleResults;
			
		} else {
			$retentionScheduleResults = "&nbsp;&nbsp;&nbsp;&nbsp;<strong>No results found</strong>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />";
			return $retentionScheduleResults;
		}
		
	}
	
	/**
	 * performs search by record category
	 *
	 * @param $_POST
	 * @return $retentionScheduleResults
	 */
	public function doRecordCategorySearch($_POST) {
		
		$recordCategory = trim($_POST['recordCategory']);
						
		// set sort by field name
		if (isset($_POST['field']) && $_POST['field'] == 2) {
			$field = "disposition";
		} elseif(isset($_POST['field']) && $_POST['field'] == 1) {
			$field = "officeOfPrimaryResponsibility";
		} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
			$field = "recordCategory";
		} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
			$field = "retentionPeriod";
		} else {
			$field = "recordName";
		}
		// set sort order
		if (isset($_POST['sortBy']) && $_POST['sortBy'] == 1) {
			$sortBy = 2; // desc : Z - A
			$sort = "desc";
		} else {
			$sortBy = 1; // asc : A - Z
			$sort = "asc";
		}
		
		$this->db->select('retentionScheduleID, recordName, recordCategory, officeOfPrimaryResponsibility, disposition, retentionPeriod, recordDescription');
		$this->db->from('rm_fullTextSearch'); 
		$this->db->where('recordCategory', $recordCategory);
		$this->db->order_by($field, $sort);
		$retentionScheduleQuery = $this->db->get();
	 	
		if ($retentionScheduleQuery->num_rows() > 0) {
				
	 			$retentionScheduleResults = "";
	 			$results = $this->generateSearchResults($divisionID="", $departmentID="", $sortBy, $retentionScheduleQuery, $keyword="", $recordCategory);
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
				$field = "officeOfPrimaryResponsibility";
			} elseif(isset($_POST['field']) && $_POST['field'] == 3) {
				$field = "recordCategory";
			} elseif(isset($_POST['field']) && $_POST['field'] == 4) {
				$field = "retentionPeriod";
			} else {
				$field = "recordName";
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
			 		 		
 			$this->db->select('retentionScheduleID, recordName, recordCategory, officeOfPrimaryResponsibility, disposition, retentionPeriod, recordDescription');
			$this->db->from('rm_fullTextSearch'); 
			$this->db->where_in('retentionScheduleID', $ids);
		 	$this->db->order_by($field, $sort);
			$retentionScheduleQuery = $this->db->get();
			
	 		if ($retentionScheduleQuery->num_rows() > 0) {
				
	 			$retentionScheduleResults = "";
	 			$results = $this->generateSearchResults($divisionID, $departmentID, $sortBy, $retentionScheduleQuery, $keyword="", $recordCategory="");
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
private function generateSearchResults($divisionID="", $departmentID="", $sortBy, $retentionScheduleQuery, $keyword="", $recordCategory="") {
		
		$recordCount = $retentionScheduleQuery->num_rows();
		// generate output
		$siteUrl = site_url();		 		
		$baseUrl = base_url();
						 						
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
			$retentionScheduleResults .= "$('#divDeptRetentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		} elseif ($keyword !== "") {
			$retentionScheduleResults .= "<script type='text/javascript'>";
			$retentionScheduleResults .= "function sortBy(field) { ";
			$retentionScheduleResults .= "$('#sorting').show(); ";
			$retentionScheduleResults .= "$.post('$siteUrl/du/fullTextSearch',{ keyword: $keyword, sortBy: $sortBy, field: field, ajax: 'true'}, function(results){ ";
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
			$retentionScheduleResults .= "$('#recordCategoryRetentionScheduleSearchResults').html(results); ";
			$retentionScheduleResults .= "$('#sorting').hide(); ";
			$retentionScheduleResults .= "}); "; // post
			$retentionScheduleResults .= "} "; // js
			$retentionScheduleResults .= "</script>";
		}
				
		$rn =  0; // 0 = recordName
		$opr = 1; // 1 = officeOfPrimaryResponsibility
		$dis = 2; // 2 = disposition
		$rc =  3; // 3 = recordCategory
		$rp =  4; // 4 = retentionPeriod
		
		$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/urmp/retention_faq.html' target='_blank'>About the Records Retention Schedule</a><br />";
		$retentionScheduleResults .= "&nbsp;&nbsp;<a href='http://library.du.edu/urmp/glossary.html' target='_blank'>What do these codes mean?</a><br /><br />";
		
		$retentionScheduleResults .= "<div id='loadingContainer'><span id='sorting'><em>Sorting...</em></span></div><br />";
		$retentionScheduleResults .= "&nbsp;&nbsp;Records Found:&nbsp;" . $recordCount;
		$retentionScheduleResults .= "<table id='searchResultsTable' width='100%'>";
		
		$retentionScheduleResults .= "<tr>";
		$retentionScheduleResults .= "<th><strong><a href='#' title='Click to sort' onClick='sortBy($rn);'>Record Name</a></strong></th>";
		$retentionScheduleResults .= "<th><strong>Record Description</strong></th>";
		$retentionScheduleResults .= "<th><strong><a href='#' title='Click to sort' onClick='sortBy($opr);'>Office of Primary Responsibility</a></strong></th>";
		$retentionScheduleResults .= "<th><strong><a href='#' title='Click to sort' onClick='sortBy($rp);'>Retention Period</a></strong></th>";
		$retentionScheduleResults .= "<th><strong><a href='#' title='Click to sort' onClick='sortBy($dis);'>Disposition</a></strong></th>";
		$retentionScheduleResults .= "<th><strong><a href='#' title='Click to sort' onClick='sortBy($rc);'>Record Category</a></strong></th>";
				
		$retentionScheduleResults .= "</tr>";
		
		foreach ($retentionScheduleQuery->result() as $results) {
			
			$retentionScheduleResults .= "<tr>";
			$retentionScheduleResults .= "<td width='15%'>";
			$retentionScheduleResults .= "<a href='/liaison/index.php/du/getRetentionSchedule/$results->retentionScheduleID?height=420&width=450' class='thickbox' title='Click to view details'>$results->recordName</a>";
			$retentionScheduleResults .= "</td>";

			
			$retentionScheduleResults .= "<td width='25%'>";
					
			$retentionScheduleID = $results->retentionScheduleID;
			$recordDescription = $results->recordDescription;
					
			$descResults = $this->getDescriptionLength($retentionScheduleID, $recordDescription);
			$retentionScheduleResults .= trim($descResults);
										
			$retentionScheduleResults .= "</td>";
									
			$retentionScheduleResults .= "<td width='20%'>";
			$retentionScheduleResults .= trim(strip_tags($results->officeOfPrimaryResponsibility)); 
			$retentionScheduleResults .= "</td>";

			$retentionScheduleResults .= "<td width='10%'>";
			$retentionScheduleResults .= trim(strip_tags($results->retentionPeriod));
			$retentionScheduleResults .= "</td>";
						
			$retentionScheduleResults .= "<td width='10%'>";
			$retentionScheduleResults .= trim(strip_tags($results->disposition));
			$retentionScheduleResults .= "</td>";
			
			$retentionScheduleResults .= "<td width='20%'>";
			$retentionScheduleResults .= trim(strip_tags($results->recordCategory));
			$retentionScheduleResults .= "</td>";
			$retentionScheduleResults .= "</tr>";	
		}
		 	
		$retentionScheduleResults .= "</table>";
		$retentionScheduleResults .= "<br /><br />";
		
		return $retentionScheduleResults;
	}
			
     /**
	 * deterines the lenght of the text in the description field and truncates it if it is more than 150 characters
	 * adds more url to full description
	 * 
	 * @param $retentionScheduleID
	 * @param $recordDescription
	 * @return $results
	 */
	private function getDescriptionLength($retentionScheduleID, $recordDescription) {
		
		$results = "";
		$length = strlen(trim($recordDescription));
		if ($length > 150) {
			$truncate = $length - 150; 
			if ($truncate <= 10) {
				$truncatedRecordDescription = substr($recordDescription, 0, -$truncate);
			} else {
				$truncatedRecordDescription = substr($recordDescription, 0, -$truncate);
			}
				if ($retentionScheduleID !== "") {
					$results .= $truncatedRecordDescription . "&nbsp;<a href='/liaison/index.php/du/getRetentionSchedule/$retentionScheduleID?height=420&width=450' class='thickbox' title='University Records Management Program'>more...</a>";	
				} else {
					$results .= $truncatedRecordDescription . "&nbsp;...";		
				}
			} else {
				$results .= $recordDescription;
		}
		return $results;
	}
} 
?>