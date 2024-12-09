<?php
header('Content-Type: application/json');
include __DIR__ . '/db.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$classID = $input['classID'] ?? null;
$personID = $input['personID'] ?? null;

if (empty($classID) || empty($personID)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

// Begin a transaction
$connect->begin_transaction();

try {
    // Deactivate the registration
    $stmt = $connect->prepare("UPDATE registrations SET isActive = 0 WHERE ClassID = ? AND PersonID = ?");
    $stmt->bind_param('ii', $classID, $personID);
    $stmt->execute();

    // Update the participant count in the Classes table
    $stmt1 = $connect->prepare("UPDATE classes SET CurrentParticipantCount = CurrentParticipantCount - 1 WHERE ClassID = ?");
    $stmt1->bind_param('i', $classID);
    $stmt1->execute();

    // Commit the transaction
    $connect->commit();

    echo json_encode(['status' => 'success', 'message' => 'Registration cancelled successfully']);
} catch (Exception $e) {
    // Rollback the transaction on error
    $connect->rollback();
    error_log("Error cancelling registration: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to cancel registration']);
} finally {
    $stmt->close();
    $stmt1->close();
    $connect->close();
}