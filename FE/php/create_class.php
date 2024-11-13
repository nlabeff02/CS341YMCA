<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include __DIR__ . '/db.php';
session_start();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and sanitize form data
    $className = $_POST['className'] ?? '';
    $classDescription = $_POST['classDescription'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    //$dayOfWeek = isset($_POST['dayOfWeek']) ? implode(',', $_POST['dayOfWeek']) : '';
    $dayOfWeek = isset($_POST['dayOfWeek']) && is_array($_POST['dayOfWeek']) ? implode(',', $_POST['dayOfWeek']) : '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $location = $_POST['location'] ?? '';
    $maxParticipants = $_POST['maxParticipants'] ?? 0;
    $priceStaff = $_POST['priceStaff'] ?? 0;
    $priceMember = $_POST['priceMember'] ?? 0;
    $priceNonMember = $_POST['priceNonMember'] ?? 0;
    $prerequisiteClassName = $_POST['prerequisiteClassName'] ?? null;

    // SQL query to insert the data
    $sql = "INSERT INTO Classes (className, classDescription, startDate, endDate, dayOfWeek, startTime, endTime, classlocation, maxParticipants, priceStaff, priceMember, priceNonMember, prerequisiteClassName)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    if ($stmt = $connect->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param(
            "ssssssssiiiis",
            $className,
            $classDescription,
            $startDate,
            $endDate,
            $dayOfWeek,
            $startTime,
            $endTime,
            $location,
            $maxParticipants,
            $priceStaff,
            $priceMember,
            $priceNonMember,
            $prerequisiteClassName
        );

        // Execute the statement and check if the insertion was successful
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Class created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create class: ' . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
    }
}

// Close the database connection
$connect->close();
