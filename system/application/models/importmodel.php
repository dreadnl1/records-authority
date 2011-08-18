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

	public function csvImport($filePath) {
		//open file
		$fh = fopen($filePath, "r");
		$data = "";

		$fields = $this->db->list_fields('rm_test');
		echo count($fields) . br();
		foreach($fields as $field) {
			echo $field . br();
		}

		for($info = fgetcsv($fh,1024); !feof($fh); $info = fgetcsv($fh,1024)) {
			//$this->db->query("INSERT INTO rm_test ($field1,$field2,$field3) VALUES (?,?,?)", $info);
			$data .= "Inserted $info[0]" . br();
		}
		//close file
		fclose($fh);
		return $data;
	}

	public function listDirs($where){
		$itemHandler=opendir($where);
		$i=0;
		$files = array();
		while(($item=readdir($itemHandler)) !== false){
			if(substr($item, 0, 1)!="."){
				$files['fileID'] = $i;
				$files['fileName'] = $item;
				echo $i . " " . $item . br();
				$i++;
			}
		}
		return $files;
	}
}
?>