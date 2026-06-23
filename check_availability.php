<?php
// includes/check_availability.php — AJAX endpoint
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
 
$date = $_GET['date'] ?? '';
if (!$date || !strtotime($date)) {
    echo json_encode(['available' => false]);
    exit;
}
 
echo json_encode(['available' => isAvailable($date)]);
 
