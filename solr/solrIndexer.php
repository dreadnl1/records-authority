<?php

  require_once( 'Apache/Solr/Service.php' );

// 
// 
// Try to connect to the named server, port, and url
// 
$solr = new Apache_Solr_Service( 'localhost', '8080', '/solr' );
  
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
  
  
$recordTypeQuery = mysql_query("SELECT recordInformationID, recordTypeDepartment, recordName, recordDescription, recordCategory FROM rm_recordTypeRecordInformation") 
  	or die(mysql_error());
  	
  
$solrData = array();
$recordsArray = array();
  
while($dbData = mysql_fetch_assoc($recordTypeQuery)) {
	foreach($dbData as $col => $value) {
	  	if ($col == 'recordInformationID') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordTypeDepartment') {
	  		$recordsArray[$col] = $value;
	  		$id = $recordsArray[$col];
	  		// get department and division
	  	} elseif ($col == 'recordName') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordDescription') {
	  		$recordsArray[$col] = $value;
	  	} elseif ($col == 'recordCategory') {
	  		$recordsArray[$col] = $value;
	  	} 
  	}
  	echo "Querying data...<br />";		 	
  	$departmentQuery = mysql_query("SELECT divisionID, departmentName FROM rm_departments WHERE departmentID = $id") 
  		or die(mysql_error()); 
 	
 	while($departmentData = mysql_fetch_assoc($departmentQuery)) {
 		foreach ($departmentData as $col2 => $deptValue) {
 			if ($col2 == 'divisionID') {
 				$id = $deptValue;
 			} elseif ($col2 == 'departmentName') {
 				$recordsArray[$col2] = $deptValue;
 			}
 		}
 	}
 
 	$divisionQuery = mysql_query("SELECT divisionName FROM rm_divisions WHERE divisionID = $id") 
  		or die(mysql_error()); 
  	  	
  	while($divisionData = mysql_fetch_assoc($divisionQuery)) {
 		foreach ($divisionData as $col3 => $divValue) {
 			if ($col3 == 'divisionName') {
 				$recordsArray[$col3] = $divValue;
 			}
 		}
 	}
 	
 	// place record set into 'records' array
  	$solrData['records'] = $recordsArray;
    
  	// build solr XML using recordsArray values
  	$documents = array();
  	echo "Creating Solr XML...<br />";
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
    echo "Indexing Records...<br /><br />";
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
