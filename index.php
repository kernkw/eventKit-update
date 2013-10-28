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
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    
    
    <!--STYLES-->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/master.css">
</head>
<body>
    <script type="text/x-handlebars" charset="utf-8">
        <div class="nav">
            <div class="nav_container">
                <div class="brand"></div>
                <div class="search">
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="body_container">
            {{outlet}}
            </div>
        </div
    </script>
    
    <script type="text/x-handlebars" id="index" charset="utf-8">
        <div class="panel panel-default" style="margin-top: 25px">
            <div class="panel-body">
                <h1>Welcome</h1>
                <p>
                    The SendGrid Event Webhook Starter Kit serves both as an example of how to harness SendGrid's Event Webhook as well as being a tool for you to keep a record of events.
                </p>
            </div>
        </div>
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