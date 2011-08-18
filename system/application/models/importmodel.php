<?php
/**
 * Copyright 2010 University of Denver--Penrose Library--University Records Management Program
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
class ImportModel extends Model {

	public function __construct() {
		parent::Model();
	}

	/**
	 * Parses data from csv file and imports it to the Retion Schedule
	 * 
	 * @access public
	 * @param $filePath
	 * @return $result
	 */
	public function csvImport($filePath) {
		//open file
		$fh = fopen($filePath, "r");
		$result = "";
		
		$iterator = 0;
		$importData = array();
		for($info = fgetcsv($fh,4096); !feof($fh); $info = fgetcsv($fh,4096)) {
			$uuid = uniqid();
			//check to see if record name or record code is blank
			if(isset($info[1]) && isset($info[2]) && $info[1] != "" && $info[2] != "") {
				$import = array(
					'recordCategory' 				=> 	$info[0],
					'recordCode'					=>	$info[1],
					'recordName'					=>	$info[2],
					'recordDescription'				=>	$info[3],
					'keywords'						=>	$info[4],
					'retentionPeriod'				=>	$info[5],
					'disposition'					=>	$info[6],
					'retentionDecisions'			=>	$info[7],
					'retentionNotes'				=>	$info[8],
					'primaryOwnerOverride'			=>	$info[9],
					'uuid'							=>	$uuid,
					'vitalRecord'					=>	'no',
					'approvedByCounsel'				=>	'no',
					'officeOfPrimaryResponsibility'	=>	'1000',
					'override'						=>	'yes',
				);
				$importData[$iterator] = $import;
				$iterator += 1;
			}
		}
		
		foreach($importData as $import) {
			$this->db->select('recordCode');
			$this->db->from('rm_retentionSchedule');
			$this->db->where('recordCode', $import['recordCode']);
			$codeCheck = $this->db->get();
			if($codeCheck->num_rows() > 0) {
				foreach($codeCheck->result() as $row) {
					$result .= "Duplicate record code " . $row->recordCode . br();
					$error = "Duplicate record code " . $row->recordCode;
					log_message('info',$error);
				}
			} else {
				$this->db->insert('rm_retentionSchedule', $import);
				$result .= "Inserted " . $import['recordCode'] . " : " . $import['recordName'] . br();
			}
		}
		//close file
		fclose($fh);
		if($result == "") {
			$result = "Import CSV was faulty!  Check for line breaks and extra commas.";
			return $result;
		} else {
			return $result;
		}
	
	}

	/**
    * gets files in the upload directory
    *
    * @access public
    * @param $start_dir
    * @return $files
    */ 
	public function listFilesInDir($start_dir) {
		$files = array();
		$dir = opendir($start_dir);
		while(($myfile = readdir($dir)) !== false) {
			if($myfile != '.' && $myfile != '..' && !is_file($myfile) && is_dir($myfile) != TRUE) {
				$files[] = $myfile;
			}
		}
		closedir($dir);
		return $files;
	}
}
?>