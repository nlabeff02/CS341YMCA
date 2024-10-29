<?PHP
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

// Make sure user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$userID = $_SESSION['user']['id'];
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Future Classes
$futureClassesQuery = "
    SELECT c.ClassName, c.DayOfWeek, c.StartTime, c.EndTime, r.RegistrationDate
    FROM Classes c
    JOIN Registrations r ON c.ClassID = r.ClassID
    WHERE r.PersonID = ? AND (c.StartDate > ? OR (c.StartDate = ? AND c.StartTime >= ?))";

