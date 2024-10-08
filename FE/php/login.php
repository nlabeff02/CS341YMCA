<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include __DIR__ . '/db.php';

// Check database connection
if ($connect->connect_error) {
    error_log("Database connection failed: " . $connect->connect_error);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
        exit();
    }

    $email = $input['username'] ?? null; // Assuming 'username' is the email in the form
    $password = $input['password'] ?? null;

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit();
    }

    // Log the email address for debugging (check in PHP error logs)
    error_log("Email input: " . $email);

    // Use prepared statements to check only the 'Email' column
    $stmt = $connect->prepare("SELECT * FROM People WHERE Email = ? LIMIT 1");

    if ($stmt === false) {
        error_log("Prepare failed: " . $connect->error);
        echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
        exit();
    }

    // Bind the email parameter (s = string type)
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hash = $row['PasswordHash'];

        // Verify password
        if (password_verify($password, $hash)) {
            echo json_encode([
                'status' => 'success',
                'personID' => $row['PersonID'],
                'role' => $row['Role'],
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    $stmt->close();
}

$connect->close();
