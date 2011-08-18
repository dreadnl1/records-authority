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


<div id="saving"></div>
<div id="mainQuestionTypeFour" class="adminForm">

<form id="addSurveyChoice4Questions" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveyContactQuestion">
	A question asking for contact information is generated.  You will be prompted to enter contact fields once the main question is submitted.
	<br />
	<input name="surveyID" type="hidden" value="<?php echo $surveyID; ?>"/>
	<input name="questionType" type="hidden" value="4" />
	<!--<input name="subQuestion" type="hidden" value="1" />-->
  	<!--<input name="fieldTypeID" type="hidden" value="0" />-->
	<br />
	Contact Question:<br />
	<input name="contactQuestion" type="text" size="80" /><br /><br />
   	<br /><br />
  	<input name="addContactQuestion" type="submit" value="Add Contact Question" /> <br /><br />
</form>
</div>

<div id="contactFieldTypeFour" class="adminForm">

<form id="addSurveyChoice4ContactFields" method="post" action="<?php echo site_url();?>/surveyBuilder/addSurveyContactField">
	<input name="contactQuestionID" type="hidden" value=""/><br /><br />

  		Contact Field Label:<br />
	  	<input name="contactField" type="text" size="80" /><br /><br />
	  	
	  	Field Type:<br />
		<?php if(!empty($fieldTypeData)){echo form_dropdown('fieldTypeID', $fieldTypeData);}else{echo anchor('addFieldType', 'Please add fieldTypes to the system');};?>
		<br /><br />
		<input type="checkbox" name="required" value="1" />Required? (check if yes)<br />
		<br /><br /><br />
  		<input name="addSubQuestion" type="submit" value="Add Contact Field Label" />&nbsp;&nbsp;
  		<input name="questionReset" type="button" value="Reset Form" id="questionTypeThreeReset" /><br />
</form>
</div> 