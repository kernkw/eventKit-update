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

// CREATE A NEW DATABASE INSTANCE
SendGrid\EventKit\DatabaseController::createNewDatabase();

// CLEAN UP
// Delete uneeded files
$parentDir = dirname( dirname( __FILE__ ) );
echo "Unlinking ".$parentDir;
unlink($parentDir.DIRECTORY_SEPARATOR."eventkit.zip");

?>
