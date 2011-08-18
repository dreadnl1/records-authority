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

<h2><?=$surveyName?></h2>

<div id="tabs">
	<div id="saving">Saving...</div>
    	<ul>
        	<li class="ui-tabs-nav-item"><a href="#fragment-1">Question Type 1</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-2">Question Type 2</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-3">Question Type 3</a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-4"><span>Question Type 4</span></a></li>
            <li class="ui-tabs-nav-item"><a href="#fragment-5" id="preview">Preview Survey</a></li>
        	<li class="ui-tabs-nav-item"><a href="#fragment-6" id="edit">Edit Survey</a><li>
        </ul>
        <div id="fragment-1">
        	<?php $this->load->view('admin/forms/addSurveyQuestionsChoice1Form');?>
        </div>
        <div id="fragment-2">
        	<?php $this->load->view('admin/forms/addSurveyQuestionsChoice2Form');?>
        </div>
        <div id="fragment-3">
        	<?php $this->load->view('admin/forms/addSurveyQuestionsChoice3Form');?>
        </div>
        <div id="fragment-4">
        	<?php $this->load->view('admin/forms/addSurveyQuestionsChoice4Form');?>
        </div>
        <div id="fragment-5">
        	<?php $this->load->view('admin/forms/surveyPreviewForm');?>
        </div>
        <div id="fragment-6">
        	<?php $this->load->view('admin/forms/editSurveyForm');?>
        </div>
</div>
        
       
<?php $this->load->view('includes/adminFooter'); ?>