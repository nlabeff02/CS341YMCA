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
    case 'getRegistrations':
        getRegistrations($connect);
        break;
    case 'updatePaymentStatus':
        updatePaymentStatus($connect);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);
        break;
}

function updatePaymentStatus($connect) {
    $registrationId = $_POST['registrationId'];
    $newStatus = $_POST['newStatus'];

    $query = "UPDATE Registrations SET PaymentStatus = ? WHERE RegistrationID = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('si', $newStatus, $registrationId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Payment status updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update payment status']);
    }

    $stmt->close();
}

// Function to fetch registrations for a specific member
function getRegistrations($connect) {
    $memberId = $_POST['memberId'];

    $query = "SELECT r.RegistrationID AS registrationId, c.ClassName AS className, 
                     c.StartDate AS startDate, c.EndDate AS endDate, r.PaymentStatus AS paymentStatus, r.isActive as active 
              FROM Registrations r
              JOIN Classes c ON r.ClassID = c.ClassID
              WHERE r.PersonID = ?";
              
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $memberId);
    $stmt->execute();
    $result = $stmt->get_result();

    $registrations = [];
    while ($row = $result->fetch_assoc()) {
        $registrations[] = $row;
    }

    echo json_encode(['status' => 'success', 'registrations' => $registrations]);
    $stmt->close();
}


// Function to search for Members and NonMembers based on a specific criterion
function searchMembers($connect) {
    $searchType = $_POST['searchType'];
    $searchText = $_POST['searchText'];

    // Build the query dynamically
    if ($searchType === 'phone') {
        // Remove non-numeric characters for phone number comparison
        $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role, isActive as active
                  FROM People 
                  WHERE Role IN ('Member', 'NonMember') AND REPLACE(REPLACE(REPLACE(PhoneNumber, '-', ''), '(', ''), ')', '') LIKE ?";
        $searchText = "%" . preg_replace('/\D/', '', $searchText) . "%"; // Remove non-numeric characters from input
    } else {
        $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role, isActive as active 
                  FROM People 
                  WHERE Role IN ('Member', 'NonMember') AND $searchType LIKE ?";
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

// Function to retrieve all Members and NonMembers
function viewAllMembers($connect) {
    $query = "SELECT PersonID AS memberId, FirstName AS firstName, LastName AS lastName, Email AS email, PhoneNumber AS phone, Role AS role, isActive as active 
              FROM People 
              WHERE Role IN ('Member', 'NonMember')";
    $result = $connect->query($query);

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode(['status' => 'success', 'members' => $members]);
}


// Function to save updated member information
function saveMember($connect) {
    // Input sanitization and retrieval
    $memberId = $_POST['memberId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $active = $_POST['active'];

    // Start a transaction for atomicity
    $connect->begin_transaction();

    try {
        // Update the People table
        $query = "UPDATE People SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Role = ?, isActive = ? WHERE PersonID = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('sssssii', $firstName, $lastName, $email, $phone, $role, $active, $memberId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update member information");
        }

        // Handle deactivation logic if the member is marked inactive
        if ($active == "0") {
            // Deactivate registrations
            $query1 = "UPDATE Registrations SET isActive = 0 WHERE PersonID = ?";
            $stmt1 = $connect->prepare($query1);
            $stmt1->bind_param('i', $memberId);

            if (!$stmt1->execute()) {
                throw new Exception("Failed to deactivate member registrations");
            }

            // Update class participant counts
            $query2 = "
                UPDATE Classes
                SET CurrentParticipantCount = CurrentParticipantCount - 1
                WHERE ClassID IN (
                    SELECT ClassID
                    FROM Registrations
                    WHERE PersonID = ? AND isActive = 0
                )";
            $stmt2 = $connect->prepare($query2);
            $stmt2->bind_param('i', $memberId);

            if (!$stmt2->execute()) {
                throw new Exception("Failed to update class participant counts");
            }

            $stmt1->close();
            $stmt2->close();
        }

        // Commit the transaction if all operations are successful
        $connect->commit();

        // Success response
        echo json_encode(['status' => 'success', 'message' => 'Member and related data updated successfully']);
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $connect->rollback();

        // Log the error and return an error response
        error_log($e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } finally {
        // Always close the main statement
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

/*
// Function to save updated member information
function saveMember($connect) {
    $memberId = $_POST['memberId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $active = $_POST['active'];

    $query = "UPDATE People SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Role = ?, isActive = ? WHERE PersonID = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('sssssii', $firstName, $lastName, $email, $phone, $role, $active, $memberId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Member updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update member']);
    }

    // inactive people automatically have all registrations cancelled in registrations table
    // and CurrentParticipantCount updated in classes table.
    if ($active == "0") {
        $query1 = "UPDATE registrations SET isActive = 0 WHERE PersonID = ?";
        $stmt1 = $connect->prepare($query1);
        $stmt1->bind_param('i', $memberId);
        // check for errors in stmt1 query
        if ($stmt1->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Member registrations removed successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove member registrations']);
        }
        // update count
        $query2 = "UPDATE classes SET CurrentParticipantCount = CurrentParticipantCount - 1 WHERE ClassID in (SELECT ClassID FROM registrations WHERE PersonID = ? AND isActive = 0 )";
        $stmt2 = $connect->prepare($query2);
        $stmt2->bind_param('i', $memberId);
        // check for errors in stmt1 query
        if ($stmt2->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Class participant counts updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update class participant counts']);
        }
    }
    
    $stmt->close();
    $stmt1->close();
    $stmt2->close();
}
*/
$connect->close();
