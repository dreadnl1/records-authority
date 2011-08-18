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
?>

<script type="text/javascript">
	$(document).ready(function() { 
		$("#loading").text("Loading...");
		$("#editSurvey").load("<?php echo site_url();?>/surveyBuilder/editSurveyQuestions/<?php echo $surveyID?>", function() 
			{ 
				$("#loading").remove();
			});
			return false;
	}); 
</script>

<div id="loading"></div>
<br />
<div id="editSurvey" class="adminForm"></div>