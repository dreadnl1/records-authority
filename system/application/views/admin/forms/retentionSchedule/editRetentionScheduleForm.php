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
	
	 	<?php // refactor
		$siteUrl = site_url();
		$retentionScheduleID = $retentionSchedule['retentionScheduleID'];
		$assocUnitsScript = "";
		$assocUnitsScript .= "<script type='text/javascript'>";
		$assocUnitsScript .= "$(document).ready(function(){ "; 
		$assocUnitsScript .= "$('select#associatedUnitDivisions').click(function(){ ";
	    $assocUnitsScript .= "$('#loading').show('slow');";
		$assocUnitsScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{divisionID: $(this).val(), retentionScheduleID: $retentionScheduleID, ajax: 'true'}, function(results){ ";
	   	$assocUnitsScript .= "$('#associatedUnitsResults').html(results); ";
		$assocUnitsScript .= "$('#loading').hide('slow');";
	   	$assocUnitsScript .= "}); "; // post
	    $assocUnitsScript .= "}); "; // select ...
	  	$assocUnitsScript .= "}); "; // document...
	    $assocUnitsScript .= "</script>";
	    echo $assocUnitsScript;
	
	    	    
	 	$checkDeptScript = "";
		$checkDeptScript .= "<script type='text/javascript'>";
		$checkDeptScript .= "function checkDepartment(departmentID, retentionScheduleID) { ";
		$checkDeptScript .= "$('#checkBox').show('slow');";
		$checkDeptScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, retentionScheduleID: retentionScheduleID, ajax: 'true'}, function(results){ ";
		$checkDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$checkDeptScript .= "$('#checkBox').hide('slow');";
		$checkDeptScript .= "}); "; // post
		$checkDeptScript .= "} "; // js
		$checkDeptScript .= "</script>";
		echo $checkDeptScript;   
	    
		
		$uncheckDeptScript = "";
		$uncheckDeptScript .= "<script type='text/javascript'>";
		$uncheckDeptScript .= "function uncheckDepartment(departmentID, associatedUnitsID) { ";
		$uncheckDeptScript .= "$('#checkBox').show('slow');";
		$uncheckDeptScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, associatedUnitsID: associatedUnitsID, retentionScheduleID: $retentionScheduleID, ajax: 'true'}, function(results){ ";
		$uncheckDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$uncheckDeptScript .= "$('#checkBox').hide('slow');";
		$uncheckDeptScript .= "}); "; // post
		$uncheckDeptScript .= "} "; // js
		$uncheckDeptScript .= "</script>";
		echo $uncheckDeptScript;
		
		
		$checkOprScript = "";
		$checkOprScript .= "<script type='text/javascript'>";
		$checkOprScript .= "function checkOpr(departmentID) { ";
		$checkOprScript .= "$.post('$siteUrl/retentionSchedule/updateOfficeOfPrimaryResponsibility',{retentionScheduleID: $retentionScheduleID, departmentID: departmentID, ajax: 'true'}, function(results){ ";
		//$checkOprScript .= "$('#associatedUnitsResults').html(results); ";
		$checkOprScript .= "}); "; // post
		$checkOprScript .= "} "; // js
		$checkOprScript .= "</script>";
		echo $checkOprScript;
		
		$checkAllDeptScript = "";
		$checkAllDeptScript .= "<script type='text/javascript'>";
		$checkAllDeptScript .= "function editCheckAll(divisionID) { ";
		$checkAllDeptScript .= "$('#checkBox').show('slow');";
		$checkAllDeptScript .= "$.post('$siteUrl/retentionSchedule/editCheckAllAssociatedUnits',{divisionID: divisionID, retentionScheduleID: $retentionScheduleID, ajax: 'true'}, function(results){ ";
		$checkAllDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$checkAllDeptScript .= "$('#checkBox').hide('slow');";
		$checkAllDeptScript .= "}); "; // post
		$checkAllDeptScript .= "} "; // js
		$checkAllDeptScript .= "</script>";
		echo $checkAllDeptScript;   
		
		$unCheckAllDeptScript = "";
		$unCheckAllDeptScript .= "<script type='text/javascript'>";
		$unCheckAllDeptScript .= "function editUncheckAll(divisionID) { ";
		$unCheckAllDeptScript .= "$('#checkBox').show('slow');";
		$unCheckAllDeptScript .= "$.post('$siteUrl/retentionSchedule/editUnCheckAllAssociatedUnits',{divisionID: divisionID, retentionScheduleID: $retentionScheduleID, ajax: 'true'}, function(results){ ";
		$unCheckAllDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$unCheckAllDeptScript .= "$('#checkBox').hide('slow');";
		$unCheckAllDeptScript .= "}); "; // post
		$unCheckAllDeptScript .= "} "; // js
		$unCheckAllDeptScript .= "</script>";
		echo $unCheckAllDeptScript;   
		
		$officeOfPrimaryResponsibility = "";
		$officeOfPrimaryResponsibility .= "<script type='text/javascript'>";
    	$officeOfPrimaryResponsibility .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$officeOfPrimaryResponsibility .= "$('select#divisions').change(function(){ ";
        $officeOfPrimaryResponsibility .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$officeOfPrimaryResponsibility .= "var options = ''; " ;
      	$officeOfPrimaryResponsibility .= "for (var i = 0; i < j.length; i++) { ";
        $officeOfPrimaryResponsibility .= "options += \"<input name='departmentID' type='radio' value=\" + j[i].departmentID + \" onClick='officeOfPrimaryResponsibilitydepartmentCheck(\" + j[i].departmentID + \", 1)' />\" + j[i].departmentName + '<br />'; " ;
      	$officeOfPrimaryResponsibility .= "}" ;
      	$officeOfPrimaryResponsibility .= "$('#departments').html(options); ";
    	$officeOfPrimaryResponsibility .= "}, 'json'); "; // post
  		$officeOfPrimaryResponsibility .= "}); "; // select ...
		$officeOfPrimaryResponsibility .= "}); "; // document...
     	$officeOfPrimaryResponsibility .= "</script>";
		echo $officeOfPrimaryResponsibility;
		
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs = "";
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "<script type='text/javascript'>";
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "function officeOfPrimaryResponsibilitydepartmentCheck(departmentID, primaryRep) { ";
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, primaryRep: primaryRep, ajax: 'true'}, function(results){ ";
		//$checkDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "}); "; // post
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "} "; // js
		$officeOfPrimaryResponsibilitydepartmentCheckWidgetJs .= "</script>";
		echo $officeOfPrimaryResponsibilitydepartmentCheckWidgetJs;
	 ?>
		
	<div id="tabs">
		<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Retention Schedule</a></li>
        </ul>
       <div id="fragment-1" class="adminForm">
       <br />
		
			<form id="retentionSchedule" method="post" action="<?php echo site_url();?>/retentionSchedule/update">
			<?php 
				// set retentionSchedule ID for updates
				if (isset($retentionSchedule['retentionScheduleID'])) {
					$retentionScheduleID = $retentionSchedule['retentionScheduleID'];
					echo "<input name='retentionScheduleID' type='hidden' value='$retentionScheduleID' />";
					echo "Record ID:<br />";
					echo $retentionScheduleID;
					echo "<br /><br />";
				}

				if (isset($retentionSchedule['uuid'])) {
					$uuid = $retentionSchedule['uuid'];
					echo "Unique Identifier:<br />";
					echo $uuid;
				}
			?>
						
			<br /><br />						
			<label for='recordName'>Record Name:&nbsp;*</label>
			<?php  
				if (isset($retentionSchedule['recordName'])) {
					$recordName = $retentionSchedule['recordName'];
					echo "<br /><input name='recordName' type='text' size='45' value='$recordName' class='required' />";
				}
			?>
			
			<br /><br />
										
			<label for='recordDescription'>Record Description:&nbsp;*</label>
			<?php 
				if (isset($retentionSchedule['recordDescription'])) {
					$recordDescription = $retentionSchedule['recordDescription'];
					echo "<br /><textarea name='recordDescription' rows='3' cols='50' wrap='hard' class='required'>$recordDescription</textarea>";
				} 
			?>
			
			<br /><br />
									
			<label for='recordCategory'>Record Category:&nbsp;*</label>
			<br />
			<select name='recordCategory' size='1' class='required'>
				<option value=''>Select a Record Category</option>
				<option value=''>-----------------</option>
				<?php
					foreach ($recordCategories as $recordCategory) {
						if ($retentionSchedule['recordCategory'] == $recordCategory) {
							echo "<option selected='yes' value='$recordCategory'>$recordCategory</option>";
						} else {
							echo "<option value='$recordCategory'>$recordCategory</option>";
						}
					}
				?>	
			</select>
						
			<br /><br />
			
			<label for='retentionPeriod'>Retention Period:&nbsp;</label><br />
			<?php $retentionPeriod = $retentionSchedule['retentionPeriod']; ?>
			<input name="retentionPeriod" id="retentionPeriod" type="text" value="<?php echo $retentionPeriod; ?>" size="40" value="" />
			<br /><br />
			
			<label for='retentionNotes'>Retention Notes:&nbsp;</label><br />
			<?php $retentionNotes = $retentionSchedule['retentionNotes'] ?>
			<textarea name="retentionNotes" rows="3" cols="50" wrap="hard"><?php echo $retentionNotes; ?></textarea>
			<br /><br />
			
			<label for='rmRetentionDecisions'>Rm Retention Decisions:&nbsp;</label><br />
			<?php $retentionDecisions = $retentionSchedule['retentionDecisions'] ?>
			<textarea name="retentionDecisions" rows="3" cols="50" wrap="hard"><?php echo $retentionDecisions; ?></textarea>
			<br /><br />
			
			<label for='disposition'>Disposition:&nbsp;*</label><br />
						
			<p>
			<select id='dispositions' name='disposition' size='1' class='required'> 
				<option value='' selected='selected'>Select a disposition</option>
				<option value=''>--------------------</option>
				<?php 		
					foreach ($dispositions as $disposition) { // $dispositionID => 
						if ($retentionSchedule['disposition'] == $disposition) {
							echo "<option selected='yes' value='$disposition'>$disposition</option>";
						} else {
							echo "<option value='$disposition'>$disposition</option>";
						}
					}
				?>		
			</select>
			</p>
						
			<div id="dispositionDetails"><!-- disposition details are rendered here --></div>
			<?php $primaryAuthority = $retentionSchedule['primaryAuthority'] ?>				
			<br />
			<label for='primaryAuthority'>Primary Authority:</label><br />
				<input name="primaryAuthority" id="primaryAuthority" type="text" size="40" value="<?php echo $primaryAuthority; ?>" /><br />
			<br />
			
			<label for='primaryAuthorityRetention'>Primary Authority Retention:</label><br />
			<?php $primaryAuthorityRetention = $retentionSchedule['primaryAuthorityRetention'] ?>	
			<input name="primaryAuthorityRetention" id="primaryAuthorityRetention" type="text" size="40" value="<?php echo $primaryAuthorityRetention; ?>" />
			<br /><br /><br />
			
						
			<fieldset>
			<legend>Related Authorities</legend>
				<?php
					$arrayLength = 0; // default is changed if related authority is set
					if (isset($retentionSchedule['relatedAuthority']) && is_array($retentionSchedule['relatedAuthority'])) {
						// get array length
						$arrayLength = count($retentionSchedule['relatedAuthority']); 
						foreach ($retentionSchedule['relatedAuthority'] as $i => $relatedAuthority) {
							echo "Related Authority:<br />";
							echo "<input name='relatedAuthorityID[]' type='hidden' value='$i' />";
							echo "<input name='relatedAuthorities[]' class='relatedAuthority' type='text' size='40' value='$relatedAuthority' /><br /><br />";
							foreach ($retentionSchedule['relatedAuthorityRetention'] as $j => $relatedAuthorityRetention) {
								if ($i == $j) {
									echo "Related Authority Retention:<br />";
									echo "<input name='relatedAuthorityRetentionID[]' type='hidden' value='$j' />";
									echo "<input name='relatedAuthorityRetentions[]' class='relatedAuthority' type='text' size='40' value='$relatedAuthorityRetention' /><br /><br />"; 
								}
							}
						}
					}
										
					// Add new related authority fields
					if ($arrayLength !== 9) {
						$fieldCount = (9 - $arrayLength);
						$fieldCount = ($fieldCount / 2);
						$fieldCount = round($fieldCount); 
						while ($fieldCount) {
							echo "Related Authority:<br />";
							echo "<input name='newRelatedAuthorities[]' class='relatedAuthority' type='text' size='40' value='' /><br /><br />";
							echo "Related Authority Retention:<br />";
							echo "<input name='newRelatedAuthorityRetentions[]' class='relatedAuthority' type='text' size='40' value='' /><br /><br />"; 
						$fieldCount--;
						}
					}
				?>
				<br /><br />
			</fieldset>
			<br />
								
			<label for='Office of Primary Responsibility'>Office of Primary Responsibility:</label><br />
			
				<label for='divisions'></label>
				<select id='divisions' name='divisionID' size='1' class='required'>
				<option value='' selected='selected'>Select your division</option>
				<option value=''>--------------------</option>
				
				<?php 
					foreach ($divisions as $divisionID => $divisionName) {
						if ($divisionID == $retentionSchedule['divisionID']) {
							echo "<option selected='yes' value='$divisionID'>$divisionName</option>";
						} else {
							echo "<option value='$divisionID'>$divisionName</option>";
						}
					}
				?>
				</select>&nbsp;&nbsp;*
				&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
								
				<div id="departments">
				<?php   
					foreach ($oprDepartments as $departmentID => $department) {
						if ($department !== "All Departments") {
							if ($retentionSchedule['officeOfPrimaryResponsibility'] == $departmentID) {
								$departmentID = $retentionSchedule['officeOfPrimaryResponsibility'];
								echo "<input name='departmentID' type='radio' value='$departmentID' onClick='checkOpr($departmentID);' checked />$department<br />";	
							} else {
								echo "<input name='departmentID' type='radio' value='$departmentID' onClick='checkOpr($departmentID);' />$department<br />";						
							}
						}
					}
				?>
				</div>
				</p>
			
			<br />
			<br />
			<label for='associatedUnits'>Associated Units:</label>
			<div id="auContainer">
				<div id="loadingContainer">
					<span id="loading">Loading...</span>
					<span id="checkBox">Saving...</span>
				</div>
				<div id="associatedUnits">	
					<select id='associatedUnitDivisions' name='divisionID' size='32' multiple='multiple' class='required'>
						<option value=''>--------------------</option>
						<?php 
							foreach ($divisions as $divisionID => $divisionName) {
								if (in_array($divisionID, $retentionSchedule['auDivisionIDs'])) {
									echo "<option value='$divisionID' class='auSelect'>->[$divisionName]</option>";
								} else {
									echo "<option value='$divisionID'>$divisionName</option>";
								}
							}
						?>
						<option value=''>--------------------</option>	
					</select>&nbsp;&nbsp;
				</div>				
				<div id="associatedUnitsResults">Select a division</div> 
			</div> 
			
			<br /><br />
			
			<label for='notes'>RM notes:&nbsp;<br /></label>
			<?php $notes = $retentionSchedule['notes'] ?>		
				<textarea name="notes" rows="3" cols="50" wrap="hard"><?php echo $notes; ?></textarea>
			<br /><br />
			
			<label for='vitalRecord'>Vital Record:</label><br />
				<?php 
					if (isset($retentionSchedule['vitalRecord']) && $retentionSchedule['vitalRecord'] == "yes") { 
						echo "<input name='vitalRecord' type='radio' value='yes' checked />&nbsp;Yes<br />";
						echo "<input name='vitalRecord' type='radio' value='no' />&nbsp;No<br />";
					}
					if (isset($retentionSchedule['vitalRecord']) && $retentionSchedule['vitalRecord'] == "no") { 
						echo "<input name='vitalRecord' type='radio' value='yes' />&nbsp;Yes<br />";
						echo "<input name='vitalRecord' type='radio' value='no' checked />&nbsp;No<br />";
					}
				?>
			<br />
			
			<label for='approvedByCouncil'>Approved by Counsel:&nbsp;*</label><br />
				<?php 
					if (isset($retentionSchedule['approvedByCounsel']) && $retentionSchedule['approvedByCounsel'] == "yes") { 
						echo "<input name='approvedByCounsel' type='radio' value='yes' checked />&nbsp;Yes<br />";
						echo "<input name='approvedByCounsel' type='radio' value='no' />&nbsp;No<br />";
					}
					if (isset($retentionSchedule['approvedByCounsel']) && $retentionSchedule['approvedByCounsel'] == "no") { 
						echo "<input name='approvedByCounsel' type='radio' value='yes' />&nbsp;Yes<br />";
						echo "<input name='approvedByCounsel' type='radio' value='no' checked />&nbsp;No<br />";
					}
				?>
			<br /><br />
			<br />
				<input name="retentionSchedule" type="submit" value="Update Retention Schedule" />&nbsp;&nbsp;
			</form>
			<br />
	</div>
</div>
<?php $this->load->view('includes/adminFooter'); ?>