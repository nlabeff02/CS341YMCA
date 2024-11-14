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
    $personRole = $input['personRole'] ?? null;

    // Validate input
    if (!$classID || !$personID || !$personRole) {
        echo json_encode(['status' => 'error', 'message' => 'Class ID, Person ID, and Person Role are required']);
        exit();
    }
    /*
    // Get user's role from the session
    $userRole = $_SESSION['user']['role'] ?? null;
    if (!$userRole) {
        echo json_encode(['status' => 'error', 'message' => 'User role not found']);
        exit();
    }
    */
    // Determine the column name based on the user's role
    $paymentColumn = '';
    switch ($personRole) {
        case 'Staff':
            $paymentColumn = 'PriceStaff';
            break;
        case 'Member':
            $paymentColumn = 'PriceMember';
            break;
        case 'NonMember':
            $paymentColumn = 'PriceNonMember';
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid user role']);
            exit();
    } 

    // Retrieve the payment amount from the determined column
    $sql = "SELECT $paymentColumn AS PaymentAmount FROM Classes WHERE classID = ? LIMIT 1";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $classID);
    $stmt->execute();
    $result = $stmt->get_result();
    $classInfo = $result->fetch_assoc();
    $stmt->close();

    // Check if class was found
    if (!$classInfo) {
        echo json_encode(['status' => 'error', 'message' => 'Class not found']);
        exit();
    }

    // Prepare registration data
    $registrationDate = date('Y-m-d'); // Today's date
    $paymentAmount = $classInfo['PaymentAmount'];
    $PaymentStatus = "Due";

    // Insert new registration
    $stmt = $connect->prepare("INSERT INTO Registrations (personID, classID, registrationDate, paymentAmount, paymentStatus) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $personID, $classID, $registrationDate, $paymentAmount, $paymentStatus);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Class registered successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to register for class: ' . $stmt->error]);
    }

    $stmt->close();
}

$connect->close();