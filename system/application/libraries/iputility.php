<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // this line is CodeIgniter framework specific

/**
 * checks if user is on DU campus
 *
 * @return $duIP
 */
class IpUtility {
	// if ip begins with 130.253 then user is on DU campus - 127.0  localhost...
	public function checkDuIp() {
		// get user ip address
		$userIp = $_SERVER['REMOTE_ADDR'];
		// convert string to array
		$ipAddress = explode('.', $userIp);
		// place desired ip values into array
		foreach ($ipAddress as $ipOctetValue) {
			if ($ipOctetValue == 130 || $ipOctetValue == 127) {
				$ipOctetArray[] = $ipOctetValue;		
			}
			if ($ipOctetValue == 253 || $ipOctetValue == 0) {
				$ipOctetArray[] = $ipOctetValue;
			}
		}
		
		// compare ip address octets
		if (isset($ipOctetArray) && $ipOctetArray[0] == 130 && $ipOctetArray[1] == 253 || isset($ipOctetArray) && $ipOctetArray[0] == 127 && $ipOctetArray[1] == 0 ) { 
			$duIP = TRUE;
		} else {
			$duIP = TRUE;
		} 
	return $duIP;
	}
}
?>