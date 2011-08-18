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

<?php 
	$data['title'] = 'Dashboard - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>

<div align="right">
	<?php
		$imagePath = base_url() . "/images";
		$imagePlus = array(
			'src'=>"$imagePath/ffd40f_11x11_icon_plus.gif",
			'border'=>0,
		);
		$imageDoc = array(
			'src'=>"$imagePath/ffd40f_11x11_icon_doc.gif",
			'border'=>0,
		);
		$imageFolder = array(
			'src'=>"$imagePath/ffd40f_11x11_icon_folder_open.gif",
			'border'=>0,
		);
		$imageClose = array(
			'src'=>"$imagePath/ffd40f_11x11_icon_close.gif",
			'border'=>0,
		);
		
		echo img($imageClose) . anchor('login/logout', 'Logout');
		//echo "<img src='$imagePath/ffd40f_11x11_icon_close.gif' alt='Logout' border=0 />&nbsp;" . anchor('login/logout', 'Logout'); 
	?>
</div>

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Dashboard</a></li>
    	<!-- <li class="ui-tabs-nav-item"><a href="#fragment-2">Search</a></li> -->
    </ul>
    
    <div id="fragment-1">
    <div class="dashboard">

		<?php 
			echo "<table border='1'>";
			echo "<tr><th>Data Entry</th><th>Search</th></tr>";
			
			echo "<tr>";	
			echo "<td style='text-align:left;width:100px;'>";
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/searchSurveys', 'Browse Submitted Surveys', $searchPopUpParams) . br();
			echo img($imagePlus) . "&nbsp". anchor_popup('/recordType/view/', 'Create Record Inventory', $popUpParams) . br();
			echo img($imagePlus) . "&nbsp". anchor_popup('/retentionSchedule/view/', 'Create Record Group', $retentionSchedulePopUp) . br();
			echo "</td>";
			
			echo "<td style='text-align:left;width:100px;'>";
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/recordTypeGlobalSearch', 'Search Record Inventory', $searchPopUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/retentionScheduleGlobalSearch', 'Search Record Groups ', $searchPopUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/searchRecordTypes', 'Browse Record Inventory', $searchPopUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/searchRetentionSchedules', 'Browse Record Groups', $searchPopUpParams) . br();
			echo "</td></tr>";
			
			echo "<tr><th>Database</th><th>Administrative</th></tr>";
			echo "<tr>";
			echo "<td style='text-align:left;width:100px;'>";
			//echo img($imagePlus) . "&nbsp" . anchor_popup('/dashboard/addSurveyName', 'Create Survey', $popUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/dashboard/listSurveys', 'Survey Editor', $popUpParams) . br(2);
			echo img($imagePlus) . "&nbsp" . anchor_popup('/upkeep/recordCategoryForm', 'Create Functional Category', $mediumPopUpParams) . br();
			echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editRecordCategoryForm', 'Edit Functional Category', $mediumPopUpParams) . br(2);
			echo img($imagePlus) . "&nbsp" . anchor_popup('/upkeep/divisionForm', 'Create Divisions', $mediumPopUpParams) . br();
			echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editDivisionForm', 'Edit Divisions', $mediumPopUpParams) . br(2);
			echo img($imagePlus) . "&nbsp" . anchor_popup('/upkeep/departmentForm', 'Create Departments', $mediumPopUpParams) . br();
			echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editDepartmentForm', 'Edit Departments', $mediumPopUpParams) . br(2);
			echo img($imageDoc) . "&nbsp" . anchor('/du/retentionSchedules', 'Public Search') . br();
			echo img($imageDoc) . "&nbsp" . anchor('/', 'Public Survey') . br(2);
			//echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Search' border=0 />&nbsp;" . anchor_popup('/search', 'Search', $searchPopUpParams) . "<br /><br />";
			echo "</td>";
			
			echo "<td style='text-align:left;width:100px;'>";
			echo img($imageDoc) . "&nbsp" . anchor_popup('/upload', 'Upload Files', $searchPopUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/import', 'Import Retention Schedules', $searchPopUpParams) . br(2);
			//echo img($imagePlus) . "&nbsp" . anchor_popup('/upkeep/addDocTypeForm', 'Create Document Types', $mediumPopUpParams) . br();
			//echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editDocTypeForm', 'Edit Document Types', $mediumPopUpParams) . br(2);
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/searchRecordTypesDeleted', 'Browse Deleted Record Inventory', $searchPopUpParams) . br();
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/searchRetentionSchedulesDeleted', 'Browse Deleted Record Groups', $searchPopUpParams) . br(2);
			
			//echo "<img src='$imagePath/ffd40f_11x11_icon_close.gif' alt='Logout' border=0 />&nbsp;" . anchor('login/logout', 'Logout'); 
			echo img($imageFolder) . "&nbsp" . anchor_popup('/retentionSchedule/indexRetentionSchedules', 'Run Database Index', $mediumPopUpParams) . br();
			echo img($imageFolder) . "&nbsp" . anchor_popup('../solr/solrIndexer.php', 'Refresh Public Search Index', $mediumPopUpParams) . br(2);
			echo img($imageDoc) . "&nbsp" . anchor_popup('/search/globalAuditSearch', 'Text Search - Audit', $searchPopUpParams) . br(2);
			
			$this->load->model('SessionManager');
			$admin = $this->SessionManager->isAdmin();
			if($admin == TRUE) {
				echo img($imagePlus) . "&nbsp" . anchor_popup('/upkeep/userForm', 'Create User', $popUpParams) . br();
				echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editUserForm', 'Edit User', $popUpParams) . br(2);
			}
			echo img($imageFolder) . "&nbsp" . anchor_popup('/upkeep/editPasswordForm', 'Change Password', $popUpParams) . br(2);
			echo "</td></tr>";
			echo "</table>";
		/*
	<!-- </div> -->
    <!-- </div> -->
    
    <!-- div id="fragment-2"> -->
    <!-- <div class="dashboard"> -->

		<?php 
			$imagePath = base_url() . "/images"; 
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Search' border=0 />&nbsp;" . anchor_popup('/search/searchRetentionSchedules', 'Search Retention Schedules', $searchPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Search' border=0 />&nbsp;" . anchor_popup('/search/searchRecordTypes', 'Search Record Types', $searchPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Search' border=0 />&nbsp;" . anchor_popup('/search/recordTypeGlobalSearch', 'Global Search', $searchPopUpParams) . "<br />";
		*/?>

		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>
