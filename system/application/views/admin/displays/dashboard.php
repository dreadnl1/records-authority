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

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Dashboard</a></li>
    </ul>
    
    <div id="fragment-1">
    <div class="dashboard">

		<?php 
			$imagePath = base_url() . "/images"; 
			echo "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Create Record Type' border=0 />&nbsp;" . anchor_popup('/dashboard/recordTypeForm', 'Create Record Type', $popUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Create Survey' border=0 />&nbsp;" . anchor_popup('/dashboard/addSurveyName', 'Create Survey', $popUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Survey Notes' border=0 />&nbsp;" . anchor_popup('/dashboard/surveyNotesForm', 'Survey Notes', $popUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Surveys' border=0 />&nbsp;" . anchor_popup('/dashboard/listSurveys', 'Surveys', $popUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_doc.gif' alt='Search' border=0 />&nbsp;" . anchor_popup('/search', 'Search', $searchPopUpParams) . "<br /><br />";
			
			echo "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Create Record Category' border=0 />&nbsp;" . anchor_popup('/upkeep/recordCategoryForm', 'Create Record Category', $mediumPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_folder_open.gif' alt='Edit Record Category' border=0 />&nbsp;" . anchor_popup('/upkeep/editRecordCategoryForm', 'Edit Record Category', $mediumPopUpParams) . "<br /><br />";
			
			echo "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Create Divisions' border=0 />&nbsp;" . anchor_popup('/upkeep/divisionForm', 'Create Divisions', $mediumPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_folder_open.gif' alt='Edit Divisions' border=0 />&nbsp;" . anchor_popup('/upkeep/editDivisionForm', 'Edit Divisions', $mediumPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_plus.gif' alt='Create Departments' border=0 />&nbsp;" . anchor_popup('/upkeep/departmentForm', 'Create Departments', $mediumPopUpParams) . "<br />";
			echo "<img src='$imagePath/ffd40f_11x11_icon_folder_open.gif' alt='Edit Departments' border=0 />&nbsp;" . anchor_popup('/upkeep/editDepartmentForm', 'Edit Departments', $mediumPopUpParams) . "<br /><br />";
			
			echo "<img src='$imagePath/ffd40f_11x11_icon_close.gif' alt='Logout' border=0 />&nbsp;" . anchor('login/logout', 'Logout'); 
		?>

		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>
