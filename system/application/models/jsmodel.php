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
 
 
 class JsModel extends Model {

	public function __construct() {
		parent::Model();
	} 
	
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
						'width' => '1000',
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
    * generates a javaScript pop up
    *
    * @access public
    * @param $siteUrl
    * @return $jQuery
    */
	public function departmentWidgetJs($siteUrl) {
		
		// form javascript...embeded in php so application url can be rendered easily in the scripts...causes other obvious problems though.
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
	
	public function managementDuplicationDepartmentWidgetJs($siteUrl) {
		
		// form javascript...embeded in php so application url can be rendered easily in the scripts...causes other obvious problems though. wtf?
		$jQueryDeptDuplicationWidget = "";
		$jQueryDeptDuplicationWidget .= "<script type='text/javascript'>";
    	
    	$jQueryDeptDuplicationWidget .= "$(document).ready(function(){ "; 
		//validation
		//$jQuery .= "$('#surveyForm').validate(); ";
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