<?php
header('Content-Type: application/json');
include __DIR__ . '/db.php'; // Database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit();
}

// Extract form data
$firstName = $input['firstName'] ?? null;
$lastName = $input['lastName'] ?? null;
$email = $input['email'] ?? null;
$phoneNumber = $input['phoneNumber'] ?? null;
$password = $input['password'] ?? null;

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

// Check if the email is already in use
$stmt = $connect->prepare("SELECT * FROM People WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email is already registered']);
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Set default values for other fields not present in registration.html
$role = 'Member'; // Default role
$permissionID = 4; // Default permission to 'Member'
$over18 = true; // Set default as true unless you want to check for specific conditions
$isParent = false; // You can manually adjust this if necessary
$isChild = false; // Default isChild to false

// Insert user into the People table
$stmt = $connect->prepare("INSERT INTO People (FirstName, LastName, Email, PhoneNumber, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssissssi", $firstName, $lastName, $email, $phoneNumber, $over18, $isParent, $isChild, $hashedPassword, $role, $permissionID);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error during registration']);
    exit();
}

// Return a success response
echo json_encode(['status' => 'success', 'message' => 'Registration successful']);

// Close the database connection
$connect->close();

