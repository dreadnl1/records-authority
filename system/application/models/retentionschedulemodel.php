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


class RetentionScheduleModel extends Model {

	public function __construct() {
 		parent::Model();
 				
 		$this->devEmail = $this->config->item('devEmail');
 	}
 	
 	/** 
	 * invokes getRecordInformationQuery();
	 * 
	 * @param $recordInformationID 
	 * @return $recordInformation
	 */
 	public function getRecordInformation($recordInformationID) {
 		$recordInformation = $this->getRecordInformationQuery($recordInformationID);
 		return $recordInformation;
 	}
 	
 	/** 
	 * gets record information
	 * 
	 * @param $recordInformationID 
	 * @return $recordInformation
	 */
 	private function getRecordInformationQuery($recordInformationID) {
 		$this->db->select('recordInformationID, recordName, recordDescription, recordCategory, managementDivisionID, managementDepartmentID, vitalRecord');
	 	$this->db->from('rm_recordType');
	 	$this->db->where('recordInformationID', $recordInformationID);
 		$recordInformationQuery = $this->db->get();
 		$recordInformation = array();
 		if ($recordInformationQuery->num_rows > 0) {
			foreach ($recordInformationQuery->result() as $recordInformationResults) {
				$recordInformation['recordInformationID'] = $recordInformationResults->recordInformationID;
				$recordInformation['recordName'] = $recordInformationResults->recordName;
				$recordInformation['recordDescription'] = $recordInformationResults->recordDescription;
				$recordInformation['recordCategory'] = $recordInformationResults->recordCategory;
				$recordInformation['managementDivisionID'] = $recordInformationResults->managementDivisionID;
				$recordInformation['managementDepartmentID'] = $recordInformationResults->managementDepartmentID;
				$recordInformation['vitalRecord'] = $recordInformationResults->vitalRecord;
			}
			return $recordInformation;	
		} else {
			send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: unable to pull record information into retention schedule form - getRecordInformationQuery()');
			$recordInformation = "Unable to render retention schedule form.";
			return $recordInformation;
		}
 	}
 	
 	/** 
	 * invokes getDispositionsQuery()
	 *
	 * @return $dispositions
	 */
 	public function getDispositions() {
 		$dispositions = $this->getDispostionsQuery();
 		return $dispositions;
 	}
 	
 	/** 
	 * gets dispositions
	 *
	 * @return $dispositions
	 */
 	private function getDispostionsQuery() {
 		$this->db->select('dispositionID, dispositionLong');
 		$this->db->from('rm_disposition');
 		$dispositionQuery = $this->db->get();
 		$dispositions = array();
 		$dispositionsLong = array();
 		$dispositionsDescription = array();
 		 		
 		if ($dispositionQuery->num_rows > 0) {
 			foreach ($dispositionQuery->result() as $disposition) {
 				$dispositions[$disposition->dispositionID] = $disposition->dispositionLong;
 			}
 			return $dispositions;
 		} else {
 			send_email($this->devEmail, 'RecordsAuthority_Error', 'database error: unable to pull dispositions from database - getDispositionsQuery()');
 		}
 	}
 	
 	/** not used **
	 * invokes getDispositionDetailsQuery();
	 *
	 * @param $dispositionID
	 * @return $dispositionDetails
	 */
 	public function getDispositionDetails($dispositionID) {
 		$dispositionDetails = $this->getDispositionDetailsQuery($dispositionID);
 		return $dispositionDetails;
 	}
 	
 	/** not used **
	 * gets disposition details
	 *
	 * @param $dispositionID
	 * @return $dispositionDetails / $message
	 */
 	private function getDispositionDetailsQuery($dispositionID) {
 		$this->db->select('dispositionLong, description');
 		$this->db->from('rm_disposition');
 		$this->db->where('dispositionID', $dispositionID);
 		$dispositionDetailQuery = $this->db->get();
 		$dispositionDetail = array();
 		
 		if ($dispositionDetailQuery->num_rows > 0) {
 			foreach ($dispositionDetailQuery->result() as $dispositionDetail) {
 				$dispositionDetails[] = "<br />" . $dispositionDetail->dispositionLong . ":<br />";
 				$dispositionDetails[] = $dispositionDetail->description . "<br />";
 			}
 			return $dispositionDetails;
 		} else {
 			return $message = "<br />Please select a disposition";
 		}
 	}
 	
 	/**
 	 * generates unique identifier for retention schedule record
 	 *
 	 * @return $uuid
 	 */
	private function createUUID() {
		// create uuid
		$uuid = uniqid(); 
		return $uuid;
	}
 	
 	/**
	 * invokes saveRetentionScheduleQuery();
	 *
	 * @param $_POST
	 * @return void
	 */
	public function saveRetentionSchedule($_POST) {
		$retentionScheduleID = $this->saveRetentionScheduleQuery($_POST);		
		return $retentionScheduleID;
	}
	
	/**
	 * saves individual retention Schedule record
	 *
	 * @param $_POST
	 * @return void
	 */
	private function saveRetentionScheduleQuery($_POST) {
		$retentionSchedule = array();
		$retentionSchedule['recordCode'] = trim(strip_tags($this->input->post('recordCode')));
		$retentionSchedule['recordName'] = trim(strip_tags($this->input->post('recordName')));
		$retentionSchedule['recordDescription'] = trim(strip_tags($this->input->post('recordDescription')));
		$retentionSchedule['recordCategory'] = trim(strip_tags($this->input->post('recordCategory')));
		$retentionSchedule['retentionPeriod'] = trim(strip_tags($this->input->post('retentionPeriod')));
		$retentionSchedule['primaryAuthorityRetention'] = trim(strip_tags($this->input->post('primaryAuthorityRetention')));
		$retentionSchedule['retentionNotes'] = trim(strip_tags($this->input->post('retentionNotes')));
		$retentionSchedule['retentionDecisions'] = trim(strip_tags($this->input->post('retentionDecisions')));
		$retentionSchedule['disposition'] = trim(strip_tags($this->input->post('disposition')));
		$retentionSchedule['primaryAuthority'] = trim(strip_tags($this->input->post('primaryAuthority')));
		
		// get department
		$retentionSchedule['officeOfPrimaryResponsibility'] = trim(strip_tags($this->input->post('departmentID')));
		$retentionSchedule['relatedAuthorities'] = trim(strip_tags($this->input->post('relatedAuthorities')));
		$retentionSchedule['notes'] = trim(strip_tags($this->input->post('notes')));
		$retentionSchedule['vitalRecord'] = trim(strip_tags($this->input->post('vitalRecord')));
		$retentionSchedule['approvedByCounsel'] = trim(strip_tags($this->input->post('approvedByCounsel')));
		$retentionSchedule['approvedByCounselDate'] = trim(strip_tags($this->input->post('approvedByCounselDate')));
		
		// generate record uuid
		$uuid = $this->createUUID();
		$retentionSchedule['uuid'] = $uuid;
		
		$this->db->trans_start();
		// insert data
		$this->db->insert('rm_retentionSchedule', $retentionSchedule);
		// get max id
		$this->db->select_max('retentionScheduleID');
				
		$this->db->trans_complete();
				
		$idQuery = $this->db->get('rm_retentionSchedule');						
		
		if ($idQuery->num_rows() > 0) {
			$result = $idQuery->row();
			$retentionScheduleID = $result->retentionScheduleID;  		
			
			// indexes retention schedule
			$this->indexRetentionSchedules($retentionScheduleID);
			//Old commented section
			
			if (!empty($_POST['relatedAuthorities'])) {
				$i = 0; // set loop variable
				$relatedAuthority = array();
				$rsRelatedAuthorityID = array();
				$relatedAuthorities = $_POST['relatedAuthorities'];
				
				foreach ($relatedAuthorities as $rAuthority) {
					if (!empty($rAuthority)) {
						$relatedAuthority['retentionScheduleID'] = $retentionScheduleID;
						$relatedAuthority['rsRelatedAuthority'] = $rAuthority;
						
						$this->db->trans_start();
						// insert data
						$this->db->insert('rm_rsRelatedAuthorities', $relatedAuthority);
						// get max id
						$this->db->select_max('rsRelatedAuthorityID');
						
						$this->db->trans_complete();
						
						$idQuery = $this->db->get('rm_rsRelatedAuthorities');
						$result = $idQuery->row();
						// place result into an array
						$rsRelatedAuthorityID[] = $result->rsRelatedAuthorityID;
						// get array length
						$arrayLength = count($rsRelatedAuthorityID);
						while ($i < $arrayLength) {
							// shift the array value at location [0] into a variable
							$relatedAuthorityRetentionValue = array_shift($_POST['relatedAuthorityRetention']);
							$relatedAuthorityRetention['rsRelatedAuthorityRetention'] = $relatedAuthorityRetentionValue;
							// update record
							$this->db->where('rsRelatedAuthorityID', $rsRelatedAuthorityID[0]); // there is only one value in the array, so grab it
							$this->db->update('rm_rsRelatedAuthorities', $relatedAuthorityRetention); 
							// clear array and update variable														
							$rsRelatedAuthorityID = array();
							$relatedAuthorityRetentionValue = "";
							
						++$i;	
						}
						$i = 0; // reset loop variable
					}
				}
			} //old commented section
			
			// save associated units (departments)
			if ($this->session->userdata('uuid')) { // if uuid is in session, then save associated units
				$uuid = $this->session->userdata('uuid'); // grab uuid from session
						
				// query temp table
				$this->db->select('departmentID');
				$this->db->from('rm_associatedUnits_temp');
				$this->db->where('uuid', $uuid);
				$checkedAus = $this->db->get();	
				
				$checkedArray = array();
				if ($checkedAus->num_rows > 0) {
					foreach ($checkedAus->result() as $checkedAu) {
						$checkedArray[] = $checkedAu->departmentID;
					}
				}
				
				$associatedUnits = array();
				foreach ($checkedArray as $associatedUnit) {
					$associatedUnits['retentionScheduleID'] = $retentionScheduleID;
					$associatedUnits['departmentID'] = $associatedUnit;		
					$this->db->insert('rm_associatedUnits', $associatedUnits);
				}
				
				$this->db->where('uuid', $uuid);
				$this->db->delete('rm_associatedUnits_temp');
				// remove uuid from session
				$this->session->unset_userdata('uuid');
			}
			return $retentionScheduleID;
		}
		
	}

	/**
	 * invokes editRetentionScheduleQuery();
	 *
	 * @param $retentionScheduleID
	 * @return $retentionSchedule
	 */
	public function editRetentionSchedule($retentionScheduleID) {
		$retentionSchedule = $this->editRetentionScheduleQuery($retentionScheduleID);		
		return $retentionSchedule;
	}
	
	/**
	 * gets retention schedule record for edit
	 *
	 * @param $retentionScheduleID
	 * @return $retentionSchedule
	 */
	private function editRetentionScheduleQuery($retentionScheduleID) {
		$this->db->select('*');
	 	$this->db->from('rm_retentionSchedule');
	 	$this->db->where('retentionScheduleID', $retentionScheduleID);
 		$retentionScheduleQuery = $this->db->get();
		
 		$retentionSchedule = array();
		if ($retentionScheduleQuery->num_rows > 0) {
 			foreach ($retentionScheduleQuery->result() as $results) {
 				$retentionSchedule['retentionScheduleID'] = $retentionScheduleID;
 				$retentionSchedule['uuid'] = $results->uuid;
 				$retentionSchedule['recordCode'] = $results->recordCode; 
 				$retentionSchedule['recordName'] = $results->recordName;
 				$retentionSchedule['recordDescription'] = $results->recordDescription;
 				$retentionSchedule['recordCategory'] = $results->recordCategory;
 				$retentionSchedule['retentionPeriod'] = $results->retentionPeriod;
 				$retentionSchedule['primaryAuthorityRetention'] = $results->primaryAuthorityRetention;
 				$retentionSchedule['retentionNotes'] = $results->retentionNotes;
 				$retentionSchedule['retentionDecisions'] = $results->retentionDecisions;
 				
 				if($results->disposition == "D") {
					$retentionSchedule['disposition'] = "Destroy";
 				} elseif ($results->disposition == "R") {
 					$retentionSchedule['disposition'] = "Recycle";
 				} elseif ($results->disposition == "P") {
 					$retentionSchedule['disposition'] = "Permanent";
 				} elseif ($results->disposition == "N/A") {
 					$retentionSchedule = "Not Applicaple";
 				} else {
 					$retentionSchedule['disposition'] = $results->disposition;
 				}
 				
 				$retentionSchedule['primaryAuthority'] = $results->primaryAuthority;
 				$retentionSchedule['officeOfPrimaryResponsibility'] = $results->officeOfPrimaryResponsibility;
 				$retentionSchedule['relatedAuthorities'] = $results->relatedAuthorities;	
 					// get divisionID
 					$this->db->select('divisionID, departmentName');
 					$this->db->from('rm_departments');
 					$this->db->where('departmentID', $results->officeOfPrimaryResponsibility);
 					$divisionQuery = $this->db->get();
 					
 					foreach ($divisionQuery->result() as $officeOfPrimaryResponsibility) {
 						$retentionSchedule['divisionID'] = $officeOfPrimaryResponsibility->divisionID;
 						$retentionSchedule['officeOfPrimaryResponsibilityDept'] = trim(strip_tags($officeOfPrimaryResponsibility->departmentName));
 					}	
 				 				
 				$retentionSchedule['notes'] = trim(strip_tags($results->notes));
 				$retentionSchedule['vitalRecord'] = trim(strip_tags($results->vitalRecord));
 				$retentionSchedule['approvedByCounsel'] = trim(strip_tags($results->approvedByCounsel));
 				$retentionSchedule['approvedByCounselDate'] = trim(strip_tags($results->approvedByCounselDate));
 				$retentionSchedule['timestamp'] = trim(strip_tags($results->timestamp));
 				$retentionSchedule['updateTimestamp'] = trim(strip_tags($results->updateTimestamp));
 			}
 			//old commented section 			 			
 			// get related authorities / related authority retentions
 			$this->db->select('rsRelatedAuthorityID, rsRelatedAuthority, rsRelatedAuthorityRetention');
 			$this->db->from('rm_rsRelatedAuthorities');
 			$this->db->where('retentionScheduleID', $retentionScheduleID);
 			$relatedAuthorityQuery = $this->db->get();
 			
 			$relatedAuthority = array();
 			$relatedAuthorityRetention = array();
 			if ($relatedAuthorityQuery->num_rows > 0) {
 				foreach ($relatedAuthorityQuery->result() as $authority) {
 					$relatedAuthority[$authority->rsRelatedAuthorityID] = $authority->rsRelatedAuthority;
 					$relatedAuthorityRetention[$authority->rsRelatedAuthorityID] = $authority->rsRelatedAuthorityRetention;
 				}
 				$retentionSchedule['relatedAuthority'] = $relatedAuthority; 
 				$retentionSchedule['relatedAuthorityRetention'] = $relatedAuthorityRetention;
 		}//old commented section
 			
 			// get associated units
 			$this->db->select('departmentID');
 			$this->db->from('rm_associatedUnits');
 			$this->db->where('retentionScheduleID', $retentionScheduleID);
 			$associatedUnitQuery = $this->db->get();
 			$checkedDivisions = array();
 			if ($associatedUnitQuery->num_rows > 0) {
 				foreach ($associatedUnitQuery->result() as $department) {
 					$this->db->select('divisionID');
 					$this->db->from('rm_departments');
 					$this->db->where('departmentID', $department->departmentID);
 					$checkedDivisionsQuery = $this->db->get();
 					if ($checkedDivisionsQuery->num_rows > 0) {
 						foreach ($checkedDivisionsQuery->result() as $divisions) {
 							$checkedDivisions[] = $divisions->divisionID;		
 						}
 						$retentionSchedule['auDivisionIDs'] = $checkedDivisions;
 					}	
 				}
 			}
 			 			
 			// get divisionIDs
 			$this->db->select('divisionID');
 			$this->db->from('rm_departments');
 			$this->db->where('departmentID', $results->officeOfPrimaryResponsibility);
 			$divisionQuery = $this->db->get();
 			if($divisionQuery->num_rows() > 0)
 			{
 				$divisionID = $divisionQuery->row();
 			}
 			else
 			{
 				echo $divisionQuery;
 				echo "Division ID not found";
 			}
 			$retentionSchedule['divisionID'] = $divisionID->divisionID;
 			
 			return $retentionSchedule;
		}
	}
	
	/**
	 * invokes updateRetentionScheduleQuery();
	 *
	 * @param $_POST
	 * @return void
	 */
	public function updateRetentionSchedule($_POST) {
		$retentionScheduleID = $this->updateRetentionScheduleQuery($_POST);		
		return $retentionScheduleID;
	}
	
	/**
	 * updates individual retention Schedule record
	 *
	 * @param $_POST
	 * @return void
	 */
	private function updateRetentionScheduleQuery($_POST) {
		$datestring = "%Y/%m/%d %h:%i:%s %a";
		$time = time();
		$now = mdate($datestring, $time);
		
		$retentionScheduleID = $_POST['retentionScheduleID'];
		$retentionSchedule = array();
		$retentionSchedule['recordCode'] = trim(strip_tags($this->input->post('recordCode')));
		$retentionSchedule['recordName'] = trim(strip_tags($this->input->post('recordName')));
		$retentionSchedule['recordDescription'] = trim(strip_tags($this->input->post('recordDescription')));
		$retentionSchedule['recordCategory'] = trim(strip_tags($this->input->post('recordCategory')));
		$retentionSchedule['retentionPeriod'] = trim(strip_tags($this->input->post('retentionPeriod')));
		$retentionSchedule['retentionNotes'] = trim(strip_tags($this->input->post('retentionNotes')));
		$retentionSchedule['retentionDecisions'] = trim(strip_tags($this->input->post('retentionDecisions')));
		$retentionSchedule['disposition'] = trim(strip_tags($this->input->post('disposition')));
		$retentionSchedule['primaryAuthority'] = trim(strip_tags($this->input->post('primaryAuthority')));
		$retentionSchedule['primaryAuthorityRetention'] = trim(strip_tags($this->input->post('primaryAuthorityRetention')));
		$retentionSchedule['officeOfPrimaryResponsibility'] = trim(strip_tags($this->input->post('departmentID')));
		$retentionSchedule['relatedAuthorities'] = trim(strip_tags($this->input->post('relatedAuthorities')));
		$retentionSchedule['notes'] = trim(strip_tags($this->input->post('notes')));
		$retentionSchedule['vitalRecord'] = trim(strip_tags($this->input->post('vitalRecord')));
		$retentionSchedule['approvedByCounsel'] = trim(strip_tags($this->input->post('approvedByCounsel')));
		$retentionSchedule['approvedByCounselDate'] = trim(strip_tags($this->input->post('approvedByCounselDate')));
		$retentionSchedule['updateTimestamp'] = $now;
		// update data
		$this->db->where('retentionScheduleID', $retentionScheduleID);
		$this->db->update('rm_retentionSchedule', $retentionSchedule);
		
		// update related authorities / related authority retentions
		if (isset($_POST['relatedAuthorityID'])) {
			$rsRelatedAuthority = array();
			$arrayLength = count($_POST['relatedAuthorityID']);
			$i = 0;
			while ($i < $arrayLength) {
				$rsRelatedAuthority['rsRelatedAuthority'] = $_POST['relatedAuthorities'][$i];
				$rsRelatedAuthorityID = $_POST['relatedAuthorityID'][$i];
				$this->db->where('rsRelatedAuthorityID', $rsRelatedAuthorityID);
				$this->db->update('rm_rsRelatedAuthorities', $rsRelatedAuthority);
			++$i;	
			}	
		}
		
		if (isset($_POST['relatedAuthorityRetentionID'])) {
			$rsRelatedAuthority = array();
			$arrayLength = count($_POST['relatedAuthorityRetentionID']);
			$j = 0;
			while ($j < $arrayLength) {
				$rsRelatedAuthorityRetention['rsRelatedAuthorityRetention'] = $_POST['relatedAuthorityRetentions'][$j];
				$rsRelatedAuthorityID = $_POST['relatedAuthorityRetentionID'][$j];
				$this->db->where('rsRelatedAuthorityID', $rsRelatedAuthorityID);
				$this->db->update('rm_rsRelatedAuthorities', $rsRelatedAuthorityRetention);
			++$j;	
			}
		}
		
		// add new authorities
		if (isset($_POST['newRelatedAuthorities'])) {
				$i = 0; // set loop variable
				$relatedAuthority = array();
				$rsRelatedAuthorityID = array();
				$relatedAuthorities = $_POST['newRelatedAuthorities'];
												
				foreach ($relatedAuthorities as $rAuthority) {
					if (!empty($rAuthority)) {
						$relatedAuthority['retentionScheduleID'] = $retentionScheduleID;
						$relatedAuthority['rsRelatedAuthority'] = $rAuthority;
						
						$this->db->trans_start();
						// insert data
						$this->db->insert('rm_rsRelatedAuthorities', $relatedAuthority);
						// get max id
						$this->db->select_max('rsRelatedAuthorityID');
						$this->db->trans_complete();
						
						$idQuery = $this->db->get('rm_rsRelatedAuthorities');
						$result = $idQuery->row();
						// place result into an array
						$rsRelatedAuthorityID[] = $result->rsRelatedAuthorityID;
						// get array length
						$arrayLength = count($rsRelatedAuthorityID);
						while ($i < $arrayLength) {
							// shift the array value at location [0] into a variable
							$relatedAuthorityRetentionValue = array_shift($_POST['newRelatedAuthorityRetentions']);
							$relatedAuthorityRetention['rsRelatedAuthorityRetention'] = $relatedAuthorityRetentionValue;
							// update record
							$this->db->where('rsRelatedAuthorityID', $rsRelatedAuthorityID[0]); // there is only one value in the array, so grab it
							$this->db->update('rm_rsRelatedAuthorities', $relatedAuthorityRetention); 
							// clear array and update variable														
							$rsRelatedAuthorityID = array();
							$relatedAuthorityRetentionValue = "";
							
						++$i;	
						}
						$i = 0; // reset loop variable
					}
				}
		}

		// updates retention schedule index
		$this->updateIndexRetentionSchedules($retentionScheduleID); 	
	}
	
	/**
	 * invokes getRetentionScheduleRecordQuery();
	 *
	 * @param $retentionScheduleID
	 * @return void
	 */
	public function getRetentionScheduleRecord($retentionScheduleID) {
		$retentionSchedule = $this->getRetentionScheduleRecordQuery($retentionScheduleID);
		return $retentionSchedule;
	}
	
	/**
	 * gets individual retention Schedule record
	 *
	 * @param $retentionScheduleID
	 * @return void
	 */
	private function getRetentionScheduleRecordQuery($retentionScheduleID) {
		$sql = "SELECT retentionScheduleID, recordName, recordCategory, recordDescription, retentionPeriod, retentionNotes, disposition, officeOfPrimaryResponsibility, approvedByCounselDate FROM rm_fullTextSearch " .
						"WHERE retentionScheduleID = ? ";
		$query = $this->db->query($sql, array($retentionScheduleID));	
		
		$retentionSchedule = "";
		foreach ($query->result() as $results) {
		 	$retentionSchedule .= "<br />";
			$retentionSchedule .= "<strong>Record ID:</strong><br />";
			$retentionSchedule .= trim(strip_tags($results->retentionScheduleID));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Record Name:</strong><br />";
			$retentionSchedule .= trim(strip_tags($results->recordName));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Record Category:</strong><br />";
			$retentionSchedule .= trim(strip_tags($results->recordCategory));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Record Description:</strong><br />";
			$retentionSchedule .= trim(strip_tags($results->recordDescription));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Retention Period:</strong><br />";
			$retentionSchedule .= trim(strip_tags($results->retentionPeriod));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Retention Notes:</strong><br />";	
			$retentionSchedule .= trim(strip_tags($results->retentionNotes));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Disposition:</strong><br />";	
			$retentionSchedule .= trim(strip_tags($results->disposition));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Office of Primary Responsibility:</strong><br />";	
			$retentionSchedule .= trim(strip_tags($results->officeOfPrimaryResponsibility));
			$retentionSchedule .= "<br /><br />";
			$retentionSchedule .= "<strong>Public Retention Schedule - Approved Date: </strong><br />";	
			$retentionSchedule .= trim(strip_tags($results->approvedByCounselDate));
			$retentionSchedule .= "<br /><br />";
		 }
		return $retentionSchedule;
	}
	
	/**
	 * invokes updateOfficeOfPrimaryResponsibilityQuery()
	 *
	 * @param $_POST
	 * @return void
	 */
	public function updateOfficeOfPrimaryResponsibility($_POST) {
		$this->updateOfficeOfPrimaryResponsibilityQuery($_POST);
	}
	
	/**
	 * updates office of primary responsibility
	 *
	 * @param $_POST
	 * @return void
	 */
	private function updateOfficeOfPrimaryResponsibilityQuery($_POST) {
		$departmentID = $this->input->post('departmentID');
		$retentionScheduleID = $this->input->post('retentionScheduleID');
		
		$oprAu = array();
		$oprRs = array();
		// used to update associated unit table
		$oprAu['departmentID'] = $departmentID;
		// used to update retentionSchedule table
		$oprRs['officeOfPrimaryResponsibility'] = $departmentID;
		
		// get existing office of primary responsibility value
		$this->db->select('officeOfPrimaryResponsibility');
 		$this->db->from('rm_retentionSchedule');
 		$this->db->where('retentionScheduleID', $retentionScheduleID);
 		$oprQuery = $this->db->get();
 		$oprResult = $oprQuery->row();
 		$officeOfPrimaryResponsibility = $oprResult->officeOfPrimaryResponsibility;
		
 		// update office of primary responsibility in retention schedule table
		$this->db->where('retentionScheduleID', $retentionScheduleID); 
		$this->db->update('rm_retentionSchedule', $oprRs); 
 		
		// update department ID (office of primary responsibility) in associated unit table
		$this->db->where('retentionScheduleID', $retentionScheduleID); 
		$this->db->where('departmentID', $officeOfPrimaryResponsibility); 
		$this->db->update('rm_associatedUnits', $oprAu); 
	}
	
	/**
	 * indexes all retention schedules
	 *
	 * @return void
	 */
	public function indexRs() {
		$this->indexRetentionSchedules($retentionScheduleID="");
	}
	
	/**
	 * indexes retention schedules
	 *
	 * @param $retentionScheduleID
	 * @return void
	 */
	private function indexRetentionSchedules($retentionScheduleID) {
		
		if ($retentionScheduleID == "") {
			$this->db->truncate('rm_fullTextSearch');
		}
		
		$this->db->select('retentionScheduleID, recordName, recordDescription, recordCategory, retentionPeriod, retentionNotes, disposition, officeOfPrimaryResponsibility, approvedByCounselDate');
		$this->db->from('rm_retentionSchedule');
		$this->db->where('approvedByCounsel', 'yes');
		if ($retentionScheduleID !== "") {
			$this->db->where('retentionScheduleID', $retentionScheduleID);
		}
		$retentionScheduleQuery = $this->db->get();
			
		foreach ($retentionScheduleQuery->result() as $rsResults) {
			$retentionScheduleArray['retentionScheduleID'] = $rsResults->retentionScheduleID;
			$retentionScheduleArray['recordName'] = $rsResults->recordName;
			$retentionScheduleArray['recordDescription'] = $rsResults->recordDescription;
			$retentionScheduleArray['recordCategory'] = $rsResults->recordCategory;
			$retentionScheduleArray['retentionPeriod'] = $rsResults->retentionPeriod;
			$retentionScheduleArray['retentionNotes'] = $rsResults->retentionNotes;
			$retentionScheduleArray['approvedByCounselDate'] = $rsResults->approvedByCounselDate;
			
			// get full dispsition name for search results display
			$this->db->select('dispositionLong');
			$this->db->from('rm_disposition');
			//$this->db->like('dispositionShort', $rsResults->disposition);
			$dispositionQuery = $this->db->get();
			$dispositionResult = $dispositionQuery->row();
			$disposition = $dispositionResult->dispositionLong;			
			
			$retentionScheduleArray['disposition'] = $disposition;
						
			$this->db->select('departmentName, divisionID');
			$this->db->from('rm_departments');
			$this->db->where('departmentID', $rsResults->officeOfPrimaryResponsibility);
			$departmentQuery = $this->db->get();
			
			foreach ($departmentQuery->result() as $results) {
				$dept = $results->departmentName;
				$this->db->select('divisionName');
				$this->db->from('rm_divisions');
				$this->db->where('divisionID', $results->divisionID);
				$divisionQuery = $this->db->get();
				
				if ($divisionQuery->num_rows() > 0) {
					$row = $divisionQuery->row();
					$div = $row->divisionName;
					$divDept = $div . " - " . $dept; 
					
					$retentionScheduleArray['officeOfPrimaryResponsibility'] = $divDept;
				}
			}
						
			$this->db->insert('rm_fullTextSearch', $retentionScheduleArray);
		}
		
		if ($retentionScheduleID == "") {
			echo "<strong>Indexing Complete.</strong>";
		}
	}
	
	/**
	 * updates retention schedule index record
	 *
	 * @param $retentionScheduleID
	 * @return void
	 */
	private function updateIndexRetentionSchedules($retentionScheduleID) {
						
		$this->db->select('retentionScheduleID, recordName, recordDescription, recordCategory, retentionPeriod, retentionNotes, disposition, officeOfPrimaryResponsibility, approvedByCounsel');
		$this->db->from('rm_retentionSchedule');
		$this->db->where('retentionScheduleID', $retentionScheduleID);
		//$this->db->where('approvedByCounsel', 'yes');
		$retentionScheduleQuery = $this->db->get();
		
		foreach ($retentionScheduleQuery->result() as $rsResults) {
			$retentionScheduleArray['retentionScheduleID'] = $rsResults->retentionScheduleID;
			$retentionScheduleArray['recordName'] = $rsResults->recordName;
			$retentionScheduleArray['recordDescription'] = $rsResults->recordDescription;
			$retentionScheduleArray['recordCategory'] = $rsResults->recordCategory;
			$retentionScheduleArray['retentionPeriod'] = $rsResults->retentionPeriod;
			$retentionScheduleArray['retentionNotes'] = $rsResults->retentionNotes;
			
			// get full dispsition name for search results display
			$this->db->select('dispositionLong');
			$this->db->from('rm_disposition');
			//$this->db->like('dispositionShort', $rsResults->disposition);
			$dispositionQuery = $this->db->get();

			$dispositionResult = $dispositionQuery->row();
			$disposition = $dispositionResult->dispositionLong;			
			$retentionScheduleArray['disposition'] = $disposition;

			
			$this->db->select('departmentName, divisionID');
			$this->db->from('rm_departments');
			$this->db->where('departmentID', $rsResults->officeOfPrimaryResponsibility);
			$departmentQuery = $this->db->get();
			
			foreach ($departmentQuery->result() as $results) {
				$dept = $results->departmentName;
				$this->db->select('divisionName');
				$this->db->from('rm_divisions');
				$this->db->where('divisionID', $results->divisionID);
				$divisionQuery = $this->db->get();
				$row = $divisionQuery->row();
				$div = $row->divisionName;
				$divDept = $div . " - " . $dept; 
				$retentionScheduleArray['officeOfPrimaryResponsibility'] = $divDept;
			}
			
			// check if record exists
			$this->db->where('retentionScheduleID', $retentionScheduleID); 
			$count = $this->db->count_all_results('rm_fullTextSearch');
			
			if ($rsResults->approvedByCounsel == "yes" && $count == 0) {
				$this->db->insert('rm_fullTextSearch', $retentionScheduleArray);
			} else if ($rsResults->approvedByCounsel == "no" && $count > 0) {
				$this->db->where('retentionScheduleID', $retentionScheduleID); 
				$this->db->delete('rm_fullTextSearch');
			} else if ($rsResults->approvedByCounsel == "no" && $count == 0) { 
				// nothing
			} else if ($rsResults->approvedByCounsel == "yes" && $count > 0) {
				$this->db->where('retentionScheduleID', $retentionScheduleID); 
				$this->db->update('rm_fullTextSearch', $retentionScheduleArray); 
			}
		}	
		//empties temp associated units
		$this->db->query('TRUNCATE TABLE rm_associatedUnits_temp; ');
	}
	
	/**
	 * invokes deleteRetentionScheduleQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteRetentionSchedule($retentionScheduleID)
	{
		$retentionScheduleID = $this->deleteRetentionScheduleQuery($retentionScheduleID);
	}
	
	/**
	 * deletes Retention Schedule and moves it to rm_retentionScheduleDeleted
	 * 
	 * @access private
	 * @return void
	 */
	private function deleteRetentionScheduleQuery($retentionScheduleID)
	{
		$this->db->trans_start();
		$this->db->query("INSERT INTO rm_retentionScheduleDeleted SELECT * FROM rm_retentionSchedule WHERE retentionScheduleID = $retentionScheduleID");
		$this->db->where('retentionScheduleID',$retentionScheduleID);
		$this->db->delete('rm_retentionSchedule');
		$this->db->trans_complete();
	}
	
	/**
	 * invokes restoreRetentionScheduleQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function restoreRetentionSchedule($retentionScheduleID)
	{
		$retentionScheduleID = $this->restoreRetentionScheduleQuery($retentionScheduleID);
	}
	
	/**
	 * restores retention schedule and moves it to rm_retentionSchedule
	 *
	 * @access private
	 * @return void
	 */
	private function restoreRetentionScheduleQuery($retentionScheduleID)
	{
		$this->db->trans_start();
		$this->db->query("INSERT INTO rm_retentionSchedule SELECT * FROM rm_retentionScheduleDeleted WHERE retentionScheduleID = $retentionScheduleID");
		$this->db->where('retentionScheduleID',$retentionScheduleID);
		$this->db->delete('rm_retentionScheduleDeleted');
		$this->db->trans_complete();
	}
	
	/**
	 * invokes permanentDeleteRetentionScheduleQuery()
	 * 
	 * @access public
	 * @return void
	 */
	public function permanentDeleteRetentionSchedule($retentionScheduleID)
	{
		$retentionScheduleID = $this->permanentDeleteRetentionScheduleQuery($retentionScheduleID);
	}
	
	/**
	 * deletes Retention Schedule
	 * 
	 * @access private
	 * @return void
	 */
	private function permanentDeleteRetentionScheduleQuery($retentionScheduleID)
	{
		$this->db->trans_start();
		$this->db->where('retentionScheduleID',$retentionScheduleID);
		$this->db->delete('rm_retentionScheduleDeleted');
		$this->db->trans_complete();
	}
	
}

?>