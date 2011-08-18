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
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Add Division</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">
		
			<form name="addDivision" method="post" action="<?php echo site_url();?>/upkeep/save">
				<select id='divisions' name='divisions' size='1'>
					<option value=''>Current Divisions</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($divisions as $id => $divisions) {
							echo "<option value='$id'>$divisions</option>";
						}
					?>
				</select>
				<input name="divisionName" type="text" size="30" />
				<input name="submit" type="submit" value="Save" /> 
			</form>
			<?php if (isset($recordSaved)) { echo $recordSaved; } ?>
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>