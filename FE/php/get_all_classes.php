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

// Query to get all classes with full details
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

$stmt = $connect->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Collect data
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = [
        'classID' => $row['ClassID'],
        'className' => $row['ClassName'],
        'classDescription' => $row['ClassDescription'],
        'startDate' => $row['StartDate'],
        'endDate' => $row['EndDate'],
        'dayOfWeek' => $row['DayOfWeek'],
        'startTime' => $row['StartTime'],
        'endTime' => $row['EndTime'],
        'classLocation' => $row['ClassLocation'],
        'maxParticipants' => $row['MaxParticipants'],
        'currentParticipantCount' => $row['CurrentParticipantCount'],
        'priceStaff' => $row['PriceStaff'],
        'priceMember' => $row['PriceMember'],
        'priceNonMember' => $row['PriceNonMember'],
        'prerequisiteClassName' => $row['PrerequisiteClassName'] ?? 'None'
    ];
}

// Return the classes in JSON format
echo json_encode(['status' => 'success', 'classes' => $classes]);

// Close the connection
$stmt->close();
$connect->close();