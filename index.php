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

// DETERMINE IF THERE'S POST DATA
if (isset($HTTP_RAW_POST_DATA)) {
    $db = new SendGrid\EventStarterKit\DatabaseController();
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
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <script type="text/x-handlebars" charset="utf-8">
        <h1>Hello World</h1>
    </script>
    
    <!--EMBER JS-->
    <script src="js/libs/jquery-1.9.1.js"></script>
    <script src="js/libs/handlebars-1.0.0.js"></script>
    <script src="js/libs/ember-1.1.2.js"></script>
    <script src="js/app.js"></script>
</body>
</html>

<?php
}
?>