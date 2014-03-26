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
session_start();

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
    <link rel="stylesheet" href="../assets/application/css/application.css">
    <link rel="stylesheet" href="../assets/vendor/css/vendor.css">

</head>
<body>
<div id="bg">
         <div id="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <img src="../images/brand.png" />
            </div>
            <div class="panel-body">
                <!-- add response from SG whether successfully updated settings or not -->
                <p>You're all set up!  If you want to update or check your settings you can visit: <a target="_blank" href="http://sendgrid.com/app">http://sendgrid.com/app</a></p>
                <p>Your endpoint has been created at:</p>
                <strong> <?php echo $_SESSION['eventurl']; ?> </strong> <br />
               
                <p><a href="../index.php">Go to dashboard</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>