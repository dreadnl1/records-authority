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
	$data['title'] = 'Search Record Types - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>
<?php echo $unitScript; ?>
	<div id="tabs">
		<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Search Record Types</a></li>
        </ul>
       	
		<div id="fragment-1" class="adminForm">
        <br/><br />
			  
			<form id="searchRecordTypes" method="post" action="<?php echo site_url();?>/search/getRecordTypes">
				<select id='divisions' name='divisionID' size='1' class='required'> 
					<option value='' selected='selected'>Select your division</option>
					<option value=''>--------------------</option>
					<?php 
						foreach ($divisions as $divisionID => $divisionName) {
							echo "<option value='$divisionID'>$divisionName</option>";
						}
					?>
				</select>
				
				<br /><br />
								
				<select id='departments' name='departmentID' size='1' class='required'>
					<option value=''>Select your department</option>
				</select>
				&nbsp;&nbsp;
				<input name="searchRecordTypes" type="submit" value="Get Record Types" /> *<br /><br />		
			</form>
        	<div id="recordTypeSearchResults"></div>
    </div>
    </div>  		
<?php $this->load->view('includes/adminFooter'); ?>