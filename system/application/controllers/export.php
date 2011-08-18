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
 
 class Export extends Controller {

	public function __construct() {
		parent::Controller();
	}
  
	/**
	 * generates export file
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
			$divDept = $results['divDept'];
			
			$headers = $this->generateHeaders($getRetentionScheduleQuery, $divDept);
			$line = $this->generateDataRows($getRetentionScheduleQuery, $ids);
														
			if ($format == "excel") {
				$this->toExcel($headers, $line);
			}
			
			if ($format == "pdf") {
				$this->toPdf($headers, $line);
			}
						
		} else {
			echo "An error has occurred.";
		} 	
	}

	private function getRetentionScheduleIDs($departmentID) {
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
	 	}
								
	 	$this->db->select('retentionScheduleID, recordName, recordCategory, recordDescription, retentionPeriod, primaryAuthorityRetention, retentionNotes, retentionDecisions, primaryAuthority, notes, vitalRecord, approvedByCounsel, officeOfPrimaryResponsibility, disposition');
	 	$this->db->from('rm_retentionSchedule');
		$this->db->where_in('retentionScheduleID', $ids);
		$this->db->order_by('recordName', 'asc');
		$getRetentionScheduleQuery = $this->db->get();	
		
		$this->load->model('LookUpTablesModel');
		$divDept = $this->LookUpTablesModel->getDivision($departmentID);
		
		$results = array();
		$results['ids'] = $ids;
		$results['rsQuery'] = $getRetentionScheduleQuery; 
		$results['divDept'] = $divDept;
		
		return $results;
	}
	
	private function generateHeaders($getRetentionScheduleQuery, $divDept) {
		// snippet based on to_excel_pi.php CI Plugin	
		// generate headers
		$fields = $getRetentionScheduleQuery->field_data();
		if ($getRetentionScheduleQuery->num_rows() == 0) {
			echo 'The requested record does not exist.';
		} else {
			$headers = "";
			// display division / department
			foreach ($divDept as $f => $results) {
				if (!is_numeric($results)) {
					if ($f == 'divisionName') {
						$headers .="<font size='+2'><strong>Division: </strong>$results</font><br />";	
					} else {
						$headers .="<font size='+2'><strong>Department:</strong> $results</font><br />";
					}
				}
			} 
			
		    $headers .= "<table width='100%' border='1'><tr align='center'>";
		    
		    	$headers .= "<th><strong><font size='+2'>Record Name</font></strong>&nbsp;</th>";	
		    	$headers .= "<th><strong><font size='+2'>Record Category</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Record Description</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Retention Period</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Retention Notes</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Retention Decisions</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Primary Authority</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Primary Authority Retention</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Notes</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Vital Record</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Approved By Counsel</font></strong>&nbsp;</th>";
		    	$headers .= "<th><strong><font size='+2'>Office of Primary Responsibility</font></strong>&nbsp;</th>";	
		    
		        $headers .= "<th><strong><font size='+2'>Disposition</font></strong>&nbsp;</th>"; 
		     	$headers .= "<th><font size='+2'><strong>Associated Units</font></strong>&nbsp;</th>";
		     	$headers .= "<th><strong><font size='+2'>Related Authorities</font></strong>&nbsp;</th>";
		     	$headers .= "<th><strong><font size='+2'>Related Authority Retentions</font></strong>&nbsp;</th>";
		     	$headers .= "</tr>";
		}
		return $headers;	
	}
	
	private function generateDataRows($getRetentionScheduleQuery, $ids) {
		// generate data rows
		$line = "";
		foreach ($getRetentionScheduleQuery->result_array() as $i => $value) {
			$line .= "<tr align='center'>";
			if ((!isset($value['recordName'])) OR ($value['recordName'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordName']);
			    $line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['recordCategory'])) OR ($value['recordCategory'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCategory']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['recordDescription'])) OR ($value['recordDescription'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordDescription']);
			    $line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['retentionPeriod'])) OR ($value['retentionPeriod'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionPeriod']);
			    $line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			
			if ((!isset($value['retentionNotes'])) OR ($value['retentionNotes'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionNotes']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['retentionDecisions'])) OR ($value['retentionDecisions'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionDecisions']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['primaryAuthority'])) OR ($value['primaryAuthority'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthority']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['primaryAuthorityRetention'])) OR ($value['primaryAuthorityRetention'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['primaryAuthorityRetention']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['notes'])) OR ($value['notes'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['notes']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['vitalRecord'])) OR ($value['vitalRecord'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['vitalRecord']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			if ((!isset($value['approvedByCounsel'])) OR ($value['approvedByCounsel'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['approvedByCounsel']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
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
			   	$line .= '<td valign="top"><font size="+1">' . trim($divDept['divisionName']) . "<br /><br />" . trim($opr) . '</font></td>';	
			}

			
			// get dispositions
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= "<td></td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['disposition']);
			   	$line .= '<td valign="top"><font size="+2">' . trim($value[$i]) . '</font></td>';	
			}
			
				
			// get associated units
			$getAssociatedUnitsSql = "SELECT rm_associatedUnits.departmentID, rm_departments.departmentName " .
											"FROM rm_associatedUnits, rm_departments " .
											"WHERE rm_associatedUnits.retentionScheduleID = ? " .
											"AND rm_associatedUnits.departmentID = rm_departments.departmentID ";
					
			$retentionScheduleID = $value['retentionScheduleID'];
			$getAssociatedUnitQuery = $this->db->query($getAssociatedUnitsSql, array($retentionScheduleID));
				
			if ($getAssociatedUnitQuery->num_rows > 0) { 
				$line .= '<td valign="top"><font size="+1">'; 
				foreach ($getAssociatedUnitQuery->result() as $associatedUnit) {
					$associatedUnit = $associatedUnit->departmentName;
					$line .= trim($associatedUnit) . "<br />";
				}
			$line .= '</font></td>'; 
			} 
								
			// get related authorities
			$this->db->select('rsRelatedAuthority');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top"><font size="+1">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthority);
			   	$line .= trim($relatedAuthority) . "<br />";	
		 	}
		 		
			// get related authority retention
			$this->db->select('rsRelatedAuthorityRetention');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top"><font size="+1">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthorityRetention);
			   	$line .= trim($relatedAuthority) . "<br />";	
		 	}
		 	
		$line .='</font></td>';
		$line .= "</tr>";
					
		} // closes foreach loop
			
		$line .= "</table>";
		return $line;	
	}
	
	private function toExcel($headers, $line) {
		$filename = "retention_schedules";
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo "$headers\n$line";  
	}
	
	private function toPdf($headers, $line) {
		// pdf transform code here
	}
 }
?>