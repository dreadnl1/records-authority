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
	
	public function getRecordTypes($departmentID, $divisionID) {
		$recordTypeResults = $this->getRecordTypesQuery($departmentID, $divisionID);
		return $recordTypeResults;
	}
	
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
			$recordTypeResults .= "<h2>$divisionName</h2>";
			$recordTypeResults .= "<h2>$departmentName</h2>";
			$recordTypeResults .= "<script src='$jsPath' type='text/javascript'></script>";
			$recordTypeResults .= "<table id='searchResultsTable'>";
			$recordTypeResults .= "<tr>"; 
   			$recordTypeResults .= "<th><strong>Record Name</strong></th>";
   			$recordTypeResults .= "<th><strong>Record Category</strong></th>";
   			$recordTypeResults .= "<th><strong>Record Description</strong></th>";
   			$recordTypeResults .= "</tr>";
			
			foreach ($recordTypes->result() as $results) {
			 		
			 	$recordTypeResults .= "<tr>";
				$recordTypeResults .= "<td>";
			 	$recordTypeResults .= anchor_popup('dashboard/editRecordTypeForm/' . $results->recordInformationID, $results->recordName, $attributes);
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= $results->recordCategory;
			 	$recordTypeResults .= "</td>";
			 	$recordTypeResults .= "<td>";
			 	$recordTypeResults .= $results->recordDescription;
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
	
	// TODO: gloabal search
	public function getGlobalRecordTypes($keyword) {
		$globalRecordTypeResults = $this->getGlobalRecordTypesQuery($keyword);
		return $globalRecordTypeResults;
	}
	
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
		
		// load model
		$CI =& get_instance();
		$CI->load->model('LookUpTablesModel', 'getDivAndDept', true);
				
		$this->db->select('recordInformationID, recordTypeDepartment, recordName, recordCategory, recordDescription');
	 	$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->like('recordName', $keyword);
	 	$this->db->or_like('recordCategory', $keyword);
	 	$this->db->or_like('recordDescription', $keyword);
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
			$globalRecordTypeResults .= $divDeptArray['divisionName'];
			$globalRecordTypeResults .= "</td>";
			$globalRecordTypeResults .= "<td>";
			$globalRecordTypeResults .= $divDeptArray['departmentName'];
			$globalRecordTypeResults .= "</td>";
			$globalRecordTypeResults .= "<td>";
		 	$globalRecordTypeResults .= anchor_popup('dashboard/editRecordTypeForm/' . $results->recordInformationID, $results->recordName, $attributes);
		 	$globalRecordTypeResults .= "</td>";
		 	$globalRecordTypeResults .= "<td>";
		 	$globalRecordTypeResults .= $results->recordCategory;
		 	$globalRecordTypeResults .= "</td>";
		 	$globalRecordTypeResults .= "<td>";
		 	$globalRecordTypeResults .= $results->recordDescription;
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
}
 
?>
