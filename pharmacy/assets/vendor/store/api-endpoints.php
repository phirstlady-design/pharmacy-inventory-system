<?php
// API Endpoints for Dashboard
include("auto-return-module.php");

// $conn is set in include/connect.php inside auto-return-module.php already

// Use $autoReturn object to call methods


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'auto-return-module.php';

try {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'getStats':
            $stats = $autoReturn->getAutoReturnStats(30);
            echo json_encode([
                'success' => true,
                'stats' => $stats
            ]);
            break;

        case 'getExpiringItems':
            $items = $autoReturn->getItemsAboutToExpire();
            echo json_encode([
                'success' => true,
                'items' => $items
            ]);
            break;

        case 'runAutoReturn':
            $result = $autoReturn->processExpiredReleases();
            echo json_encode($result);
            break;

        default:
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action'
            ]);
            break;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
