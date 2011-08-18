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

        <br/><br />
	        <form name="divisions" method="post" action="<?php echo site_url();?>/search/searchRecordTypes" />
				<!--<label for='divisions'>Divisions:</label><br />-->
				<select id='divisions' name='divisionID' size='1' onChange="submit();" class='required'>
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
							
			<form name="departments" method="post" action="<?php echo site_url();?>/search/searchRecordTypes" />
				<!--<label for='departments'>Departments:</label><br />-->
				<select id='departments' name='departmentID' size='1' class='required'>
					<option value=''>Select a Department</option>
					<option value=''>-----------------</option>
					<?php 
						if (!empty($departmentData)) {
							foreach ($departmentData as $id => $departments) {
								echo "<option value='$id'>$departments</option>";
							}
						}
					?>
				</select> <input name="department" type="submit" value="Get Department Record Types" />*
				
			</form>
        	<br /><br /><br />
        	
        	<form name="searchForm" method="post" action="<?php echo site_url();?>/search/searchRecordTypes">
        		<input name="keyword" type="text" size="50" />
        		<input name="search" type="submit" value="Search" />
        	</form>

	
	<?php if(!empty($recordTypes)){ echo $recordTypes; } ?>
