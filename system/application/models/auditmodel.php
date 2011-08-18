<?php

/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu
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


class AuditModel extends Model {

	public function __construct() {
 		parent::Model();
 				
 		$this->devEmail = $this->config->item('devEmail');
 	}
 	
 	/** 
	 * invokes auditQuery();
	 * 
	 * @param $_POST 
	 * @return void
	 */
 	public function audit($_POST) {
 		$this->auditQuery($_POST);
 	}
 	
 	 private function auditQuery($_POST) {
 		$auditData = array();
 		$now = time();
 		
 		$auditData['username'] = $this->session->userdata('username');
		$auditData['updateDate'] = unix_to_human($now, TRUE, 'us');
		$auditData['previousData'] = "";
		$auditData['currentData'] = "";
		
		if(isset($_POST['recordInformationID'])){
			$id = $_POST['recordInformationID'];
			
			$this->db->select('*');
			$this->db->from('rm_recordType');
			$this->db->where('recordInformationID', $id);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $results) {
					foreach($results as $key => $value) {
			    		$auditData['previousData'] .= "$key: $value" . "; ";
					} 
				}
			}
		} elseif(isset($_POST['retentionScheduleID'])) {
			$id = $_POST['retentionScheduleID'];
			
			$this->db->select('*');
			$this->db->from('rm_retentionSchedule');
			$this->db->where('retentionScheduleID', $id);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				foreach ($query->result() as $results) {
					foreach($results as $key => $value) {
			    		$auditData['previousData'] .= "$key: $value" . "; ";
					} 
				}
			}
		} else {
			$auditData['previousData'] = "No previous data recorded";
		}
		
		foreach($_POST as $key => $value) {
    		$auditData['currentData'] .= "$key: $value" . "; ";
		} 
		$this->db->insert('rm_audit', $auditData);
		$this->db->select_max('auditID');
		$this->db->get('rm_audit');
 	}
}

?>