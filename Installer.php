<?php
/* ==========================================================================
 * INSTALLER
 * ==========================================================================
 *
 * SUMMARY
 * This class takes care of the initial installation of the
 * SendGrid Event Webhook Starter Kit
 *
 */

namespace SendGrid\EventStarterKit;

require_once("Logger.php");
require_once("DatabaseController.php");

use PDO;
use Logger;

class Installer
{
    /* __construct
 
     SUMMARY
     Upon initiating the installer, this checks to see if the Installer needs
     to be run or not.
 
     PARAMETERS
     None.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public function __construct()
    {
        if (file_exists("events.db"))
        {
            echo "Database exists!";
        } else {
            $this->createNewDatabase("scottkawai","test123");
        }
    }
    
    
    /* createNewDatabase
 
     SUMMARY
     Creates a new SQLite database and stores the credentials in a separate
     file
 
     PARAMETERS
     None.
  
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public function createNewDatabase()
    {
        try {
            // CREATE THE DATABASE
            $db_name = uniqid().".db";
            $file_db = new PDO('sqlite:'.$db_name);
            Logger::logSystem("Created new $db_name database.");
            
            // STORE THE CREDENTIALS
            $credentials = "<?php\n    define(\"DB_NAME\", \"$db_name\");\n?>";
            file_put_contents("Constants.php", $credentials, FILE_APPEND | LOCK_EX);
            
            // CREATE THE TABLES
            // Each event will have their own table, so first we need to
            // organize the events and all the columns their tables will
            // have.
            $events = DatabaseController::$schemas;
            
            // CYCLE THROUGH EACH EVENT AND CREATE THE TABLE
            foreach($events as $key => $event) {
                $table_columns = "";
                foreach($event as $column => $type) {
                    if (strlen($table_columns) > 0) $table_columns .= ",\n";
                    $table_columns .= "`$column` $type";
                }
                $create_table = "CREATE TABLE IF NOT EXISTS `$key` (\n$table_columns\n);";
                $file_db->exec($create_table);
                Logger::logSystem("Created table \"$key\" in the database.");
            }
        } catch(Exception $e) {
            // LOG THE ERROR
            Logger::logError("An error occurred while creating a new database: ".$e->getMessage());
            die($e->getMessage());
        }
    }
}

?>