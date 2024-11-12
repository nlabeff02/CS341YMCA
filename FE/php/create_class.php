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
$className = $_POST['className'] ?? null;
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$dayOfWeek = $_POST['dayOfWeek'] ?? null;
$startTime = $_POST['startTime'] ?? null;
$endTime = $_POST['endTime'] ?? null;
$location = $_POST['location'] ?? null;
$maxParticipants = $_POST['maxParticipants'] ?? null;
$priceMember = $_POST['priceMember'] ?? null;
$priceNonMember = $_POST['priceNonMember'] ?? null;
$prerequisiteClassName = $_POST['prerequisiteClassName'] ?? null;

// Validate required fields
if (empty($className) || empty($startDate) || empty($endDate) || empty($dayOfWeek) || empty($startTime) || empty($endTime) || empty($location) || empty($maxParticipants) || empty($priceMember) || empty($priceNonMember)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

// If PrerequisiteClassName is empty, set it to NULL
if (empty($prerequisiteClassName)) {
    $prerequisiteClassName = NULL;
}

// Insert class into the database
$stmt = $connect->prepare("
    INSERT INTO Classes
    (ClassName, StartDate, EndDate, DayOfWeek, StartTime, EndTime, Location, MaxParticipants, PriceMember, PriceNonMember, PrerequisiteClassName)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

// Use `bind_param` with appropriate types, setting `PrerequisiteClassName` to NULL if necessary
$stmt->bind_param(
    "sssssssidii",
    $className,
    $startDate,
    $endDate,
    $dayOfWeek,
    $startTime,
    $endTime,
    $location,
    $maxParticipants,
    $priceMember,
    $priceNonMember,
    $prerequisiteClassName
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Class created successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error creating class']);
}

// Close database connection
$connect->close();
?>
