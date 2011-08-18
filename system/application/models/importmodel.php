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
	 * @return data
	 */
	public function csvImport($filePath) {
		//open file
		$fh = fopen($filePath, "r");
		$data = "";

	//	$fields = $this->db->list_fields('rm_test');
	//	echo count($fields) . br();
	//	foreach($fields as $field) {
	//		echo $field . ", ";
	//	}
	
		$this->db->trans_start();
		for($info = fgetcsv($fh,4096); !feof($fh); $info = fgetcsv($fh,4096)) {
			$uuid = uniqid();
			$this->db->query("INSERT INTO rm_retentionSchedule (
							recordCode,
							recordCategory,
							recordName,
							recordDescription,
							keywords,
							retentionPeriod,
							disposition,
							retentionDecisions,
							retentionNotes,
							uuid,
							vitalRecord,
							approvedByCounsel,
							officeOfPrimaryResponsibility)
							VALUES (?,?,?,?,?,?,?,?,?,'$uuid','no','no','1000')", $info);
			$data .= "Inserted $info[2]" . br();	
		}
		//close file
		fclose($fh);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$data .= "Error with data at $info[2]" . br();
		}
		return $data;
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