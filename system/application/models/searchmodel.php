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
	
	public function getRecordTypes($departmentID) {
		$recordTypeResults = $this->getRecordTypesQuery($departmentID);
		return $recordTypeResults;
	}
	
	private function getRecordTypesQuery($departmentID) {
		$recordTypeDepartment = $departmentID;
		$this->db->select('recordInformationID, recordTypeDepartment, recordName, recordDescription');
	 	$this->db->from('rm_recordTypeRecordInformation');
	 	$this->db->where('recordTypeDepartment', $recordTypeDepartment);
 		$recordTypes = $this->db->get();
		
		$attributes = array(
			'width' => '700',
			'height' => '700',
			'scrollbars' => 'yes',
             'status'     => 'yes',
             'resizable'  => 'yes',
             'screenx'    => '0',
             'screeny'    => '0'
		);
		
		$recordTypeResults = "";
		if ($recordTypes->num_rows() > 0) {
			foreach ($recordTypes->result() as $results) {
			 	$departmentID = $results->recordTypeDepartment;
			 	$recordTypeResults .= anchor_popup('dashboard/editRecordTypeForm/' . $departmentID, $results->recordName, $attributes);
			 	$recordTypeResults .= "<br />";
			 	$recordTypeResults .= $results->recordDescription . "<br />";
			 }
			 return $recordTypeResults;
		} else {
			$noResults = "No results found";
			return $noResults;
		}
	}
}
 
?>
