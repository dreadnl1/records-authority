/**
*
* University of Denver - Penrose Library
* provides ajax functionality to liaison public search screens
*
*/
		
// prepare the form when the DOM is ready and set AJAX submit options
$(document).ready(function() { 
	
	// changes search page title  ex. Search retention schedule to Retention schedule results
	function changePageTitle() {
		document.getElementById('title').innerHTML = "Retention Schedule Search Results";
	}	
	
//----------------full-text search form------------------------------//	
    var searchRetentionSchedulesOptions = { 
        success:		displayRetentionScheduleSearchResults, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		4000, 
	    beforeSend: 	retentionScheduleSearchBegins, // changes submit button text
		complete: 		retentionScheduleSearchEnds 
    }; 

	
	var validateFTSearchRetentionSchedules = $("#searchRetentionSchedules").validate({
									rules: {
										//keyword: "required"
									},
									messages: {
										//keyword: "Required"	
									},		
									submitHandler: function(searchRetentionSchedules) {
									$(searchRetentionSchedules).ajaxSubmit(searchRetentionSchedulesOptions); 
								}
							});


function retentionScheduleSearchBegins() {
	document.forms['searchRetentionSchedules'].searchRetentionSchedules.value = "Searching...";
}

function retentionScheduleSearchEnds() {
	document.forms['searchRetentionSchedules'].searchRetentionSchedules.value = "Search";
	changePageTitle();
}

// post-submit callback...injects search results into div
function displayRetentionScheduleSearchResults(responseText, statusText) {
	$("#retentionScheduleSearchResults").html(responseText); // injects html into div
}

//------------------search by div/dept-------------------------------//

    var divDeptsearchRetentionSchedulesOptions = { 
        success:		displayRetentionScheduleDivDeptSearchResults, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		4000, 
	    beforeSend: 	retentionScheduleDivDeptSearchBegins, // changes submit button text
		complete: 		retentionScheduleDivDeptSearchEnds 
    }; 

	
	var validateDDSearchRetentionSchedules = $("#divDeptSearchRetentionSchedules").validate({
									rules: {
										departmentID: "required"
									},
									messages: {
										departmentID: "Please select a department."	
									},		
									submitHandler: function(divDeptSearchRetentionSchedules) {
									$(divDeptSearchRetentionSchedules).ajaxSubmit(divDeptsearchRetentionSchedulesOptions); 
								}
							});


function retentionScheduleDivDeptSearchBegins() {
	document.forms['divDeptSearchRetentionSchedules'].searchRetentionSchedules.value = "Searching...";
}

function retentionScheduleDivDeptSearchEnds() {
	document.forms['divDeptSearchRetentionSchedules'].searchRetentionSchedules.value = "Search";
	changePageTitle();
}

// search response
// post-submit callback...injects search results into div
function displayRetentionScheduleDivDeptSearchResults(responseText, statusText) {
	$("#divDeptRetentionScheduleSearchResults").html(responseText); // injects html into div
}


//------------------search by record category -------------------------------//

    var recordCategorySearchRetentionSchedulesOptions = { 
        success:		displayRetentionScheduleRecordCategorySearchResults, // post-submit callback 
        resetForm:		false,        // reset the form after successful submit 
     	timeout:   		4000, 
	    beforeSend: 	retentionScheduleRecordCategorySearchBegins, // changes submit button text
		complete: 		retentionScheduleRecordCategorySearchEnds 
    }; 

	
	var validateRCSearchRetentionSchedules = $("#recordCategorySearchRetentionSchedules").validate({
									rules: {
										recordCategory: "required"
									},
									messages: {
										recordCategory: "Please select a record category."	
									},		
									submitHandler: function(recordCategorySearchRetentionSchedules) {
									$(recordCategorySearchRetentionSchedules).ajaxSubmit(recordCategorySearchRetentionSchedulesOptions); 
								}
							});


function retentionScheduleRecordCategorySearchBegins() {
	document.forms['recordCategorySearchRetentionSchedules'].searchRetentionSchedules.value = "Searching...";
}

function retentionScheduleRecordCategorySearchEnds() {
	document.forms['recordCategorySearchRetentionSchedules'].searchRetentionSchedules.value = "Search";
	changePageTitle();
}

// search response
// post-submit callback...injects search results into div
function displayRetentionScheduleRecordCategorySearchResults(responseText, statusText) {
	$("#recordCategoryRetentionScheduleSearchResults").html(responseText); // injects html into div
}

});  // closes document