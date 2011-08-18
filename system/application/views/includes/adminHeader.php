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

$this->output->set_header("HTTP/1.0 200 OK");
$this->output->set_header("HTTP/1.1 200 OK");
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
$this->output->set_header("Pragma: no-cache");
 
?>

<html>
<head>
<title>Records Management</title>
	<!-- jquery -->
	<script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.ui.all.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jqueryForm.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.pack.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/adminFormEffects.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/ajax.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.autocomplete.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/submit.js" type="text/javascript"></script>
		 	
	<!-- css -->
	<!--<link rel="stylesheet" href="http://ui.jquery.com/applications/themeroller/css/app_screen.css" type="text/css" media="screen">-->
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui-themeroller.css" type="text/css" media="screen">
	<link rel="stylesheet" href="<?php echo base_url();?>css/admin.css" type="text/css"  />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.autocomplete.css" type="text/css"  />
		
</head>
<body>