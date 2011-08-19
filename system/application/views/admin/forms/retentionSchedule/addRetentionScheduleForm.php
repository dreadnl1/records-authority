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
	$data['title'] = 'Record Series - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>

<?php 
	echo $unitScript;
	echo $unitRadioButtonScript;
	echo $unitRadioButtonCheckScript;
	echo $assocUnitsScript;
	echo $checkAllScript;
	echo $uncheckAllScript;
   	echo $checkDeptScript;
	echo $uncheckDeptScript;
?>

	<div id="tabs">
		<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Record Series</a></li>
        </ul>
       <div id="fragment-1" class="adminForm">
       <br />
		
			<form id="retentionSchedule" method="post" action="<?php echo site_url();?>/retentionSchedule/save">
			<?php 
				// set when this form is triggered by record type form
				if (isset($departmentID)) {
					echo "<input name='departmentTypeID' type='hidden' value='$departmentID' />";
				} 
			?>
			<label for='recordCode'>Record Code:&nbsp;</label><br />
			<input name="recordCode" id="recordCode" type="text" size="40" value="" class='required'/>
			<br /><br />	
								
			<label for='recordName'>Record Name:&nbsp;* (Public)</label>
			<?php  
				if (isset($recordInformation['recordName'])) {
					$recordName = $recordInformation['recordName'];
					echo $recordName; 
					echo "<input name='recordName' type='hidden' value='$recordName' class='required' />";
				} else {
					echo "<br /><input name='recordName' type='text' size='45' value='' class='required' />";
				}
			?>
			
			<br /><br />
										
			<label for='recordDescription'>Record Description:&nbsp;* (Public)</label>
			<?php 
				if (isset($recordInformation['recordDescription'])) {
					$recordDescription = $recordInformation['recordDescription'];
					echo $recordDescription; 
					echo "<div id='hideMe'>";
					echo "<textarea name='recordDescription' rows='3' cols='50' wrap='hard' class='required'>$recordDescription</textarea>";
					echo "</div>";
				} else {
					echo "<br /><textarea name='recordDescription' rows='3' cols='50' wrap='hard' class='required'></textarea>";
				}
			?>
			
			<br /><br />
							
			<label for='recordCategory'>Functional Category:&nbsp;* (Public)</label><br />
			<?php 
				if (isset($recordInformation['recordCategory'])) {
					$recordCategory = $recordInformation['recordCategory']; 
					echo $recordCategory;  
					echo "<input name='recordCategory' type='hidden' value='$recordCategory' class='required' />";
				} else {
					echo "<br /><select name='recordCategory' size='1' class='required'>";
					echo "<option value=''>Select a Record Category</option>";
					echo "<option value=''>-----------------</option>";
						foreach ($recordCategories as $recordCategory) {
							echo "<option value='$recordCategory'>$recordCategory</option>";
						}
					echo "</select>"; 
				}
			?>
			<br /><br />
			
			<label for='keywords'>Keywords:&nbsp;* (Public)</label><br />
			<textarea name="keywords" id="keywords" rows="10" cols="20" wrap="hard"></textarea>
			<br /><br />
			
			<label for='retentionPeriod'>Retention Period:&nbsp; (Public)</label><br />
			<textarea name="retentionPeriod" id="retentionPeriod" rows="3" cols="50" wrap="hard"></textarea>
			<!-- <input name="retentionPeriod" id="retentionPeriod" type="text" size="40" value="" />-->
			<br /><br />
			
			<!-- <label for='retentionNotes'>Retention Notes: (Public)</label><br />
			<textarea name="retentionNotes" rows="3" cols="50" wrap="hard"></textarea>
			<br /><br />-->
			
			<label for='rmRetentionDecisions'>Rm Retention Decisions:</label><br />
			<textarea name="retentionDecisions" rows="3" cols="50" wrap="hard"></textarea>
			<br /><br />
			
			<label for='disposition'>Retention Rule:&nbsp;* (Public)</label><br />
			<input id="dispositions" name='disposition' type='text' size='45' value='' class='required' />				
			<?/*<p>
				<select id='dispositions' name='disposition' size='1' class='required'> 
				<option value='' selected='selected'>Select a disposition</option>
				<option value=''>--------------------</option>
				<?php
					foreach ($dispositions as $disposition) { // $dispositionID =>
						echo "<option value='$disposition'>$disposition</option>";
					}
				?>
				</select>
			</p>*/?>
						
			<div id="dispositionDetails"><!-- disposition details are rendered here --></div>
							
			<br />
			<label for='primaryAuthority'>Primary Authority:</label><br />
				<textarea name="primaryAuthority" rows="3" cols="50" wrap="hard"/></textarea><br />
				<!-- <input name="primaryAuthority" id="primaryAuthority" type="text" size="40" value="" /><br /> -->
			<br />
			
			<label for='primaryAuthorityRetention'>Primary Authority Retention:</label><br />
			<textarea name="primaryAuthorityRetention" rows="3" cols="50" wrap="hard" /></textarea><br />
			<!-- <input name="primaryAuthorityRetention" id="primaryAuthorityRetention" type="text" size="40" value="" /><br /> -->
			<br />
			
			<label for='relatedAuthorities'>Related Authorities:</label><br />
			<textarea name="relatedAuthorities" rows="3" cols="50" wrap="hard" /></textarea><br />			
			<!-- <fieldset>
			<legend>Related Authorities</legend>
				Related Authority:
				<textarea name="relatedAuthorities" rows="3" cols="50" wrap="hard" /></textarea><br />
				<?php /*<input name="relatedAuthorities[]" class="relatedAuthority" type="text" size="40" value="" /><br /><br />
				Related Authority Retention:
				<input name="relatedAuthorityRetention[]" class="relatedAuthorityRetention" type="text" size="40" value="" /><br /><br />
				Related Authority:
				<input name="relatedAuthorities[]" class="relatedAuthority" type="text" size="40" value="" /><br /><br />
				Related Authority Retention:
				<input name="relatedAuthorityRetention[]" class="relatedAuthorityRetention" type="text" size="40" value="" /><br /><br />
				Related Authority:
				<input name="relatedAuthorities[]" class="relatedAuthority" type="text" size="40" value="" /><br /><br />
				Related Authority Retention:
				<input name="relatedAuthorityRetention[]" class="relatedAuthorityRetention" type="text" size="40" value="" /><br /><br />
				Related Authority:
				<input name="relatedAuthorities[]" class="relatedAuthority" type="text" size="40" value="" /><br /><br />
				Related Authority Retention:
				<input name="relatedAuthorityRetention[]" class="relatedAuthorityRetention" type="text" size="40" value="" /><br /><br />
				Related Authority:
				<input name="relatedAuthorities[]" class="relatedAuthority" type="text" size="40" value="" /><br /><br />
				Related Authority Retention:
				<input name="relatedAuthorityRetention[]" class="relatedAuthorityRetention" type="text" size="40" value="" /><br /><br />*/?>
		 	</fieldset> -->
			<br />
								
			<label for='Office of Primary Responsibility'>Primary Owner: (Public)</label><br />
				
				<label for='divisions'></label>
				<select id='divisions' name='divisionID' size='1' class='required'> 
				<option value='' selected='selected'>Select a division</option>
				<option value=''>--------------------</option>
				
				<?php 
					foreach ($divisions as $divisionID => $divisionName) {
						if(isset($recordInformation['managementDivisionID'])) {
							if ($recordInformation['managementDivisionID']  == $divisionID) {
								echo "<option selected='yes' value='$divisionID'>$divisionName</option>";
							} else {
								echo "<option value='$divisionID'>$divisionName</option>";
							}
						} else {
							echo "<option value='$divisionID'>$divisionName</option>";
						}
					}
				?>
				</select>&nbsp;&nbsp;*
				<br />
				
				
				<div id="departments">
				<?php   
					if(isset($recordInformation['managementDepartmentID'])) {
						$oprDepartments = $this->LookUpTablesModel->setDepartments($recordInformation['managementDivisionID']);
							foreach ($oprDepartments as $departmentID => $department) {
								if ($department !== "All Departments") {
									if ($recordInformation['managementDepartmentID'] == $departmentID) {
										$departmentID = $recordInformation['managementDepartmentID'];
										echo "<input name='departmentID' type='radio' value='$departmentID' onClick='checkOpr($departmentID);' checked />$department<br />";	
									} else {
										echo "<input name='departmentID' type='radio' value='$departmentID' onClick='checkOpr($departmentID);' />$department<br />";						
									}
								}
							}
					}
				?>
				</div>
								
			</p>
			<br /><br />
			
			<label for='override'>Override Primary Owner:&nbsp;(In case primary owner is multiple departments or divisions)</label><br />
			<input name="override" type="radio" value="yes" <?php if (isset($recordInformation['override']) && $recordInformation['override'] == "Yes") { echo "checked=\"true\""; }?>/>&nbsp;Yes<br />
			<input name="override" type="radio" value="no" checked="checked" <?php if (isset($recordInformation['override']) && $recordInformation['override'] == "No") { echo "checked=\"true\""; }?> />&nbsp;No<br />
			<br />
			
			<label for='primaryOwnerOverride'>Primary Owner Override:&nbsp;</label><br />
			<textarea name="primaryOwnerOverride" rows="3" cols="50" wrap="hard"></textarea>
			<br /><br />
				
			<label for='associatedUnits'>Associated Units:</label>
			<div id="auContainer">
				<div id="loadingContainer">
					<span id="loading">Loading...</span>
					<span id="checkBox">Saving...</span>
				</div>
				<div id="associatedUnits">	
					<select id='associatedUnitDivisions' name='divisionID' size='36'>
						<option value=''>--------------------</option>
						<?php 
							foreach ($divisions as $divisionID => $divisionName) {
								echo "<option value='$divisionID'>$divisionName</option>";
							}
						?>
						<option value=''>--------------------</option>	
					</select>&nbsp;&nbsp;
				</div>				
				<div id="associatedUnitsResults">Select a division</div> 
			</div> 
			
			<br /><br />
			
			<label for='notes'>RM notes:&nbsp;<br /></label>
			<textarea name="notes" rows="3" cols="50" wrap="hard"></textarea>
			<br /><br />
			
			<label for='vitalRecord'>Vital Record:</label><br />
			<input name="vitalRecord" type="radio" value="yes" <?php if (isset($recordInformation['vitalRecord']) && $recordInformation['vitalRecord'] == "Yes") { echo "checked=\"true\""; }?>/>&nbsp;Yes<br />
			<input name="vitalRecord" type="radio" value="no" checked="checked" <?php if (isset($recordInformation['vitalRecord']) && $recordInformation['vitalRecord'] == "No") { echo "checked=\"true\""; }?> />&nbsp;No<br />
			<br />
			
			<label for='approvedByCounsel'>Approve and Publish:&nbsp;*</label><br />
			<input name="approvedByCounsel" type="radio" value="yes" class="required" />&nbsp;Yes<br />
			<input name="approvedByCounsel" type="radio" value="no" class="required" checked="checked" />&nbsp;No<br />
			<br />
			
			<label for='approvedByCounselDate'>Public Record Series - Approved Date:&nbsp;*</label><br />
				<!-- <input name="approvedByCounselDate" id="approvedByCounselDate" type="text" size="40" value="" /><br />-->
			<div style="width:0%;">
				<script>DateInput('approvedByCounselDate', true, 'YYYY-MM-DD')</script>
			</div>
			<br /><br />
			<br />
				<input name="retentionSchedule" type="submit" value="Create Record Series" />&nbsp;&nbsp;
			</form>
			<br />
	</div>
</div>
<?php $this->load->view('includes/adminFooter'); ?>