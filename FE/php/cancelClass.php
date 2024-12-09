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
    $personID = $input['personID'] ?? null;

    // Validate input
    if (!$classID || !$personID) {
        echo json_encode(['status' => 'error', 'message' => 'Class ID and Person ID are required']);
        exit();
    }

    // Check if the person is a staff member (or has the right role) to cancel the class
    $roleQuery = "SELECT role FROM People WHERE PersonID = ? LIMIT 1";
    $stmt = $connect->prepare($roleQuery);
    $stmt->bind_param("i", $personID);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if (!$role || $role !== 'Staff') { // You can check for 'Admin' or other roles here if needed
        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to cancel this class.']);
        exit();
    }

    // Step 1: Mark the class as inactive (canceled)
    $cancelClassQuery = "UPDATE Classes SET isActive = 0 WHERE classID = ? AND isActive = 1"; 
    $stmt = $connect->prepare($cancelClassQuery);
    $stmt->bind_param("i", $classID);
    
    if ($stmt->execute()) {
        // Step 2: Remove all registrations for the class (this cancels registrations)
        $cancelRegistrationsQuery = "DELETE FROM Registrations WHERE classID = ?";
        $stmt = $connect->prepare($cancelRegistrationsQuery);
        $stmt->bind_param("i", $classID);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Class canceled successfully for all registered users']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel class: ' . $stmt->error]);
    }

    $stmt->close();
}

$connect->close();

