<?php

/*

 WARNING - IF YOU'RE READING THIS MESSAGE IN YOUR BROWSER, THIS MEANS THAT
 YOUR WEBHOST DOES NOT HAVE PHP INSTALLED.

 The SendGrid Event Webhook Starter Kit requires PHP 5.3 or higher installed
 as well as SQLite 3 or higher.

 Contact your webhost for more information on getting PHP 5.3 or higher
 installed.

 ==========================================================================*/
 

$directory_writable = true;

$installerURL = "https://raw.githubusercontent.com/sendgrid/eventkit/master/Downloader.php";

function get_data($url) {
    $ch = curl_init();
    $timeout = 5; 
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

if ( is_writable( dirname( __FILE__ ) ) ) {
    $downloader = get_data($installerURL);
    $file = dirname( __FILE__ ).DIRECTORY_SEPARATOR."Downloader.php";
    file_put_contents($file, $downloader);
} else {
    $directory_writable = false;
}
 ?>

 <html>
 <head>
    <title>SendGrid Event Webhook Starter Kit Installer</title>
    <style type="text/css">
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
            height: 400px;
            left: 50%;
            top: 50%;
            margin-left: -400px;
            margin-top: -200px;
            position: absolute;
        }
    </style>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    
 </head>
 <body>
<div id="bg">

<?php

if (!$directory_writable) {
?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Uh Oh!</h3>
            </div>
            <div class="panel-body">
                It looks like the folder you put this installer in doesn't give permission to write and install new files.  Modify the permissions on the folder you placed this in or contact your web host.
            </div>
        </div>
<?php
} else {
?>
    <script>
        window.location = "Downloader.php";
    </script>
<?php
}
?>
    </div>
</div>
</body>
</html>
