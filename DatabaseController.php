<?php
/* ==========================================================================
 * DatabaseController
 * ==========================================================================
 *
 * SUMMARY
 * This class manages the database (reading, writing, etc.). It also handles
 * inserting new POST data into the database when a notification is 
 * received.
 *
 */

namespace SendGrid\EventStarterKit;

require_once("Logger.php");
include_once("Constants.php");

use PDO;
use Logger;

class DatabaseController
{
    /* 
    
     PROPERTIES
    
     *===========================================*/
    private $_db = null;
    
    public static $schemas = array(
        "bounce" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "smtp-id" => "TEXT",
            "sg_event_id" => "TEXT",
            "reason" => "TEXT",
            "status" => "TEXT",
            "type" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "click" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "ip" => "TEXT",
            "useragent" => "TEXT",
            "url" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "deferred" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "smtp-id" => "TEXT",
            "sg_event_id" => "TEXT",
            "response" => "TEXT",
            "attempt" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "delivered" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "smtp-id" => "TEXT",
            "sg_event_id" => "TEXT",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "subject" => "TEXT",
            "response" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "dropped" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "smtp-id" => "TEXT",
            "sg_event_id" => "TEXT",
            "reason" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "open" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "subject" => "TEXT",
            "ip" => "TEXT",
            "useragent" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "processed" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "smtp-id" => "TEXT",
            "sg_event_id" => "TEXT",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "subject" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "spamreport" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "unsubscribe" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT PRIMARY KEY",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
    );
    
    
    
    
    /* 
    
     FUNCTIONS
    
     *=========================================================================*/
    
    /* __construct
 
     SUMMARY
     Initializes the controller - opens the database;
 
     PARAMETERS
     None.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public function __construct()
    {
        try {
            $this->_db = new PDO('sqlite:'.DB_NAME);
        } catch(Exception $e) {
            Logger::logError("An error occurred while creating a new database: ".$e->getMessage());
            die($e->getMessage());
        }
    }
    
    
    
    /* processPost
 
     SUMMARY
     Accepts a JSON string from the webhook POST to parse and insert into the
     database.
 
     PARAMETERS
     $json      The JSON string from the webhook POST.
 
     RETURNS
     A string representing the header to indicate to the webhook a success or
     failure.
 
     ==========================================================================*/
    public function processPost($json)
    {
        $parsed;
        
        // TRY PARSING THE STRING, THROW AN ERROR IF NEEDED.
        try {
            $parsed = json_decode($json);
        } catch (Exception $e) {
            Logger::logError("An error occurred while parsing the POST JSON: ".$e->getMessage());
            return "HTTP/1.0 400 Received a body that cannot be parsed as JSON.";
        }
        
        // CHECK IF AN ARRAY
        // This starter kit uses V3 of the SendGrid Event Webhook, and is
        // expecting an array.  If our parsed data isn't an array, throw
        // an error.
        if (!is_array($parsed)) {
            Logger::logError("The parsed body received is not an array as expected: ".print_r($parsed, true));
            return "HTTP/1.0 400 Received a body that is not an array.";
        }
        
        return "HTTP/1.0 200 Event POST accepted.";
    }
}

?>
