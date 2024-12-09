<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

session_start();

// Enable error reporting for debugging (comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Validate session
if (!isset($_SESSION['user']['personID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

// Get the current user's personID from the session
$personID = $_SESSION['user']['personID'];

// SQL query to fetch past classes
$query = "
    SELECT 
        r.classID,
        r.registrationDate,
        r.paymentStatus,
        r.isActive as regIsActive,
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
        c.prerequisiteClassName,
        c.isActive as classIsActive
    FROM 
        Registrations r
    JOIN 
        Classes c ON r.classID = c.classID
    WHERE 
        r.personID = ? 
        AND (
            c.endDate < CURRENT_DATE
            OR c.isActive = 0
            OR r.isActive = 0
        )
";

// Prepare and execute the statement
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $personID);
$stmt->execute();
$result = $stmt->get_result();

// Collect the data
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

// Close the statement and connection
$stmt->close();
$connect->close();

// Return the data as JSON
echo json_encode(['status' => 'success', 'classes' => $classes]);
