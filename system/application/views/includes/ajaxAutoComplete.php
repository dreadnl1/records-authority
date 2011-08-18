<?php
/*
 * File is used to dynamically generate javascript variables and locations
 */

	$siteName = $this->config->item('site_name');
?>

<script type="text/javascript">
	$(document).ready(function() {
	// auto suggest used on file format text field in recordTypeForm
	$('#fileFormat').autocomplete('/<?php echo $siteName;?>/index.php/dashboard/autoSuggest_docTypes');	
	// auto suggest used on retentionPeriod field in addRetentionScheduleForm
	$('#retentionPeriod').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_retentionPeriods');
	// auto suggest used on primary authority fields in addRetentionScheduleForm
	$('#primaryAuthority').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_primaryAuthorities');		
	// auto suggest used on primary authority retention field in addRetentionScheduleForm
	$('#primaryAuthorityRetention').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_primaryAuthorityRetentions');
	// auto suggest used on related authority fields in addRetentionScheduleForm
	$('.relatedAuthority').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_relatedAuthorities');	
	// auto suggest used on Minimum Required Retention field in addRetentionScheduleForm
	$('.relatedAuthorityRetention').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_relatedAuthorityRetention');
	// auto suggest used on editRecordTypeForm
	$('#recordName').autocomplete('/<?php echo $siteName;?>/index.php/retentionSchedule/autoSuggest_recordName');
		
	});
</script>
