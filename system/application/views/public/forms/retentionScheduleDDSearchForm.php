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
 * 
 * -- remove DU specific source code (CSS) before release --
 **/
?>

<?php $this->load->view("includes/searchHeader"); echo $unitScript; ?>
	
	<div id="searchContent">
		<h3 id="title">Search the Records Retention Schedule</h3>
		<br />
		<div id="searchByDepartmentForm">
			<div class="searchFormByDepartment">
				<form id="divDeptSearchRetentionSchedules" method="post" action="<?php echo site_url();?>/du/searchByDepartment">
					<select id='divisions' name='divisionID' size='1' class='required'> 
						<option value='' selected='selected'>Select a division</option>
						<option value=''>--------------------</option>
						<?php 
							foreach ($divisions as $divisionID => $divisionName) {
								echo "<option value='$divisionID'>$divisionName</option>";
							}
						?>
					</select>
					
					<select id='departments' name='departmentID' size='1' class='required'>
						<option value=''>Select a department</option>
					</select>
					&nbsp;&nbsp;
					<br /><br />
					<input name="searchRetentionSchedules" type="submit" value="Search" />
					<span class="searchByDept-div">
						<a href="<?php echo site_url();?>/du/retentionSchedules">Browse By Record Category</a>
						<br />
						<a href="<?php echo site_url();?>/du/retentionSchedules/fullText">Full-Text Search</a>	
					</span>
				<br />
				</form>
			</div>
		</div>
	</div>
	<div id="divDeptRetentionScheduleSearchResults"></div>
	
<?php $this->load->view("includes/footer"); ?>