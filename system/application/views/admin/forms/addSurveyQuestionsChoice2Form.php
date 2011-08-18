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

<div id="mainQuestionTypeTwo" class="adminForm">

<form id="addSurveyChoice2Questions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveyQuestion">

	<input name="surveyID" type="hidden" value="<?php echo $surveyID?>"/>
	<input name="questionType" type="hidden" value="2" />
	
	A question with a Yes/No option (two radio buttons) is generated.<br /><br />
	
	<label for="question">Question:</label><br />
	<input name="question" type="text" size="80" /><br /><br />
  	<input name="required" type="checkbox" value="1" />Required? (check if yes)<br />
  	<input name="subQuestion" type="checkbox" value="1" />Add a Sub Question? (check if yes)
  	<input name="fieldTypeID" type="hidden" value="0" />
  	<br /><br />
  	<input name="addQuestion" type="submit" value="Add Question" /> <br /><br />	
</form>
</div>

<div id="subQuestionTypeTwo" class="adminForm">

<form id="addSurveyChoice2SubQuestions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveySubQuestion">
	
	<input name="questionID" type="hidden" value=""/><br /><br />
		
		<label for="subQuestion">SubQuestion:</label><br />
	  	<input name="subQuestion" type="text" size="80" /><br /><br />
	  	
	  	Field Type:<br />
		<?php if(!empty($fieldTypeData)){echo form_dropdown('fieldTypeID', $fieldTypeData);}else{echo anchor('addFieldType', 'Please add fieldTypes to the system');};?>
		<br /><br />
  		<input name="toggle" type="checkbox" value="1" />Toggle Sub Question? (check if yes)
  		<br /><br />
  		<input name="addSubQuestion" type="submit" value="Add Sub Question" /><br />
</form>
</div> 