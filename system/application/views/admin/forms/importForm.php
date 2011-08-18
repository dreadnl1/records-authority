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
	$data['title'] = 'Import - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>
<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Import File</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">
    	<br/><br/>
			<form id="importRetentionSchedules" method="post" action="<?php echo site_url();?>/import/importCSV">
				<select id='files' name='fileID' size='1' class='required'> 
					<option value='' selected='selected'>Select your file</option>
					<option value=''>--------------------</option>
					<?php
						foreach($files as $fileID => $fileName) {
							echo "<option value='$fileID'>$fileName</option>";
						}
						print_r($directory);
					?>
				</select>
				
				<br/><br/>
				
				<input name="importRetentionSchedules" type="submit" value="Import Retention Schedules" /> *<br /><br />
			</form>
			<div id="importScheduleSearchResults"></div>
		</div>
    </div>
</div>

<?php $this->load->view('includes/adminFooter'); ?>