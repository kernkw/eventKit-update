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
            "email" => "TEXT",
            "smtpid" => "TEXT",
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
            "email" => "TEXT",
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
            "email" => "TEXT",
            "smtpid" => "TEXT",
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
            "email" => "TEXT",
            "smtpid" => "TEXT",
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
            "email" => "TEXT",
            "smtpid" => "TEXT",
            "sg_event_id" => "TEXT",
            "reason" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "open" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT",
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
            "email" => "TEXT",
            "smtpid" => "TEXT",
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
            "email" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "unsubscribe" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        ),
        "other" => array(
            "timestamp" => "INT",
            "event" => "TEXT",
            "email" => "TEXT",
            "smtpid" => "TEXT",
            "sg_event_id" => "TEXT",
            "category" => "TEXT",
            "newsletter" => "TEXT",
            "subject" => "TEXT",
            "response" => "TEXT",
            "reason" => "TEXT",
            "ip" => "TEXT",
            "useragent" => "TEXT",
            "attempt" => "TEXT",
            "status" => "TEXT",
            "type" => "TEXT",
            "url" => "TEXT",
            "additional_arguments" => "TEXT",
            "event_post_timestamp" => "INT",
            "raw" => "TEXT"
        )
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
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
            $parsed = json_decode($json, true);
        } catch (Exception $e) {
            Logger::logError("An error occurred while parsing the POST JSON: ".$e->getMessage());
            return "HTTP/1.0 400 Received a body that cannot be parsed as JSON.";
        }
        
        // CHECK IF AN ARRAY
        // This starter kit uses V3 of the SendGrid Event Webhook, and is
        // expecting an array.  If our parsed data isn't an array, throw
        // an error.
        function isAssociativeArray($a) {
        	foreach(array_keys($a) as $key)
        		if (!is_int($key)) return true;
        	return false;
        };
        if (!is_array($parsed) or isAssociativeArray($parsed)) {
            Logger::logError("The parsed body received is not an indexed array as expected: ".var_export($parsed, true));
            return "HTTP/1.0 400 Received a body that is not an array.";
        }
        
        // LOOP THROUGH ARRAY
        // We're now going to loop through the array and process each event.
        foreach($parsed as $notification) {
            $this->insertNotification($notification);
        }
                
        return "HTTP/1.0 200 Event POST accepted.";
    }
    
    
    
    /* insertNotification
 
     SUMMARY
     This function takes a single associative array from the webhook POST and
     inserts it into the appropriate table of the database (the database has
     a table for each type of event).
 
     PARAMETERS
     $notification      An associative array containing the notification info.
 
     RETURNS
     A BOOL indicating if the notification was successfully inserted into the
     database or not.
 
     ==========================================================================*/
    public function insertNotification($notification)
    {
        // CHECK EVENT TYPE
        // At the very minimum we need an event parameter.
        if (!array_key_exists("event", $notification)) {
            Logger::logError("Could not insert notification into table - missing \"event\" key: ".var_export($notification, true));
            return false;
        }
        
        $event = $notification["event"];
        
        // GRAB THE SCHEMA FOR THE EVENT
        // If for some reason a schema doesn't exist for the given event, grab
        // the generic "other" schema which contains columns for every possible
        // parameter.
        $schema = self::$schemas["other"];
        if (array_key_exists($event, self::$schemas)) $schema = self::$schemas[$event];
        else Logger::logError("Couldn't find a schema for event type \"$event\". Using generic \"other\" schema.");
        
        // ARRAY FOR INSERTING INTO TABLE
        // This is the array we'll use to add data to the database. It
        // follows the schema of the target table.
        $table_values = array(
            "event_post_timestamp" => time(),
            "raw" => json_encode($notification)
        );
        
        // FIND THE ADDITIONAL ARGUMENTS
        // Find the additional parameters that aren't part of the default 
        // parameters (i.e. unique arguments) and group them into their
        // own array. All other arguments will be grouped into a separate
        // array to be used for inserting into the database.
        $additional_arguments = array();
        
        foreach($notification as $key => $value) {
            $mod_key = str_replace("-", "" ,$key);
            if (array_key_exists($mod_key, $schema)) {
                if ($mod_key === "newsletter" or $mod_key === "category") {
                    $table_values[$mod_key] = json_encode($value);
                } else {
                    $table_values[$mod_key] = $value;
                }
            } else {
                $additional_arguments[$mod_key] = $value;
            }
        }
        
        // ADD ADDITIONAL ARGUMENT ARRAY TO TABLE ARRAY
        // Now that we've separated the additional arguments, convert
        // them to JSON and add the string to the array used to insert
        // data into the table.
        if (!empty($additional_arguments)) {
            $table_values["additional_arguments"] = json_encode($additional_arguments);
        }
                
        // PREP THE SQL STATEMENT
        // Create a string to run as a SQL statement for adding the 
        // data into the database.
        $columns = "";
        $values = "";
        $bindings = array();
        foreach($table_values as $column => $value) {
            $bindings[":$column"] = $value;
            
            if (strlen($columns)) $columns .= ", ";
            $columns .= "`$column`";
            
            if (strlen($values)) $values .= ", ";
            $values .= ":$column";
        }
        $sql = "INSERT INTO `$event` ($columns) VALUES ($values)";
                    
        // EXECUTE THE SQL STATEMENT
        // Run the command and add the data into the database.
        try {
            $stmt = $this->_db->prepare($sql);
            if ($stmt->execute($bindings) === FALSE) {
                Logger::logError("An error occurred while inserting data into the database: ".var_export($bindings, true));
            }
        } catch (PDOException $e) {
            Logger::logError("An error occurred while inserting data into the database: ".$e->getMessage());
        }
        
        return true;
    }
}

?>
