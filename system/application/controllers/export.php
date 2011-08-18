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
 
 class Export extends Controller {

	public function __construct() {
		parent::Controller();
	}
  
	/**
	 * generates export file
	 * 
	 * @access public
	 * @param $departmentID
	 * @return void
	 */
 	public function transform() {
 				
 		if ($this->uri->segment(3)) {
			$departmentID = $this->uri->segment(3);	
			$format = $this->uri->segment(4);
						
			$results = $this->getRetentionScheduleIDs($departmentID);
			
			$ids = $results['ids']; 
			$getRetentionScheduleQuery = $results['rsQuery'];
			if($departmentID != 999999) {
				$divDept = $results['divDept'];
			} else {
				$divDept = 999999;
			}
			$filename = "retention_schedule";
			$headers = $this->generateHeaders($getRetentionScheduleQuery, $divDept);
			$line = $this->generateDataRows($getRetentionScheduleQuery, $ids);
														
			if ($format == "excel") {
				$this->toExcel($headers, $line, $filename);
			}
			
			if ($format == "pdf") {
				$html = $headers . $line;
				//$this->toPdf($headers, $line);
    		
    			$this->toPdf($html, $filename);
			}
			
			if ($format == "csv") {
				$this->toCsv($getRetentionScheduleQuery,$filename);
			}
			
			if ($format == "xml") {
				$xmlHeader = $this->generateXMLHeader();
				$xmlFooter = $this->generateXMLFooter();
				$xmlData = $this->generateXMLDataRows($getRetentionScheduleQuery,$ids);
				$this->toXml($xmlHeader,$xmlData,$xmlFooter,$filename);
			}
			
			if ($format == "html") {
				$this->toHtml($headers, $line,$filename);
			}
			
			if ($format == "public") {
				$headers = $this->generatePublicHeaders($getRetentionScheduleQuery, $divDept);
				$line = $this->generatePublicDataRows($getRetentionScheduleQuery, $ids);
				$this->toExcel($headers, $line,$filename);
			}
						
		} else {
			echo "An error has occurred.";
		} 	
	}

	public function transformAudit() {
		if ($this->uri->segment(3)) {
			$format = $this->uri->segment(3);
			
			$results = $this->getAudit();
			
			$headers = $this->generateAuditHeaders($results);
			$line = $this->generateAuditDataRows($results);
			$filename = "records_authority_audit";
			if ($format == "excel") {
				$this->toExcel($headers, $line, $filename);
			}
			
			if ($format == "pdf") {
				$html = $headers . $line;
				//$this->toPdf($headers, $line);
    		
    			$this->toPdf($html,$filename);
			}
			
			if ($format == "csv") {
				$this->toCsv($results,$filename);
			}
			
			if ($format == "auditXml") {
				$xmlHeader = $this->generateXMLHeader();
				$xmlFooter = $this->generateXMLFooter();
				$xmlData = $this->generateXMLAuditDataRows($results);
				$this->toAuditXml($xmlHeader,$xmlData,$xmlFooter,$filename);
			}
			if ($format == "html") {
				$this->toHtml($headers, $line, $filename);
			}
			
		} else {
			echo "An error has occurred.";
		} 
	}
	
	/**
	 * generates Retention Schedule Data
	 * 
	 * @param $departmentID
	 * @return $results
	 */
	private function getRetentionScheduleIDs($departmentID) {
		// get retention schedule ids
		$this->db->select('retentionScheduleID');
		$this->db->from('rm_retentionSchedule');
		if($departmentID != 999999) {
			$this->db->where('officeOfPrimaryResponsibility', $departmentID);
		}
	 	$retentionScheduleIDs = $this->db->get();
	 		
	 	if ($retentionScheduleIDs->num_rows() > 0) {
			// package id's in an array
	 		$ids = array();
			foreach ($retentionScheduleIDs->result() as $id) {
				$ids[] = $id->retentionScheduleID;				
			}	 		
	 	}
								
	 	$this->db->select('retentionScheduleID, recordCode, recordName, recordCategory, recordDescription, keywords, retentionPeriod, primaryAuthorityRetention, relatedAuthorities, retentionDecisions, primaryAuthority, notes, vitalRecord, approvedByCounsel, approvedByCounselDate, officeOfPrimaryResponsibility, override, primaryOwnerOverride, disposition');
	 	$this->db->from('rm_retentionSchedule');
		$this->db->where_in('retentionScheduleID', $ids);
		$this->db->order_by('recordCode', 'asc');
		$getRetentionScheduleQuery = $this->db->get();	
		
		$this->load->model('LookUpTablesModel');
		$divDept = $this->LookUpTablesModel->getDivision($departmentID);
		
		$results = array();
		$results['ids'] = $ids;
		$results['rsQuery'] = $getRetentionScheduleQuery; 
		$results['divDept'] = $divDept;
		
		return $results;
	}
	
	/**
	 * generates Audit Data
	 * 
	 * @return $results
	 */
	private function getAudit() {
		$this->db->select('username, updateDate, previousData, currentData');
		$this->db->from('rm_audit');
		$this->db->order_by('updateDate', 'asc');
		$results = $this->db->get();
		
		return $results;
	}
	
	/**
	 * generates Retention Schedule Headers
	 * 
	 * @param $getRetentionScheduleQuery, $divDept
	 * @return $headers
	 */
	private function generateHeaders($getRetentionScheduleQuery, $divDept) {
		// snippet based on to_excel_pi.php CI Plugin	
		// generate headers
		$fields = $getRetentionScheduleQuery->field_data();
		if ($getRetentionScheduleQuery->num_rows() == 0) {
			echo 'The requested record does not exist.';
		} else {
			$headers = "";
			// display division / department
			if($divDept != 999999) {
				foreach ($divDept as $f => $results) {
					if (!is_numeric($results)) {
						if ($f == 'divisionName') {
							$headers .="<strong>Division: </strong>$results<br />";	
						} else {
							$headers .="<strong>Department:</strong> $results<br />";
						}
					}
				} 
			}
			
		    $headers .= "<table width='100%' border='1'><tr align='center'>";
		    	
		    	$headers .= "<th><strong>Code</strong>&nbsp;</th>";	
		    	$headers .= "<th><strong>Record Category</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Record Name</strong>&nbsp;</th>";	
		    	$headers .= "<th><strong>Record Description</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>keywords</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention Period</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Disposition</strong>&nbsp;</th>"; 
		    	$headers .= "<th><strong>Retention Decisions</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention Notes</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Primary Authority</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Primary Authority Retention</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Related Authorities</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Notes</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Vital Record</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Approved By Counsel</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Approved By Counsel Date</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Primary Owner Override</strong>&nbsp;</th>";	
		    	$headers .= "<th><strong>Office of Primary Responsibility</strong>&nbsp;</th>";	
		     	$headers .= "<th><strong>Associated Units</strong>&nbsp;</th>";
		     	//$headers .= "<th><strong>Related Authority Retentions</strong>&nbsp;</th>";
		     	$headers .= "</tr>";
		}
		return $headers;	
	}
	
	/**
	 * generates public Retention Schedule Headers
	 * 
	 * @param $getRetentionScheduleQuery, $divDept
	 * @return $headers
	 */
 	private function generatePublicHeaders($getRetentionScheduleQuery, $divDept) {
		// snippet based on to_excel_pi.php CI Plugin	
		// generate headers
		$fields = $getRetentionScheduleQuery->field_data();
		if ($getRetentionScheduleQuery->num_rows() == 0) {
			echo 'The requested record does not exist.';
		} else {
			$headers = "";
			// display division / department
			if($divDept != 999999) {
				foreach ($divDept as $f => $results) {
					if (!is_numeric($results)) {
						if ($f == 'divisionName') {
							$headers .="<strong>Division: </strong>$results<br />";	
						} else {
							$headers .="<strong>Department:</strong> $results<br />";
						}
					}
				} 
			}
			
		    $headers .= "<table width='100%' border='1'><tr align='center'>";
		    
		    	$headers .= "<th><strong>Code</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Functional Category</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Record Group</strong>&nbsp;</th>";	
		    	$headers .= "<th><strong>Description</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Search Terms</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Retention Notes</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Retention Decisions</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Primary Authority</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Primary Authority Retention</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Notes</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Vital Record</strong>&nbsp;</th>";
		    	//$headers .= "<th><strong>Approved By Counsel</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Rules</strong>&nbsp;</th>"; 
		    	$headers .= "<th><strong>Publish Date</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Owner</strong>&nbsp;</th>";	
		     	//$headers .= "<th><strong>Associated Units</strong>&nbsp;</th>";
		     	//$headers .= "<th><strong>Related Authorities</strong>&nbsp;</th>";
		     	//$headers .= "<th><strong>Related Authority Retentions</strong>&nbsp;</th>";
		     	$headers .= "</tr>";
		}
		return $headers;	
	}
	
	/**
	 * generates Audit Headers
	 * 
	 * @param $results
	 * @return $headers
	 */
	private function generateAuditHeaders($results) {
		// snippet based on to_excel_pi.php CI Plugin	
		// generate headers
		$fields = $results->field_data();
		if ($results->num_rows() == 0) {
			echo 'The requested record does not exist.';
		} else {
			$headers = "";
		    $headers .= "<table width='100%' border='1'><tr align='center'>";
		    
		    foreach($fields as $field) {
		    	$col = $field->name;
		    	$headers .= "<th><strong>$col</strong>&nbsp;</th>";
		    }
		    	
		    	/*
		    	$headers .= "<th><strong>User</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Date</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Previous Data</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Current Data</strong>&nbsp;</th>";*/
		     $headers .= "</tr>";
		}
		return $headers;	
	}
	
	/**
	 * generates Retention Schedule data rows
	 * 
	 * @param $getRetentionScheduleQuery, $ids
	 * @return $line
	 */
	private function generateDataRows($getRetentionScheduleQuery, $ids) {
		// generate data rows
		$line = "";
		foreach ($getRetentionScheduleQuery->result_array() as $i => $value) {
			$line .= "<tr align='center'>";
			
			if ((!isset($value['recordCode'])) OR ($value['recordCode'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCode']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['recordCategory'])) OR ($value['recordCategory'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCategory']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['recordName'])) OR ($value['recordName'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordName']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['recordDescription'])) OR ($value['recordDescription'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordDescription']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['keywords'])) OR ($value['keywords'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionPeriod']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['retentionPeriod'])) OR ($value['retentionPeriod'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionPeriod']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['disposition']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['retentionNotes'])) OR ($value['retentionNotes'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionNotes']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['retentionDecisions'])) OR ($value['retentionDecisions'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionDecisions']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['primaryAuthority'])) OR ($value['primaryAuthority'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthority']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['primaryAuthorityRetention'])) OR ($value['primaryAuthorityRetention'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthorityRetention']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['relatedAuthorities'])) OR ($value['relatedAuthorities'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['relatedAuthorities']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['notes'])) OR ($value['notes'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['notes']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['vitalRecord'])) OR ($value['vitalRecord'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['vitalRecord']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['approvedByCounsel'])) OR ($value['approvedByCounsel'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['approvedByCounsel']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['approvedByCounselDate'])) OR ($value['approvedByCounselDate'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['approvedByCounselDate']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			if ((!isset($value['primaryOwnerOverride'])) OR ($value['primaryOwnerOverride'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryOwnerOverride']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			
			// get office of primary responsibility
			$getOfficeOfPrimaryResponsibilitySql = "SELECT rm_departments.departmentName " .
													"FROM rm_departments " .
													"WHERE rm_departments.departmentID = ? ";
				
			
			
			$officeOfPrimaryResponsibility = $value['officeOfPrimaryResponsibility'];
			$getOfficePrimaryResponsibilityQuery = $this->db->query($getOfficeOfPrimaryResponsibilitySql, array($officeOfPrimaryResponsibility));
			
			$this->load->model('LookUpTablesModel');
			$divDept = $this->LookUpTablesModel->getDivision($officeOfPrimaryResponsibility);
			
			if ($getOfficePrimaryResponsibilityQuery->num_rows > 0) {
				$row = $getOfficePrimaryResponsibilityQuery->row();
				$opr = str_replace('"', '""', $row->departmentName);
			   	$line .= '<td valign="top">' . trim($divDept['divisionName']) . " - " . trim($opr) . '</td>';	
			}

			/*
			// get dispositions
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['disposition']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			*/
				
			// get associated units
			$getAssociatedUnitsSql = "SELECT rm_associatedUnits.departmentID, rm_departments.departmentName " .
											"FROM rm_associatedUnits, rm_departments " .
											"WHERE rm_associatedUnits.retentionScheduleID = ? " .
											"AND rm_associatedUnits.departmentID = rm_departments.departmentID ";
					
			$retentionScheduleID = $value['retentionScheduleID'];
			$getAssociatedUnitQuery = $this->db->query($getAssociatedUnitsSql, array($retentionScheduleID));
				
			if ($getAssociatedUnitQuery->num_rows > 0) { 
				$line .= '<td valign="top">'; 
				foreach ($getAssociatedUnitQuery->result() as $associatedUnit) {
					$associatedUnit = $associatedUnit->departmentName;
					$line .= trim($associatedUnit) . "; ";
				}
			$line .= '</td>'; 
			} else {
				$line .= '<td valign="top">&nbsp</td>';
			}
			/*					
			// get related authorities
			$this->db->select('rsRelatedAuthority');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthority);
			   	$line .= trim($relatedAuthority) . "; ";	
		 	}
		 		
			// get related authority retention
			$this->db->select('rsRelatedAuthorityRetention');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top"><font size="+1">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthorityRetention);
			   	$line .= trim($relatedAuthority) . " ";	
		 	}
		 	*/
		//$line .='</td>';
		$line .= "</tr>";
					
		} // closes foreach loop
			
		$line .= "</table>";
		return $line;	
	}
 	
 	/**
	 * generates public Retention Schedule data rows
	 * 
	 * @param $getRetentionScheduleQuery, $ids
	 * @return $line
	 */
 	private function generatePublicDataRows($getRetentionScheduleQuery, $ids) {
		// generate data rows
		$line = "";
		foreach ($getRetentionScheduleQuery->result_array() as $i => $value) {
			$line .= "<tr align='center'>";
			if ((!isset($value['recordCode'])) OR ($value['recordCode'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCode']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['recordCategory'])) OR ($value['recordCategory'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCategory']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['recordName'])) OR ($value['recordName'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordName']);
			    $line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['recordDescription'])) OR ($value['recordDescription'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordDescription']);
			    $line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['keywords'])) OR ($value['keywords'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['keywords']);
			    $line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['retentionPeriod'])) OR ($value['retentionPeriod'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionPeriod']);
			    $line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['disposition']);
			    $line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			/*if ((!isset($value['retentionNotes'])) OR ($value['retentionNotes'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionNotes']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['retentionDecisions'])) OR ($value['retentionDecisions'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionDecisions']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['primaryAuthority'])) OR ($value['primaryAuthority'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthority']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['primaryAuthorityRetention'])) OR ($value['primaryAuthorityRetention'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthorityRetention']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['notes'])) OR ($value['notes'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['notes']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['vitalRecord'])) OR ($value['vitalRecord'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['vitalRecord']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['approvedByCounsel'])) OR ($value['approvedByCounsel'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['approvedByCounsel']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}*/
			if ((!isset($value['approvedByCounselDate'])) OR ($value['approvedByCounselDate'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['approvedByCounselDate']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			
			// get office of primary responsibility
			if((!isset($value['primaryOwnerOverride'])) OR ($value['primaryOwnerOverride'] == "")) {
				$getOfficeOfPrimaryResponsibilitySql = "SELECT rm_departments.departmentName " .
														"FROM rm_departments " .
														"WHERE rm_departments.departmentID = ? ";
					
				
				
				$officeOfPrimaryResponsibility = $value['officeOfPrimaryResponsibility'];
				$getOfficePrimaryResponsibilityQuery = $this->db->query($getOfficeOfPrimaryResponsibilitySql, array($officeOfPrimaryResponsibility));
				
				$this->load->model('LookUpTablesModel');
				$divDept = $this->LookUpTablesModel->getDivision($officeOfPrimaryResponsibility);
				
				if ($getOfficePrimaryResponsibilityQuery->num_rows > 0) {
					$row = $getOfficePrimaryResponsibilityQuery->row();
					$opr = str_replace('"', '""', $row->departmentName);
				   	$line .= '<td valign="top" align="left">' . trim($divDept['divisionName']) . " - " . trim($opr) . '</td>';	
				}
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryOwnerOverride']);
			   	$line .= '<td valign="top" align="left">' . trim($value[$i]) . '</td>';	
			}
			
			/*	
			// get associated units
			$getAssociatedUnitsSql = "SELECT rm_associatedUnits.departmentID, rm_departments.departmentName " .
											"FROM rm_associatedUnits, rm_departments " .
											"WHERE rm_associatedUnits.retentionScheduleID = ? " .
											"AND rm_associatedUnits.departmentID = rm_departments.departmentID ";
					
			$retentionScheduleID = $value['retentionScheduleID'];
			$getAssociatedUnitQuery = $this->db->query($getAssociatedUnitsSql, array($retentionScheduleID));
				
			if ($getAssociatedUnitQuery->num_rows > 0) { 
				$line .= '<td valign="top" align="left">'; 
				foreach ($getAssociatedUnitQuery->result() as $associatedUnit) {
					$associatedUnit = $associatedUnit->departmentName;
					$line .= trim($associatedUnit) . "; ";
				}
			$line .= '</td>'; 
			} 
								
			// get related authorities
			$this->db->select('rsRelatedAuthority');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top" align="left">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthority);
			   	$line .= trim($relatedAuthority) . "; ";	
		 	}
		 	/*	
			// get related authority retention
			$this->db->select('rsRelatedAuthorityRetention');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top" align="left"><font size="+1">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthorityRetention);
			   	$line .= trim($relatedAuthority) . " ";	
		 	}
		 	*/
		//$line .='</td>';
		$line .= "</tr>";
					
		} // closes foreach loop
			
		$line .= "</table>";
		return $line;	
	}
	
	/**
	 * generates Retention Schedule data rows
	 * 
	 * @param $results
	 * @return $line
	 */
	private function generateAuditDataRows($results) {
		// generate data rows
		$line = "";
		foreach ($results->result_array() as $i => $value) {
			$line .= "<tr align='center'>";
			if ((!isset($value['username'])) OR ($value['username'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['username']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['updateDate'])) OR ($value['updateDate'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['updateDate']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['previousData'])) OR ($value['previousData'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['previousData']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['currentData'])) OR ($value['currentData'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['currentData']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
	
		$line .= "</tr>";
					
		} // closes foreach loop
			
		$line .= "</table>";
		return $line;	
	}
	
	/**
	 * generates Retention Schedule XML header
	 *
	 * @return $header
	 */
	private function generateXMLHeader() {
		$nl = "\n";
		$tb = "\t";
		$baseUrl = base_url();
		$header = 	'<?xml version="1.0" encoding="UTF-8"?>'.$nl.
					'<Class xmlns="http://www.dlm-network.org/moreq2/1.04.01"'.$nl.
					$tb.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'.$nl.
					//$tb.'xsi:schemaLocation="http://www.dlm-network.org/moreq2/1.04.01 file:/C:/Users/evan.blount/workspace/recordsAuthority/xml/MoReq2/MoReq2.xsd">'.$nl.
					$tb.'xsi:schemaLocation="'.$baseUrl.'xml/MoReq2/MoReq2.xsd">'.$nl.
					$tb.'<Description xmlns="">'.$nl.
					$tb.$tb.'<classification>'.$nl.
					$tb.$tb.$tb.'<classification_code>[]</classification_code>'.$nl.
					$tb.$tb.$tb.'<fully_qualified_classification_code>[]</fully_qualified_classification_code>'.$nl.
					$tb.$tb.'</classification>'.$nl.
					$tb.$tb.'<title>[]</title>'.$nl.
					$tb.'</Description>'.$nl.
					$tb.'<Event_history xmlns="">'.$nl.
					$tb.$tb.'<date>'.$nl.
					$tb.$tb.$tb.'<created>2011-05-10T09:00:00</created>'.$nl.
					$tb.$tb.$tb.'<opened>2011-05-10T09:00:00</opened>'.$nl.
					$tb.$tb.'</date>'.$nl.
					$tb.'</Event_history>'.$nl.
					$tb.'<Event_plan xmlns="">'.$nl.
					$tb.$tb.'<status>'.$nl.
					$tb.$tb.$tb.'<permanent>0</permanent>'.$nl.
					$tb.$tb.'</status>'.$nl.
					$tb.'</Event_plan>'.$nl.
					$tb.'<Identity xmlns="">'.$nl.
					$tb.$tb.'<system_identifier>[]</system_identifier>'.$nl.
					$tb.'</Identity>'.$nl.
					$tb.'<Relation xmlns="">'.$nl.
					$tb.$tb.'<agent>'.$nl.
					$tb.$tb.$tb.'<owner>[]</owner>'.$nl.
					$tb.$tb.'</agent>'.$nl.
					$tb.$tb.'<is_child_of>[]</is_child_of>'.$nl.
					$tb.$tb.'<retention_and_disposition_schedule>[]</retention_and_disposition_schedule>'.$nl.
					$tb.'</Relation>'.$nl.
					$tb.'<Use xmlns="">'.$nl.
					$tb.$tb.'<status>'.$nl.
					$tb.$tb.$tb.'<active>0</active>'.$nl.
					$tb.$tb.$tb.'<physical>0</physical>'.$nl.
					$tb.$tb.'</status>'.$nl.
					$tb.'</Use>'.$nl.
					'<Files xmlns="">'.$nl.
					$tb.'<File_Stub xmlns="http://www.dlm-network.org/moreq2/1.04.01">'.$nl.
					$tb.'<Description xmlns="">'.$nl.
					$tb.$tb.'<abstract>'.$nl.
					$tb.$tb.$tb.'<description>[]</description>'.$nl.
					$tb.$tb.'</abstract>'.$nl.
					$tb.$tb.'<classification>'.$nl.
					$tb.$tb.$tb.'<new_fully_qualified_classification_code>[]</new_fully_qualified_classification_code>'.$nl.
					$tb.$tb.'</classification>'.$nl.
					$tb.$tb.'<title>[]</title>'.$nl.
					$tb.'</Description>'.$nl.
					$tb.'<Relation xmlns="">'.$nl.
					$tb.$tb.'<agent>'.$nl.
					$tb.$tb.$tb.'<destroy_or_transfer_or_relocate>[]</destroy_or_transfer_or_relocate>'.$nl.
					$tb.$tb.'</agent>'.$nl.
					$tb.'</Relation>'.$nl.
					$tb.'<Records xmlns="">'.$nl.
					$tb.$tb.'<Record xmlns="http://www.dlm-network.org/moreq2/1.04.01">'.$nl.
					$tb.$tb.$tb.'<Description xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<author>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<name>[]</name>'.$nl.
					$tb.$tb.$tb.$tb.'</author>'.$nl.
					$tb.$tb.$tb.$tb.'<classification>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<classification_code>[]</classification_code>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<fully_qualified_classification_code>[]</fully_qualified_classification_code>'.$nl.
					$tb.$tb.$tb.$tb.'</classification>'.$nl.
					$tb.$tb.$tb.$tb.'<date>2011-05-10T09:00:00</date>'.$nl.
					$tb.$tb.$tb.$tb.'<title>[]</title>'.$nl.
					$tb.$tb.$tb.'</Description>'.$nl.
					$tb.$tb.$tb.'<Event_history xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<date>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<captured>2011-05-10T09:00:00</captured>'.$nl.
					$tb.$tb.$tb.$tb.'</date>'.$nl.
					$tb.$tb.$tb.'</Event_history>'.$nl.
					$tb.$tb.$tb.'<Event_plan xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<date>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<return>2011-05-10</return>'.$nl.
					$tb.$tb.$tb.$tb.'</date>'.$nl.
					$tb.$tb.$tb.$tb.'<status>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<permanent>0</permanent>'.$nl.
					$tb.$tb.$tb.$tb.'</status>'.$nl.
					$tb.$tb.$tb.'</Event_plan>'.$nl.
					$tb.$tb.$tb.'<Identity xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<system_identifier>[]</system_identifier>'.$nl.
					$tb.$tb.$tb.'</Identity>'.$nl.
					$tb.$tb.$tb.'<Relation xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<agent>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<owner></owner>'.$nl.
					$tb.$tb.$tb.$tb.'</agent>'.$nl.
					$tb.$tb.$tb.$tb.'<is_child_of>[]</is_child_of>'.$nl.
					$tb.$tb.$tb.$tb.'<retention_and_disposition_schedule>[]</retention_and_disposition_schedule>'.$nl.
					$tb.$tb.$tb.$tb.'<record_type>[]</record_type>'.$nl.
					$tb.$tb.$tb.'</Relation>'.$nl.
					$tb.$tb.$tb.'<Use xmlns="">'.$nl.
					$tb.$tb.$tb.$tb.'<status>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<electronic_signature>[]</electronic_signature>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<physical>0</physical>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<vital_record>0</vital_record>'.$nl.
					$tb.$tb.$tb.$tb.'</status>'.$nl.
					$tb.$tb.$tb.$tb.'<technical_environment>'.$nl.
					$tb.$tb.$tb.$tb.$tb.'<electronic_signature>[]</electronic_signature>'.$nl.
					$tb.$tb.$tb.$tb.'</technical_environment>'.$nl.
					$tb.$tb.$tb.'</Use>'.$nl.
					$tb.$tb.$tb.'<Components xmlns="">'.$nl;
					
		return $header;
	}
	
	/**
	 * generates Retention Schedule XML footer
	 *
	 * @return $footer
	 */
	private function generateXMLFooter() {
		$nl = "\n";
		$tb = "\t";
		
		$footer = 	$tb.$tb.$tb.'</Components>'.$nl.
					$tb.$tb.'</Record>'.$nl.
					$tb.'</Records>'.$nl.
					$tb.'</File_Stub>'.$nl.
					'</Files>'.$nl.
					'</Class>'.$nl;

		return $footer;
	}
	
	/**
	 * generates Retention Schedule XML element
	 *
	 * @return $element
	 */
	private function generateXMLElement($name,$table,$data) {
		$nl = "\n";
		$tb = "\t";
		
		$element = 		$tb.$tb.$tb.$tb.'<Component xmlns="http://www.dlm-network.org/moreq2/1.04.01">'.$nl.
						$tb.$tb.$tb.$tb.$tb.'<Description>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'<abstract><reason_for_rendition>'.$name.'</reason_for_rendition></abstract>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'<classification>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.$tb.'<classification_code>###</classification_code>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.$tb.'<fully_qualified_classification_code>[]</fully_qualified_classification_code>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'</classification>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'</Description>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'<Identity>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'<system_identifier>[]</system_identifier>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'</Identity>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'<Relation>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'<is_child_of>'.$table.'</is_child_of>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'</Relation>'.$nl.
						$tb.$tb.$tb.$tb.$tb.'<Use>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.'<technical_environment>'.$nl.
						$tb.$tb.$tb.$tb.$tb.$tb.$tb.'<file_format>[]</file_format>'.$nl.
	                    $tb.$tb.$tb.$tb.$tb.$tb.$tb.'<file_format_original>[]</file_format_original>'.$nl.
	                    $tb.$tb.$tb.$tb.$tb.$tb.$tb.'<file_format_version>[]</file_format_version>'.$nl.
	                    $tb.$tb.$tb.$tb.$tb.$tb.$tb.'<file_format_version_original>[]</file_format_version_original>'.$nl.
	                	$tb.$tb.$tb.$tb.$tb.$tb.'</technical_environment>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.'</Use>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.'<Custom>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.$tb.'<Description>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.$tb.$tb.'<abstract>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.$tb.$tb.$tb.'<description>'.$data.'</description>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.$tb.$tb.'</abstract>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.$tb.'</Description>'.$nl.
	            		$tb.$tb.$tb.$tb.$tb.'</Custom>'.$nl.
						$tb.$tb.$tb.$tb.'</Component>'.$nl;
		return $element;
	}
	
	/**
	 * generates Retention Schedule XML data
	 *
	 * @params $getRetentionScheduleQuery, $ids
	 * @return $line
	 */
	private function generateXMLDataRows($getRetentionScheduleQuery,$ids) {
		$this->load->helper('xml');
		$nl = "\n";
		$tb = "\t";
		
		$line = "";
		foreach ($getRetentionScheduleQuery->result_array() as $i => $value) {
			if ((!isset($value['recordCode'])) OR ($value['recordCode'] == "")) {
				$line .= $this->generateXMLElement('recordCode','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['recordCode']);
			   	$line .= $this->generateXMLElement('recordCode','rm_retentionSchedule',$value[$i]);
			}
		
			if ((!isset($value['recordCategory'])) OR ($value['recordCategory'] == "")) {
				$line .= $this->generateXMLElement('recordCategory','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['recordCategory']);
			   	$line .= $this->generateXMLElement('recordCategory','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['recordName'])) OR ($value['recordName'] == "")) {
				$line .= $this->generateXMLElement('recordName','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['recordName']);
			   	$line .= $this->generateXMLElement('recordName','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['recordDescription'])) OR ($value['recordDescription'] == "")) {
				$line .= $this->generateXMLElement('recordDescription','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['recordDescription']);
			   	$line .= $this->generateXMLElement('recordDescription','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['keywords'])) OR ($value['keywords'] == "")) {
				$line .= $this->generateXMLElement('keywords','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['keywords']);
			   	$line .= $this->generateXMLElement('keywords','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['retentionPeriod'])) OR ($value['retentionPeriod'] == "")) {
				$line .= $this->generateXMLElement('retentionPeriod','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['retentionPeriod']);
			   	$line .= $this->generateXMLElement('retentionPeriod','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= $this->generateXMLElement('disposition','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['disposition']);
			   	$line .= $this->generateXMLElement('disposition','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['retentionNotes'])) OR ($value['retentionNotes'] == "")) {
				$line .= $this->generateXMLElement('retentionNotes','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['retentionNotes']);
			   	$line .= $this->generateXMLElement('retentionNotes','rm_retentionSchedule',$value[$i]);
			}

			if ((!isset($value['retentionDecisions'])) OR ($value['retentionDecisions'] == "")) {
				$line .= $this->generateXMLElement('retentionDecisions','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['retentionDecisions']);
			   	$line .= $this->generateXMLElement('retentionDecisions','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['primaryAuthority'])) OR ($value['primaryAuthority'] == "")) {
				$line .= $this->generateXMLElement('primaryAuthority','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['primaryAuthority']);
			   	$line .= $this->generateXMLElement('primaryAuthority','rm_retentionSchedule',$value[$i]);	
			}
			
			if ((!isset($value['primaryAuthorityRetention'])) OR ($value['primaryAuthorityRetention'] == "")) {
				$line .= $this->generateXMLElement('primaryAuthorityRetention','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['primaryAuthorityRetention']);
			   	$line .= $this->generateXMLElement('primaryAuthorityRetention','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['notes'])) OR ($value['notes'] == "")) {
				$line .= $this->generateXMLElement('notes','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['notes']);
			   	$line .= $this->generateXMLElement('notes','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['vitalRecord'])) OR ($value['vitalRecord'] == "")) {
				$line .= $this->generateXMLElement('vitalRecord','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['vitalRecord']);
			   	$line .= $this->generateXMLElement('vitalRecord','rm_retentionSchedule',$value[$i]);	
			}
			
			if ((!isset($value['approvedByCounsel'])) OR ($value['approvedByCounsel'] == "")) {
				$line .= $this->generateXMLElement('approvedByCounsel','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['approvedByCounsel']);
			   	$line .= $this->generateXMLElement('approvedByCounsel','rm_retentionSchedule',$value[$i]);
			}
			
			if ((!isset($value['approvedByCounselDate'])) OR ($value['approvedByCounselDate'] == "")) {
				$line .= $this->generateXMLElement('approvedByCounselDate','rm_retentionSchedule','[]');
			} else {
				$value[$i] = xml_convert($value['approvedByCounselDate']);
			   	$line .= $this->generateXMLElement('approvedByCounselDate','rm_retentionSchedule',$value[$i]);	
			}
			
			// get office of primary responsibility
			$getOfficeOfPrimaryResponsibilitySql = "SELECT rm_departments.departmentName " .
													"FROM rm_departments " .
													"WHERE rm_departments.departmentID = ? ";
				
			
			
			$officeOfPrimaryResponsibility = $value['officeOfPrimaryResponsibility'];
			$getOfficePrimaryResponsibilityQuery = $this->db->query($getOfficeOfPrimaryResponsibilitySql, array($officeOfPrimaryResponsibility));
			
			$this->load->model('LookUpTablesModel');
			$divDept = $this->LookUpTablesModel->getDivision($officeOfPrimaryResponsibility);
			
			if ($getOfficePrimaryResponsibilityQuery->num_rows > 0) {
				$row = $getOfficePrimaryResponsibilityQuery->row();
				$opr = xml_convert($row->departmentName);
			   	$line .= $this->generateXMLElement('divisionName','rm_divisions',$divDept['divisionName']);
			   	$line .= $this->generateXMLElement('departmentName','rm_departments',$opr);	
			}
				
			// get associated units
			$getAssociatedUnitsSql = "SELECT rm_associatedUnits.departmentID, rm_departments.departmentName " .
											"FROM rm_associatedUnits, rm_departments " .
											"WHERE rm_associatedUnits.retentionScheduleID = ? " .
											"AND rm_associatedUnits.departmentID = rm_departments.departmentID ";
					
			$retentionScheduleID = $value['retentionScheduleID'];
			$getAssociatedUnitQuery = $this->db->query($getAssociatedUnitsSql, array($retentionScheduleID));
				
			if ($getAssociatedUnitQuery->num_rows > 0) { 
				$units = ""; 
				foreach ($getAssociatedUnitQuery->result() as $associatedUnit) {
					$associatedUnit = $associatedUnit->departmentName;
					$units .= xml_convert($associatedUnit) . "; ";
				}
			xml_convert($units);
			$line .=  $this->generateXMLElement('associatedUnits','rm_associatedUnits',$units);
			} 
		}
		
		return $line;
	}
	
	/**
	 * generates Audit XML data
	 *
	 * @params $results
	 * @return $line
	 */
	private function generateXMLAuditDataRows($results) {
		$this->load->helper('xml');
		
		// generate data rows
		$line = "";
		foreach ($results->result_array() as $i => $value) {
			if ((!isset($value['username'])) OR ($value['username'] == "")) {
				$line .= $this->generateXMLElement('username','rm_audit','[]');
			} else {
				$value[$i] = xml_convert($value['username']);
			   	$line .= $this->generateXMLElement('username','rm_audit',$value[$i]);	
			}
			
			if ((!isset($value['updateDate'])) OR ($value['updateDate'] == "")) {
				$line .= $this->generateXMLElement('updateDate','rm_audit','[]');
			} else {
				$value[$i] = xml_convert($value['updateDate']);
			   	$line .= $this->generateXMLElement('updateDate','rm_audit',$value[$i]);	
			}
			
			if ((!isset($value['previousData'])) OR ($value['previousData'] == "")) {
				$line .= $this->generateXMLElement('previousData','rm_audit','[]');
			} else {
				$value[$i] = xml_convert($value['previousData']);
			   	$line .= $this->generateXMLElement('previousData','rm_audit',$value[$i]);	
			}
			
			if ((!isset($value['currentData'])) OR ($value['currentData'] == "")) {
				$line .= $this->generateXMLElement('currentData','rm_audit','[]');
			} else {
				$value[$i] = xml_convert($value['currentData']);
			   	$line .= $this->generateXMLElement('currentData','rm_audit',$value[$i]);	
			}
					
		}
		return $line;	
	}
	
	/**
	 * generates excel document
	 * 
	 * @param $headers, $line
	 * @return void
	 */
	private function toExcel($headers, $line, $filename) {
		header("Content-type: application/msexcel");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo "$headers\n$line"; 

		//In case of complaint http://support.microsoft.com/kb/948615
	}
	
	/**
	 * DEPRICATED
	 * generates pdf document
	 * 
	 * @param $html, $filename
	 * @return void
	 */
	private function toPdf($html, $filename, $stream=TRUE) {
	    require_once("system/plugins/dompdf/dompdf_config.inc.php");
	    spl_autoload_register('DOMPDF_autoload');
	    
	    $dompdf = new DOMPDF();
	    $dompdf->load_html($html);
	    $dompdf->render();
	    $dompdf->stream($filename.".pdf");
	}
 	
 	/**
	 * generates csv document
	 * 
	 * @param $query
	 * @return void
	 */
	private function toCsv($query, $filename) {
		header("Content=type: text/csv");
		header("Content-Disposition: attachment; filename=$filename.csv");
		$this->load->dbutil();
		$delimiter = ",";
		echo $this->dbutil->csv_from_result($query,$delimiter);
	}
	
	 /**
	 * generates xml document
	 * 
	 * @param $query
	 * @return void
	 */
	private function toXml($headers,$line,$footers,$filename) {
		header("Content=type: text/xml");
		header("Content-Disposition: attachment; filename=$filename.xml");
		echo "$headers\n$line\n$footers";
	}
	
	 /**
	 * generates xml document
	 * 
	 * @param $query
	 * @return void
	 */
	private function toAuditXml($headers,$line,$footers,$filename) {
		header("Content=type: text/xml");
		header("Content-Disposition: attachment; filename=$filename.xml");
		echo "$headers\n$line\n$footers";
	}
	
	/**
	 * generates html document
	 * 
	 * @param $headers, $line
	 * @return void
	 */
	private function toHtml($headers,$line,$filename) {
		$data = $filename;
		$this->load->view('includes/adminHeader', $data); 
		
		echo "<body>";
		echo "$headers\n$line";
		echo "</body>";
		
		$this->load->view('includes/adminFooter'); 
	}
	
	/**
	 * generates public excel document
	 * 
	 * @param $headers, $line
	 * @return void
	 */
	private function toPublic($headers,$line,$filename) {
		$data = "retention_schedules";
		$this->load->view('includes/adminHeader',$data);

		echo "<script language='javascript'>window.print()</script>";
		echo "<body>";
		echo "$headers\n$line";
		echo "</body>";
	}
}
?>