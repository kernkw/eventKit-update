<?php
/* ==========================================================================
 * INDEX PAGE
 * ==========================================================================
 *
 * IF YOU ARE VIEWING THIS IN A WEB BROWSER ON YOUR WEBPAGE, PHP IS CURRENTLY
 * NOT INSTALLED ON YOUR WEB HOST.  THE SENDGRID EVENT STARTER KIT REQUIRES:
 *     
 *     - PHP 5.3 OR HIGHER
 *     - SQLITE 3.0 OR HIGHER
 *
 *
 *
 * SUMMARY
 * This page is the main index page. Upon load, it'll determine if a user is
 * viewing the page and display the GUI, or if it's receiving a POST from the
 * webhook, in which case it'll log the notification and send a response
 * back.
 *
 */

require_once("DatabaseController.php");

// DETERMINE IF THERE'S POST DATA
if (isset($HTTP_RAW_POST_DATA)) {
    $db = new SendGrid\EventStarterKit\DatabaseController();
    $response = $db->processPost($HTTP_RAW_POST_DATA);
    header($response);
    return;
} else {
    // IF THERE ISN'T ANY POST DATA, SHOW THE GUI
?>

<h1>Hello World</h1>

<?php
}
?>