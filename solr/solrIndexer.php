<?php

  require_once( 'Apache/Solr/Service.php' );
 
// 
// 
// Try to connect to the named server, port, and url
// 
$solr = new Apache_Solr_Service( '127.0.0.1', '8080', '/solr' );
  
if ( ! $solr->ping() ) {
	echo 'Solr service not responding.';
    exit;
} 

// 
// connect to database
// query database
// place results into array
//
mysql_connect("localhost", "webapp", "w3bapp*^f3rnand0") 
 	or die(mysql_error());
 	

mysql_select_db("recordsManagementDB")
  	or die(mysql_error());
  

$recordTypeQuery = mysql_query("SELECT retentionScheduleID, recordName, recordCategory, officeOfPrimaryResponsibility, disposition, retentionPeriod, recordDescription FROM rm_fullTextSearch") 
  	or die(mysql_error());
  	
$solrData = array();
$recordsArray = array();

while($dbData = mysql_fetch_assoc($recordTypeQuery)) {
	foreach($dbData as $col => $value) {
	  	if ($col == 'retentionScheduleID') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordName') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordCategory') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'officeOfPrimaryResponsibility') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'disposition') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'retentionPeriod') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordDescription') {
	  		$recordsArray[$col] = $value;
	  	} 
  	}
  	 	  	
 	// place record set into 'records' array
  	$solrData['records'] = $recordsArray;
    
  	// build solr XML using recordsArray values
  	$documents = array();
  	
  	foreach ( $solrData as $item => $fields ) {
    	$record = new Apache_Solr_Document();
    	foreach ( $fields as $key => $value ) {
      		if ( is_array( $value ) ) {
        		foreach ( $value as $datum ) {
          			$record->setMultiValue( $key, $datum );
        		}
      		} else {
        	$record->$key = $value;
 	  		}
    	}
      	$documents[] = $record;
  	}
    
   	//
  	//
  	// Load the documents into the solr index
  	// 
   try {
    	$solr->addDocuments( $documents );
    	$solr->commit();
  		$solr->optimize();
  	}
  	catch ( Exception $e ) {
    	echo $e->getMessage();
  	}	
}

echo "Records Indexed Successfully.";

?>