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


class RecordTypeModel extends CI_Model  {
	
	public function __construct() {
		parent::__construct();
		
		$this->devEmail = $this->config->item('devEmail');
	}
	
	/**
	 * invokes getRecordTypeQuery();
	 * 
	 * @param $recordInformationID
	 * @return $recordTypeResults
	 */
	public function getRecordType($recordInformationID) {
		$recordTypeResults = $this->getRecordTypeQuery($recordInformationID);
		return $recordTypeResults;
	}
	
	/**
    * gets existing record type records from database based on departmentID
    *
    * @access private
    * @param $departmentID
    * @return $recordTypeResults
    */ 
	private function getRecordTypeQuery($recordInformationID) {
		
		$this->db->select('*');
	 	$this->db->from('rm_recordType');
	 	$this->db->where('recordInformationID', $recordInformationID);
 		$recordInformation = $this->db->get();
		
		$recordTypeResults = array();
				
		if ($recordInformation->num_rows > 0) {
			foreach ($recordInformation->result() as $recordInformationResults) {
				$recordRegulationChoices = explode(",", $recordInformationResults->recordRegulations);
				
				$recordTypeResults['recordInformationID'] = $recordInformationResults->recordInformationID;
				$recordTypeResults['recordTypeDepartment'] = $recordInformationResults->recordTypeDepartment;
				$recordTypeResults['recordName'] = $recordInformationResults->recordName;
				$recordTypeResults['recordDescription'] = $recordInformationResults->recordDescription;
				$recordTypeResults['recordCategory'] = $recordInformationResults->recordCategory;
				$recordTypeResults['managementDivisionID'] = $recordInformationResults->managementDivisionID;
				$recordTypeResults['managementDepartmentID'] = $recordInformationResults->managementDepartmentID;
				$recordTypeResults['recordNotesDeptAnswer'] = $recordInformationResults->recordNotesDeptAnswer;
				$recordTypeResults['recordNotesRmNotes'] = $recordInformationResults->recordNotesRmNotes;
				$recordTypeResults['recordFormat'] = $recordInformationResults->recordFormat;
					if ($recordTypeResults['recordFormat'] == "Other Physical") {
						$recordTypeResults['otherPhysicalText'] = $recordInformationResults->otherPhysicalText;
					} else {
						$recordTypeResults['otherPhysicalText'] = "";
					}
					if ($recordTypeResults['recordFormat'] == "Other Electronic") {
						$recordTypeResults['otherElectronicText'] = $recordInformationResults->otherElectronicText;
					} else {
						$recordTypeResults['otherElectronicText'] = "";
					}
				
				$recordTypeResults['recordStorage'] = $recordInformationResults->recordStorage;
					if ($recordTypeResults['recordStorage'] == "Physical storage in other DU building") {
						$recordTypeResults['otherDUBuildingText'] = $recordInformationResults->otherDUBuildingText;
					} else {
						$recordTypeResults['otherDUBuildingText'] = "";
					}
					if ($recordTypeResults['recordStorage'] == "Physical offsite storage") {
						$recordTypeResults['otherOffsiteStorageText'] = $recordInformationResults->otherOffsiteStorageText;
					} else {
						$recordTypeResults['otherOffsiteStorageText'] = "";
					}
					if ($recordTypeResults['recordStorage'] == "Other electronic system") {
						$recordTypeResults['otherElectronicSystemText'] = $recordInformationResults->otherElectronicSystemText;
					} else {
						$recordTypeResults['otherElectronicSystemText'] = "";
					}
					
				$recordTypeResults['formatAndLocationDeptAnswer'] = $recordInformationResults->formatAndLocationDeptAnswer;
				$recordTypeResults['formatAndLocationRmNotes'] = $recordInformationResults->formatAndLocationRmNotes;
				$recordTypeResults['recordRetentionAnswer'] = humanize($recordInformationResults->recordRetentionAnswer);
				$recordTypeResults['usageNotesAnswer'] = $recordInformationResults->usageNotesAnswer;
				$recordTypeResults['retentionAuthoritiesAnswer'] = $recordInformationResults->retentionAuthoritiesAnswer;
				$recordTypeResults['vitalRecord'] = $recordInformationResults->vitalRecord;
				$recordTypeResults['vitalRecordNotesAnswer'] = $recordInformationResults->vitalRecordNotesAnswer;
				$recordTypeResults['recordRegulations'] = $recordRegulationChoices;
				$recordTypeResults['personallyIdentifiableInformationAnswer'] = $recordInformationResults->personallyIdentifiableInformationAnswer;
				$recordTypeResults['personallyIdentifiableInformationRmNotes'] = $recordInformationResults->personallyIdentifiableInformationRmNotes;
				$recordTypeResults['otherDepartmentCopiesAnswer'] = $recordInformationResults->otherDepartmentCopiesAnswer;	
				$recordTypeResults['updateTimestamp'] = $recordInformationResults->updateTimestamp;		
				$recordTypeResults['timestamp'] = $recordInformationResults->timestamp;																
			}	
		}
		
		return $recordTypeResults;
	}
	
	/**
    * invokes updateRecordTypeQuery()
    *
    * @access public
    * @param $_POST
    * @return void
    */ 
	public function updateRecordType($_POST) {
		$this->updateRecordTypeQuery($_POST);
	}
	
    /**
	* updates record Type "record Information"
    *
    * @access private
    * @param $_POST
    * @return void
    */ 
	private function updateRecordTypeQuery($_POST) {
		
		$recordInformationID = $_POST['recordInformationID'];
		$datestring = "%Y-%m-%d %h:%i %A";
		$time = time();

		$now = mdate($datestring, $time);
		
		$recordRegulations = implode(",", $_POST['recordRegulations']);
		$recordInformation = array();
		
		$recordInformation['recordName'] = $_POST['recordName'];
		$recordInformation['recordDescription']	= $_POST['recordDescription'];
		$recordInformation['recordCategory'] = $_POST['recordCategory'];
		$recordInformation['managementDivisionID'] = $_POST['managementDivisionID'];
		$recordInformation['managementDepartmentID'] = $_POST['managementDepartmentID'];
		$recordInformation['recordNotesDeptAnswer'] = $_POST['recordNotesDeptAnswer'];	
		$recordInformation['recordNotesRmNotes'] = $_POST['recordNotesRmNotes'];
		$recordInformation['recordFormat'] = $_POST['recordFormat'];
		$recordInformation['otherPhysicalText'] = $_POST['otherPhysicalText'];
		$recordInformation['otherElectronicText'] = $_POST['otherElectronicText'];
		$recordInformation['recordStorage'] = $_POST['recordStorage'];
		$recordInformation['otherDUBuildingText'] = $_POST['otherDUBuildingText'];
		$recordInformation['otherOffsiteStorageText'] = $_POST['otherOffsiteStorageText'];
		$recordInformation['otherElectronicSystemText'] = $_POST['otherElectronicSystemText'];
		$recordInformation['formatAndLocationDeptAnswer'] = $_POST['formatAndLocationDeptAnswer'];
		$recordInformation['formatAndLocationRmNotes'] = $_POST['formatAndLocationRmNotes'];
		$recordInformation['recordRetentionAnswer'] = underscore($_POST['recordRetentionAnswer']);
		$recordInformation['usageNotesAnswer'] = $_POST['usageNotesAnswer'];
		$recordInformation['retentionAuthoritiesAnswer'] = $_POST['retentionAuthoritiesAnswer'];
		$recordInformation['vitalRecord'] = $_POST['vitalRecord'];
		$recordInformation['vitalRecordNotesAnswer'] = $_POST['vitalRecordNotesAnswer'];
		$recordInformation['recordRegulations'] = $recordRegulations;
		$recordInformation['personallyIdentifiableInformationAnswer'] = $_POST['personallyIdentifiableInformationAnswer'];
		$recordInformation['personallyIdentifiableInformationRmNotes'] = $_POST['personallyIdentifiableInformationRmNotes'];
		$recordInformation['otherDepartmentCopiesAnswer'] = $_POST['otherDepartmentCopiesAnswer'];
		$recordInformation['updateTimestamp'] = $now;
		
		$this->db->where('recordInformationID', $recordInformationID);
		$this->db->update('rm_recordType', $recordInformation);
	}
	
	/**
    * invokes saveRecordTypeRecordInformationQuery()
    *
    * @access public
    * @param $recordInformation
    * @return $recordInformationID
    */
	public function saveRecordType($recordInformation) {
		$recordInformationID = $this->saveRecordTypeQuery($recordInformation);
		return $recordInformationID;
	}	
	
	/**
    * saves record type information to database
    *
    * @access private
    * @param $recordInformation
    * @return $recordInformationID
    */
	private function saveRecordTypeQuery($recordInformation) {
		$this->db->insert('rm_recordType', $recordInformation);
		$this->db->select_max('recordInformationID');
		$recordInformationIDquery = $this->db->get('rm_recordType');						
		if ($recordInformationIDquery->num_rows() > 0) {
	   		$result = $recordInformationIDquery->row();
			$recordInformationID = $result->recordInformationID; 
			return $recordInformationID; 	
		}				
	}
	
	/**
	 * invokes deleteRecordTypeQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteRecordType($recordInformationID)
	{
		$recordInformationID = $this->deleteRecordTypeQuery($recordInformationID);
	}
	
	/**
	 * deletes Record Type and moves it to rm_recordTypeDeleted
	 *
	 * @access private
	 * @return void
	 */
	private function deleteRecordTypeQuery($recordInformationID)
	{
		$this->db->trans_start();
		$this->db->query("INSERT INTO rm_recordTypeDeleted SELECT * FROM rm_recordType WHERE recordInformationID = $recordInformationID");
		$this->db->where('recordInformationID',$recordInformationID);
		$this->db->delete('rm_recordType');
		$this->db->trans_complete();
	}
	
	/**
	 * invokes restoreRecordTypeQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function restoreRecordType($recordInformationID)
	{
		$recordInformationID = $this->restoreRecordTypeQuery($recordInformationID);
	}
	
	/**
	 * restores Record Type and moves it to rm_recordType
	 *
	 * @access private
	 * @return void
	 */
	private function restoreRecordTypeQuery($recordInformationID)
	{
		$this->db->trans_start();
		$this->db->query("INSERT INTO rm_recordType SELECT * FROM rm_recordTypeDeleted WHERE recordInformationID = $recordInformationID");
		$this->db->where('recordInformationID',$recordInformationID);
		$this->db->delete('rm_recordTypeDeleted');
		$this->db->trans_complete();
	}
	
	/**
	 * invokes permanentDeleteRecordTypeQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function permanentDeleteRecordType($recordInformationID)
	{
		$recordInformationID = $this->permanentDeleteRecordTypeQuery($recordInformationID);
	}
	
	/**
	 * deletes Record Type
	 *
	 * @access private
	 * @return void
	 */
	private function permanentDeleteRecordTypeQuery($recordInformationID)
	{
		$this->db->trans_start();
		$this->db->where('recordInformationID',$recordInformationID);
		$this->db->delete('rm_recordTypeDeleted');
		$this->db->trans_complete();
	}
}
?>