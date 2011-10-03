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

<div id="adminFooter">
<?php
	$baseUrl = base_url();
	if(isset($timestamp))
	{
		echo "Form Created: ";
		echo $timestamp;
		echo br();
	}
	
	if(isset($updateTimestamp))
	{ 	
		echo "Form Updated: ";
		echo $updateTimestamp;
		echo br();
	}
	$user = $this->session->userdata('username');
	$loginTime = $this->session->userdata('loginTime')
?>
<p>Records Authority ver. <a href="<?php echo $baseUrl?>RecordsAuthority_Release_Notes.txt">2.0.1</a> | <?php echo $this->benchmark->elapsed_time();?>
<br />University of Denver--Penrose Library--University Records Management Program
<br />User: <?php echo $user;?>
<br />Login Time: <?php echo $loginTime;?>
<div align="right">
	<a href="http://www.du.edu/bfa/records/" target="_blank">
	<img src="<?php echo base_url();?>images/rmLogo.jpg" height="112" width="282" border="0" alt="logo" />
	</a>
</div>
</p>
</div>
</body>
</html>