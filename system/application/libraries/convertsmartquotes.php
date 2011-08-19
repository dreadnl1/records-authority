<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // this line is CodeIgniter framework specific

/**
 *	Class used to remove smart quotes
 *
 *	invoke with CodeIgniter Framework:
 *	place convertsmartquotes.php in libraries directory
 *	$this->load->library("convertsmartquotes"); 
 *	$this->convertsmartquotes->convert($_POST);
 *
 *	invoke with no framework:
 *	include("convertsmartquotes.php");
 *	$convertObj = new ConvertSmartQuotes();
 *	$convertObj->convert($_POST);
*/
 
 class ConvertSmartQuotes {
		
	/**
    * converts Micro$oft smart quotes to standard ascii
    *
    * @access public
    * @param $_POST
    * @return $_POST
    */
	public function convert($_POST) {
		
		// http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php
		$search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 
 
    	$replace = array("Õ", 
                     "'", 
                     'Ò', 
                     'Ó', 
                     '-'); 
 
		foreach ($_POST as $i => $postValue) {
			$_POST[$i] = str_replace($search, $replace, $postValue);
			if (is_array($postValue)) {
				foreach ($postValue as $j => $postValue1) {
					$_POST[$j] = str_replace($search, $replace, $postValue1);
				}
			}
		}
    return $_POST;
	}
 }
 
?>