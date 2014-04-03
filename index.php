<?php
/* ==========================================================================
 * INDEX PAGE
 * ==========================================================================
 *
 * SUMMARY
 * This page is the main index page. Upon load, it'll determine if a user is
 * viewing the page and display the GUI, or if it's receiving a POST from the
 * webhook, in which case it'll log the notification and send a response
 * back.
 *
 */

require_once("DatabaseController.php");

if ( !file_exists( 'db' ) or !is_dir( 'db' ) ) {
    // If there's no database, go to the Installer to setup everything.
    header( "Location: Installer.php" );
    die();
}

// DETERMINE IF THERE'S POST DATA
if (isset($HTTP_RAW_POST_DATA)) {
    $db = new SendGrid\EventKit\DatabaseController();
    $response = $db->processPost($HTTP_RAW_POST_DATA);
    header($response);
    return;
} else {
    // IF THERE ISN'T ANY POST DATA, SHOW THE GUI
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SendGrid Event Webhook Starter Kit</title>
    
    <!--META TAGS-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <!--STYLES-->
    <link rel="stylesheet" href="assets/vendor/css/vendor.css">
    <link rel="stylesheet" href="assets/application/css/application.css">
</head>
<body>

    <!--EMBER JS-->
    <script src="assets/vendor/js/vendor.js"></script>
    <script src="assets/application/js/application.js"></script>
</body>
</html>

<?php
}
?>