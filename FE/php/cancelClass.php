<?php
header('Content-Type: application/json');
include __DIR__ . '/db.php'; // Database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $classID = $input['classID'] ?? null;

    // Validate input
    if (!$classID) {
        echo json_encode(['status' => 'error', 'message' => 'Class ID is required']);
        exit();
    }

    // Start transaction
    $connect->begin_transaction();

    try {
        // 1. Update 'isActive' to 0 for all users registered for the class
        $updateRegistrationsQuery = "UPDATE Registrations SET isActive = 0 WHERE ClassID = ?";
        $stmt1 = $connect->prepare($updateRegistrationsQuery);
        $stmt1->bind_param("i", $classID);

        if (!$stmt1->execute()) {
            throw new Exception('Failed to update Registrations: ' . $stmt1->error);
        }

        // 2. Mark the class as inactive (cancel it)
        $cancelClassQuery = "UPDATE Classes SET isActive = 0 WHERE classID = ? AND isActive = 1";
        $stmt2 = $connect->prepare($cancelClassQuery);
        $stmt2->bind_param("i", $classID);

        if (!$stmt2->execute()) {
            throw new Exception('Failed to cancel class: ' . $stmt2->error);
        }

        // Commit transaction
        $connect->commit();

        echo json_encode(['status' => 'success', 'message' => 'Class and all registrations canceled successfully']);

    } catch (Exception $e) {
        // Rollback transaction if any query fails
        $connect->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    // Close statements
    $stmt1->close();
    $stmt2->close();
}

$connect->close();
?>


