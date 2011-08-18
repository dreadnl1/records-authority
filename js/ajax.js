/*
 * Survey Builder AJAX/jQuery - f.r. University of Denver - Penrose Library 
 */

$(function(){	
	// tabs
	$('#tabs > ul').tabs();
}); 
		
// prepare the form when the DOM is ready and set AJAX submit options
$(document).ready(function() { 
   
   // Survey Builder
    var questionTypeOne = { 
        //success:      showQuestionTypeOneResponse,  // post-submit callback 
        resetForm: 		true,        // reset the form after successful submit 
        // $.ajax options can be used here too, for example: 
        //timeout:   	3000 
		beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();} 
    }; 
 
    var questionTypeTwo = { 
        success:		questionTypeTwoResponse, // post-submit callback 
        resetForm:		true,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();} 
    }; 
	
	var subQuestionTypeTwo = {  
        success:		subQuestionTypeTwoResponse,  // post-submit callback 
        resetForm: 		true,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();} 
    }; 
	
	var questionTypeThree = {  
        success:		questionTypeThreeResponse, // post-submit callback 
        resetForm:		true,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();} 
    }; 
	
	var subQuestionTypeThree = { 
        success:		subQuestionTypeThreeResponse,  // post-submit callback 
        resetForm: 		false,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();} 
    }; 
	
	var questionTypeFour = {
		success:		questionTypeFourResponse,  // post-submit callback 
        resetForm: 		true,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();}  
	}
	
	var questionTypeFourContactField = {
		//success:		questionTypeFourContactFieldResponse,  // post-submit callback 
        resetForm: 		true,        // reset the form after successful submit 
        beforeSend: 	function(){$("#saving").fadeIn();}, 
		complete: 		function(){$("#saving").fadeOut();}  
	}
	
	// Record Type Form
 	var recordTypeFormOptions = { 
        success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
        timeout:   		3000, 
		beforeSend: 	function(){$("#setDepartment").fadeIn();}, 
		complete: 		function(){$("#setDepartment").fadeOut();} 
    }; 
	
	var recordTypeRecordInformationOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
        timeout:   		3000, 
		beforeSend: 	saveRecordInformation, // changes submit button text   
		complete: 		recordInformationSaved // disables submit button
    }; 
			
	var recordTypeFormatAndLocationOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		3000, 
	    beforeSend: 	saveFormatAndLocation,  // changes submit button text 
		complete: 		formatAndLocationSaved // disables submit button
    }; 
	
	var recordTypeManagementOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
        timeout:   		3000, 
		beforeSend: 	saveManagement,  // changes submit button text 
		complete: 		managementSaved // disables submit button
    }; 
	
	// update Record Type Form
	var updateRecordTypeRecordInformationOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
        timeout:   		3000, 
		beforeSend: 	updateRecordInformation, // changes submit button text  
		complete: 		recordInformationUpdated  // disables submit button
    }; 
    
    var updateRecordTypeFormatAndLocationOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		3000, 
	    beforeSend: 	updateFormatAndLocation, // changes submit button text
		complete: 		formatAndLocationUpdated //  disables submit button
    }; 
    
     var updateRecordTypeManagementOptions = { 
        //success:		recordTypeDepartmentResponse, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		3000, 
	    beforeSend: 	updateManagement, // changes submit button text
		complete: 		managementUpdated // disables submit button
    }; 

//-----------------------------------------------------------------//

	// Survey Builder / validate and send form values 
	var validateQuestionTypeOne = $("#addSurveyChoice1Questions").validate({
									rules: {
										question: "required"
									},
									messages: {
										question: "Please enter a question"	
									},		
									submitHandler: function(addSurveyChoice1Questions) {
									$(addSurveyChoice1Questions).ajaxSubmit(questionTypeOne); 
								}
							});
	
    
	var validateQuestionTypeTwo = $("#addSurveyChoice2Questions").validate({
									rules: {
										question: "required"	
									},
									messages: {
										question: "Please enter a question"
									},
									submitHandler: function(addSurveyChoice2Questions) {
									$(addSurveyChoice2Questions).ajaxSubmit(questionTypeTwo); 
								}
							});
							
	var validateSubQuestionTypeTwo = $("#addSurveyChoice2SubQuestions").validate({
									rules: {
										subQuestion: "required"	
									},
									messages: {
										subQuestion: "Please enter a sub question"
									},
									submitHandler: function(addSurveyChoice2SubQuestions) {
									$(addSurveyChoice2SubQuestions).ajaxSubmit(subQuestionTypeTwo); 
								}
							});
							
	var validateQuestionTypeThree = $("#addSurveyChoice3Questions").validate({
									rules: {
										question: "required"	
									},
									messages: {
										question: "Please enter a question"
									},
									submitHandler: function(addSurveyChoice3Questions) {
									$(addSurveyChoice3Questions).ajaxSubmit(questionTypeThree); 
								}
							});
							
	
	var validateSubQuestionTypeThree = $("#addSurveyChoice3SubQuestions").validate({
									rules: {
										subQuestion: "required"	
									},
									messages: {
										subQuestion: "Please enter a sub question"
									},
									submitHandler: function(addSurveyChoice3SubQuestions) {
									$(addSurveyChoice3SubQuestions).ajaxSubmit(subQuestionTypeThree); 
								}
							});						

	
	var validateQuestionTypeFour = $("#addSurveyChoice4Questions").validate({
								rules: {
									contactQuestion: "required"
								},
								messages: {
									contactQuestion: "Please enter a contact question"
								},
								submitHandler: function(addSurveyChoice4Questions) {
								$(addSurveyChoice4Questions).ajaxSubmit(questionTypeFour);
							}
						});
	
	
	var validateQuestionTypeFourContactField = $("#addSurveyChoice4ContactFields").validate({
								//rules: {
									//contactQuestion: "required"
								//},
								//messages: {
									//contactQuestion: "Please enter a contact question"
								//},
								submitHandler: function(addSurveyChoice4ContactFields) {
								$(addSurveyChoice4ContactFields).ajaxSubmit(questionTypeFourContactField);
							}
						});
							
	// Record type form
	var validateRecordTypeForm = $("#recordTypeDepartment").validate({
									rules: {
										departmentID: "required"	
									},
									messages: {
										departmentID: "Please select a department"
									},
									submitHandler: function(recordTypeDepartment) {
									$(recordTypeDepartment).ajaxSubmit(recordTypeFormOptions); 
								}
							});	
							
	var validateRecordInformationRecordTypeForm = $("#recordInformation").validate({
									rules: {
										recordTypeDepartment: "required"	
									},
									messages: {
										recordTypeDepartment: "Please select a department"
									},
									submitHandler: function(recordInformation) {
									$(recordInformation).ajaxSubmit(recordTypeRecordInformationOptions); 
								}
							});	
							
	var validateFormatAndLocationRecordTypeForm = $("#formatAndLocation").validate({
									rules: {
										recordTypeDepartment: "required"	
									},
									messages: {
										recordTypeDepartment: "Please select a department"
									},
									submitHandler: function(formatAndLocation) {
									$(formatAndLocation).ajaxSubmit(recordTypeFormatAndLocationOptions); 
								}
							});	
							
	var validateManagementRecordTypeForm = $("#management").validate({
									rules: {
										recordTypeDepartment: "required"	
									},
									messages: {
										recordTypeDepartment: "Please select a department"
									},
									submitHandler: function(management) {
									$(management).ajaxSubmit(recordTypeManagementOptions); 
								}
							});												

   var validateUpdateRecordInformationRecordTypeForm = $("#updateRecordInformation").validate({
									rules: {
										recordName: "required"
										//recordCategory: "required"	
									},
									messages: {
										recordName: "Please enter a Record Name"
										//recordCategory: "Please enter a Record Category
									},
									submitHandler: function(updateRecordInformation) {
									$(updateRecordInformation).ajaxSubmit(updateRecordTypeRecordInformationOptions); 
								}
							});	
    
    var validateUpdateFormatAndLocationRecordTypeForm = $("#updateFormatAndLocation").validate({
									rules: {
										//recordTypeDepartment: "required"	
									},
									messages: {
										//recordTypeDepartment: "Please select a department"
									},
									submitHandler: function(updateFormatAndLocation) {
									$(updateFormatAndLocation).ajaxSubmit(updateRecordTypeFormatAndLocationOptions); 
								}
							});	
							
	 var validateUpdateManagementRecordTypeForm = $("#updateManagement").validate({
									rules: {
										//recordTypeDepartment: "required"	
									},
									messages: {
										//recordTypeDepartment: "Please select a department"
									},
									submitHandler: function(updateManagement) {
									$(updateManagement).ajaxSubmit(updateRecordTypeManagementOptions); 
								}
							});			

//-----------------------------------------------------------------//

// post-submit callback for question type 2
function questionTypeTwoResponse(responseText, statusText)  { 
	//add returned value to hidden form field if the response is not equal to 0
	if (responseText !== '0') {
		document.forms['addSurveyChoice2SubQuestions'].questionID.value = responseText;
		$("#mainQuestionTypeTwo").hide();
		$("#subQuestionTypeTwo").show();
	}
} 

// post-submit callback for sub question type 2 
function subQuestionTypeTwoResponse(responseText, statusText)  { 
	//add returned value to hidden form field if the response is not equal to 0
	//if (responseText !== "") {
		//document.forms['addSurveyChoice3SubQuestions'].questionID.value = responseText;
		$("#mainQuestionTypeTwo").show();
		$("#subQuestionTypeTwo").hide();
	//}
} 

// post-submit callback for question type 3
function questionTypeThreeResponse(responseText, statusText)  { 
	//add returned value to hidden form field if the response is not equal to 0
	if (responseText !== '0') {
		document.forms['addSurveyChoice3SubQuestions'].questionID.value = responseText;
		$("#mainQuestionTypeThree").hide();
		$("#subQuestionTypeThree").show();
	}
} 

// post-submit callback for sub question type 3 
function subQuestionTypeThreeResponse(responseText, statusText)  { 
	//clear subQuestion values after submission
	document.forms['addSurveyChoice3SubQuestions'].subQuestion.value = "";
	document.forms['addSurveyChoice3SubQuestions'].subChoiceQuestion.value = "";
	$("#mainQuestionTypeThree").hide();
	$("#subQuestionTypeThree").show();
} 

// record type call back function / places departmentID into form hidden fields
function recordTypeDepartmentResponse(responseText, statusText)  { 
	//add returned value to hidden form field if the response is not null
	if (responseText !== ' ') {
		document.forms['recordInformation'].recordTypeDepartment.value = responseText;
		document.forms['formatAndLocation'].recordTypeDepartment.value = responseText;
		document.forms['management'].recordTypeDepartment.value = responseText;
	}
} 

// post-submit callback for question type 4
function questionTypeFourResponse(responseText, statusText) {
	// add returned value to hidden form field if response is not null
	if (responseText !== 0) {
		document.forms['addSurveyChoice4ContactFields'].contactQuestionID.value = responseText;
		$("#addSurveyChoice4Questions").hide();
		$("#contactFieldTypeFour").show();
	}
}

// resets the question type 3 form if user no longer wishes to add sub questions
$("#questionTypeThreeReset").click(function () {
	$("#mainQuestionTypeThree").show("slide", {}, "fast");
	$("#subQuestionTypeThree").hide("slide", {}, "fast");
   });

// displays sub question form field for question type 3
$("#subChoiceQuestionToggle").click(function () {
      $("#subChoiceQuestion").toggle("slide", {}, "fast");
    });

//-----------------------------------------------------------------//

// changes submit button text on recordTypeForm
function saveRecordInformation() {
	document.forms['recordInformation'].recordInformation.value = "Saving...";
}

// disables submit button
function recordInformationSaved() {
	document.forms['recordInformation'].recordInformation.value = "Record Saved";
	document.forms['recordInformation'].recordInformation.disabled = true;
}

// changes submit button text on recordTypeForm
function saveFormatAndLocation() {
	document.forms['formatAndLocation'].formatAndLocation.value = "Saving...";
}

// disables submit button
function formatAndLocationSaved() {
	document.forms['formatAndLocation'].formatAndLocation.value = "Record Saved";
	document.forms['formatAndLocation'].formatAndLocation.disabled = true;
}

// changes submit button text on recordTypeForm
function saveManagement() {
	document.forms['management'].management.value = "Saving...";
}

// disables submit button
function managementSaved() {
	document.forms['management'].management.value = "Record Saved";
	document.forms['management'].management.disabled = true;
}

function updateRecordInformation() {
	document.forms['updateRecordInformation'].updateRecordInformation.value = "Updating...";
}

function recordInformationUpdated() {
	document.forms['updateRecordInformation'].updateRecordInformation.value = "Record Updated";
}

function updateFormatAndLocation() {
	document.forms['updateFormatAndLocation'].updateFormatAndLocation.value = "Updating...";
}

function formatAndLocationUpdated() {
	document.forms['updateFormatAndLocation'].updateFormatAndLocation.value = "Record Updated";
}

function updateManagement() {
	document.forms['updateManagement'].updateManagement.value = "Updating...";
}

function managementUpdated() {
	document.forms['updateManagement'].updateManagement.value = "Record Updated";
}

//-----------------------------------------------------------------//

// toggle form divs for record type form
$('#showSystem').click(function () { 
$('#system').show('fast'); 
}); 
		
$('#hideSystem').click(function () { 
$('#system').hide('fast'); 
}); 
		
$('#toggleOther').click(function () { 
$('#otherText').toggle('fast'); 
}); 
		
$('#showPaperVersion').click(function () { 
$('#paperVersion').show('fast'); 
}); 
		
$('#hidePaperVersion').click(function () { 
$('#paperVersion').hide('fast'); 
}); 
		
$('#toggleOtherBuilding').click(function () { 
$('#otherBuildingText').toggle('fast'); 
}); 
		
$('#toggleOtherStorage').click(function () { 
$('#otherStorageText').toggle('fast'); 
}); 
		
$('#showRecordLocation').click(function () { 
$('#recordLocation').show('fast'); 
}); 
		
$('#hideRecordLocation').click(function () { 
$('#recordLocation').hide('fast'); 
}); 
				
$('#toggleBackupMedia').click(function () { 
$('#backupMediaText').toggle('fast'); 
}); 
		
$('#toggleOtherRecordLocation').click(function () { 
$('#otherRecordLocationText').toggle('fast'); 
}); 
		
$('#showLegalRequirments').click(function () { 
$('#legalRequirments').show('fast'); 
}); 
		
$('#hideLegalRequirments').click(function () { 
$('#legalRequirments').hide('fast'); 
}); 
		
$('#showDestroyRecords').click(function () { 
$('#destruction').show('fast'); 
}); 
		
$('#hideDestroyRecords').click(function () { 
$('#destruction').hide('fast'); 
}); 
		
$('#showTransferToArchives').click(function () { 
$('#transferToArchives').show('fast'); 
}); 
		
$('#hideTransferToArchives').click(function () { 
$('#transferToArchives').hide('fast'); 
}); 
		
$('#showVitalRecords').click(function () { 
$('#vitalRecords').show('fast'); 
}); 
		
$('#hideVitalRecords').click(function () { 
$('#vitalRecords').hide('fast'); 
}); 
		
$('#showSensitiveInformation').click(function () { 
$('#sensitiveInformation').show('fast'); 
});
		
$('#hideSensitiveInformation').click(function () { 
$('#sensitiveInformation').hide('fast'); 
}); 
		
$('#showSecureRecords').click(function () { 
$('#security').show('fast'); 
}); 
		
$('#hideSecureRecords').click(function () { 
$('#security').hide('fast'); 
}); 

$('#showDuplication').click(function () { 
$('#duplication').show('fast'); 
}); 
	
$('#hideDuplication').click(function () { 
$('#duplication').hide('fast'); 
}); 
	
// auto suggest used on file format text field
$('#fileFormat').autocomplete(['pdf', 'docx', 'doc', 'txt', 'gif', 'jpg', 'jpeg', 'vsd', 'xls'] ); 	
				
});  // closes document