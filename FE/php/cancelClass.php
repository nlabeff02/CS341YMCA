<?php
header('Content-Type: application/json');
include __DIR__ . '/db.php'; // Database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $classID = $input['classID'] ?? null;

    // Validate input
    if (!$classID) {
        echo json_encode(['status' => 'error', 'message' => 'Class ID and Person ID are required']);
        exit();
    }

    //mark the class as inactive (canceled)
    $cancelClassQuery = "UPDATE Classes SET isActive = 0 WHERE classID = ? AND isActive = 1"; 
    $stmt = $connect->prepare($cancelClassQuery);
    $stmt->bind_param("i", $classID);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Class canceled successfully ']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel class: ' . $stmt->error]);
    }

    $stmt->close();
}

$connect->close();

