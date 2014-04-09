<?php

session_start();
require_once "../Logger.php";
require_once  "../DatabaseController.php";

?>

<html>
<head>
  <title>SendGrid Event Webhook Starter Kit</title>
  <script>
    function init() {
<?php

// CREATE POST TO SETUP USERS EVENT NOTIFICATIONS APP WITH NEW ENDPOINT

//Enable Event Notifications App
$url = 'https://api.sendgrid.com/';
$user = $_SESSION["sg_username"];
$pass = $_SESSION["sg_password"];
$params = array(
  'api_user' => $user,
  'api_key' => $pass,
  'name' => 'eventnotify'
);
$request = $url . 'api/filter.activate.json';

// Generate first curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response_1 = curl_exec($session);

// Create curl request to setup setting of the event notifications app
$url = 'https://api.sendgrid.com/';
$user = $_SESSION["sg_username"];
$pass = $_SESSION["sg_password"];

// set to 0 if not checked
$processed = $_SESSION['Processed'];
$dropped = $_SESSION["Dropped"];
$deferred = $_SESSION["Deferred"];
$delivered = $_SESSION['Delivered'];
$bounce = $_SESSION['Bounced'];
$click = $_SESSION['Clicked'];
$open = $_SESSION['Opened'];
$unsubscribe = $_SESSION['Unsubscribed'];
$spamreport = $_SESSION['Spam'];
$eventurl = $_SESSION['eventurl'];

$params = array(
  'api_user' => $user,
  'api_key' => $pass,
  'name' => 'eventnotify',
  'processed' => $processed,
  'dropped' => $dropped,
  'deferred' => $deferred,
  'delivered' => $delivered,
  'bounce' => $bounce,
  'click' => $click,
  'open' => $open,
  'unsubscribe' => $unsubscribe,
  'spamreport' => $spamreport,
  'url' => $eventurl
  );
$request = $url . 'api/filter.setup.json';

// Generate curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response_2 = curl_exec($session);
curl_close($session);

$response_1_json = json_decode($response_1, true);
$response_2_json = json_decode($response_2, true);

if (($response_1_json['message'] == 'success') && ($response_2_json['message'] == 'success')) {
//if ($response_1 === '{"message":"success"}') {

?>

        window.location = "success.php";

<?php

} else {

?>

        window.location = '../Step2Installer.php?error1=<?php echo $response_1?>&error2=<?php echo $response_2?>';

<?php


}

?>
    }
  </script>
</head>
<body onload="init();">
</body>