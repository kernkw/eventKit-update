<?php
/* ==========================================================================
 * INSTALLER
 * ==========================================================================
 *
 * SUMMARY
 * This script takes care of the initial installation of the
 * SendGrid Event Webhook Starter Kit
 *
 */

require_once "Logger.php";
require_once "DatabaseController.php";

$http = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';

if ( file_exists( 'db' ) and is_dir( 'db' ) ) {
    // We've already set things up - so redirect to the index page.
    header( "Location: index.php" );
    die();
}

?>

<html>
<head>
    <title>SendGrid Event Webhook Starter Kit Setup</title>

    <!-- STYLES -->
    <style type="text/css">
        body {
            background: #EFEFEF;
        }

        #container {
            width: 800px;
            height: 400px;
            left: 50%;
            top: 50%;
            margin-left: -400px;
            margin-top: -200px;
            position: absolute;
        }
    </style>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

</head>
<body>
<?php

if ( isset( $_POST['username'] ) and isset( $_POST['password'] ) ) {
    // CREATE THE HTACCESS
    $location = dirname( __FILE__ );
    $contents = "AuthType Basic\nAuthUserFile ".$location."/.htpasswd\nAuthName \"Members Area\"\nrequire valid-user";
    $htaccess = $location.'/.htaccess';
    file_put_contents( $htaccess, $contents );

    // CREATE THE HTPASSWD
    $hash = base64_encode( sha1( $_POST['password'], true ) );
    $password = $_POST['username'].':{SHA}' . $hash;
    $htpasswd = $location.'/.htpasswd';
    file_put_contents( $htpasswd, $password );

    // PERMISSIONS
    chmod( '.htaccess', 0777 );
    chmod( '.htpasswd', 0777 );
?>

    <div id="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <img src="images/brand.png" />
            </div>
            <div class="panel-body">
                <p>You're all set up!  Now you can log in to <a target="_blank" href="http://sendgrid.com/app">http://sendgrid.com/app</a> and set the following URL in the Event Notification settings:</p>
                <strong><?php echo $http.$_POST['username'].':'.$_POST['password'].'@'.$_SERVER['HTTP_HOST'].dirname( $_SERVER['PHP_SELF'] ); ?></strong>
                <p><a href="index.php">Go to dashboard</a></p>
            </div>
        </div>
    </div>

<?php
} else {
    // CREATE A NEW DATABASE INSTANCE
    SendGrid\EventKit\DatabaseController::createNewDatabase();

    // CLEAN UP
    // Delete uneeded files
    $parentDir = dirname( dirname( __FILE__ ) );
    unlink( $parentDir.DIRECTORY_SEPARATOR."eventkit.zip" );
    unlink( $parentDir.DIRECTORY_SEPARATOR."index.php" );
?>

    <div id="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <img src="images/brand.png" />
            </div>
            <div class="panel-body">
                <p>Welcome to the SendGrid Event Webhook Starter Kit Installer. You're almost ready to start - but first you'll need to choose a username and password.  This will create an HTTP Basic Authentication credential to add a basic layer of security.  Since this starter kit is meant as an example, it's strongly recommended that you look into better security options since your database is behind a basic username and password.</p>
                <form role="form" action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>

<?php
}
?>

</body>
</html>
