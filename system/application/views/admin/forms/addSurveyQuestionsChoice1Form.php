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

<div id="mainQuestionTypeOne" class="adminForm">

  <form id="addSurveyChoice1Questions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveyQuestion">
  	
  <input name="surveyID" type="hidden" value="<?php echo $surveyID?>"/>
  <input name="subQuestion" type="hidden" value="0" />
  <input name="questionType" type="hidden" value="1" />
  
  	<label for="question">Question:</label><br />
	<input name="question" id="question" type="text" size="80" class="required" /><br /><br />
	Field Type:<br />
	<?php if(!empty($fieldTypeData)){echo form_dropdown('fieldTypeID', $fieldTypeData);}else{echo anchor('addFieldType', 'Please add fieldTypes to the system');};?>
  	<br /><br />
  	<input name="required" id="required" type="checkbox" value="1" />Required? (check if yes)<br />
  	
  	<br /><br />	
  	
  	<input name="addQuestion" type="submit" value="Add Question" /> 	
  	
  </form>
	
</div>
  

  

