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
	$data['title'] = 'Department - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Edit Department</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">
		
			<form name="divisions" method="post" action="<?php echo site_url();?>/upkeep/edit" />
				<input name="getDept" type="hidden" value="1" />
				<select id='divisions' name='divisionID' size='1' onChange="submit();" class='required'>
					<option value=''>Select a Division to Retrieve Departments</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($divisions as $id => $divisions) {
							echo "<option value='$id'>$divisions</option>";
						}
					?>
				</select> *
			</form>	
		<br />	
			<form name="department" method="post" action="<?php echo site_url();?>/upkeep/edit" />
				<input name="divisionID" type="hidden" value="<?php if (isset($_POST['divisionID'])) { echo $_POST['divisionID']; } ?>" />
				<input name="editDept" type="hidden" value="1" />
				<select id='departments' name='departmentID' size='1' onChange="submit();" class='required'>
					<option value=''>Select a Department to Edit</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($departments as $id => $departments) {
							echo "<option value='$id'>$departments</option>";
						}
					?>
				</select> *
			</form>	
		<br />	
		<form name="editDepartment" method="post" action="<?php echo site_url();?>/upkeep/update">
			<input name="departmentID" type="hidden" value="<?php if (isset($_POST['departmentID'])) { echo $_POST['departmentID']; } ?>" />
			<input name="departmentName" type="text" size="30" value="<?php if (isset($departmentName)) { echo $departmentName; } ?>" class="required" />
			<input name="submit" type="submit" value="Update" /> 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
				if (isset($_POST['departmentID'])) { 
					$departmentID = $_POST['departmentID'];
					$siteUrl = site_url();
					$deleteUrl = $siteUrl . "/upkeep/delete";
					echo "<a href='$deleteUrl/delDept/$departmentID'  onClick='return confirm(\"Are you sure you want to DELETE this record?\")'>[Delete]</a>";
				}
			 ?>
		</form>
		<?php if (isset($recordUpdated)) { echo $recordUpdated; } ?>
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>