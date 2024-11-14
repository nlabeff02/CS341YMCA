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
    // Retrieve prerequisite class name for the selected class
    $prerequisiteQuery = "SELECT PrerequisiteClassName FROM Classes WHERE ClassID = ? LIMIT 1";
    $stmt = $connect->prepare($prerequisiteQuery);
    $stmt->bind_param("i", $classID);
    $stmt->execute();
    $result = $stmt->get_result();
    $classInfo = $result->fetch_assoc();
    $stmt->close();

    if ($classInfo && !empty($classInfo['PrerequisiteClassName'])) {
        $prerequisiteClassName = $classInfo['PrerequisiteClassName'];

        // Check if the user has completed a class that matches the prerequisite class name
        $prerequisiteCheckQuery = "
            SELECT 1 
            FROM Registrations r
            INNER JOIN Classes c ON r.ClassID = c.ClassID
            WHERE r.PersonID = ? 
              AND c.ClassName = ? 
              AND r.PaymentStatus = 'Paid'
            LIMIT 1";
        
        $stmt = $connect->prepare($prerequisiteCheckQuery);
        $stmt->bind_param("is", $personID, $prerequisiteClassName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Prerequisite not met: You must complete "' . $prerequisiteClassName . '" before registering for this class.']);
            $stmt->close();
            exit();
        }
        $stmt->close();
    }

    // Check if the user is already registered for this class
    $checkQuery = "SELECT 1 FROM Registrations WHERE personID = ? AND classID = ? LIMIT 1";
    $stmt = $connect->prepare($checkQuery);
    $stmt->bind_param("ii", $personID, $classID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You are already registered for this class.']);
        $stmt->close();
        exit();
    }
    $stmt->close();
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
    $paymentStatus = "Due";

    // Insert new registration
    $stmt = $connect->prepare("INSERT INTO Registrations (personID, classID, registrationDate, paymentAmount, paymentStatus) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $personID, $classID, $registrationDate, $paymentAmount, $paymentStatus);

    if ($stmt->execute()) {
        // Increment the CurrentParticipantCount in the Classes table
        $stmt->close();
        $stmt = $connect->prepare("UPDATE Classes SET CurrentParticipantCount = CurrentParticipantCount + 1 WHERE classID = ?");
        $stmt->bind_param("i", $classID);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Class registered successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update participant count: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to register for class: ' . $stmt->error]);
    }

    $stmt->close();
}

$connect->close();