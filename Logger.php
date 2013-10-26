<?php

/* ==========================================================================
 * LOGGER
 * ==========================================================================
 *
 * SUMMARY
 * This class serves as a general logging service.
 *
 */


class Logger
{
    /* 
    
     PARAMETERS
    
     *===========================================*/
    public static $log_folder = "logs";
    public static $error_file = "errors.log";
    public static $system_file = "system.log";
    
    
    
    /* prepLogs
 
     SUMMARY
     Upon initiating the logger, check for the required files and folders.
 
     PARAMETERS
     None.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public static function prepLogs()
    {
        $folder = Logger::$log_folder;
        if (!file_exists($folder) and !is_dir($folder)) {
            $oldumask = umask(0);
            mkdir($folder, 0777);
            umask($oldumask);
        }
    }
    
    
    
    /* _log
 
     SUMMARY
     This function does the actual writing, and is used by both logError and
     logSystem.
 
     PARAMETERS
     $file      The log file to write to.
     $value     The value to write.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    private static function _log($file, $value)
    {
        self::prepLogs();
        $file = fopen(join(DIRECTORY_SEPARATOR, array(self::$log_folder, $file)), 'a+');
        if ($file) {
            fwrite($file, date(DATE_ISO8601)." (".time().") ".trim(preg_replace('/\s+/', ' ', $value))."\n");
            fclose($file);
        }
    }
    
    
    
    /* logError
 
     SUMMARY
     Writes to the error log.
 
     PARAMETERS
     A string, containing the value to write to the error log.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public static function logError($value)
    {
        error_log($value);
        self::_log(self::$error_file, $value);
    }
    
    
    
    /* logSystem
 
     SUMMARY
     Writes to the system log.
 
     PARAMETERS
     A string, containing the value to write to the system log.
 
     RETURNS
     Nothing.
 
     ==========================================================================*/
    public static function logSystem($value)
    {
        self::_log(self::$system_file, $value);
    }
}

?>