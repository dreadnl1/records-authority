<?php
/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu
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

	$data['title'] = 'Upload File - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>
<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Upload File</a></li>
    </ul>
    <?php
    	$baseUrl = base_url();
    ?>
    <div id="fragment-1">
    	<div class="adminForm">
    	 <h4>*** Delete Header Row before conversion to CSV format ***</h4>
   		<table border=2>
   			<tr><td>Division Name</td><td>Department Name</td></tr>
   			<tr><td>-----</td><td>-----</td></tr>
   		</table>
   		<br /><br />
    	<h3><a href="<?php echo $baseUrl; ?>RA_upload_template.xlsx">Data Template</a> - Downloadable xcel spreadsheet form</h3>
   		<h4>*** Delete Header Row before conversion to CSV format ***</h4>
   		<table border=2>
   			<tr><td>recordCategory</td><td>recordCode</td><td>recordName</td><td>recordDescription</td><td>keywords</td><td>retentionPeriod</td><td>disposition</td><td>retentionDecisions</td><td>retentionNotes</td><td>primaryOwnerOverride</td></tr>
   			<tr><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td></tr>
   		</table>
			<?php 
				echo br(3);
				echo $error;?>
			<?php echo form_open_multipart('upload/do_upload');?>
			<input type="file" name="userfile" size="20" />
			<?php echo br(2);?>
			<input type="submit" value="upload" />
			</form>
		</div>
    </div>
</div>

<?php $this->load->view('includes/adminFooter'); ?>