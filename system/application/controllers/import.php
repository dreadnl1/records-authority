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

class Import extends Controller {
	
	public function __construct() {
		parent::Controller();
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
		
		$this->load->model('ImportModel');
		$this->uploadDir = $this->config->item('uploadDirectory');
	}
	
	public function index() {
		$siteUrl = site_url();
		$data['files'] = $this->ImportModel->listDirs("./uploads/");
		echo br(3);
		$filePath = "./uploads/products.csv";
		$data['csv'] = $this->ImportModel->csvImport($filePath);
		$this->load->view('admin/forms/importForm', $data);
	}
	
	public function importCSV() {
		if (!empty($_POST['fileID'])) {
			$data['csv'] = $this->ImportModel->csvImport("./uploads/" . $_POST['fileName']);
			echo "Imported!";
		}
		$this->load->view('admin/forms/importForm', $data);
	}
}

?>