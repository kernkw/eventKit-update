<?php

/*

 WARNING - IF YOU'RE READING THIS MESSAGE IN YOUR BROWSER, THIS MEANS THAT
 YOUR WEBHOST DOES NOT HAVE PHP INSTALLED.

 The SendGrid Event Webhook Starter Kit requires PHP 5.3 or higher installed
 as well as SQLite 3 or higher.

 Contact your webhost for more information on getting PHP 5.3 or higher
 installed.

 ==========================================================================*/
 
 ?>

 <html>
 <head>
 	<title>SendGrid Event Webhook Starter Kit Installer</title>
 	<style type="text/css">
 		body {
 			background: #EFEFEF;
 		}

 		#container {
 			width: 800px;
 			height: 120px;
 			left: 50%;
 			top: 50%;
 			margin-left: -400px;
 			margin-top: -60px;
 			position: absolute;
 		}
 	</style>
 	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
 </head>
 <body>
 	<div id="container">

<?php
$folder = "eventkit";
if (file_exists($folder) and is_dir($folder)) {
    header("Location: eventkit/index.php");
    die();
}

$installerURL = "http://localhost/event_webhook_starter_kit/eventkit.zip";
if ( is_writable( dirname( __FILE__ ) ) ) {
    $file = "eventkit.zip";
	file_put_contents($file, fopen($installerURL, 'r'));
	
	$zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo(dirname(__FILE__));
        $zip->close();
        chmod("eventkit", 0777);
        header("Location: eventkit/Installer.php");
        die();
    } else {
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Uh Oh!</h3>
	</div>
	<div class="panel-body">
    	It looks like something went wrong during the installation. Check the permissions of the folder you placed this file in and try again.
	</div>
</div>

<?php
    }
} else {
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
}
?>
	</div>
</body>
</html>
