<?php
include("auto-return-module.php");

// $conn is set in include/connect.php inside auto-return-module.php already

// Use $autoReturn object to call methods

// Cron Job Script - Run this every hour to check for expired releases
// Add this to your crontab: 0 * * * * /usr/bin/php /path/to/your/cron-job.php

require_once 'auto-return-module.php';

// Log file for cron job execution
$logFile = 'auto_return_cron.log';

function writeLog($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

try {
    writeLog("Starting auto return cron job");
    
    // Process expired releases
    $result = $autoReturn->processExpiredReleases();
    
    if ($result['success']) {
        writeLog("Success: " . $result['message']);
    } else {
        writeLog("Error: " . $result['error']);
    }
    
} catch (Exception $e) {
    writeLog("Fatal error: " . $e->getMessage());
}

writeLog("Auto return cron job completed");
?>
