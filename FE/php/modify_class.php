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

// Collect data from the POST request
$classID = $_POST['classID'] ?? null;
$className = $_POST['className'] ?? null;
$classDescription = $_POST['classDescription'] ?? null;
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$dayOfWeek = $_POST['dayOfWeek'] ?? null;
$startTime = $_POST['startTime'] ?? null;
$endTime = $_POST['endTime'] ?? null;
$location = $_POST['location'] ?? null;
$maxParticipants = $_POST['maxParticipants'] ?? null;
$priceStaff = $_POST['priceStaff'] ?? null;
$priceMember = $_POST['priceMember'] ?? null;
$priceNonMember = $_POST['priceNonMember'] ?? null;
$prerequisiteClassName = $_POST['prerequisiteClassName'] ?? null;

// Validate required fields
if (empty($classID) || empty($className) || empty($startDate) || empty($endDate) || empty($dayOfWeek) || empty($startTime) || empty($endTime) || empty($location) || empty($maxParticipants) || empty($priceStaff) || empty($priceMember) || empty($priceNonMember)) {
    echo json_encode(['status' => 'error', 'message' => 'All required fields are missing']);
    exit();
}

// Update class in the database
$stmt = $connect->prepare("
    UPDATE Classes
    SET 
        ClassName = ?, 
        ClassDescription = ?, 
        StartDate = ?, 
        EndDate = ?, 
        DayOfWeek = ?, 
        StartTime = ?, 
        EndTime = ?, 
        Location = ?, 
        MaxParticipants = ?, 
        PriceStaff = ?, 
        PriceMember = ?, 
        PriceNonMember = ?, 
        PrerequisiteClassID = ?
    WHERE 
        ClassID = ?
");

// Use `bind_param` with appropriate types for each parameter
$stmt->bind_param(
    "sssssssisdissi",
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
    $prerequisiteClassID,
    $classID
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Class updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating class']);
}

// Close database connection
$connect->close();
