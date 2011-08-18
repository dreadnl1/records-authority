<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 

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
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
?>

<html>
<head>
   <meta name="robots" content="noindex nofollow" />	
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta http-equiv="EXPIRES" content="Mon, 22 Jul 2015 11:12:01 GMT" />
   <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<!-- GLOBAL CSS and Javascript -->
	    <link href="<?php echo base_url();?>css/global.css" rel="stylesheet" type="text/css" media="screen" />
	    <link rel="stylesheet" href="<?php echo base_url();?>js/shadowbox/shadowbox.css" type="text/css" />
	
	    <script src="<?php echo base_url();?>js/title.js" type="text/javascript"></script>
		<link href="<?php echo base_url();?>images/favicon.ico" rel="Shortcut Icon" type="image/x-icon" />
		<link rel="stylesheet" href="<?php echo base_url();?>js/shadowbox/shadowbox.css" type="text/css" />
		<script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/jqueryForm.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/jquery.validate.pack.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/publicAjax.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/shadowbox/shadowbox.js" type="text/javascript"></script>
		<script type="text/javascript">Shadowbox.init();</script>
		<!-- load thick box image -->
		<?php $this->load->view('includes/thickBoxImageNames'); ?>
		
		<script src="<?php echo base_url();?>js/thickBox.js" type="text/javascript"></script>
		<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" media="screen" /> 
		<link href="<?php echo base_url();?>css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />

		<?php header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  ?>
		<title>University of Denver | Records Management</title>
    <!--To set the title to your webpage, edit this line above-->
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
<!--end header section-->

<body class="red three">

<!--this is where the structure is chosen, this page is set to "white two" as can be seen above-->

<div class="skipnav"><a href="#content">Skip Navigation</a></div>
<div id="main-wrapper">
<!-- global-header -->
<!--please do not edit the standard global header on all du pages-->
<?php /*
	<div id="global-header">
		<div id="global-inside-header">
		    <h1><a href="http://www.du.edu/" title="University of Denver">University of Denver</a></h1>
			<!-- begin du google search -->
            <!--Do not edit this section-->
				<div id="global-search">
					<form name="g2006search" action="http://search1.du.edu/search" method="get">
						<input type="hidden" name="site" value="du_collection" />
						<input type="hidden" name="client" value="du_frontend" />
						<input type="hidden" name="proxystylesheet" value="du_frontend" />
						<input type="hidden" name="output" value="xml_no_dtd" />
						<input type="hidden" name="lr" value="" />
						<input type="hidden" name="ie" value="utf8" />
						<input type="hidden" name="oe" value="utf8" />
						<p>
							<input id="global-searchInput" class="searchinput" type="text" value="Search DU" name="q" onclick="document.g2006search.q.value = ''" maxlength="255" />
							<!--<input id="global-searchBtn" class="search-button" type="image" name="btnG" value="Search" />-->
							<button value="Search" type="submit" name="btnG" id="global-searchBtn">Go!</button>
						</p>
					</form>
				</div>
			<!-- end general du google search 2006_2007 -->
			<ul id="global-header-nav">
				<li id="global-nav_news"><a href="http://www.du.edu/today/">News</a></li>
				<li id="global-nav_events"><a href="http://www.du.edu/calendar/">Calendar</a></li>
				<li id="global-nav_directory"><a href="http://www.du.edu/Directory/servlet/DirectoryServlet">Directory</a></li>
				<li id="global-nav_az"><a href="http://www.du.edu/az/">A-Z Directory</a></li>
			</ul>
		</div>
	</div>
	*/ ?>
<!-- end global-header -->

<div id="inner-wrapper">
<div id="content-wrapper">
	<div id="masthead"><a href="http://www.du.edu/bfa/records/"><div class="header"></div></a></div>
	<div class="bottom-border"></div>