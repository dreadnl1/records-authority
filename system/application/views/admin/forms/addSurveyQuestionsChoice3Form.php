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


<div id="saving"></div>
<div id="mainQuestionTypeThree" class="adminForm">

<form id="addSurveyChoice3Questions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveyQuestion">
	A question with muliple choices is generated.  You will be prompted to enter sub questions once the main question is submitted.
	<br />
	<input name="surveyID" type="hidden" value="<?php echo $surveyID?>"/>
	<input name="questionType" type="hidden" value="3" />
	<input name="subQuestion" type="hidden" value="1" />
  	<input name="fieldTypeID" type="hidden" value="0" />
	<br />
	Question:<br />
	<input name="question" type="text" size="80" /><br /><br />
  	<input type="checkbox" name="required" value="1" />Required? (check if yes)<br />
  	<br /><br />
  	<input name="addQuestion" type="submit" value="Add Question" /> <br /><br />
  	<br />	
</form>
</div>

<div id="subQuestionTypeThree" class="adminForm">

<form id="addSurveyChoice3SubQuestions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveySubQuestion">
	<input name="questionID" type="hidden" value=""/><br /><br />

  		Sub choice:<br />
	  	<input name="subQuestion" type="text" size="80" /><br /><br />
	  	
	  	Field Type:<br />
		<?php if(!empty($fieldTypeData)){echo form_dropdown('fieldTypeID', $fieldTypeData);}else{echo anchor('addFieldType', 'Please add fieldTypes to the system');};?>
		<br /><br />
		<input name="subChoiceQuestionCheck" type="checkbox" value="1" id="subChoiceQuestionToggle" />Add sub question? (check if yes)<br />
			<div id="subChoiceQuestion">
				<br />
				<input name="subChoiceQuestion" type="text" size="60"  />
				<br /><br />
				Field Type:<br />
				<?php if(!empty($fieldTypeData)){echo form_dropdown('subFieldTypeID', $fieldTypeData);}else{echo anchor('addFieldType', 'Please add fieldTypes to the system');};?>
			</div>
		<br /><br />
  		<input name="addSubQuestion" type="submit" value="Add Sub Choice" />&nbsp;&nbsp;
  		<input name="questionReset" type="button" value="Add New Question" id="questionTypeThreeReset" /><br />
</form>
</div> 