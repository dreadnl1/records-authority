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

<?php $this->load->view('includes/adminHeader'); ?>

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Edit Document Type</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">
		
			<form name="docType" method="post" action="<?php echo site_url();?>/upkeep/edit" />
				<select id='docTypes' name='docTypeID' size='1' onChange="submit();" class='required'>
					<option value=''>Select a Document Type to Edit</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($docTypes as $id => $docTypes) {
							echo "<option value='$id'>$docTypes</option>";
						}
					?>
				</select> *
			</form>	
		<br />		
		<form name="editDocType" method="post" action="<?php echo site_url();?>/upkeep/update">
				<input name="docTypeID" type="hidden" value="<?php if (isset($_POST['docTypeID'])) { echo $_POST['docTypeID']; } ?>" />
				<input name="docType" type="text" size="30" value="<?php if (isset($docType)) { echo $docType; } ?>" class="required" />
				<input name="submit" type="submit" value="Update" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
					if (isset($_POST['docTypeID'])) { 
						$docTypeID = $_POST['docTypeID'];
						$siteUrl = site_url();
						$deleteUrl = $siteUrl . "/upkeep/delete";
						echo "<a href='$deleteUrl/delDocType/$docTypeID'  onClick='return confirm(\"Are you sure you want to DELETE this record?\")'>[Delete]</a>";
					}
			 	?>  
			</form>
		<?php if (isset($recordUpdated)) { echo $recordUpdated; } ?>
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>