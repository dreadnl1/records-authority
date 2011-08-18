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
 
 
 class JsModel extends CI_Model {

	public function __construct() {
		parent::__construct();
	} 
	
	// TODO: refactor...use only one pop up method - passing width and height as arguments
	
	/**
    * generates a javaScript pop up
    *
    * @access public
    * @return $popUpParams
    */
	public function popUp() {
		$popUpParams = array(
						'width' => '800',
						'height' => '800',
						'scrollbars' => 'yes',
             			'status'     => 'yes',
             			'resizable'  => 'yes',
             			'screenx'    => '0',
             			'screeny'    => '0'
					);
		return $popUpParams;
	}
	
	/**
    * generates a javaScript search pop up
    *
    * @access public
    * @return $popUpParams
    */
	public function searchPopUp() {
		$searchPopUpParams = array(
						'width' => '1300',
						'height' => '1000',
						'scrollbars' => 'yes',
             			'status'     => 'yes',
             			'resizable'  => 'yes',
             			'screenx'    => '0',
             			'screeny'    => '0'
					);		
		return $searchPopUpParams;
	}

 	/**
    * generates a small javaScript pop up
    *
    * @access public
    * @return $popUpParams
    */
	public function smallPopUp() {
		$popUpParams = array(
						'width' => '500',
						'height' => '175',
						'scrollbars' => 'yes',
             			'status'     => 'yes',
             			'resizable'  => 'yes',
             			'screenx'    => '0',
             			'screeny'    => '0'
					);
		return $popUpParams;
	}
	
 	/**
    * generates a small javaScript pop up
    *
    * @access public
    * @return $popUpParams
    */
	public function mediumPopUp() {
		$popUpParams = array(
						'width' => '700',
						'height' => '270',
						'scrollbars' => 'yes',
             			'status'     => 'yes',
             			'resizable'  => 'yes',
             			'screenx'    => '0',
             			'screeny'    => '0'
					);
		return $popUpParams;
	}
	
	/**
    * generates a shadowbox
    *
    * @access public
    * @return $popUpParams
    */
	public function shadowboxPopUp() {
		$popUpParams = array(
						'rel' => 'shadowbox;player=iframe;width=1200;height=800'
					);
		return $popUpParams;
	}
	
  	/**
    * generates a small shadowbox
    *
    * @access public
    * @return $popUpParams
    */
	public function shadowboxMediumPopUp() {
		$popUpParams = array(
						'rel' => 'shadowbox;player=iframe;width=700;height=270'
					);
		return $popUpParams;
	}
	
 	/**
    * generates a javaScript pop up for retention schedule form
    *
    * @access public
    * @return $popUpParams
    */
	public function retentionSchedulePopUp() {
		$popUpParams = array(
						'width' => '800',
						'height' => '800',
						'scrollbars' => 'yes',
             			'status'     => 'yes',
             			'resizable'  => 'yes',
             			'screenx'    => '0',
             			'screeny'    => '0'
					);
		return $popUpParams;
	}
	
	/**
    * retrieves departments (JSON) based on the division that is selected
    *
    * @access public
    * @param $siteUrl
    * @return $jQuery
    */
	public function departmentWidgetJs($siteUrl) {
		
		$jQuery = "";
		$jQuery .= "<script type='text/javascript'>";
    	$jQuery .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$jQuery .= "$('select#divisions').change(function(){ ";
        $jQuery .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQuery .= "var options = ''; " ;
      	$jQuery .= "for (var i = 0; i < j.length; i++) { ";
        $jQuery .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQuery .= "}" ;
      	$jQuery .= "$('select#departments').html(options); ";
    	$jQuery .= "}, 'json'); "; // post
  		$jQuery .= "}); "; // select ...
		$jQuery .= "}); "; // document...
     	$jQuery .= "</script>";
     	
     	return $jQuery;
		
	}
	
	
 	/**
    * retrieves departments (JSON) based on the division that is selected
    * places values in input radio buttons
    *
    * @access public
    * @param $siteUrl
    * @return $jQuery
    */
	public function departmentRadioButtonWidgetJs($siteUrl) {
		
		$jQuery = "";
		$jQuery .= "<script type='text/javascript'>";
    	$jQuery .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$jQuery .= "$('select#divisions').change(function(){ ";
        $jQuery .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQuery .= "var options = ''; " ;
      	$jQuery .= "for (var i = 0; i < j.length; i++) { ";
        $jQuery .= "options += \"<input name='departmentID' type='radio' value=\" + j[i].departmentID + \" onClick='officeOfPrimaryResponsibilitydepartmentCheck(\" + j[i].departmentID + \", 1)' />\" + j[i].departmentName + '<br />'; " ;
      	$jQuery .= "}" ;
      	$jQuery .= "$('#departments').html(options); ";
    	$jQuery .= "}, 'json'); "; // post
  		$jQuery .= "}); "; // select ...
		$jQuery .= "}); "; // document...
     	$jQuery .= "</script>";
     	
     	return $jQuery;
		
	}
	
	
 	public function officeOfPrimaryResponsibilitydepartmentCheckWidgetJs($siteUrl) {
	 	$checkDeptScript = "";
		$checkDeptScript .= "<script type='text/javascript'>";
		$checkDeptScript .= "function officeOfPrimaryResponsibilitydepartmentCheck(departmentID, primaryRep) { ";
		$checkDeptScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, primaryRep: primaryRep, ajax: 'true'}, function(results){ ";
		//$checkDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$checkDeptScript .= "}); "; // post
		$checkDeptScript .= "} "; // js
		$checkDeptScript .= "</script>";
		
		return $checkDeptScript;
 	}
	
	/**
    * retrieves dispositions (JSON) 
    *
    * @access public
    * @param $siteUrl
    * @return $dispositionScript
    */
	public function dispositionWidgetJs($siteUrl) {
		$dispositionScript = "";
		$dispositionScript .= "<script type='text/javascript'>";
		$dispositionScript .= "$(document).ready(function(){ "; 
		$dispositionScript .= "$('select#dispositions').change(function(){ ";
	    $dispositionScript .= "$.post('$siteUrl/retentionSchedule/getDispositionDetails',{dispositionID: $(this).val(), ajax: 'true'}, function(results){ ";
	    $dispositionScript .= "$('#dispositionDetails').html(results); ";
	    $dispositionScript .= "}); "; // post
	    $dispositionScript .= "}); "; // select ...
	  	$dispositionScript .= "}); "; // document...
	    $dispositionScript .= "</script>";
		
	    return $dispositionScript;
	}
	
	
	public function associatedUnitWidgetJs($siteUrl) { 
		$assocUnitsScript = "";
		$assocUnitsScript .= "<script type='text/javascript'>";
		$assocUnitsScript .= "$(document).ready(function(){ "; 
		$assocUnitsScript .= "$('select#associatedUnitDivisions').click(function(){ ";
	    $assocUnitsScript .= "$('#loading').show('slow');";
		$assocUnitsScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{divisionID: $(this).val(), ajax: 'true'}, function(results){ ";
	    $assocUnitsScript .= "$('#associatedUnitsResults').html(results); ";
	    $assocUnitsScript .= "$('#loading').hide('slow');";
	    $assocUnitsScript .= "}); "; // post
	    $assocUnitsScript .= "}); "; // select ...
	  	$assocUnitsScript .= "}); "; // document...
	    $assocUnitsScript .= "</script>";
	    
	    return $assocUnitsScript;
 	}
 	 	 	
 	public function associatedUnitCheckAllWidgetJs($siteUrl) {
 		$checkAllScript = "";
		$checkAllScript .= "<script type='text/javascript'>";
		$checkAllScript .= "function checkAll(divisionID) { ";
		$checkAllScript .= "$('#checkBox').show('slow');";
		$checkAllScript .= "$.post('$siteUrl/retentionSchedule/check_associatedUnits',{divisionID: divisionID, ajax: 'true'}, function(results){ ";
		$checkAllScript .= "$('#associatedUnitsResults').html(results); ";
		$checkAllScript .= "$('#checkBox').hide('slow');";
		$checkAllScript .= "}); "; // post
		$checkAllScript .= "} "; // js
		$checkAllScript .= "</script>";
		
		return $checkAllScript;
 	}
 	
 	public function associatedUnitUnCheckAllWidgetJs($siteUrl) {
 		$uncheckAllScript = "";
		$uncheckAllScript .= "<script type='text/javascript'>";
		$uncheckAllScript .= "function uncheckAll(divisionID, uuid) { ";
		$uncheckAllScript .= "$('#checkBox').show('slow');";
		$uncheckAllScript .= "$.post('$siteUrl/retentionSchedule/uncheck_associatedUnits',{divisionID: divisionID, uuid: uuid, ajax: 'true'}, function(results){ ";
		$uncheckAllScript .= "$('#associatedUnitsResults').html(results); ";
		$uncheckAllScript .= "$('#checkBox').hide('slow');";
		$uncheckAllScript .= "}); "; // post
		$uncheckAllScript .= "} "; // js
		$uncheckAllScript .= "</script>";
		
		return $uncheckAllScript;
 	}
 	
 	public function associatedUnitDeptCheck($siteUrl) {
	 	$checkDeptScript = "";
		$checkDeptScript .= "<script type='text/javascript'>";
		$checkDeptScript .= "function checkDepartment(departmentID) { ";
		$checkDeptScript .= "$('#checkBox').show('slow');";
		$checkDeptScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, ajax: 'true'}, function(results){ ";
		$checkDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$checkDeptScript .= "$('#checkBox').hide('slow');";
		$checkDeptScript .= "}); "; // post
		$checkDeptScript .= "} "; // js
		$checkDeptScript .= "</script>";
		
		return $checkDeptScript;
 	}
 	
 	public function associatedUnitDeptUnCheck($siteUrl) {
 		$uncheckDeptScript = "";
		$uncheckDeptScript .= "<script type='text/javascript'>";
		$uncheckDeptScript .= "function uncheckDepartment(departmentID) { ";
		$uncheckDeptScript .= "$('#checkBox').show('slow');";
		$uncheckDeptScript .= "$.post('$siteUrl/retentionSchedule/getAssociatedUnits',{departmentID: departmentID, ajax: 'true'}, function(results){ ";
		$uncheckDeptScript .= "$('#associatedUnitsResults').html(results); ";
		$uncheckDeptScript .= "$('#checkBox').hide('slow');";
		$uncheckDeptScript .= "}); "; // post
		$uncheckDeptScript .= "} "; // js
		$uncheckDeptScript .= "</script>";
		
		return $uncheckDeptScript; 
 	}
	
	
 	public function sortByWidgetJs($siteUrl) {
 		$sortByScript = "";
		$sortByScript .= "<script type='text/javascript'>";
		$sortByScript .= "function sort(departmentID, divisionID, sortBy) { ";
		$sortByScript .= "$.post('$siteUrl/search/getRetentionSchedules',{departmentID: departmentID, divisionID: divisionID, sortBy: sortBy ajax: 'true'}, function(results){ ";
		$sortByScript .= "$('#retentionScheduleSearchResults').html(results); ";
		$sortByScript .= "}); "; // post
		$sortByScript .= "} "; // js
		$sortByScript .= "</script>";
		
		return $sortByScript;
 	}
 	
  	public function sortByWidgetRecordTypeJs($siteUrl) {
 		$sortByScript = "";
		$sortByScript .= "<script type='text/javascript'>";
		$sortByScript .= "function sort(departmentID, divisionID, sortBy) { ";
		$sortByScript .= "$.post('$siteUrl/search/getRecordTypes',{departmentID: departmentID, divisionID: divisionID, sortBy: sortBy ajax: 'true'}, function(results){ ";
		$sortByScript .= "$('#recordTypeSearchResults').html(results); ";
		$sortByScript .= "}); "; // post
		$sortByScript .= "} "; // js
		$sortByScript .= "</script>";
		
		return $sortByScript;
 	}
 	
 	
	/**
    * provides ajax functionality to admin create record type forms
    * TODO: too redundant...make more compact
    * @access public
    * @return $siteUrl
    */
	public function managementDuplicationDepartmentWidgetJs($siteUrl) {
		
		// form javascript...embeded in php so application url can be rendered easily in the scripts...causes other obvious problems though. wtf?
		$jQueryDeptDuplicationWidget = "";
		$jQueryDeptDuplicationWidget .= "<script type='text/javascript'>";
    	
    	$jQueryDeptDuplicationWidget .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions0').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments0').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
		     	
     	$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions1').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments1').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
		     	
     	$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions2').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments2').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions3').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments3').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions4').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments4').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions5').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments5').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions6').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments6').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions7').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments7').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions8').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments8').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions9').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments9').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
  		$jQueryDeptDuplicationWidget .= "$('select#duplicationDivisions10').change(function(){ ";
        $jQueryDeptDuplicationWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptDuplicationWidget .= "var options = ''; " ;
      	$jQueryDeptDuplicationWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptDuplicationWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptDuplicationWidget .= "}" ;
      	$jQueryDeptDuplicationWidget .= "$('select#duplicationDepartments10').html(options); ";
    	$jQueryDeptDuplicationWidget .= "}, 'json'); "; // post
  		$jQueryDeptDuplicationWidget .= "}); "; // select ...
  		
		$jQueryDeptDuplicationWidget .= "}); "; // document...
     	$jQueryDeptDuplicationWidget .= "</script>";
     	
     	return $jQueryDeptDuplicationWidget;
		
	}
	
	/**
    * provides ajax functionality to admin create record type forms
    * TODO: too redundant..adds same functionality as managementDuplicationDepartmentWidgetJs() 
    * @access public
    * @return $siteUrl
    */
	public function managementDepartmentWidgetJs($siteUrl) {
		
		// form javascript...embeded in php so application url can be rendered easily in the scripts...causes other obvious problems though.
		$jQueryDeptWidget = "";
		$jQueryDeptWidget .= "<script type='text/javascript'>";
    	$jQueryDeptWidget .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$jQueryDeptWidget .= "$('select#managementDivisions').change(function(){ ";
        $jQueryDeptWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptWidget .= "var options = ''; " ;
      	$jQueryDeptWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptWidget .= "}" ;
      	$jQueryDeptWidget .= "$('select#managementDepartments').html(options); ";
    	$jQueryDeptWidget .= "}, 'json'); "; // post
  		$jQueryDeptWidget .= "}); "; // select ...
		$jQueryDeptWidget .= "}); "; // document...
     	$jQueryDeptWidget .= "</script>";
     	
     	return $jQueryDeptWidget;
		
	}
	
	/**
    * provides ajax functionality to admin create record type forms
    * TODO: too redundant..adds same functionality as managementDuplicationDepartmentWidgetJs() 
    * @access public
    * @return $siteUrl
    */
	public function managementMasterCopyDepartmentWidgetJs($siteUrl) {
		
		// form javascript...embeded in php so application url can be rendered easily in the scripts...causes other obvious problems though.
		$jQueryDeptMasterCopyWidget = "";
		$jQueryDeptMasterCopyWidget .= "<script type='text/javascript'>";
    	$jQueryDeptMasterCopyWidget .= "$(document).ready(function(){ "; 
		// gets departments based on the division selected.  uses AJAX / JSON
		$jQueryDeptMasterCopyWidget .= "$('select#masterCopyDivisions').change(function(){ ";
        $jQueryDeptMasterCopyWidget .= "$.post('$siteUrl/survey/getDepartments',{divisionID: $(this).val(), ajax: 'true'}, function(j){ ";
      	$jQueryDeptMasterCopyWidget .= "var options = ''; " ;
      	$jQueryDeptMasterCopyWidget .= "for (var i = 0; i < j.length; i++) { ";
        $jQueryDeptMasterCopyWidget .= "options += '<option value=' + j[i].departmentID + '>' + j[i].departmentName + '</option>'; " ;
      	$jQueryDeptMasterCopyWidget .= "}" ;
      	$jQueryDeptMasterCopyWidget .= "$('select#masterCopyDepartments').html(options); ";
    	$jQueryDeptMasterCopyWidget .= "}, 'json'); "; // post
  		$jQueryDeptMasterCopyWidget .= "}); "; // select ...
		$jQueryDeptMasterCopyWidget .= "}); "; // document...
     	$jQueryDeptMasterCopyWidget .= "</script>";
     	
     	return $jQueryDeptMasterCopyWidget;
		
	}
	
 }
 
?>