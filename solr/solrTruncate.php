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
$data = "";

if (isset($_POST['yes'])) {
	$solr->deleteByQuery('*:*');
	$solr->commit();
	$data = "Index Delete Successful";
}

if (isset($_POST['no'])) {
    $data = "Index Delete Cancelled";
}

echo $data;
?>