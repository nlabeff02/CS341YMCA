<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

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
        searchManageClasses($connect);
        break;
    case 'viewAll':
        getAllClasses($connect);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);
        break;
}

// Function to search for classes
function searchManageClasses($connect) {
    $searchType = $_POST['searchType'];
    $searchText = '%' . $_POST['searchText'] . '%';

    // Validate the search type and build the query dynamically
    $validSearchTypes = ['className', 'startDate', 'endDate', 'classLocation'];
    if (!in_array($searchType, $validSearchTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid search type']);
        return;
    }

    // Map search type to corresponding database column
    $columns = [
        'className' => 'ClassName',
        'startDate' => 'StartDate',
        'endDate' => 'EndDate',
        'classLocation' => 'ClassLocation'
    ];
    $column = $columns[$searchType];

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
            c.PrerequisiteClassName,
            c.IsActive
        FROM Classes c
        WHERE $column LIKE ?
    ";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $searchText);
    $stmt->execute();
    $result = $stmt->get_result();

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = formatClassData($row);
    }

    echo json_encode(['status' => 'success', 'classes' => $classes]);
    $stmt->close();
}

// Function to fetch all classes
function getAllClasses($connect) {
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
            c.PrerequisiteClassName,
            c.IsActive
        FROM Classes c
        ORDER BY c.StartDate DESC
    ";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = formatClassData($row);
    }

    echo json_encode(['status' => 'success', 'classes' => $classes]);
    $stmt->close();
}


// Helper function to format class data
function formatClassData($row) {
    return [
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
        'currentParticipantCount' => $row['CurrentParticipantCount'] ?? '0',
        'priceStaff' => $row['PriceStaff'],
        'priceMember' => $row['PriceMember'],
        'priceNonMember' => $row['PriceNonMember'],
        'prerequisiteClassName' => $row['PrerequisiteClassName'] ?? 'None',
        'isActive' => $row['IsActive']
    ];
}

// Close the database connection
$connect->close();
?>
