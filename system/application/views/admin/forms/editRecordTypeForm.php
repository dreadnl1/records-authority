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

<?php $this->load->view('includes/adminHeader');?>

<script type="text/javascript">
	// validates required fields
	$(document).ready(function() {
		$("#recordInformation").validate({
			rules: {
				recordName: "required",
				recordDescription: "required",
				recordCategory: "required"
			},
			messages: {
				recordName: "required",
				recordDescription: "required",
				recordCategory: "required"	
			}
		});
	});
</script>


<div id="tabs">
		<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Record Information</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-2">Format and Location</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-3"><span>Management</span></a></li>
        </ul>
       <div id="fragment-1" class="adminForm">
       <br />
       <form id="updateRecordInformation" name="recordInformation"  method="post" action="<?php echo site_url();?>/dashboard/updateRecordTypeEditForm"> 
					
			<input name="recordInformationID" type="hidden" value="<?php if (!empty($recordTypeData['recordInformationID'])) { echo $recordTypeData['recordInformationID']; } ?>" /><br />
			
			<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" /><br />	
			
			<input name="recordInformationDivisionID" type="hidden" value="<?php if (!empty($_POST['divisionID'])) {echo $_POST['divisionID']; } ?>" />
						
															
			<label for='recordName'>1.) Record Name:</label><br />
			<input name='recordName' id='recordName' type='text' value='<?php if (!empty($recordTypeData['recordName'])) { echo $recordTypeData['recordName']; } ?>' class="required" />
			<br /><br />
						
			<label for='recordDescription'>2.) Record Description:</label><br />
			<textarea name="recordDescription" rows="3" cols="50" wrap="hard" class="required"><?php if (!empty($recordTypeData['recordDescription'])) { echo $recordTypeData['recordDescription']; } ?></textarea>
			<br /><br />
						
			<label for='recordCategory'>3.) Record Category:</label><br />
			<textarea name="recordCategory" rows="3" cols="50" wrap="hard" class="required"><?php if (!empty($recordTypeData['recordCategory'])) { echo $recordTypeData['recordCategory']; } ?></textarea>
			<br /><br />
						
			<label for='recordNotes'>4.) Records Notes</label><br />
			Notes: (Department Answer)<br />
			<textarea name="recordNotesDeptAnswer" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['recordNotesDeptAnswer'])) { echo $recordTypeData['recordNotesDeptAnswer']; } ?></textarea>
			<br /><br />
			Notes: (Records Management)<br />
			<textarea name="recordNotesRmNotes" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['recordNotesRmNotes'])) { echo $recordTypeData['recordNotesRmNotes']; } ?></textarea>
			<br /><br /><br />
			<input name="updateRecordInformation" type="submit" value="Update" />	
		</form>
		<br /><br />
	</div>
    <div id="fragment-2" class="adminForm">
    <br /><br />
    <form id="updateFormatAndLocation" method="post" action="<?php echo site_url();?>/dashboard/updateRecordTypeEditForm">
		
		<input name="formatAndLocationID" type="hidden" value="<?php if(!empty($recordTypeData['formatAndLocationID'])) {echo $recordTypeData['formatAndLocationID'];} ?>" />			
		<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" /><br />
						
		<label for='electronicRecord'>1.)Is this record created electronically?</label><!--id="showSystem"--><!--id="hideSystem"--><br />
		<?php 
			if (!isset($recordTypeData['electronicRecord']) && $recordTypeData['electronicRecord'] == "yes") { 
				echo "<input name='electronicRecord' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='electronicRecord' type='radio' value='no' />&nbsp;&nbsp;No<br />";
			} 
		
			if (!isset($recordTypeData['electronicRecord']) && $recordTypeData['electronicRecord'] == "no") {
				echo "<input name='electronicRecord' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='electronicRecord' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
			}	
		?>
		
		<br />			
		<label for='system'>Select the system used to create the record</label><br />
			
		<?php 
			
		// refactor...pull from database
		$systems = array('Banner', 'BlackBoard', 'Desktop software', 'Email', 'Portfolio', 'VAGA', 'Other');
				
		if (isset($recordTypeData['system'])) {
			foreach ($systems as $checked) {
				foreach ($recordTypeData['system'] as $checkedValue) {
					if ($checked == $checkedValue) {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
				}
			}
									
			if (isset($recordTypeData['otherText'])) {
				$otherText = $recordTypeData['otherText'];
				echo "<input name='otherText' type='text' value='$otherText' />"; 
				echo "<br />";	
			}
							
			foreach ($systems as $notChecked) {
				if (!in_array($notChecked, $recordTypeData['system'])) {
					echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
				}					
			}
		}
		?>
		
		<br /><br />
						
		<label for='paperVersion'>2.) Does a paper version of the final record exist?</label><br />
		<?php
			if (isset($recordTypeData['paperVersion']) && $recordTypeData['paperVersion'] == "yes") { 
				echo "<input name='paperVersion' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='paperVersion' type='radio' value='no' />&nbsp;&nbsp;No<br />";
			} 
			
			if (isset($recordTypeData['paperVersion']) && $recordTypeData['paperVersion'] == "no") {
				echo "<input name='paperVersion' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='paperVersion' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
			}	
		?>
		<br />
		<label for='paperVersion'>Where are the paper records stored?</label><br />
		<?php
			if (isset($recordTypeData['location'])) {
					
				$recordStorage = array('Within the department', 'Other DU building', 'Offsite storage');
				
				foreach ($recordTypeData['location'] as $checkedValue) {
					echo "<input name='location[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					if (isset($recordTypeData['otherBuilding'])) {
						$otherBuilding = $recordTypeData['otherBuilding'];
						echo "<input name='otherBuilding' type='text' value='$otherBuilding' /><br />";
					}
					if (isset($recordTypeData['otherStorageText'])) {
						$otherStorageText = $recordTypeData['otherStorageText'];
						echo "<input name='otherStorageText' type='text' value='$otherStorageText' /><br />";
					}
				}
			
				foreach ($recordStorage as $notChecked) {
					if (!in_array($notChecked, $recordTypeData['location'])) {
						echo "<input name='location[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
				}
			}
		?>
		
		<br /><br />
						
		<label for='finalRecordExist'>3.) Does an electronic version of the final record exist?</label><br />
		<?php
			if (!empty($recordTypeData['finalRecordExist']) && $recordTypeData['finalRecordExist'] == "yes") { 
				echo "<input name='finalRecordExist' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='finalRecordExist' type='radio' value='no' />&nbsp;&nbsp;No<br />";
			} 
			
			if (!empty($recordTypeData['finalRecordExist']) && $recordTypeData['finalRecordExist'] == "no") {
				echo "<input name='finalRecordExist' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='finalRecordExist' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
			}	
		?>
		<br />					
		<?php 
			$recordLocations = array('ADR', 'Backup media', 'Banner', 'Blackboard', 'Email', 'Local HD', 'Network Drive', 'Portfolio', 'VAGA', 'Other');
			
			foreach ($recordTypeData['recordLocation'] as $checked) {
				if ($checked == 'Backup media' ) {
					$backupMedia = $recordTypeData['backupMedia'];
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
					echo "<input name='backupMedia' type='text' value='$backupMedia' />"; 
					echo "<br />";	
				}	
				if ($checked == 'Other') {
					$otherRecordLocation = $recordTypeData['otherRecordLocation'];
					echo "<input name='otherRecordLocation' type='text' value='$otherRecordLocation' />"; 
					echo "<br />";	
				} elseif ($checked !== 'Backup media' && $checked !== 'Other') {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";	
				}
			}
			
			foreach ($recordLocations as $notChecked) {
				if (!in_array($notChecked, $recordTypeData['recordLocation'])) {
					echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
				}					
			}
				
		?>
		<br /><br />
								
		<label for='fileFormat'>4.) Electronic File Format</label><br />
		<?php $fileFormat = $recordTypeData['fileFormat']; ?>
		<input name="fileFormat" type="text" id="fileFormat" value='<?php echo $fileFormat; ?>' size="25" />
		<br /><br />
						
		<label for='formatAndLocationNotes'>5.) Format and Location Notes</label><br />
		Notes: (Department Answer)<br />
		<textarea name="formatAndLocationDeptAnswer" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['formatAndLocationDeptAnswer']; ?></textarea>
		<br /><br />
		Notes: (Records Management)<br />
		<textarea name="formatAndLocationRmNotes" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['formatAndLocationRmNotes']; ?></textarea>
		<br /><br /><br />
		<input name="updateFormatAndLocation" type="submit" value="Update" />	
	</form>	
	<br /><br />	
	</div>
	<div id="fragment-3" class="adminForm">
	<br /><br />
    	<form id="updateManagement" method="post" action="<?php echo site_url();?>/dashboard/updateRecordTypeEditForm">
					
			<input name="managementID" type="hidden" value="<?php if(!empty($recordTypeData['formatAndLocationID'])) {echo $recordTypeData['formatAndLocationID'];} ?>" />			
			<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" /><br />
								
				<div class="ui-accordion-sections">
					Access and Use<br /><br />
				</div>
				<br />
					
				<label for='accessAndUse'>1.) Who needs access to these records? Who uses them?</label><br />
				Notes: (Department Answer)<br />
				<textarea name="accessAndUseDeptAnswer" id="accessAndUse" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['accessAndUseDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="accessAndUseRmNotes" id="accessAndUse" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['accessAndUseRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
				Active Period<br />
				</div>
				<br />
				2.) How long are the records active?&nbsp;&nbsp;<input name="yearsActive" type="text" size="3" value="<?php echo $recordTypeData['yearsActive'] ?>" />&nbsp;&nbsp;Years<br /><br />
				3.) How long do the records need to be immediately available to the department?&nbsp;&nbsp;<input name="yearsAvailable" type="text" size="3" value="<?php echo $recordTypeData['yearsAvailable'] ?>" />&nbsp;&nbsp;Years<br /><br />
						
				<label for='activePeriodNotes'>4.) Format and Location Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="activePeriodDeptAnswer" id="activePeriodNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['activePeriodDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="activePeriodRmNotes" id="activePeriodNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['activePeriodRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Retention Period
				</div>
				<br />
				5.) How long are the records currently kept?&nbsp;&nbsp;<input name="yearsKept" type="text" size="3" value="<?php echo $recordTypeData['yearsKept']; ?>" />&nbsp;&nbsp;Years<br /><br />
						
				<label for='retentionPeriod'>6.) Retention Period Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="retentionPeriodDeptAnswer" id="retentionPeriod" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['retentionPeriodDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="retentionPeriodRmNotes" id="retentionPeriod" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['retentionPeriodRmNotes']; ?></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Custodian
				</div>
				<br />
				7.) What department is the custodian of these records?<br /><br />
				
				<select id='managementDivisions' name='managementDivisionID' size='1' class='required'>
					<option value=''>Select a Division</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($divisionData as $id => $divisions) {
							if ($recordTypeData['managementDivisionID'] == $id) {
								echo "<option selected='yes' value='$id'>$divisions</option>";
							} else {
								echo "<option value='$id'>$divisions</option>";
							}
						}
					?>
				</select> 
				&nbsp;&nbsp;
				<?php
					// get department
					$departmentID = $recordTypeData['managementDepartmentID'];
					echo $departmentID;
					$this->load->model('LookUpTablesModel');
					$department = $this->LookUpTablesModel->getDepartment($departmentID);
							
					echo "<select id='managementDepartments' name='managementDepartmentID' size='1'>";
					echo "<option value='$departmentID'>$department</option>";
					echo "</select>"; 
					echo "<br /><br />";
				?>		
				
				<label for='custodian'>8.) Custodian Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="custodianDeptAnswer" id="custodian" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['custodianDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="custodianRmNotes" id="custodian" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['custodianRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Legal Requirements
				</div>
				<br />
					
				<label for='legislationGovernRecords'>9.) Does legislation govern the retention of these records?</label><br />
				<?php 
					if (!empty($recordTypeData['legislationGovernRecords']) && $recordTypeData['legislationGovernRecords'] == "yes") { 
						echo "<input name='legislationGovernRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='legislationGovernRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['legislationGovernRecords']) && $recordTypeData['legislationGovernRecords'] == "no") {
						echo "<input name='legislationGovernRecords' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='legislationGovernRecords' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
				
				What legislation?&nbsp;&nbsp;<input name="legislation" type="text" size="40" value="<?php echo $recordTypeData['legislation']; ?>" /><br /><br />
				For how long?&nbsp;&nbsp;<input name="legislationHowLong" type="text" size="3" value="<?php echo $recordTypeData['legislationHowLong']; ?>" />&nbsp;&nbsp;Years<br /><br />
				
				<br />
						
				<label for='legalRequirmentsNotes'>10.) Legal Requirements Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="legalRequirmentsDeptAnswer" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['legalRequirmentsDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="legalRequirmentsRmNotes" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['legalRequirmentsRmNotes']; ?></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Standards and Best Practices
				</div>
				<br />
				11.) What other authorities govern retention of this series?<br /><br />
				12.) Are there any standards or best practices that make retention suggestions about the material?<br /><br />
						
				<label for='standardsAndBestPracticesNotes'>13.) Standards and Best Practices Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="standardsAndBestPracticesDeptAnswer" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['standardsAndBestPracticesDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="standardsAndBestPracticesRmNotes" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['standardsAndBestPracticesRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Destruction
				</div>
				<br />
					
				<label for='destroyRecords'>14.) Do you ever destroy these records?</label><br />
				<!--
				<input name="destroyRecords" type="radio" value="yes" id="showDestroyRecords" />&nbsp;&nbsp;Yes<br />
				<input name="destroyRecords" type="radio" value="no" id="hideDestroyRecords" />&nbsp;&nbsp;No<br />
				-->
				<?php 
					if (!empty($recordTypeData['destroyRecords']) && $recordTypeData['destroyRecords'] == "yes") { 
						echo "<input name='destroyRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='destroyRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['destroyRecords']) && $recordTypeData['destroyRecords'] == "no") {
						echo "<input name='destroyRecords' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='destroyRecords' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
		
				If so, how often?<br />
				Every&nbsp;&nbsp;<input name="howOftenDestruction" type="text" size="3" value="<?php echo $recordTypeData['howOftenDestruction']; ?>" />&nbsp;&nbsp;years<br /><br />
				How are records destroyed?<br />
				<input name="howAreRecordsDestroyed" type="text" value="<?php echo $recordTypeData['howAreRecordsDestroyed']; ?>" /><br />
				
				<br />
						
				<label for='destructionNotes'>15.) Destruction Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="destructionDeptAnswer" id="destructionNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['destructionDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="destructionRmNotes" id="destructionNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['destructionRmNotes']; ?></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Transfer to Archives
				</div>
				<br />
				<label for='transferToArchives'>16.) Do you ever send records in this series to the University archives?</label><br />
				<!--
				<input name="transferToArchives" type="radio" value="yes" id="showTransferToArchives" />&nbsp;&nbsp;Yes<br />
				<input name="transferToArchives" type="radio" value="no" id="hideTransferToArchives" />&nbsp;&nbsp;No<br /><br />
				-->
				<?php 
					if (!empty($recordTypeData['transferToArchives']) && $recordTypeData['transferToArchives'] == "yes") { 
						echo "<input name='transferToArchives' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='transferToArchives' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['transferToArchives']) && $recordTypeData['transferToArchives'] == "no") {
						echo "<input name='transferToArchives' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='transferToArchives' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
					
				If so, how often?<br />
				Every&nbsp;&nbsp;<input name="howOftenArchive" type="text" size="3" value="<?php echo $recordTypeData['howOftenArchive']; ?>" />&nbsp;&nbsp;years<br /><br />
						
				<label for='transferToArchivesNotes'>17.) Destruction Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="transferToArchivesDeptAnswer" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['transferToArchivesDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="transferToArchivesRmNotes" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['transferToArchivesRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Vital Records
				</div>
				<br />
				Include definition of vital records on form: "Emergency operation records immediately necessary to begin recovery of business after a disaster, as well as rights-and-interests records necessary to protect the assests, obligations, and resources of the organization, as well as its employees and customers or citizens; essential records."
				<br /><br />
					
				<label for='vitalRecords'>18.) Are these records vital records?</label><br />
				<!--
				<input name="vitalRecords" type="radio" value="yes" id="showVitalRecords" />&nbsp;&nbsp;Yes<br />
				<input name="vitalRecords" type="radio" value="no" id="hideVitalRecords" />&nbsp;&nbsp;No<br />
				-->
				<?php 
					if (!empty($recordTypeData['vitalRecords']) && $recordTypeData['vitalRecords'] == "yes") { 
						echo "<input name='vitalRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='vitalRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['vitalRecords']) && $recordTypeData['vitalRecords'] == "no") {
						echo "<input name='vitalRecords' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='vitalRecords' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
					
				<label for='manageVitalRecords'>How does the department manage vital records?</label><br />
				<textarea name="manageVitalRecords" id="manageVitalRecords" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['manageVitalRecords']; ?></textarea>
				<br />		
				<br />
						
				<label for='vitalRecordsNotes'>19.) Vital Records Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="vitalRecordsDeptAnswer" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['vitalRecordsDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="vitalRecordsRmNotes" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['vitalRecordsRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Sensitive Information
				</div>
				<br />
					
				<label for=''>20.) Do the records contain sensitive, confidential or personally idenfifiable information?</label><br />
				<!--
				<input name="sensitiveInformation" type="radio" value="yes" id="showSensitiveInformation" />&nbsp;&nbsp;Yes<br />
				<input name="sensitiveInformation" type="radio" value="no" id="hideSensitiveInformation" />&nbsp;&nbsp;No<br />
				-->
				<?php 
					if (!empty($recordTypeData['sensitiveInformation']) && $recordTypeData['sensitiveInformation'] == "yes") { 
						echo "<input name='sensitiveInformation' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='sensitiveInformation' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['sensitiveInformation']) && $recordTypeData['sensitiveInformation'] == "no") {
						echo "<input name='sensitiveInformation' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='sensitiveInformation' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
						
				<label for='describeInformation'>Describe the Information</label><br />
				<textarea name="describeInformation" id="describeInformation" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['describeInformation']; ?></textarea>	
				<br />
										
				<br />
						
				<label for='sensitiveInformationNotes'>21.) Sensitive Information Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="sensitiveInformationDeptAnswer" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['sensitiveInformationDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="sensitiveInformationRmNotes" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['sensitiveInformationRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Security
				</div>
				<br />
					
				<label for='secureRecords'>22.) Do the records need to be secure?</label><br />
				<!--
				<input name="secureRecords" type="radio" value="yes" id="showSecureRecords" />&nbsp;&nbsp;Yes<br />
				<input name="secureRecords" type="radio" value="no" id="hideSecureRecords" />&nbsp;&nbsp;No<br />
				-->
				<?php 
					if (!empty($recordTypeData['secureRecords']) && $recordTypeData['secureRecords'] == "yes") { 
						echo "<input name='secureRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='secureRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['secureRecords']) && $recordTypeData['secureRecords'] == "no") {
						echo "<input name='secureRecords' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='secureRecords' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
				
						
				<div id="security">
					<label for='describeSecurityRecords'>Describe any security in place on the records</label><br />
					<textarea name="describeSecurityRecords" id="describeSecurityRecords" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['describeSecurityRecords']; ?></textarea>	
					<br />
				</div>
						
				<br />
						
				<label for='securityNotes'>23.) Security Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="securityDeptAnswer" id="securityNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['securityDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="securityRmNotes" id="securityNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['securityRmNotes']; ?></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Duplication
				</div>
				<br />
					
				<label for='duplication'>24.) Are these records duplicated in other departments?</label><br />
				<!--
				<input name="duplication" type="radio" value="yes" id="showDuplication" />&nbsp;&nbsp;Yes<br />
				<input name="duplication" type="radio" value="no" id="hideDuplication" />&nbsp;&nbsp;No<br />
				-->
				<?php 
					if (!empty($recordTypeData['duplication']) && $recordTypeData['duplication'] == "yes") { 
						echo "<input name='duplication' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='duplication' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (!empty($recordTypeData['duplication']) && $recordTypeData['duplication'] == "no") {
						echo "<input name='duplication' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='duplication' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
				<br />		
				Which departments?<br />
				<?php
					if (isset($recordTypeData['duplicationDivisionID']) && is_array($recordTypeData['duplicationDivisionID'])) { 
						$arraySize = count($recordTypeData['duplicationDivisionID']);
						$i = 0;
						while ($i < $arraySize) {
							echo "<select id='duplicationDivisions' name='duplicationDivisionID[]' size='1'>";
							echo "<option value=''>Select a Division</option>";
							echo "<option value=''>-----------------</option>";
							foreach ($divisionData as $id => $divisions) {
								if ($recordTypeData['duplicationDivisionID'][$i] == $id) {
										echo "<option selected='yes' value='$id'>$divisions</option>";
								} else {
									echo "<option value='$id'>$divisions</option>";
								}
							}
							echo "</select>";
							
							echo "&nbsp;&nbsp;"; 
							// get department
							$departmentID = $recordTypeData['duplicationDepartmentID'][$i];
							$this->load->model('LookUpTablesModel');
							$department = $this->LookUpTablesModel->getDepartment($departmentID);
							
							echo "<select id='duplicationDepartments' name='duplicationDepartmentID[]' size='1'>";
							echo "<option value='$departmentID'>$department</option>";
							echo "</select>"; 
							echo "<br /><br />";
		
						++$i;	
						}
					}
				?>
												
				<br />
					
				<label for='duplicationNotes'>26.) Duplication Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="duplicationDeptAnswer" id="duplicationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['duplicationDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="duplicationRmNotes" id="duplicationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['duplicationRmNotes']; ?></textarea>
				<br /><br />	
				<br />
				<input name="updateManagement" type="submit" value="Update" />	
			</form>
			<br /><br />
        </div>
</div>