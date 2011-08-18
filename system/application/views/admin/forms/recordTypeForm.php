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

<?php echo $jQuery; ?>
<?php echo $jQueryDeptWidget; ?>
<?php echo $jQueryDeptMasterCopyWidget; ?>
<?php echo $jQueryDeptDuplicationWidget; ?>

<div id="tabs">
	<div id="setDepartment">Setting Department...</div>
	  	<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Record Information</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-2">Format and Location</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-3"><span>Management</span></a></li>
        </ul>
       <div id="fragment-1" class="adminForm">
       <br />
       
       <?php if (!isset($division)) { ?>
       	
       	<form name="department" method="post" action="<?php echo site_url();?>/dashboard/recordTypeFormRevised" />
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
										
		<form id="recordTypeDepartment" method="post" action="<?php echo site_url();?>/dashboard/setRecordTypeFormDepartment" />
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
			</select> <input name="department" type="submit" value="Set Department" />&nbsp;*
			<br />
		</form>
		
		<?php } else {
				echo "<h1>" . $division['divisionName'] . "</h1>";
				echo "<h2>" . $division['departmentName'] . "</h2>";
		} ?>
		
					
		<form id="recordInformation" method="post" action="<?php echo site_url();?>/dashboard/saveRecordTypeRecordInformation">
					
			<input name="recordInformationDivisionID" type="hidden" value="<?php if (!empty($_POST['divisionID'])) {echo $_POST['divisionID'];} elseif (isset($division)) { echo $division['divisionID']; } ?>" />
					
			<input name="recordTypeDepartment" type="hidden" value="<?php if (isset($division)) {echo $division['departmentID'];} ?>" class="required" /><br />	
										
			<label for='recordName'>1.) Record Name:&nbsp;*</label><br />
			<input name='recordName' id='recordName' type='text' value="" class="required" tabindex=1 />
			<br /><br />
						
			<label for='recordDescription'>2.) Record Description:&nbsp;*</label><br />
			<textarea name="recordDescription" rows="3" cols="50" wrap="hard" class="required" tabindex=2 ></textarea>
			<br /><br />
						
			<!-- <label for='recordCategory'>3.) Record Category:</label><br />  -->
			3.) 
			<select name='recordCategory' size='1' class='required' tabindex=3>
				<option value=''>Select a Record Category</option>
				<option value=''>-----------------</option>
				<?php 
					foreach ($recordCategories as $recordCategory) {
						echo "<option value='$recordCategory'>$recordCategory</option>";
					}
				?>
			</select>
			&nbsp;*&nbsp;
			<!-- refresh icon from http://jimmac.musichall.cz/icons.php -->
			<?php 
				$imagePath = base_url() . "/images";
				$addCategoryRecord = "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Add Record Category' border=0 />";   
				echo anchor_popup('upkeep/recordCategoryForm', $addCategoryRecord, $smallPopUp) . "&nbsp;&nbsp;&nbsp;<a href='javascript:history.go(0)'><img src='$imagePath/refresh.png' alt='Refresh' border=0 /></a><br />";
			?>
			
			<!-- 
			<textarea name="recordCategory" rows="3" cols="50" wrap="hard" class="required"></textarea>
			 -->
			 
			<br /><br />
						
			<label for='recordNotes'>4.) Records Notes</label><br />
			Notes: (Department Answer)<br />
			<textarea name="recordNotesDeptAnswer" rows="3" cols="50" wrap="hard" tabindex=4></textarea>
			<br /><br />
			Notes: (Records Management)<br />
			<textarea name="recordNotesRmNotes" rows="3" cols="50" wrap="hard" tabindex=5></textarea>
			<br /><br /><br />
			<input name="recordInformation" type="submit" value="Save" tabindex=6 />	
		</form>
		<br /><br />
	</div>
    <div id="fragment-2" class="adminForm">
    <br />
    <form id="formatAndLocation" method="post" action="<?php echo site_url();?>/dashboard/saveRecordTypeFormatAndLocation">
					
		<input name="recordTypeDepartment" type="hidden" value="<?php if (isset($division)) {echo $division['departmentID'];} ?>" class="required" /><br />	
		<input name="recordInformationID" type='hidden' value="" class="required" /><br /><br />
						
		<label for='electronicRecord'>1.)Is this record created electronically?</label><br />
		<input name="electronicRecord" type="radio" value="yes" id="showSystem" tabindex=1 />&nbsp;&nbsp;Yes<br />
		<input name="electronicRecord" type="radio" value="no" id="hideSystem" checked="checked" tabindex=2 />&nbsp;&nbsp;No<br />
						
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
		<input name="paperVersion" type="radio" value="yes" id="showPaperVersion" tabindex=3 />&nbsp;&nbsp;Yes<br />
		<input name="paperVersion" type="radio" value="no" id="hidePaperVersion" checked="checked" tabindex=4 />&nbsp;&nbsp;No<br />
						
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
		<input name="finalRecordExist" type="radio" value="yes" id="showRecordLocation" tabindex=4 />&nbsp;&nbsp;Yes<br />
		<input name="finalRecordExist" type="radio" value="no" id="hideRecordLocation" checked="checked" tabindex=5 />&nbsp;&nbsp;No<br />
						
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
		<input name="fileFormat" type="text" id="fileFormat" size="25" tabindex=6 />&nbsp;&nbsp;
		<?php 
			$imagePath = base_url() . "/images";
			$addCategoryRecord = "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Add Record Category' border=0 />";   
			echo anchor_popup('upkeep/addDocTypeForm', $addCategoryRecord, $mediumPopUp) . "<br />";
		?>
		<br /><br />
						
		<label for='formatAndLocationNotes'>5.) Format and Location Notes</label><br />
		Notes: (Department Answer)<br />
		<textarea name="formatAndLocationDeptAnswer" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard" tabindex=7></textarea>
		<br /><br />
		Notes: (Records Management)<br />
		<textarea name="formatAndLocationRmNotes" id="formatAndLocationNotes" rows="3" cols="50" wrap="hard" tabindex=8></textarea>
		<br /><br /><br />
		<input name="formatAndLocation" type="submit" value="Save" />	
	</form>	
	<br /><br />	
	</div>
	<div id="fragment-3" class="adminForm">
	<br />
    	<form id="management" method="post" action="<?php echo site_url();?>/dashboard/saveRecordTypeManagement">
					
			<input name="recordTypeDepartment" type="hidden" value="<?php if (isset($division)) {echo $division['departmentID'];} ?>" class="required" /><br />	
			<input name="recordInformationID" type='hidden' value="" class="required" /><br /><br />
					
				<div class="ui-accordion-sections">
					Access and Use<br /><br />
				</div>
				<br />
					
				<label for='accessAndUse'>1.) Who needs access to these records? Who uses them?</label><br />
				Notes: (Department Answer)<br />
				<textarea name="accessAndUseDeptAnswer" id="accessAndUse" rows="3" cols="50" wrap="hard" tabindex=1></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="accessAndUseRmNotes" id="accessAndUse" rows="3" cols="50" wrap="hard" tabindex=2></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
				Active Period<br />
				</div>
				<br />
				2.) How long are the records active?&nbsp;&nbsp;<input name="yearsActive" type="text" size="10" tabindex=3 />&nbsp;&nbsp;Years<br /><br />
				3.) How long do the records need to be immediately available to the department?&nbsp;&nbsp;<input name="yearsAvailable" type="text" size="10" tabindex=4 />&nbsp;&nbsp;Years<br /><br />
						
				<label for='activePeriodNotes'>4.) Format and Location Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="activePeriodDeptAnswer" id="activePeriodNotes" rows="3" cols="50" wrap="hard" tabindex=5></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="activePeriodRmNotes" id="activePeriodNotes" rows="3" cols="50" wrap="hard" tabindex=6></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Retention Period
				</div>
				<br />
				5.) How long are the records currently kept?&nbsp;&nbsp;<input name="yearsKept" type="text" size="10" tabindex=7 />&nbsp;&nbsp;Years<br /><br />
						
				<label for='retentionPeriod'>6.) Retention Period Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="retentionPeriodDeptAnswer" id="retentionPeriod" rows="3" cols="50" wrap="hard" tabindex=8></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="retentionPeriodRmNotes" id="retentionPeriod" rows="3" cols="50" wrap="hard" tabindex=9></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Custodian
				</div>
				<br />
				7.) What department is the custodian of these records?<br /><br />
						
				<select id='managementDivisions' name='managementDivisionID' size='1' class='required' tabindex=10>
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
				<textarea name="custodianDeptAnswer" id="custodian" rows="3" cols="50" wrap="hard" tabindex=11></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="custodianRmNotes" id="custodian" rows="3" cols="50" wrap="hard" tabindex=12></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Legal Requirements
				</div>
				<br />
					
				<label for='legislationGovernRecords'>9.) Does legislation govern the retention of these records?</label><br />
				<input name="legislationGovernRecords" type="radio" value="yes" id="showLegalRequirments" tabindex=13 />&nbsp;&nbsp;Yes<br />
				<input name="legislationGovernRecords" type="radio" value="no" id="hideLegalRequirments" checked="checked" tabindex=14 />&nbsp;&nbsp;No<br />
						
				<div id="legalRequirments">
					What legislation?&nbsp;&nbsp;<input name="legislation" type="text" size="45" /><br /><br />
					For how long?&nbsp;&nbsp;<input name="legislationHowLong" type="text" size="10" />&nbsp;&nbsp;Years<br /><br />
				</div>
				<br />
						
				<label for='legalRequirmentsNotes'>10.) Legal Requirements Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="legalRequirmentsDeptAnswer" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard" tabindex=15></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="legalRequirmentsRmNotes" id="legalRequirmentsNotes" rows="3" cols="50" wrap="hard" tabindex=16></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Standards and Best Practices
				</div>
				<br />
				11.) What other authorities govern retention of this series?<br /><br />
				12.) Are there any standards or best practices that make retention suggestions about the material?<br /><br />
						
				<label for='standardsAndBestPracticesNotes'>13.) Standards and Best Practices Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="standardsAndBestPracticesDeptAnswer" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard" tabindex=17></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="standardsAndBestPracticesRmNotes" id="standardsAndBestPracticesNotes" rows="3" cols="50" wrap="hard" tabindex=18></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Destruction
				</div>
				<br />
					
				<label for='destroyRecords'>14.) Do you ever destroy these records?</label><br />
				<input name="destroyRecords" type="radio" value="yes" id="showDestroyRecords" tabindex=19 />&nbsp;&nbsp;Yes<br />
				<input name="destroyRecords" type="radio" value="no" id="hideDestroyRecords" checked="checked" tabindex=20 />&nbsp;&nbsp;No<br />
						
				<div id="destruction">
					If so, how often?<br />
					Every&nbsp;&nbsp;<input name="howOftenDestruction" type="text" size="3" />&nbsp;&nbsp;years<br /><br />
					How are records destroyed?<br />
					<input name="howAreRecordsDestroyed" type="text" size="45" /><br />
				</div>
				<br />
						
				<label for='destructionNotes'>15.) Destruction Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="destructionDeptAnswer" id="destructionNotes" rows="3" cols="50" wrap="hard" tabindex=21></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="destructionRmNotes" id="destructionNotes" rows="3" cols="50" wrap="hard" tabindex=22></textarea>
				<br /><br />	
					
				<div class="ui-accordion-sections">
					Transfer to Archives
				</div>
				<br />
				<label for='transferToArchives'>16.) Do you ever send records in this series to the University archives?</label><br />
				<input name="transferToArchives" type="radio" value="yes" id="showTransferToArchives" tabindex=23 />&nbsp;&nbsp;Yes<br />
				<input name="transferToArchives" type="radio" value="no" id="hideTransferToArchives" checked="checked" tabindex=24 />&nbsp;&nbsp;No<br /><br />
					
				<div id="transferToArchives">
					If so, how often?<br />
					Every&nbsp;&nbsp;<input name="howOftenArchive" type="text" size="10" />&nbsp;&nbsp;years<br /><br />
				</div>
						
				<label for='transferToArchivesNotes'>17.) Transfer to Archives</label><br />
				Notes: (Department Answer)<br />
				<textarea name="transferToArchivesDeptAnswer" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard" tabindex=25 ></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="transferToArchivesRmNotes" id="transferToArchivesNotes" rows="3" cols="50" wrap="hard" tabindex=26 ></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Vital Records
				</div>
				<br />
				"Emergency operation records immediately necessary to begin recovery of business after a disaster, as well as rights-and-interests records necessary to protect the assests, obligations, and resources of the organization, as well as its employees and customers or citizens; essential records."
				<br /><br />
					
				<label for='vitalRecords'>18.) Are these records vital records?</label><br />
				<input name="vitalRecords" type="radio" value="yes" id="showVitalRecords" tabindex=27 />&nbsp;&nbsp;Yes<br />
				<input name="vitalRecords" type="radio" value="no" id="hideVitalRecords" checked="checked" tabindex=28 />&nbsp;&nbsp;No<br />
					
				<div id="vitalRecords">
					<label for='manageVitalRecords'>How does the department manage vital records?</label><br />
					<textarea name="manageVitalRecords" id="manageVitalRecords" rows="3" cols="50" wrap="hard"></textarea>
					<br />
				</div>
						
				<br />
						
				<label for='vitalRecordsNotes'>19.) Vital Records Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="vitalRecordsDeptAnswer" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard" tabindex=29 ></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="vitalRecordsRmNotes" id="vitalRecordsNotes" rows="3" cols="50" wrap="hard" tabindex=30 ></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Sensitive Information
				</div>
				<br />
					
				<label for=''>20.) Do the records contain sensitive, confidential or personally identifiable information?</label><br />
				<input name="sensitiveInformation" type="radio" value="yes" id="showSensitiveInformation" tabindex=31 />&nbsp;&nbsp;Yes<br />
				<input name="sensitiveInformation" type="radio" value="no" id="hideSensitiveInformation" checked="checked" tabindex=32 />&nbsp;&nbsp;No<br />
						
				<div id="sensitiveInformation">
					<label for='describeInformation'>Describe the Information</label><br />
					<textarea name="describeInformation" id="describeInformation" rows="3" cols="50" wrap="hard"></textarea>	
					<br />
				</div>
						
				<br />
						
				<label for='sensitiveInformationNotes'>21.) Sensitive Information Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="sensitiveInformationDeptAnswer" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard" tabindex=33 ></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="sensitiveInformationRmNotes" id="sensitiveInformationNotes" rows="3" cols="50" wrap="hard" tabindex=34 ></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Security
				</div>
				<br />
					
				<label for='secureRecords'>22.) Do the records need to be secure?</label><br />
				<input name="secureRecords" type="radio" value="yes" id="showSecureRecords" tabindex=35 />&nbsp;&nbsp;Yes<br />
				<input name="secureRecords" type="radio" value="no" id="hideSecureRecords" checked="checked" tabindex=36 />&nbsp;&nbsp;No<br />
						
				<div id="security">
					<label for='describeSecurityRecords'>Describe any security in place on the records</label><br />
					<textarea name="describeSecurityRecords" id="describeSecurityRecords" rows="3" cols="50" wrap="hard"></textarea>	
					<br />
				</div>
						
				<br />
						
				<label for='securityNotes'>23.) Security Notes</label><br />
				Notes: (Department Answer)<br />
				<textarea name="securityDeptAnswer" id="securityNotes" rows="3" cols="50" wrap="hard" tabindex=37 ></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="securityRmNotes" id="securityNotes" rows="3" cols="50" wrap="hard" tabindex=38 ></textarea>
				<br /><br />	
						
				<div class="ui-accordion-sections">
					Duplication
				</div>
				<br />
					
				<label for='duplication'>24.) Are these records duplicated in other departments?</label><br />
				<input name="duplication" type="radio" value="yes" id="showDuplication" tabindex=39 />&nbsp;&nbsp;Yes<br />
				<input name="duplication" type="radio" value="no" id="hideDuplication" checked="checked" tabindex=40 />&nbsp;&nbsp;No<br />
						
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
				<textarea name="duplicationDeptAnswer" id="duplicationNotes" rows="3" cols="50" wrap="hard" tabindex=41></textarea>
				<br /><br />
				Notes: (Records Management)<br />
				<textarea name="duplicationRmNotes" id="duplicationNotes" rows="3" cols="50" wrap="hard" tabindex=42></textarea>
				<br /><br />	
				<br />
				<input name="management" type="submit" value="Save" />	
			</form>
			<br /><br />
        </div>
</div>
<?php $this->load->view('includes/adminFooter'); ?>