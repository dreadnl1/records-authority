<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 

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

<html>
<head>
	<meta name="robots" content="noindex nofollow" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
   	<!-- <meta http-equiv="EXPIRES" content="Mon, 22 Jul 2015 11:12:01 GMT" />-->
	<title>
	<?php
		header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  
		if(isset($title))
		{ 
			echo $title;
		} else {
			echo 'Records Authority';
		}
	?>
	</title>
	
	<!-- css -->
	<link href="<?php echo base_url();?>images/favicon.ico" rel="Shortcut Icon" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui-themeroller.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/admin.css" type="text/css"  />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.autocomplete.css" type="text/css"  />
	<link rel="stylesheet" href="<?php echo base_url();?>js/shadowbox/shadowbox.css" type="text/css" />
	<!-- jquery -->
	<script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.ui.all.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jqueryForm.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.pack.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/ajax.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/shadowbox/shadowbox.js" type="text/javascript"></script>
	<script type="text/javascript">Shadowbox.init();</script>
	
	<!-- load dynamic ajax javascript -->
	<?php $this->load->view('includes/ajaxAutoComplete'); ?>
	
	<script src="<?php echo base_url();?>js/jquery.autocomplete.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/submit.js" type="text/javascript"></script>
	<!-- load dynamic calendar javascript -->
	<?php $this->load->view('includes/calendarDateInputImageNames'); ?>
	
	<script src="<?php echo base_url();?>js/calendarDateInput.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/ieSucks.js" type="text/javascript"></script>
	<!--<h1>Records Management - <blink>DEMO APPLICATION</blink></h1>-->
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-19605537-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</head>
<body>