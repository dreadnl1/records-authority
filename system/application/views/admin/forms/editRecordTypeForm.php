<?php
/**
 * Copyright 2008 University of Denver--Penrose Library--University Records Management Program
 * Author fernando.reyes@du.edu
 * Edited and updated 2010
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
?>

<?php 
	$data['title'] = 'Record Inventory - Records Authority';
	
	$var1 = $recordTypeData['timestamp'];
	$var2 = mysql_to_unix($var1);
	$creation = unix_to_human($var2);
	$data['timestamp'] = $creation;
	
	$data['updateTimestamp'] = $recordTypeData['updateTimestamp'];
	$this->load->view('includes/adminHeader', $data); 
?>

<?php 
	echo $jQuery;
	echo $jQueryDeptWidget;
 	//echo $jQueryDeptMasterCopyWidget;
	//echo $jQueryDeptDuplicationWidget; 
?>

<div id="tabs">
		<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Record Inventory</a></li>
            <!-- <li class="ui-tabs-nav-item"><a href="#fragment-2">Format and Location</a></li>-->
            <!-- <li class="ui-tabs-nav-item"><a href="#fragment-3"><span>Management</span></a></li>-->
        </ul>
       <div id="fragment-1" class="adminForm">
       <br />
<!-- Begin Form -->
       <form id="updateRecordInformation" name="recordInformation"  method="post" action="<?php echo site_url();?>/recordType/updateRecordTypeEditForm"> 
			<input name="updateRecordInformation" type="submit" value="Update" /><br />		
			<input name="recordInformationID" type="hidden" value="<?php if (!empty($recordTypeData['recordInformationID'])) { echo $recordTypeData['recordInformationID']; } ?>" />
			<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" />
			<input name="recordInformationDivisionID" type="hidden" value="<?php if (!empty($_POST['divisionID'])) {echo $_POST['divisionID']; } ?>" />

<!-- Question 1 -->				
			<label for='recordName'>1.) Record Name:</label><br />
			<input name="recordName" id="recordName" type="text" value="<?php if (!empty($recordTypeData['recordName'])) { echo $recordTypeData['recordName']; } ?>" class="required" />
			<br /><br />
<!-- Question 2 -->						
			<label for='recordDescription'>2.) Record Description:</label><br />
			<textarea name="recordDescription" rows="3" cols="50" wrap="hard" class="required"><?php if (!empty($recordTypeData['recordDescription'])) { echo $recordTypeData['recordDescription']; } ?></textarea>
			<br /><br />
<!-- Question 3 -->									
			3.) Functional Category<br /><br />
				
				<select name='recordCategory' size='1' class='required'>
					<option value=''>Select a Functional Category</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($recordCategories as $recordCategory) {
							if ($recordTypeData['recordCategory'] == $recordCategory) {
								echo "<option selected='yes' value='$recordCategory'>$recordCategory</option>";
							} else {
								echo "<option value='$recordCategory'>$recordCategory</option>";
							}
						}
					?>
				</select> 
			 
			<br /><br />
<!-- Question 4 -->	
			<label for='Office of Primary Responsibility'>4.) What department is the Primary Owner for these records?</label>
			<br />
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
					$this->load->model('LookUpTablesModel');
					$department = $this->LookUpTablesModel->getDepartment($departmentID);
							
					echo "<select id='managementDepartments' name='managementDepartmentID' size='1'>";
					echo "<option value='$departmentID'>$department</option>";
					echo "</select>"; 
					echo "<br /><br />";
				?>		
<!-- Question 5 -->		
			<label for='recordNotes'>4.) Records Notes</label><br />
			Notes: (Department Answer)<br />
			<textarea name="recordNotesDeptAnswer" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['recordNotesDeptAnswer'])) { echo $recordTypeData['recordNotesDeptAnswer']; } ?></textarea>
			<br /><br />
			Notes: (Records Management)<br />
			<textarea name="recordNotesRmNotes" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['recordNotesRmNotes'])) { echo $recordTypeData['recordNotesRmNotes']; } ?></textarea>
			<br /><br /><br />	
		<br />
<!-- Question 6 -->
			<label for='recordFormat'>6.) What is the format of the official record?</label><br />
			<input name="recordFormat" type="radio" value="Paper" <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Paper") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Paper<br />
			<input name="recordFormat" type="radio" value="Banner"  <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Banner") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Banner Database Record<br />
			<input name="recordFormat" type="radio" value="Electronic Document" <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Electronic Document") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Electronic Document<br />
			<input name="recordFormat" type="radio" value="Email" <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Email") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Email<br />
			<input name="recordFormat" type="radio" value="Other Physical" id="toggleOtherPhysical" <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Other Physical") { echo "checked=\"true\""; } ?> />&nbsp;&nbsp;Other Physical: (enter other type)<br />
			
	<?php /*
			if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Paper") { 
				echo "<input name='recordFormat' type='radio' value='Paper' checked  />&nbsp;&nbsp;Paper<br /> ";
				echo "<input name='recordFormat' type='radio' value='Banner'  />&nbsp;&nbsp;Banner Database Record<br />";
				echo "<input name='recordFormat' type='radio' value='Electronic Document'  />&nbsp;&nbsp;Electronic Document<br />";
				echo "<input name='recordFormat' type='radio' value='Email' />&nbsp;&nbsp;Email<br />";
				echo "<input name='recordFormat' type='radio' value='Other Physical' id='toggleOtherPhysical' />&nbsp;&nbsp;Other Physical: (enter other type)<br />";
				echo "<input name='recordFormat' type='radio' value='Other Electronic' id='toggleOtherElectronic' />&nbsp;&nbsp;Other Electronic: (enter other type)<br />";
			}	*/		
	?>			
			
			<div id="<?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Other Physical") { echo "showOtherPhysical"; } else { echo "otherPhysicalText"; }?>">
				<input name="otherPhysicalText" type="text" value="<?php if (!empty($recordTypeData['otherPhysicalText'])) { echo $recordTypeData['otherPhysicalText']; } ?>" />
			</div>
			
			<input name="recordFormat" type="radio" value="Other Electronic" id="toggleOtherElectronic" <?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Other Electronic") { echo "checked=\"true\""; } ?> />&nbsp;&nbsp;Other Electronic: (enter other type)<br />
			
			<div id="<?php if (isset($recordTypeData['recordFormat']) && $recordTypeData['recordFormat'] == "Other Electronic") { echo "showOtherElectronic"; } else { echo "otherElectronicText"; }?>">
				<input name="otherElectronicText" type="text" value="<?php if (!empty($recordTypeData['otherElectronicText'])) { echo $recordTypeData['otherElectronicText']; } ?>" />
			</div>
			
			<br /><br />			
<!-- Question 7 -->
			<label for='recordStorage'>7.) Where is the record stored?</label><br />
			<input name="recordStorage" type="radio" value="Physical storage in department" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Physical storage in department") { echo "checked=\"true\""; }?>/>&nbsp;&nbsp;Physical Storage in department<br />
			<input name="recordStorage" type="radio" value="Physical storage in other DU building" id="toggleOtherDUBuilding" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Physical storage in other DU building") { echo "checked=\"true\""; } ?>/>&nbsp;&nbsp;Physical Storage in other DU building (enter other type)<br />
			
			<div id="<?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Physical storage in other DU building") { echo "showDUBuildingText"; } else { echo "otherDUBuildingText"; }?>">
				<input name="otherDUBuildingText" type="text" value="<?php if (!empty($recordTypeData['otherDUBuildingText'])) { echo $recordTypeData['otherDUBuildingText']; } ?>"/>
			</div>			
		
			<input name="recordStorage" type="radio" value="Physical offsite storage" id="toggleOffsiteStorage" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Physical offsite storage") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Physical offsite storage (enter other type)<br />
				
			<div id="<?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Physical offsite storage") { echo "showOffsiteStorageText"; } else { echo "otherOffsiteStorageText"; }?>">
				<input name="otherOffsiteStorageText" type="text" value="<?php if (!empty($recordTypeData['otherOffsiteStorageText'])) { echo $recordTypeData['otherOffsiteStorageText']; } ?>" />
			</div>					
			
			<input name="recordStorage" type="radio" value="Banner" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Banner") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Banner<br />
			<input name="recordStorage" type="radio" value="Peak Digital"  <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Peak Digital") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Peak Digital<br />
			<input name="recordStorage" type="radio" value="DU Networked Computer/Server" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "DU Networked Computer/Server") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;DU Networked Computer/Server<br />
			<input name="recordStorage" type="radio" value="Local HD"  <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Local HD") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Local HD<br />
			<input name="recordStorage" type="radio" value="Other electronic system" id="toggleOtherElectronicSystem" <?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Other electronic system") { echo "checked=\"true\""; }?>/>&nbsp;&nbsp;Other electronic system (enter other type)<br />
				
			<div id="<?php if (isset($recordTypeData['recordStorage']) && $recordTypeData['recordStorage'] == "Other electronic system") { echo "showElectronicSystemText"; } else { echo "otherElectronicSystemText"; }?>">
				<input name="otherElectronicSystemText" type="text" value="<?php if (!empty($recordTypeData['otherElectronicSystemText'])) { echo $recordTypeData['otherElectronicSystemText']; } ?>"/>
			</div>	
					
			<br /><br />
<!-- Question 8 -->
			<label for='formatAndLocationNotes'>8.) Format and Location Notes</label><br />
			Notes: (Department Answer)<br />
			<textarea name="formatAndLocationDeptAnswer" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard" ><?php if (!empty($recordTypeData['formatAndLocationDeptAnswer'])) { echo $recordTypeData['formatAndLocationDeptAnswer']; } ?></textarea>
			<br /><br />
			Notes: (Records Management)<br />
			<textarea name="formatAndLocationRmNotes" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard" ><?php if (!empty($recordTypeData['formatAndLocationRmNotes'])) { echo $recordTypeData['formatAndLocationRmNotes']; } ?></textarea>
			<br /><br />
<!-- Question 9 -->
			<label for='recordRetention'>9.) How long are the records currently retained?</label><br />
			<textarea name="recordRetentionAnswer" id="recordRetention" rows="3" cols="50" wrap="hard" ><?php if (!empty($recordTypeData['recordRetentionAnswer'])) { echo $recordTypeData['recordRetentionAnswer']; } ?></textarea>
			<br /><br />
<!-- Question 10 -->
			<label for='usageNotes'>10.) Usage Notes</label><br />
			<textarea name="usageNotesAnswer" id="usageNotes" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['usageNotesAnswer'])) { echo $recordTypeData['usageNotesAnswer']; } ?></textarea>
			<br /><br />
<!-- Question 11 -->
			<label for='retentionAuthorities'>11.) Retention Authorities</label><br />
			<textarea name="retentionAuthoritiesAnswer" id="retentionAuthorities" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['retentionAuthoritiesAnswer'])) { echo $recordTypeData['retentionAuthoritiesAnswer']; } ?></textarea>
			<br /><br />
<!-- Question 12 -->
			<label for='vitalRecord'>12.) Is this a vital record?</label><br />
			<input name="vitalRecord" type="radio" value="Yes" <?php if (isset($recordTypeData['vitalRecord']) && $recordTypeData['vitalRecord'] == "Yes") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;Yes<br />
			<input name="vitalRecord" type="radio" value="No" <?php if (isset($recordTypeData['vitalRecord']) && $recordTypeData['vitalRecord'] == "No") { echo "checked=\"true\""; }?> />&nbsp;&nbsp;No<br />
			<br /><br />
<!-- Question 13 -->
			<label for='vitalRecordNotes'>13.) Vital Record Notes</label><br />
			<textarea name="vitalRecordNotesAnswer" id="vitalRecordNotes" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['vitalRecordNotesAnswer'])) { echo $recordTypeData['vitalRecordNotesAnswer']; } ?></textarea>
			<br /><br />
<!-- Question 14 -->
			<label for='recordRegulations'>14.)  Do the records contain information that may be subject to any of the following regulations:</label><br />
			<?php 
				$recordRegulations = array('FERPA', 'HIPAA', 'PCI DSS', 'GLBA', 'FCRA/FACTA');		
				if (isset($recordTypeData['recordRegulations'])) {
					foreach ($recordRegulations as $notChecked) {
						if (!in_array($notChecked, $recordTypeData['recordRegulations'])) {
							if ($notChecked == "FERPA") {
								$value1 = FALSE;	
							}
							if ($notChecked == "HIPAA") {
								$value2 = FALSE;
							}
							if ($notChecked == "PCI DSS") {
								$value3 = FALSE;
							}
							if ($notChecked == "GLBA") {
								$value4 = FALSE;
							}
							if ($notChecked == "FCRA/FACTA") {
								$value5 = FALSE;
							}
						}					
					}
					
					foreach ($recordRegulations as $checked) {
						foreach ($recordTypeData['recordRegulations'] as $checkedValue) {
							if ($checked == $checkedValue && $checkedValue == "FERPA") {
								$value1 = TRUE;
							}
							if ($checked == $checkedValue && $checkedValue == "HIPAA") {
								$value2 = TRUE;
							}
							if ($checked == $checkedValue && $checkedValue == "PCI DSS") {
								$value3 = TRUE;
							}
							if ($checked == $checkedValue && $checkedValue == "GLBA") {
								$value4 = TRUE;
							}
							if ($checked == $checkedValue && $checkedValue == "FCRA/FACTA") {
								$value5 = TRUE;
							}
						}
					}	
				}
				
				$box1 = array(
					'name' => 'recordRegulations[]',
					'value' => 'FERPA',
					'checked' => $value1,
				);
				$box2 = array(
					'name' => 'recordRegulations[]',
					'value' => 'HIPAA',
					'checked' => $value2,
				);
				$box3 = array(
					'name' => 'recordRegulations[]',
					'value' => 'PCI DSS',
					'checked' => $value3,
				);
				$box4 = array(
					'name' => 'recordRegulations[]',
					'value' => 'GLBA',
					'checked' => $value4,
				);
				$box5 = array(
					'name' => 'recordRegulations[]',
					'value' => 'FCRA/FACTA',
					'checked' => $value5,
				);
				
				echo form_checkbox($box1) . "&nbsp;&nbsp;FERPA<br />";
				echo form_checkbox($box2) . "&nbsp;&nbsp;HIPAA<br />";
				echo form_checkbox($box3) . "&nbsp;&nbsp;PCI DSS<br />";
				echo form_checkbox($box4) . "&nbsp;&nbsp;GLBA<br />";
				echo form_checkbox($box5) . "&nbsp;&nbsp;FCRA/FACTA<br />";
			?>
	
<!-- Question 15 -->
			<label for='personallyIdentifiableInformation'>15.) Personally Identifiable Information (PII) Notes</label><br />
			Notes: (Department Answer)<br />
			<textarea name="personallyIdentifiableInformationAnswer" id="personallyIdentifiableInformation" rows="3" cols="50" wrap="hard" ><?php if (!empty($recordTypeData['personallyIdentifiableInformationAnswer'])) { echo $recordTypeData['personallyIdentifiableInformationAnswer']; } ?></textarea>
			<br /><br />
			Notes: (Records Management)<br />
			<textarea name="personallyIdentifiableInformationRmNotes" id="personallyIdentifiableInformation" rows="3" cols="50" wrap="hard" ><?php if (!empty($recordTypeData['personallyIdentifiableInformationRmNotes'])) { echo $recordTypeData['personallyIdentifiableInformationRmNotes']; } ?></textarea>
			<br /><br />
<!-- Question 16 -->
			<label for='otherDepartmentCopies'>16.) Which other departments might hold copies of these records?</label><br />
			<textarea name="otherDepartmentCopiesAnswer" id="otherDepartmentCopies" rows="3" cols="50" wrap="hard"><?php if (!empty($recordTypeData['otherDepartmentCopiesAnswer'])) { echo $recordTypeData['otherDepartmentCopiesAnswer']; } ?></textarea>
			<br /><br />
<!-- Save Form -->			
			<br /><br /><br />
			<input name="updateRecordInformation" type="submit" value="Update" />
			
		</form>
<!-- End Form -->	
	   <?php
	   		echo "<span class='deleteSpan'>";
	   		if (isset($recordTypeData['recordInformationID'])) { 
					$recordInformationID = $recordTypeData['recordInformationID'];
					$siteUrl = site_url();
					$deleteUrl = $siteUrl . "/recordType/delete";
					echo "<form method='link' action='$deleteUrl/$recordInformationID' onClick='return confirm(\"Are you sure you want to DELETE this record?\")'>";
					echo "<input type='submit' value='Delete'>";
					echo "</form>";
	   		}
	   		echo "</span>";
	   		echo br(2);
	   		$recordInformationID = $recordTypeData['recordInformationID'];
	   		echo anchor_popup('/retentionSchedule/view/' . $recordInformationID, 'Create Record Group', $popUp);
	  	?>


		<br /><br />
	</div>

	
	
	
<?php /*	
    <div id="fragment-2" class="adminForm">
    <br /><br />
    
    <!-- check to determine if form is in insert or edit mode -->
    <?php if (isset($recordTypeData['newFormatAndLocation']) && $recordTypeData['newFormatAndLocation'] == "insertMode") { ?>
        	
    	<form id="formatAndLocation" method="post" action="<?php echo site_url();?>/dashboard/saveRecordTypeFormatAndLocation">
					
		<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" class="required" /><br />
		<input name="recordInformationID" type='hidden' value="<?php echo $recordTypeData['recordInformationID'] ?>" />
						
		<label for='electronicRecord'>1.)Is this record created electronically?</label><br />
		<input name="electronicRecord" type="radio" value="yes" id="showSystem" />&nbsp;&nbsp;Yes<br />
		<input name="electronicRecord" type="radio" value="no" id="hideSystem" checked="checked" />&nbsp;&nbsp;No<br />
						
		<div id="system">
			<label for='system'>Select the system used to create the record</label><br />
			<input name="system[]" type="checkbox" value="Banner" />&nbsp;&nbsp;Banner<br />
			<input name="system[]" type="checkbox" value="Blackboard" />&nbsp;&nbsp;Blackboard<br />
			<input name="system[]" type="checkbox" value="Desktop software" />&nbsp;&nbsp;Desktop software (MS Office, Adobe Acrobat)<br />
			<input name="system[]" type="checkbox" value="Email" />&nbsp;&nbsp;Email<br />
			<input name="system[]" type="checkbox" value="Portfolio" />&nbsp;&nbsp;Portfolio<br />
			<input name="system[]" type="checkbox" value="VAGA" />&nbsp;&nbsp;VAGA<br />
			<input name="system[]" type="checkbox" value="Other" id="toggleOther" />&nbsp;&nbsp;Other<br />
					
			<div id="otherText">
				<input name="otherText" type="text" />
			</div>
			<br />
		</div>
						
		<br />
						
		<label for='paperVersion'>2.) Does a paper version of the final record exist?</label><br />
		<input name="paperVersion" type="radio" value="yes" id="showPaperVersion" />&nbsp;&nbsp;Yes<br />
		<input name="paperVersion" type="radio" value="no" id="hidePaperVersion" checked="checked" />&nbsp;&nbsp;No<br />
						
		<div id="paperVersion">
			<label for='paperVersion'>Where are the paper records stored?</label><br />
			<input name="location[]" type="checkbox" value="Within the department" />&nbsp;&nbsp;Within the department<br />
			<input name="location[]" type="checkbox" value="Other DU building" id="toggleOtherBuilding" />&nbsp;&nbsp;Other DU building<br />
						
			<div id="otherBuildingText">
				<input name="otherBuilding" type="text" />
			</div>
			<input name="location[]" type="checkbox" value="Offsite storage" id="toggleOtherStorage" />&nbsp;&nbsp;Offsite storage<br />
							
			<div id="otherStorageText">
				<input name="otherStorage" type="text" />
			</div>
		</div>
						
		<br /><br />
						
		<label for='finalRecordExist'>3.) Does an electronic version of the final record exist?</label><br />
		<input name="finalRecordExist" type="radio" value="yes" id="showRecordLocation" />&nbsp;&nbsp;Yes<br />
		<input name="finalRecordExist" type="radio" value="no" id="hideRecordLocation" checked="checked" />&nbsp;&nbsp;No<br />
						
		<div id="recordLocation">
			<input name="recordLocation[]" type="checkbox" value="ADR" />&nbsp;&nbsp;ADR<br />
			<input name="recordLocation[]" type="checkbox" value="Backup media" id="toggleBackupMedia" />&nbsp;&nbsp;Backup media<br />
						
			<div id="backupMediaText">
				<input name="backupMedia" type="text" />
			</div>
			<input name="recordLocation[]" type="checkbox" value="Banner" />&nbsp;&nbsp;Banner<br />
			<input name="recordLocation[]" type="checkbox" value="Blackboard" />&nbsp;&nbsp;Blackboard<br />
			<input name="recordLocation[]" type="checkbox" value="Email" />&nbsp;&nbsp;Email<br />
			<input name="recordLocation[]" type="checkbox" value="Local HD" />&nbsp;&nbsp;Local HD<br />
			<input name="recordLocation[]" type="checkbox" value="Network Drive" />&nbsp;&nbsp;Network Drive<br />
			<input name="recordLocation[]" type="checkbox" value="Portfolio" />&nbsp;&nbsp;Portfolio<br />
			<input name="recordLocation[]" type="checkbox" value="VAGA" />&nbsp;&nbsp;VAGA<br />
			<input name="recordLocation[]" type="checkbox" value="Other" id="toggleOtherRecordLocation" />&nbsp;&nbsp;Other<br />
					
			<div id="otherRecordLocationText">
				<input name="otherRecordLocation" type="text" />
			</div>
							
		</div>
						
		<br /><br />
						
		<label for='fileFormat'>4.) Electronic File Format</label><br />
		<input name="fileFormat" type="text" id="fileFormat" size="25" />
		<br /><br />
						
		<label for='formatAndLocationNotes'>5.) Format and Location Notes</label><br />
		Notes: (Department Answer)<br />
		<textarea name="formatAndLocationDeptAnswer" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"></textarea>
		<br /><br />
		Notes: (Records Management)<br />
		<textarea name="formatAndLocationRmNotes" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"></textarea>
		<br /><br /><br />
		<input name="formatAndLocation" type="submit" value="Save" />	
	</form>	
 	<br /><br />
    <?php } else { ?>
    <!-- edit mode -->
    <form id="updateFormatAndLocation" method="post" action="<?php echo site_url();?>/dashboard/updateRecordTypeEditForm">
		
		<input name="formatAndLocationID" type="hidden" value="<?php if(!empty($recordTypeData['formatAndLocationID'])) {echo $recordTypeData['formatAndLocationID'];} ?>" />			
	    <input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" /><br />	
						
		<label for='electronicRecord'>1.)Is this record created electronically?</label><br />
		<?php 
			if (isset($recordTypeData['electronicRecord']) && $recordTypeData['electronicRecord'] == "yes") { 
				echo "<input name='electronicRecord' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='electronicRecord' type='radio' value='no' />&nbsp;&nbsp;No<br />";
			} 
		
			if (isset($recordTypeData['electronicRecord']) && $recordTypeData['electronicRecord'] == "no") {
				echo "<input name='electronicRecord' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='electronicRecord' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
			}	
		?>
		
		<br />			
		<label for='system'>Select the system used to create the record</label><br />
			
		<?php 
			
		// refactor...
		$systems = array('Banner', 'Blackboard', 'Desktop software', 'Email', 'Portfolio', 'VAGA', 'Other');
				
		if (isset($recordTypeData['system'])) {
			foreach ($systems as $checked) {
				foreach ($recordTypeData['system'] as $checkedValue) {
					if ($checked == $checkedValue && $checkedValue == "Banner") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "Blackboard") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "Desktop software") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "Email") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "Portfolio") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "VAGA") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
					}
					if ($checked == $checkedValue && $checkedValue == "Other") {
						echo "<input name='system[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue&nbsp;&nbsp;";
						if (isset($recordTypeData['otherText']) && $recordTypeData['otherText'] != "") {
							$otherText = $recordTypeData['otherText'];
							echo "<input name='otherText' type='text' value='$otherText' />"; 
							echo "<br />";	
						}
					}
				}
			}
									
			foreach ($systems as $notChecked) {
				if (!in_array($notChecked, $recordTypeData['system'])) {
					if ($notChecked == "Banner") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";	
					}
					if ($notChecked == "BlackBoard") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Desktop software") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Email") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Portfolio") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "VAGA") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Other") {
						echo "<input name='system[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked&nbsp;&nbsp;";
						echo "<input name='otherText' type='text' value='' /><br />";
					}
				}					
			}
			
		}
		?>
		<br /><hr /><br />
						
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
					if ($checkedValue != "") {
						if ($checkedValue == "Within the department") {
							echo "<input name='location[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue<br />";
						}
						if ($checkedValue == "Other DU building") {
							if (isset($recordTypeData['otherBuilding']) && $recordTypeData['otherBuilding'] != "") {
								$otherBuilding = $recordTypeData['otherBuilding'];
								echo "<input name='location[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue&nbsp;&nbsp;";
								echo "<input name='otherBuilding' type='text' value='$otherBuilding' /><br />";
							}
						} 	
						if ($checkedValue == "Offsite storage") {
							if (isset($recordTypeData['otherStorage']) && $recordTypeData['otherStorage'] != "") {
								$otherStorage = $recordTypeData['otherStorage'];
								echo "<input name='location[]' type='checkbox' value='$checkedValue' checked />&nbsp;&nbsp;$checkedValue&nbsp;&nbsp;";
								echo "<input name='otherStorage' type='text' value='$otherStorage' /><br />";
							}
						} 
				
					}
					
				}
			
				foreach ($recordStorage as $notChecked) {
					if (!in_array($notChecked, $recordTypeData['location'])) {
						if ($notChecked == "Within the department") {
							echo "<input name='location[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />"; 			
						}
						if ($notChecked == "Other DU building") {
							echo "<input name='location[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked&nbsp;&nbsp;";
							echo "<input name='otherBuilding' type='text' value='' /><br />";
						}
						if ($notChecked == "Offsite storage") {
							echo "<input name='location[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked&nbsp;&nbsp;";
							echo "<input name='otherStorage' type='text' value='' /><br />";
						}
					}
				}
			}
		?>
		
		<br /><hr /><br />
						
		<label for='finalRecordExist'>3.) Does an electronic version of the final record exist?</label><br />
		<?php
			if (isset($recordTypeData['finalRecordExist']) && $recordTypeData['finalRecordExist'] == "yes") { 
				echo "<input name='finalRecordExist' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='finalRecordExist' type='radio' value='no' />&nbsp;&nbsp;No<br />";
			} 
			
			if (isset($recordTypeData['finalRecordExist']) && $recordTypeData['finalRecordExist'] == "no") {
				echo "<input name='finalRecordExist' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
				echo "<input name='finalRecordExist' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
			}	
		?>
		<br />					
		<?php 
			$recordLocations = array('ADR', 'Backup media', 'Banner', 'Blackboard', 'Email', 'Local HD', 'Network Drive', 'Portfolio', 'VAGA', 'Other');
			
			foreach ($recordTypeData['recordLocation'] as $checked) {
				if ($checked == "ADR") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Backup media") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked&nbsp;&nbsp;";
					$backupMedia = $recordTypeData['backupMedia'];
					echo "<input name='backupMedia' type='text' value='$backupMedia' /><br />"; 
				}
				if ($checked == "Banner") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Blackboard") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Email") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Local HD") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Network Drive") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "Portfolio") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == "VAGA") {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked<br />";
				}
				if ($checked == 'Other') {
					echo "<input name='recordLocation[]' type='checkbox' value='$checked' checked />&nbsp;&nbsp;$checked&nbsp;&nbsp;";
					$otherRecordLocation = $recordTypeData['otherRecordLocation'];
					echo "<input name='otherRecordLocation' type='text' value='$otherRecordLocation' /><br />"; 
				}
			}
			
			foreach ($recordLocations as $notChecked) {
				if (!in_array($notChecked, $recordTypeData['recordLocation'])) {
					if ($notChecked == "ADR") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Backup media") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked&nbsp;&nbsp;";
						echo "<input name='backupMedia' type='text' value='' /><br />"; 
					}
					if ($notChecked == "Banner") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Blackboard") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Email") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Local HD") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Network Drive") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Portfolio") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "VAGA") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked<br />";
					}
					if ($notChecked == "Other") {
						echo "<input name='recordLocation[]' type='checkbox' value='$notChecked' />&nbsp;&nbsp;$notChecked&nbsp;&nbsp;";
						echo "<input name='otherRecordLocation' type='text' value='' />";
					}
				}					
			}
				
		?>
		<br /><hr /><br />
								
		<label for='fileFormat'>4.) Electronic File Format</label><br />
		<?php $fileFormat = $recordTypeData['fileFormat']; ?>
		<input name="fileFormat" type="text" id="fileFormat" value='<?php echo $fileFormat; ?>' size="25" />
		<br /><br /><hr /><br />
						
		<label for='formatAndLocationNotes'>5.) Format and Location Notes</label><br />
		Notes: (Department Answer)<br />
		<textarea name="formatAndLocationDeptAnswer" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['formatAndLocationDeptAnswer']; ?></textarea>
		<br /><br />
		Notes: (Records Management)<br />
		<textarea name="formatAndLocationRmNotes" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['formatAndLocationRmNotes']; ?></textarea>
		<br /><br /><br />
		
		<input name="updateFormatAndLocation" type="submit" value="Update" /> 
	</form>	
	<?php } // closes if else statement ?> 
	<br /><br />	
	</div>
	<div id="fragment-3" class="adminForm">
	<br /><br />
    <!-- check to determine if form is in insert or edit mode -->
    <?php if (isset($recordTypeData['newManagement']) && $recordTypeData['newManagement'] == "insertMode") { ?>

    	<form id="management" method="post" action="<?php echo site_url();?>/dashboard/saveRecordTypeManagement">
					
			<input name="recordTypeDepartment" type="hidden" value="<?php if (!empty($recordTypeData['recordTypeDepartment'])) { echo $recordTypeData['recordTypeDepartment']; } ?>" class="required" /><br />
			<input name="recordInformationID" type='hidden' value="<?php echo $recordTypeData['recordInformationID']; ?>" />
					
				<div class="ui-accordion-sections">
					Access and Use<br /><br />
				</div>
				<br />
					
				<label for='accessAndUse'>1.) Who needs access to these records? Who uses them?</label><br />
				Notes: (Department Answer)<br />
				<textarea name="accessAndUseDeptAnswer" id="accessAndUse" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="accessAndUseRmNotes" id="accessAndUse" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
				Active Period<br />
				</div>
				<br />
				2.) How long are the records active?&nbsp;&nbsp;<input name="yearsActive" type="text" size="3" />&nbsp;&nbsp;Years<br /><br />
				3.) How long do the records need to be immediately available to the department?&nbsp;&nbsp;<input name="yearsAvailable" type="text" size="3" />&nbsp;&nbsp;Years<br /><br />
						
				<label for='activePeriodNotes'>4.) Format and Location Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="activePeriodDeptAnswer" id="activePeriodNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="activePeriodRmNotes" id="activePeriodNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Retention Period
				</div>
				<br />
				5.) How long are the records currently kept?&nbsp;&nbsp;<input name="yearsKept" type="text" size="3" />&nbsp;&nbsp;Years<br /><br />
						
				<label for='retentionPeriod'>6.) Retention Period Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="retentionPeriodDeptAnswer" id="retentionPeriod" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="retentionPeriodRmNotes" id="retentionPeriod" rows="3" cols="50" wrap="hard"></textarea>
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
							echo "<option value='$id'>$divisions</option>";
						}
					?>
				</select> *
				&nbsp;&nbsp;
				<select id='managementDepartments' name='managementDepartmentID' size='1' class='required'>
					<option value=''>departments</option>
				</select> *
				<br /><br />
						
				<label for='custodian'>8.) Custodian Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="custodianDeptAnswer" id="custodian" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="custodianRmNotes" id="custodian" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Legal Requirements
				</div>
				<br />
					
				<label for='legislationGovernRecords'>9.) Does legislation govern the retention of these records?</label><br />
				<input name="legislationGovernRecords" type="radio" value="yes" id="showLegalRequirments" />&nbsp;&nbsp;Yes<br />
				<input name="legislationGovernRecords" type="radio" value="no" id="hideLegalRequirments" checked="checked" />&nbsp;&nbsp;No<br />
						
				<div id="legalRequirments">
					What legislation?&nbsp;&nbsp;<input name="legislation" type="text" size="45" /><br /><br />
					For how long?&nbsp;&nbsp;<input name="legislationHowLong" type="text" size="3" />&nbsp;&nbsp;Years<br /><br />
				</div>
				<br />
						
				<label for='legalRequirmentsNotes'>10.) Legal Requirements Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="legalRequirmentsDeptAnswer" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="legalRequirmentsRmNotes" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Standards and Best Practices
				</div>
				<br />
				11.) What other authorities govern retention of this series?<br /><br />
				12.) Are there any standards or best practices that make retention suggestions about the material?<br /><br />
						
				<label for='standardsAndBestPracticesNotes'>13.) Standards and Best Practices Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="standardsAndBestPracticesDeptAnswer" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="standardsAndBestPracticesRmNotes" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Destruction
				</div>
				<br />
					
				<label for='destroyRecords'>14.) Do you ever destroy these records?</label><br />
				<input name="destroyRecords" type="radio" value="yes" id="showDestroyRecords" />&nbsp;&nbsp;Yes<br />
				<input name="destroyRecords" type="radio" value="no" id="hideDestroyRecords" checked="checked" />&nbsp;&nbsp;No<br />
						
				<div id="destruction">
					If so, how often?<br />
					Every&nbsp;&nbsp;<input name="howOftenDestruction" type="text" size="3" />&nbsp;&nbsp;years<br /><br />
					How are records destroyed?<br />
					<input name="howAreRecordsDestroyed" type="text" size="45" /><br />
				</div>
				<br />
						
				<label for='destructionNotes'>15.) Destruction Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="destructionDeptAnswer" id="destructionNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="destructionRmNotes" id="destructionNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Transfer to Archives
				</div>
				<br />
				<label for='transferToArchives'>16.) Do you ever send records in this series to the University archives?</label><br />
				<input name="transferToArchives" type="radio" value="yes" id="showTransferToArchives" />&nbsp;&nbsp;Yes<br />
				<input name="transferToArchives" type="radio" value="no" id="hideTransferToArchives" checked="checked" />&nbsp;&nbsp;No<br /><br />
					
				<div id="transferToArchives">
					If so, how often?<br />
					Every&nbsp;&nbsp;<input name="howOftenArchive" type="text" size="3" />&nbsp;&nbsp;years<br /><br />
				</div>
						
				<label for='transferToArchivesNotes'>17.) Transfer to Archives</label><br />
				Notes: (Department Answer)<br />
				<textarea name="transferToArchivesDeptAnswer" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="transferToArchivesRmNotes" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Vital Records
				</div>
				<br />
				"Emergency operation records immediately necessary to begin recovery of business after a disaster, as well as rights-and-interests records necessary to protect the assests, obligations, and resources of the organization, as well as its employees and customers or citizens; essential records."
				<br /><br />
					
				<label for='vitalRecords'>18.) Are these records vital records?</label><br />
				<input name="vitalRecords" type="radio" value="yes" id="showVitalRecords" />&nbsp;&nbsp;Yes<br />
				<input name="vitalRecords" type="radio" value="no" id="hideVitalRecords" checked="checked" />&nbsp;&nbsp;No<br />
					
				<div id="vitalRecords">
					<label for='manageVitalRecords'>How does the department manage vital records?</label><br />
					<textarea name="manageVitalRecords" id="manageVitalRecords" rows="3" cols="50" wrap="hard"></textarea>
					<br />
				</div>
						
				<br />
						
				<label for='vitalRecordsNotes'>19.) Vital Records Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="vitalRecordsDeptAnswer" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="vitalRecordsRmNotes" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Sensitive Information
				</div>
				<br />
					
				<label for=''>20.) Do the records contain sensitive, confidential or personally identifiable information?</label><br />
				<input name="sensitiveInformation" type="radio" value="yes" id="showSensitiveInformation" />&nbsp;&nbsp;Yes<br />
				<input name="sensitiveInformation" type="radio" value="no" id="hideSensitiveInformation" checked="checked" />&nbsp;&nbsp;No<br />
						
				<div id="sensitiveInformation">
					<label for='describeInformation'>Describe the Information</label><br />
					<textarea name="describeInformation" id="describeInformation" rows="3" cols="50" wrap="hard"></textarea>	
					<br />
				</div>
						
				<br />
						
				<label for='sensitiveInformationNotes'>21.) Sensitive Information Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="sensitiveInformationDeptAnswer" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="sensitiveInformationRmNotes" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Security
				</div>
				<br />
					
				<label for='secureRecords'>22.) Do the records need to be secure?</label><br />
				<input name="secureRecords" type="radio" value="yes" id="showSecureRecords" />&nbsp;&nbsp;Yes<br />
				<input name="secureRecords" type="radio" value="no" id="hideSecureRecords" checked="checked" />&nbsp;&nbsp;No<br />
						
				<div id="security">
					<label for='describeSecurityRecords'>Describe any security in place on the records</label><br />
					<textarea name="describeSecurityRecords" id="describeSecurityRecords" rows="3" cols="50" wrap="hard"></textarea>	
					<br />
				</div>
						
				<br />
						
				<label for='securityNotes'>23.) Security Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="securityDeptAnswer" id="securityNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="securityRmNotes" id="securityNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Duplication
				</div>
				<br />
					
				<label for='duplication'>24.) Are these records duplicated in other departments?</label><br />
				<input name="duplication" type="radio" value="yes" id="showDuplication" />&nbsp;&nbsp;Yes<br />
				<input name="duplication" type="radio" value="no" id="hideDuplication" checked="checked" />&nbsp;&nbsp;No<br />
						
				<div id="duplication">
					Which departments?<br />
												
					<select id='duplicationDivisions0' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments0' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions1' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments1' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions2' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments2' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions3' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments3' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions4' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments4' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions5' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments5' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions6' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments6' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions7' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments7' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions8' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments8' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
					<select id='duplicationDivisions9' name='duplicationDivisionID[]' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments9' name='duplicationDepartmentID[]' size='1'>
						<option value=''>departments</option>
					</select> 
					<br /><br />
						
						
					What department holds the master copy of these records?<br /> 
					<select id='duplicationDivisions10' name='masterCopyDivisionID' size='1'>
						<option value=''>Select a Division</option>
						<option value=''>-----------------</option>
						<?php 
							foreach ($divisionData as $id => $divisions) {
								echo "<option value='$id'>$divisions</option>";
							}
						?>
					</select> 
					&nbsp;&nbsp;
					<select id='duplicationDepartments10' name='masterCopyDepartmentID' size='1'>
						<option value=''>departments</option>
					</select> 
					<br />
				</div>
					
				<br />
													
				<label for='duplicationNotes'>25.) Duplication Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="duplicationDeptAnswer" id="duplicationNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="duplicationRmNotes" id="duplicationNotes" rows="3" cols="50" wrap="hard"></textarea>
				<br /><br />	
				<br />
				<input name="management" type="submit" value="Save" />	
			</form>
			
    <br /><br /> 
    <?php } else { ?>
    	<!-- edit mode -->
    	<form id="updateManagement" method="post" action="<?php echo site_url();?>/dashboard/updateRecordTypeEditForm">
					
			<input name="managementID" type="hidden" value="<?php if(!empty($recordTypeData['managementID'])) {echo $recordTypeData['managementID'];} ?>" />			
														
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
				2.) How long are the records active?&nbsp;&nbsp;<input name="yearsActive" type="text" size="10" value="<?php echo $recordTypeData['yearsActive'] ?>" />&nbsp;&nbsp;Years<br /><br />
				3.) How long do the records need to be immediately available to the department?&nbsp;&nbsp;<input name="yearsAvailable" type="text" size="10" value="<?php echo $recordTypeData['yearsAvailable'] ?>" />&nbsp;&nbsp;Years<br /><br />
						
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
				5.) How long are the records currently kept?&nbsp;&nbsp;<input name="yearsKept" type="text" size="10" value="<?php echo $recordTypeData['yearsKept']; ?>" />&nbsp;&nbsp;Years<br /><br />
						
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
				For how long?&nbsp;&nbsp;<input name="legislationHowLong" type="text" size="10" value="<?php echo $recordTypeData['legislationHowLong']; ?>" />&nbsp;&nbsp;Years<br /><br />
				
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
					if (isset($recordTypeData['destroyRecords']) && $recordTypeData['destroyRecords'] == "yes") { 
						echo "<input name='destroyRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='destroyRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['destroyRecords']) && $recordTypeData['destroyRecords'] == "no") {
						echo "<input name='destroyRecords' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='destroyRecords' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
		
				If so, how often?<br />
				Every&nbsp;&nbsp;<input name="howOftenDestruction" type="text" size="10" value="<?php echo $recordTypeData['howOftenDestruction']; ?>" />&nbsp;&nbsp;years<br /><br />
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
				
				<?php 
					if (isset($recordTypeData['transferToArchives']) && $recordTypeData['transferToArchives'] == "yes") { 
						echo "<input name='transferToArchives' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='transferToArchives' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['transferToArchives']) && $recordTypeData['transferToArchives'] == "no") {
						echo "<input name='transferToArchives' type='radio' value='yes' />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='transferToArchives' type='radio' value='no' checked />&nbsp;&nbsp;No<br />";
					}	
				?>
					
				If so, how often?<br />
				Every&nbsp;&nbsp;<input name="howOftenArchive" type="text" size="10" value="<?php echo $recordTypeData['howOftenArchive']; ?>" />&nbsp;&nbsp;years<br /><br />
						
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
					if (isset($recordTypeData['vitalRecords']) && $recordTypeData['vitalRecords'] == "yes") { 
						echo "<input name='vitalRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='vitalRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['vitalRecords']) && $recordTypeData['vitalRecords'] == "no") {
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
				
				<?php 
					if (isset($recordTypeData['sensitiveInformation']) && $recordTypeData['sensitiveInformation'] == "yes") { 
						echo "<input name='sensitiveInformation' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='sensitiveInformation' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['sensitiveInformation']) && $recordTypeData['sensitiveInformation'] == "no") {
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
					if (isset($recordTypeData['secureRecords']) && $recordTypeData['secureRecords'] == "yes") { 
						echo "<input name='secureRecords' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='secureRecords' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['secureRecords']) && $recordTypeData['secureRecords'] == "no") {
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
					if (isset($recordTypeData['duplication']) && $recordTypeData['duplication'] == "yes") { 
						echo "<input name='duplication' type='radio' value='yes' checked  />&nbsp;&nbsp;Yes<br /> ";
						echo "<input name='duplication' type='radio' value='no' />&nbsp;&nbsp;No<br />";
					} 
		
					if (isset($recordTypeData['duplication']) && $recordTypeData['duplication'] == "no") {
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
														
							echo "<select id='duplicationDivisions$i' name='duplicationDivisionID[]' size='1'>";
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
							
							echo "<select id='duplicationDepartments$i' name='duplicationDepartmentID[]' size='1'>";
							echo "<option value='$departmentID'>$department</option>";
							echo "</select>"; 
							echo "<br /><br />";
		
						++$i;	
						}
					}
				?>
												
				<br />
				
				
				What department holds the master copy of these records?<br /> 
				<select id='duplicationDivisions10' name='masterCopyDivisionID' size='1'>
					<option value=''>Select a Division</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($divisionData as $id => $divisions) {
							echo "<option value='$id'>$divisions</option>";
						}
					?>
				</select> 
				&nbsp;&nbsp;
				<select id='duplicationDepartments10' name='masterCopyDepartmentID' size='1'>
					<option value=''>departments</option>
				</select> 
					
				<br />
				<br />
					
				<label for='duplicationNotes'>25.) Duplication Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="duplicationDeptAnswer" id="duplicationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['duplicationDeptAnswer']; ?></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="duplicationRmNotes" id="duplicationNotes" rows="3" cols="50" wrap="hard"><?php echo $recordTypeData['duplicationRmNotes']; ?></textarea>
				<br /><br />	
				<br />
				<input name="updateManagement" type="submit" value="Update" />	
			</form>
			<?php } // closes if else statement ?>
			<br /><br />
        </div>*/ ?>
</div>
<?php $this->load->view('includes/adminFooter',$data); ?>