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
class Upload extends Controller {
	
	public function __construct() {
		parent::Controller();
		
		//admin user must be logged in
		$this->load->model('SessionManager');
		$this->SessionManager->isAdminLoggedIn();
	}
	
	/**
	 * displays upload
	 * 
	 * @access public
	 * @return void  
	 */
	public function index()
	{	
		$this->load->view('admin/forms/uploadForm', array('error' => ' ' ));
	}
	
	public function do_upload()
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|txt';
		$config['remove_spaces'] = TRUE;
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			
			$this->load->view('admin/forms/uploadForm', $error);
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			
			$this->load->view('admin/displays/uploadSuccess', $data);
		}
	}	
}
?>