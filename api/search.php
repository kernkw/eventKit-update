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
    if ( isset( $_GET['query'] ) and $_GET['query'] === 'dashboard' ) {
        $response['recent'] = $db->processQuery( array(
                'query' => 'recent',
                'limit' => 10
            ) );
        $response['totals'] = $db->processQuery( array(
                'query' => 'total',
                'hours' => 24
            ) );
    } else {
        $response['data'] = $db->processQuery( $_GET );
    }

    if ( array_key_exists( "resultsPerPage", $_GET ) ) {
        $count_query = $_GET['query'].'_count';
        $copy = array( 'query' => $count_query );
        foreach ( $_GET as $key => $value ) {
            if ( $key == 'query' ) continue;
            $copy[$key] = $value;
        }
        $count = $db->processQuery( $copy );
        $resultsPerPage = $copy["resultsPerPage"];
        $mod = $count[0]['COUNT(*)'] % ( $copy["resultsPerPage"] * 1 );
        $totalPages = ( $count[0]['COUNT(*)'] - $mod ) / $resultsPerPage;
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
    header( "Content-type: text/csv" );
    header( "Content-Disposition: attachment; filename=Export.csv" );

    $outstream = fopen( "php://output", 'w' );
    $headers = array();
    foreach ( $response['data'][0] as $key => $value ) {
        if ( $key === 'raw' or $key === 'uid' ) continue;
        array_push( $headers, $key );
    }

    fputcsv( $outstream, $headers );

    foreach ( $response['data'] as $result ) {
        $flattened = array();
        foreach ( $result as $key => $value ) {
            $insert = $value;
            if ( $key === 'category' ) {
                $insert = join( ",", $value );
            } else if ( $key === 'newsletter' or $key === 'additional_arguments' ) {
                    $key_value_string = array();
                    foreach ( $value as $sub_key => $sub_value ) {
                        array_push( $key_value_string, $sub_key.'='.$sub_value );
                    }
                    $insert = join( ",", $key_value_string );
                } else if ( $key === 'raw' or $key === 'uid' ) {
                    continue;
                }
            $flattened[$key] = $insert;
        }
        fputcsv( $outstream, $flattened );
    }

    fclose( $outstream );
} else {
    header( "Content-type: application/json" );
    echo json_encode( $response );
}

?>
