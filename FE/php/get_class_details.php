<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if classID is provided in the URL
if (!isset($_GET['classID']) || empty($_GET['classID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Class ID is required']);
    exit();
}

$classID = intval($_GET['classID']); // Ensure classID is an integer

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Query to fetch class details by classID
$query = "
    SELECT
        c.ClassID,
        c.ClassName,
        c.ClassDescription,
        c.StartDate,
        c.EndDate,
        c.DayOfWeek,
        c.StartTime,
        c.EndTime,
        c.ClassLocation,
        c.MaxParticipants,
        c.CurrentParticipantCount,
        c.PriceStaff,
        c.PriceMember,
        c.PriceNonMember,
        c.PrerequisiteClassName
    FROM Classes c
";

// Prepare and execute the statement
$stmt = $connect->prepare($query);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
    exit();
}

//$stmt->bind_param('i', $classID); // Bind $classID as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if class was found
if ($result->num_rows > 0) {
    $class = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'class' => $class]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Class not found']);
}

// Close the connection
$stmt->close();
$connect->close();
