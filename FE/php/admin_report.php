<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log('Received POST data: ' . json_encode($_POST));

function getAdminReport($connect) {
    // Read raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Debugging the incoming JSON payload
    error_log('Received JSON payload: ' . json_encode($data));

    // Extract data from the request
    $startDate = $data['startDate'] ?? null;
    $endDate = $data['endDate'] ?? null;
    $includeAllClasses = $data['includeAllClasses'] ?? false; // Checkbox flag

    // Validate the dates
    if (!$startDate || !$endDate) {
        echo json_encode(['status' => 'error', 'message' => 'Both start date and end date are required.']);
        return;
    }

    if ($startDate > $endDate) {
        echo json_encode(['status' => 'error', 'message' => 'Start date cannot be later than end date.']);
        return;
    }

    // Determine the query based on the checkbox
    $query = "";
    switch ($includeAllClasses) {
        case true:
            // Include any class that overlaps the date range
            $query = "
                SELECT 
                    r.RegistrationID,
                    p.PersonID AS userID,
                    p.FirstName AS firstName,
                    p.LastName AS lastName,
                    p.Email AS email,
                    c.ClassID AS classID,
                    c.ClassName AS className,
                    c.StartDate,
                    c.EndDate,
                    r.PaymentStatus
                FROM Registrations r
                JOIN People p ON r.PersonID = p.PersonID
                JOIN Classes c ON r.ClassID = c.ClassID
                WHERE c.StartDate <= ? AND c.EndDate >= ?
                ORDER BY c.StartDate, p.LastName, p.FirstName;
            ";
            break;

        default:
            // Default behavior: classes that are completely within the date range
            $query = "
                SELECT 
                    r.RegistrationID,
                    p.PersonID AS userID,
                    p.FirstName AS firstName,
                    p.LastName AS lastName,
                    p.Email AS email,
                    c.ClassID AS classID,
                    c.ClassName AS className,
                    c.StartDate,
                    c.EndDate,
                    r.PaymentStatus
                FROM Registrations r
                JOIN People p ON r.PersonID = p.PersonID
                JOIN Classes c ON r.ClassID = c.ClassID
                WHERE c.StartDate >= ? AND c.EndDate <= ?
                ORDER BY c.StartDate, p.LastName, p.FirstName;
            ";
            break;
    }

    // Prepare and execute the query
    $stmt = $connect->prepare($query);
    if ($includeAllClasses) {
        $stmt->bind_param('ss', $endDate, $startDate); // Bind dates in reverse for overlap condition
    } else {
        $stmt->bind_param('ss', $startDate, $endDate); // Bind dates for within range condition
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize report data array
    $reportData = [];

    // Fetch data from the result
    while ($row = $result->fetch_assoc()) {
        // Format dates
        $startDateFormatted = date('Y-m-d', strtotime($row['StartDate']));
        $endDateFormatted = date('Y-m-d', strtotime($row['EndDate']));
        $paymentStatusFormatted = $row['PaymentStatus'] == 1 ? 'Paid' : 'Unpaid'; // Example logic

        // Add formatted data to the report array
        $reportData[] = [
            'firstName' => $row['firstName'],
            'lastName' => $row['lastName'],
            'email' => $row['email'],
            'className' => $row['className'],
            'startDate' => $startDateFormatted,
            'endDate' => $endDateFormatted,
            'paymentStatus' => $paymentStatusFormatted,
        ];
    }

    // Return the response
    if (count($reportData) > 0) {
        echo json_encode(['status' => 'success', 'data' => $reportData]); // Return the data if found
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found for the selected period.']); // No data found
    }

    // Close the prepared statement
    $stmt->close();
}

// Call the function
getAdminReport($connect);

?>
