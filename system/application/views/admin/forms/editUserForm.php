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
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Edit User</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">
		
			<form name="department" method="post" action="<?php echo site_url();?>/upkeep/edit" />
				<select id='users' name='userID' size='1' onChange="submit();" class='required'>
					<option value=''>Select a User to Edit</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($users as $id => $users) {
							echo "<option value='$id'>$users</option>";
						}
					?>
				</select> *
			</form>	
		<br />		
		<form name="editUser" method="post" action="<?php echo site_url();?>/upkeep/update">
				<input name="userID" type="hidden" value="<?php if (isset($_POST['userID'])) { echo $_POST['userID']; } ?>" />
				<input name="username" type="text" size="16" maxlength="10" value="<?php if (isset($user)) { echo $user; } ?>" class="required" />
				<input name="submit" type="submit" value="Update" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
					if (isset($_POST['userID'])) { 
						$userID = $_POST['userID'];
						$siteUrl = site_url();
						$deleteUrl = $siteUrl . "/upkeep/delete";
						echo "<a href='$deleteUrl/delUser/$userID'  onClick='return confirm(\"Are you sure you want to DELETE this user?\")'>[Delete]</a>";
					}
			 	?>  
			</form>
		<?php if (isset($recordUpdated)) { echo $recordUpdated; } ?>
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>