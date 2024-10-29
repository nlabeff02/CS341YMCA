<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Query to get classes that have not yet started
$currentDate = date('Y-m-d');
$query = "SELECT ClassName, StartDate FROM Classes WHERE StartDate >= ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$result = $stmt->get_result();

// Collect data
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = [
        'className' => $row['ClassName'],
        'startDate' => $row['StartDate']
    ];
}

// Return the classes in JSON format
echo json_encode(['status' => 'success', 'classes' => $classes]);

// Close the connection
$stmt->close();
$connect->close();