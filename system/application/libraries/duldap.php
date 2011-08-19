<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // this line is CodeIgniter framework specific

/**
 *	Class used to authenticate users against DU LDAP 
 *
 *	invoke with CodeIgniter Framework:
 *	place duldap.php in libraries directory
 *	$this->load->library("duldap"); 
 *	$this->duldap->authenticate($ldapUser, $ldapPswd);
 *
 *	invoke with no framework:
 *	include("duldap.php");
 *	$ldapObj = new duLdap();
 *	$ldapObj->authenticate($ldapUser, $ldapPswd);
*/

class duLdap {
	
	private $ldapHost = "ldap://ldap.du.edu";
	private $ldapPort = "636";
	private $dn = "ou=people,o=du.edu,o=universityofdenver"; 
	
	public function authenticate($ldapUser, $ldapPswd) {
		
		$ldapUser = "uid=" . $ldapUser . "," . $this->dn; 
				
		$ldapServer = @ldap_connect($this->ldapHost, $this->ldapPort)
			or die("Unable connect to server.");
			
		$setVersion = @ldap_set_option($ldapServer,LDAP_OPT_PROTOCOL_VERSION,3)
	   		or die("Unable to set version.");
		
		// Try authenticating the user. "Bind" is LDAP
		// terminology for an attempted authentication.
		$bind = @ldap_bind($ldapServer, $ldapUser, $ldapPswd); 
		$ret = array();
		
		if ($bind) {
			// Did we bind? If so, return TRUE. Otherwise return 
			// a tuple with FALSE and an error message
			$ret['result'] = TRUE;
		} else {
			$ret['result'] = FALSE;
			$ret['error'] = "Authentication failed. Please check your DU ID and passcode and try again.";
		}
		// We've done our attempt at authentication, so unbind
		// and return the result.
		ldap_unbind($ldapServer);
		return $ret;
	} 		  
}

?>