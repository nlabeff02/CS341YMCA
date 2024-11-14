<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Check if personID is set in the session
//if (!isset($_SESSION['user'])) {
//    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
//    exit();
//}

// Get today's date
$currentDate = date('Y-m-d');

$personID = $_SESSION['user']['personID'];

// Query to get classes that have not yet ended
// Retrieve all classes for a member from Registrations
// and join with Classes where EndDate is >= today
$query = "
    SELECT 
        r.classID,
        r.registrationDate,
        r.paymentStatus,
        c.className,
        c.classDescription,
        c.startDate,
        c.endDate,
        c.dayOfWeek,
        c.startTime,
        c.endTime,
        c.classLocation,
        c.maxParticipants,
        c.currentParticipantCount,
        c.priceStaff,
        c.priceMember,
        c.priceNonMember,
        c.prerequisiteClassName
    FROM 
        Registrations r
    JOIN 
        Classes c ON r.classID = c.classID
    WHERE 
        r.personID = ? AND c.endDate >= ?
";

$stmt = $connect->prepare($query);
$stmt->bind_param('is', $personID, $currentDate);
$stmt->execute();
$result = $stmt->get_result();

// Collect data
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

$stmt->close();
$connect->close();

// Output the result as JSON
echo json_encode(['status' => 'success', 'classes' => $classes]);