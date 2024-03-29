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
 * 
 **/
?>

<?php $this->load->view("includes/loginHeader"); ?>
	
	<div id="main-content">
		<br /><br />
		<noscript><h2>!!You must have JavaScript enabled in order to effectively use this application!!</h2></noscript>
       <form name="surveyLoginForm" id="login" method="post" action="<?php echo site_url();?>/login/authenticate">
		<p>

			<?php if (!empty($error)) {echo "<font color='red'>" . $error . "</font><br /><br />";} ?>
			
			<div id="loginMessage">
				Welcome to the DU Records Management Program Departmental Survey. Please log in with your DU ID and passcode to fill out the survey.
	 			<br /><br />
				<!-- Your DU ID is the 9-digit number on your DU ID card. Your passcode is the same as the one you use to log in to MyWeb or WebCentral.  For more information about the DU ID and passcode, please go here:
				<a href="http://www.du.edu/uts/helpdesk/du-id.html" target="_blank">http://www.du.edu/uts/helpdesk/du-id.html</a>
	 			<br /><br />-->
				If you have questions about this survey, please contact the Records Management Program at <br /><a href="mailto:<?php echo $this->config->item('prodEmail');?>"><?php echo $this->config->item('prodEmail');?></a> or x13662.
				<br /><br />
				<a href="<?php $siteUrl = site_url();echo $siteUrl;?>/du/retentionSchedules">Records Authority Search</a>
			</div>
			
			<div id="loginForm">
				<div class="loginForm">
				<label for="username">Username:</label><br />
				<input name="uname" id="username" type="text" size="16" maxlength="9" value="<?php if (!empty($error)) {echo $uname;} ?>" class="required" /><br />
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