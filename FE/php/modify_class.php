<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include __DIR__ . '/db.php';

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Collect data from the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$classID = $_POST['classID'] ?? null;
$className = $_POST['className'] ?? null;
$classDescription = $_POST['classDescription'] ?? null;
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$dayOfWeek = isset($_POST['dayOfWeek']) && is_array($_POST['dayOfWeek']) ? implode(',', $_POST['dayOfWeek']) : '';
$startTime = $_POST['startTime'] ?? null;
$endTime = $_POST['endTime'] ?? null;
$location = $_POST['location'] ?? ''; // Ensure $location is always a string
$maxParticipants = $_POST['maxParticipants'] ?? null;
$priceStaff = $_POST['priceStaff'] ?? null;
$priceMember = $_POST['priceMember'] ?? null;
$priceNonMember = $_POST['priceNonMember'] ?? null;
$prerequisiteClassName = $_POST['prerequisiteClassName'] ?? null;
$isActive = $_POST['isActive'] ?? null;

// Validate required fields, skip validation for `location`
if (empty($className)) { echo json_encode(['status' => 'error', 'message' => 'Class Name is missing']); exit(); }
if (empty($startDate)) { echo json_encode(['status' => 'error', 'message' => 'Start Date is missing']); exit(); }
if (empty($endDate)) { echo json_encode(['status' => 'error', 'message' => 'End Date is missing']); exit(); }
if (empty($startTime)) { echo json_encode(['status' => 'error', 'message' => 'Start Time is missing']); exit(); }
if (empty($endTime)) { echo json_encode(['status' => 'error', 'message' => 'End Time is missing']); exit(); }
if (empty($maxParticipants)) { echo json_encode(['status' => 'error', 'message' => 'Max Participants is missing']); exit(); }
if (empty($isActive)) { echo json_encode(['status' => 'error', 'message' => 'Active Status is missing']); exit(); }

// Prepare the SQL statement
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
        ClassLocation = ?, 
        MaxParticipants = ?, 
        PriceStaff = ?, 
        PriceMember = ?, 
        PriceNonMember = ?, 
        PrerequisiteClassName = ?,
        IsActive = ?
    WHERE 
        ClassID = ?
");

// Use `bind_param` with appropriate types for each parameter
$stmt->bind_param(
    "sssssssiiiiissi",
    $className,
    $classDescription,
    $startDate,
    $endDate,
    $dayOfWeek,
    $startTime,
    $endTime,
    $location, // Location as a string
    $maxParticipants,
    $priceStaff,
    $priceMember,
    $priceNonMember,
    $prerequisiteClassName,
    $isActive,   // isActive as a string
    $classID
);

// Execute and check for errors
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Class updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating class']);
}
}
// Close database connection
$connect->close();