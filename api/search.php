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


require_once join( DIRECTORY_SEPARATOR, array( dirname( dirname( __FILE__ ) ), 'Constants.php' ) );
require_once join( DIRECTORY_SEPARATOR, array( ROOT_DIR, 'DatabaseController.php' ) );

// SETUP A GENERIC ARRAY FOR THE RESPONSE BACK
$db = new SendGrid\EventKit\DatabaseController();
$response = array();

// CHECK IF THE QUERY PARAMETER IS SET
if ( array_key_exists( "query", $_GET ) ) {
    $response['message'] = 'success';
    $response['data'] = $db->processQuery( $_GET );

    if ( array_key_exists( "resultsPerPage", $_GET ) ) {
        $resultsPerPage = $_GET["resultsPerPage"];
        $mod = count( $response['data'] ) % ( $_GET["resultsPerPage"] * 1 );
        $totalPages = ( count( $response['data'] ) - $mod ) / $resultsPerPage;
        if ( $mod ) $totalPages++;
        $pageArray = array();
        for ( $i = 1; $i <= $totalPages; $i++ ) {
            array_push( $pageArray, $i );
        }
        $response['pages'] = $pageArray;
    }
} else {
    // IF NO QUERY PARAMETER IS SET, FILL IN AN ERROR MESSAGE IN THE RESPONSE.
    $response['message'] = 'error';
    $response['errors'] = array( 'Missing required "query" parameter.' );
}

if ( array_key_exists( "csv", $_GET ) and count( $response['data'] ) > 0 ) {
    $outstream = fopen( "php://output", 'w' );
    $headers = array();
    foreach ( $response['data'][0] as $key => $value ) {
        if ($key === 'raw' or $key === 'uid') continue;
        array_push( $headers, $key );
    }

    fputcsv( $outstream, $headers );

    foreach ( $response['data'] as $result ) {
        $flattened = array();
        foreach ( $result as $key => $value ) {
            $insert = $value;
            if ( $key === 'category' ) {
                $insert = join( ",", $value );
            } else if ($key === 'newsletter' or $key === 'additional_arguments') {
                $key_value_string = array();
                foreach($value as $sub_key => $sub_value) {
                    array_push($key_value_string, $sub_key.'='.$sub_value);
                }
                $insert = join(",", $key_value_string);
            } else if ($key === 'raw' or $key === 'uid') {
                continue;
            }
            $flattened[$key] = $insert;
        }
        fputcsv( $outstream, $flattened );
    }

    header( "Content-type: text/csv" );
    header( "Content-Disposition: attachment; filename=file.csv" );
    header( "Pragma: no-cache" );
    header( "Expires: 0" );
    fclose( $outstream );
} else {
    header( "Content-type: application/json" );
    echo json_encode( $response );
}

?>
