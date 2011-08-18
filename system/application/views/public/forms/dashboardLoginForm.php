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
 * 
 * -- remove DU specific code before release --
 **/
?>

<?php $this->load->view("includes/loginHeader"); ?>

	<div id="main-content">
		<br /><br />
		
       <!--here an image is inserted into the content, a certain width is determined (the height, since undefined, the height is "auto" and proportionate the width defined. it is also set to the right side of the page -->

	    <form name="surveyLoginForm" id="login" method="post" action="<?php echo site_url();?>/login/authdashboard">
		<p>
			
			<?php if (!empty($error)) {echo "<font color='red'>" . $error . "</font><br /><br />";} ?>
			
			<div id="loginMessage">
				Liaison Admin Dashboard.
			</div>
			
			<div id="loginForm">
				<div class="loginForm">
				<label for="username">Username:</label><br />
				<input name="uname" id="username" type="text" size="16" maxlength="10" value="<?php if (!empty($error)) {echo $uname;} ?>" class="required" /><br />
	     		<br />
	     		<label for="passcode">Passcode:</label><br />
	     		<input name="pcode" id="passcode" type="password" size="16" maxlength="20" value="<?php if (!empty($error)) {echo $pcode;} ?>" class="required" /><br />
	     		<br />
	     		<input type="submit" value="Login" name="submit"><br />
				</div>
			</div>
			
		</p>
		</form>
	
        <br />
		
	</div>

<?php $this->load->view("includes/footer"); ?>