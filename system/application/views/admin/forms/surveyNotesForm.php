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
?>

<?php $this->load->view('includes/adminHeader'); ?>

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Survey Notes</a></li>
    </ul>
    
    <div id="fragment-1">
    <div class="adminForm">

	<?php if (!isset($_POST['departmentID'])) { ?>
		<form method="post" action="<?php echo site_url();?>/dashboard/surveyNotesForm" />
			<select name='divisionID' size='1' onChange="submit();" class='required'>
				<option value=''>Select a Division</option>
				<option value=''>-----------------</option>
					<?php 
						foreach ($divisionData as $id => $divisions) {
							echo "<option value='$id'>$divisions</option>";
						}
					?>
			</select> *
		</form>	
									
		&nbsp;&nbsp;	
									
		<form method="post" action="<?php echo site_url();?>/dashboard/surveyNotesForm" />
			<select name='departmentID' size='1' class='required'>
				<option value=''>Select a Department</option>
				<option value=''>-----------------</option>
					<?php 
						if (!empty($departmentData)) {
							foreach ($departmentData as $id => $departments) {
								echo "<option value='$id'>$departments</option>";
							}
						}
					?>
			</select> <input name="department" type="submit" value="Get Department Survey Responses" />*
			<br /><br />
		</form>
		<?php } else {$siteUrl = site_url(); echo "<a href='$siteUrl/dashboard/surveyNotesForm'>Select a new Division/Department</a>";} ?>
			
		<?php if (!empty($surveyResponses)) { ?>
			<form name="surveyNotesForm" method="post" action="<?php echo site_url();?>/dashboard/surveyNotesForm" onSubmit="return submitForm(this.submit), this.submit.disabled=true">
			<input name="departmentID" type="hidden" value="<?php if (!empty($_POST['departmentID'])) { echo $_POST['departmentID'];} ?>" />
		<?php } ?>

		<?php 
			// display survey notes form
			if (isset($surveyResponses)) {
				echo $surveyResponses;
			}

			echo "</form><br />";
	 
			if (isset($_POST['departmentID']) && $surveyResponses !== '<br />No surveys found for the selected department') { 
				$departmentID = $_POST['departmentID']; 
				$url = "dashboard/recordTypeForm/" . $departmentID;
				echo anchor_popup($url, 'Create Record Type', $popUpParams) . "<br />";
			}
		?>
 
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>