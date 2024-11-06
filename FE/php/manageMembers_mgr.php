<?php
header('Content-Type: application/json');
include 'db.php'; // Include your database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if action is set in POST request
if (!isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'No action specified']);
    exit();
}

$action = $_POST['action'];

switch ($action) {
    case 'search':
        searchMembers($connect);
        break;
    case 'viewAll':
        viewAllMembers($connect);
        break;
    case 'save':
        saveMember($connect);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);
        break;
}

// Function to search members based on a specific criterion
function searchMembers($connect) {
    $searchType = $_POST['searchType'];
    $searchText = $_POST['searchText'];

    // Build the query dynamically
    if ($searchType === 'phone') {
        // Remove non-numeric characters for phone number comparison
        $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role 
                  FROM People 
                  WHERE REPLACE(REPLACE(REPLACE(PhoneNumber, '-', ''), '(', ''), ')', '') LIKE ?";
        $searchText = "%" . preg_replace('/\D/', '', $searchText) . "%"; // Remove non-numeric characters from input
    } else {
        $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role 
                  FROM People 
                  WHERE $searchType LIKE ?";
        $searchText = "%" . $searchText . "%";
    }

    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $searchText);
    $stmt->execute();
    $result = $stmt->get_result();

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode(['status' => 'success', 'members' => $members]);

    $stmt->close();
}

// Function to retrieve all members
function viewAllMembers($connect) {
    $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role FROM People";
    $result = $connect->query($query);

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode(['status' => 'success', 'members' => $members]);
}

// Function to save updated member information
function saveMember($connect) {
    $memberId = $_POST['memberId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $query = "UPDATE People SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Role = ? WHERE PersonID = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('sssssi', $firstName, $lastName, $email, $phone, $role, $memberId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Member updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update member']);
    }

    $stmt->close();
}

$connect->close();
