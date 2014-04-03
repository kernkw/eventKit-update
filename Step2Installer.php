<?php
/* ==========================================================================
* Event URL APP Setup
* ==========================================================================
*
* SUMMARY
* This script prompts user for SG User/Pass
* and runs curl script to make the API call
* to SendGrid to set Event Notification
* URL and settings.
* SendGrid Event Webhook Starter Kit
*
*/

session_start();
require_once "Logger.php";
require_once "DatabaseController.php";

?>

<html>
<head>
    <title>SendGrid Event Webhook Starter Kit Setup</title>

    <!-- STYLES -->
    <style type="text/css">
        body {
            background: #EFEFEF;

        }

        #bg {
            background: #EFEFEF;
            position: absolute;
            left: 0px;
            right: 0px;
            top: 0px;
            bottom: 0px;
        }

        .error {color: #FF0000;}

        #container {
            width: 800px;
            height: 600px;
            left: 50%;
            top: 50%;
            margin-left: -400px;
            margin-top: -300px;
            position: absolute;
        }
    </style>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/application/css/application.css">
    <link rel="stylesheet" href="assets/vendor/css/vendor.css">


  </head>

  <body>
    <div id="bg">
    <div id="container">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <img src="images/brand.png" />
        </div>
        <div class="panel-body">
            <p>Now we will need your active SendGrid Username and Password to setup your SendGrid Event Webhook. We recommend setting up a user credential with only API permission <a href="https://sendgrid.com/credentials"> Click here to Manage User Credentials</a></p>
<?php
// define variables and set to empty values
$usernameErr = $passwordErr = "";
$username = $password = "";
?>

<span class="error">
<?php
if ( isset( $_GET['error1'] ) && isset( $_GET['error2'] ) ) {
  $curl_response1 = $_GET['error1'];
  $curl_response2 = $_GET['error2'];
  echo "There was an error processing your request.\r\n";
  echo $curl_response1;
  echo $curl_response2;
}
?>
</span>

<?php

if ( isset( $_POST['submit'] ) ) {
  if ( empty( $_POST["sg_username"] ) || empty( $_POST["sg_password"] ) ) {
?>

<p><span class="error">* required field.</span></p>
<?php
    if ( empty( $_POST["sg_username"] ) ) {
      $usernameErr = "* Username is required";
    }

    if ( empty( $_POST["sg_password"] ) ) {
      $passwordErr = "* Password is required";
    }
  }
  else {
    $username = clean_up_input( $_POST["sg_username"] );
    $password = clean_up_input( $_POST["sg_password"] );
    $_SESSION["sg_username"] = $username;
    $_SESSION["sg_password"] = $password;
    $_SESSION["Processed"] = ( isset( $_POST['Processed'] ) ) ? 1 : 0;
    $_SESSION["Dropped"] = ( isset( $_POST['Dropped'] ) ) ? 1 : 0;
    $_SESSION["Deferred"] = ( isset( $_POST['Deferred'] ) ) ? 1 : 0;
    $_SESSION["Delivered"] = ( isset( $_POST['Delivered'] ) ) ? 1 : 0;
    $_SESSION["Bounced"] = ( isset( $_POST['Bounced'] ) ) ? 1 : 0;
    $_SESSION["Clicked"] = ( isset( $_POST['Clicked'] ) ) ? 1 : 0;
    $_SESSION["Opened"] = ( isset( $_POST['Opened'] ) ) ? 1 : 0;
    $_SESSION["Unsubscribed"] = ( isset( $_POST['Unsubscribed'] ) ) ? 1 : 0;
    $_SESSION["Spam"] = ( isset( $_POST['Spam'] ) ) ? 1 : 0;
    header( "Location: api/curl-post.php" );
  }
}

function clean_up_input( $data ) {
  $data = trim( $data );
  return $data;
}
?>
          <form role="form" action="" method="POST">
            <div class="form-group">
              <label for="sg_username">SendGrid Username</label>
              <input type="text" class="form-control" id="sg_username" name="sg_username" value="<?php echo $username; ?>" placeholder="Sg_Username">
              <span class="error"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
              <label for="sg_password">SendGrid Password</label>
              <input type="password" class="form-control" id="sg_password" name="sg_password" value="<?php echo $password; ?>" placeholder="Sg_Password">
              <span class="error"><?php echo $passwordErr; ?></span>
            </div>
            <p>Please select which events you would like to track. NOTE: You can update these at anytime by going to your app settings for the <a href="https://sendgrid.com/app/appSettings/type/eventnotify/id/15"> Event Notifications App</a></p>
            <div class="form-group">
              <input type="checkbox" name="Processed" value= "1" checked>Processed<br />
              <input type="checkbox" name="Dropped" value= "1" checked>Dropped<br />
              <input type="checkbox" name="Deferred" value="1" checked>Deferred<br />
              <input type="checkbox" name="Delivered" value="1" checked>Delivered<br />
              <input type="checkbox" name="Bounced" value="1" checked>Bounced<br />
              <input type="checkbox" name="Opened" value="1" checked>Opened<br />
              <input type="checkbox" name="Clicked" value="1" checked>Clicked<br />
              <input type="checkbox" name="Unsubscribed" value="1" checked>Unsubscribed From<br />
              <input type="checkbox" name="Spam" value="1" checked>Marked as Spam<br />
            </div>
              <button type="submit" name="submit" class="btn btn-default">Finish</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </body>
</html>
