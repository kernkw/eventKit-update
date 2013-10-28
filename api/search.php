<?php

/* ==========================================================================
 * SEARCH.PHP
 * ==========================================================================
 *
 * This PHP script serves as an end point for the front end to query the 
 * database.  At minimum, a 'query' parameter is needed as part of the GET
 * call which specifies what type of information is being queried.
 *
 */


header("Content-type: application/json");

require_once(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'Constants.php')));
require_once(join(DIRECTORY_SEPARATOR, array(ROOT_DIR, 'DatabaseController.php')));

// SETUP A GENERIC ARRAY FOR THE RESPONSE BACK
$db = new SendGrid\EventKit\DatabaseController();
$response = array();

// CHECK IF THE QUERY PARAMETER IS SET
if ($_GET['query']) {
    $response['message'] = 'success';
    $response['data'] = $db->processQuery($_GET);
} else {
    // IF NO QUERY PARAMETER IS SET, FILL IN AN ERROR MESSAGE IN THE RESPONSE.
    $response['message'] = 'error';
    $response['errors'] = array('Missing required "query" parameter.');
}

echo json_encode($response);

?>