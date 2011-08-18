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

<script type="text/javascript">
	$(document).ready(function() { 
		$("#preview").click(function () {
			$("#loading").text("Loading...");
			$("#generateSurvey").load("<?php echo site_url();?>/surveyBuilder/generateSurvey/<?php echo $surveyID?>/preview", function()
			{
				$("#loading").remove();
			});
	    	return false;
	    });	

	}); 
</script>
<div id="loading"></div>
<br />
<div id="generateSurvey" class="adminForm"></div>