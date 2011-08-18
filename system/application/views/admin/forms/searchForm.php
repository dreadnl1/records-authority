<?php
/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu and fernando.reyes@du.edu
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
	$data['title'] = 'Search - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>
<?php echo $unitScript; ?>

	<div id="tabs">
	<div id="setDepartment">Setting Department...</div>
	  	<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Search by Division/Department</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-2">Global Search</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-3">Search Retention Schedules</a></li>
            <!-- <li class="ui-tabs-nav-item"><a href="#fragment-4"><span>Full-Text Search</span></a></li> -->
        </ul>
       <div id="fragment-1" class="adminForm">
        <br/><br />
	        
	        <div class="newSearch1"><a href="#" class="showNewSearchUrl1">New Search</a></div>
	        <div class="searchMenus1">
	        
	        <form name="divisions" method="post" action="<?php echo site_url();?>/search" />
				<!--<label for='divisions'>Divisions:</label><br />-->
				<select id='divisions1' name='divisionID' size='1' onChange="submit();" class='required'>
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
							
			<form name="departments" id="searchRecordTypes" method="post" action="<?php echo site_url();?>/search/searchByDepartment" />
				<?php if (isset($_POST['divisionID'])) { $divisionID = $_POST['divisionID']; echo "<input name='divisionID' type='hidden' value='$divisionID' />"; } ?>
				<!--<label for='departments'>Departments:</label><br />-->
				<select id='departments1' name='departmentID' size='1' class='required'>
					<option value=''>Select a Department</option>
					<option value=''>-----------------</option>
					<?php 
						if (!empty($departmentData)) {
							foreach ($departmentData as $id => $departments) {
								echo "<option value='$id'>$departments</option>";
							}
						}
					?>
				</select> <input name="searchRecordTypes" type="submit" value="Get Department Record Types" /> *
				
			</form>
        	<br /><br />
        	</div>
        	<div id="recordTypeSearchResults"></div>
        </div>
       	
       	
       	
       	
       	<div id="fragment-2" class="adminForm">
        <br/><br />
        	<!--  
        	<form id="searchGlobalRecordTypes" method="post" action="<?php echo site_url();?>/search/globalSearch">
        		<input name="keyword" type="text" size="50" class="required" />
        		<input name="searchGlobalRecordTypes" type="submit" value="Search" /><br/><br />
        	</form>
			<div id="globalRecordTypeSearchResults"></div>
			-->
			<form id="searchRetentionSchedules" method="post" action="<?php echo site_url();?>/search/retentionScheduleAdminSearch">
				<select id='divisions' name='divisionID' size='1' class='required'> 
					<option value='' selected='selected'>Select your division</option>
					<option value=''>--------------------</option>
					<?php 
						foreach ($divisions as $divisionID => $divisionName) {
							echo "<option value='$divisionID'>$divisionName</option>";
						}
					?>
				</select>&nbsp;&nbsp;*
				
				<br /><br />
								
				<select id='departments' name='departmentID' size='1' class='required'>
					<option value=''>Select your department</option>
				</select>&nbsp;&nbsp;*
				&nbsp;&nbsp;
				<input name="searchRetentionSchedules" type="submit" value="Get Retention Schedules" /><br /><br />		
			</form>
        	<div id="retentionScheduleSearchResults"></div>		
		</div>
		
		
		
		<div id="fragment-3" class="adminForm">
        <br/><br />
			<!--  
			<form id="searchRetentionSchedules" method="post" action="<?php echo site_url();?>/search/retentionScheduleAdminSearch">
				<select id='divisions' name='divisionID' size='1' class='required'> 
					<option value='' selected='selected'>Select your division</option>
					<option value=''>--------------------</option>
					<?php 
						foreach ($divisions as $divisionID => $divisionName) {
							echo "<option value='$divisionID'>$divisionName</option>";
						}
					?>
				</select>&nbsp;&nbsp;*
				
				<br /><br />
								
				<select id='departments' name='departmentID' size='1' class='required'>
					<option value=''>Select your department</option>
				</select>&nbsp;&nbsp;*
				&nbsp;&nbsp;
				<input name="searchRetentionSchedules" type="submit" value="Get Retention Schedules" /><br /><br />		
			</form>
        	<div id="retentionScheduleSearchResults"></div>
        	-->
        	<form id="searchGlobalRecordTypes" method="post" action="<?php echo site_url();?>/search/globalSearch">
        		<input name="keyword" type="text" size="50" class="required" />
        		<input name="searchGlobalRecordTypes" type="submit" value="Search" /><br/><br />
        	</form>
			<div id="globalRecordTypeSearchResults"></div>
        </div>
		
		<!--  
		<div id="fragment-4" class="adminForm">
        <br/><br />
        	
		</div>-->
	
<?php $this->load->view('includes/adminFooter'); ?>