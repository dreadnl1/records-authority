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
			
			//if ($format == "csv") {
			//	$this->toCsv($headers, $line);
			//}
			
			if ($format == "html") {
				$this->toHtml($headers, $line);
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
								
	 	$this->db->select('retentionScheduleID, recordName, recordCategory, recordDescription, retentionPeriod, primaryAuthorityRetention, retentionNotes, retentionDecisions, primaryAuthority, notes, vitalRecord, approvedByCounsel, approvedByCounselDate, officeOfPrimaryResponsibility, disposition');
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
						$headers .="<strong>Division: </strong>$results<br />";	
					} else {
						$headers .="<strong>Department:</strong> $results<br />";
					}
				}
			} 
			
		    $headers .= "<table width='100%' border='1'><tr align='center'>";
		    
		    	$headers .= "<th><strong>Record Name</strong>&nbsp;</th>";	
		    	$headers .= "<th><strong>Record Category</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Record Description</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention Period</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention Notes</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Retention Decisions</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Primary Authority</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Primary Authority Retention</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Notes</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Vital Record</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Approved By Counsel</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Approved By Counsel Date</strong>&nbsp;</th>";
		    	$headers .= "<th><strong>Office of Primary Responsibility</strong>&nbsp;</th>";	
		    
		        $headers .= "<th><strong>Disposition</strong>&nbsp;</th>"; 
		     	$headers .= "<th><strong>Associated Units</strong>&nbsp;</th>";
		     	$headers .= "<th><strong>Related Authorities</strong>&nbsp;</th>";
		     	//$headers .= "<th><strong>Related Authority Retentions</strong>&nbsp;</th>";
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
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordName']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['recordCategory'])) OR ($value['recordCategory'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordCategory']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['recordDescription'])) OR ($value['recordDescription'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['recordDescription']);
			    $line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
			}
			if ((!isset($value['retentionPeriod'])) OR ($value['retentionPeriod'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['retentionPeriod']);
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
			   	$line .= '<td valign="top"><font size="+1">' . trim($divDept['divisionName']) . " - " . trim($opr) . '</td>';	
			}

			
			// get dispositions
			if ((!isset($value['disposition'])) OR ($value['disposition'] == "")) {
				$line .= "<td>&nbsp</td>";
			} else {
				$value[$i] = str_replace('"', '""', $value['disposition']);
			   	$line .= '<td valign="top">' . trim($value[$i]) . '</td>';	
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
					$line .= trim($associatedUnit) . "; ";
				}
			$line .= '</td>'; 
			} 
								
			// get related authorities
			$this->db->select('rsRelatedAuthority');
			$this->db->from('rm_rsRelatedAuthorities');
			$this->db->where_in('retentionScheduleID', $ids);
		 	$getRelatedAuthorityQuery = $this->db->get();
			$line .='<td valign="top"><font size="+1">';
				
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
			$line .='<td valign="top"><font size="+1">';
				
		 	foreach ($getRelatedAuthorityQuery->result() as $result) {
		 		$relatedAuthority = str_replace('"', '""', $result->rsRelatedAuthorityRetention);
			   	$line .= trim($relatedAuthority) . " ";	
		 	}
		 	*/
		$line .='</td>';
		$line .= "</tr>";
					
		} // closes foreach loop
			
		$line .= "</table>";
		return $line;	
	}
	
	private function toExcel($headers, $line) {
		$filename = "retention_schedules";
		header("Content-type: application/msexcel");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo "$headers\n$line"; 

		//In case of complaint http://support.microsoft.com/kb/948615
	}
	
	//private function toPdf($headers, $line) {
	private function toPdf($headers,$line) {
		$data = "retention_schedules";
		$filename = 'retention_schedules';
		$html = $this->load->view('includes/adminHeader', $data, true); 
		$this->load->plugin('to_pdf');
		// page info here, db calls, etc.     
		$html .= "$headers\n$line";
		echo $html;
		pdf_create($html, $filename);
	}
	
 	/*
	private function toCsv($headers,$line) {
		$filename = "retention_schedules";
		header("Content=type: application/csv");
		header("Content-Disposition: attachment; filename=$filename.csv");
		echo "$headers\n$line";
	}
	*/
	private function toHtml($headers,$line) {
		$data = "retention_schedules";
		$this->load->view('includes/adminHeader', $data); 
		$filename = "retention_schedules";
		
		echo "<body>";
		echo "$headers\n$line";
		echo "</body>";
		
		$this->load->view('includes/adminFooter'); 
	}
}
?>