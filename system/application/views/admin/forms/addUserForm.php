<?php
/**
 * Copyright 2010 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@library.du.edu
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
?>

<?php 
	$data['title'] = 'User - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
	$this->load->model('SessionManager');
	$siteUrl = site_url();
	$loginUrl = $siteUrl . "/login/dashboard";
	
	$isAdmin = $this->SessionManager->isAdmin();
	if (!isset($isAdmin) || $isAdmin != TRUE){
		header("Location: $loginUrl");
		exit();
	}
?>

<div id="tabs">
	<ul>
		<li class="ui-tabs-nav-item"><a href="#fragment-1">Add User</a></li>
	</ul>
	<div id="fragment-1">
		<div class="adminForm">
		
			<form name="addUser" method="post" action="<?php echo site_url();?>/upkeep/save" />
			Username:
			<input name="username" size="16" maxlength="10" type="text"/>
			<br /><br />
			Passcode:
			<input name="passcode" size="16" maxlength="20" type="password"/>
			<br /><br />
			<input name="userdata" type="submit" value="Save" />
			</form>

		</div>
	</div>
</div>

<?php $this->load->view('includes/adminFooter',$data); ?>