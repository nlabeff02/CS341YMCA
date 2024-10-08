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
$fullName = $input['fullName'] ?? null;
$email = $input['email'] ?? null;
$phoneNumber = $input['phoneNumber'] ?? null;
$password = $input['password'] ?? null;
$isParent = $input['isParent'] ?? false;
$children = $input['children'] ?? [];

// Validate required fields
if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($password)) {
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

// Insert user (parent or adult) into the People table
$stmt = $connect->prepare("INSERT INTO People (FirstName, LastName, Email, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$fullNameParts = explode(" ", $fullName);
$firstName = $fullNameParts[0];
$lastName = isset($fullNameParts[1]) ? $fullNameParts[1] : ''; // Handles cases where there's no last name

// Set default role and permission
$role = 'Member';
$permissionID = 1; // Assuming 1 corresponds to 'Member' permissions
$over18 = true; // Default to true for adults
$isChild = false; // Parent or adult, so `IsChild` is false for the primary person

$stmt->bind_param("ssssisssi", $firstName, $lastName, $email, $over18, $isParent, $isChild, $hashedPassword, $role, $permissionID);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error during registration']);
    exit();
}

// Get the last inserted user ID (PersonID)
$parentID = $connect->insert_id;

// If the user is a parent, insert children as individual entries in the People table
if ($isParent && !empty($children)) {
    foreach ($children as $child) {
        $childName = $child['name'];
        $childAge = $child['age'];

        // For simplicity, split the child’s full name into first and last names
        $childNameParts = explode(" ", $childName);
        $childFirstName = $childNameParts[0];
        $childLastName = isset($childNameParts[1]) ? $childNameParts[1] : '';

        $stmt = $connect->prepare("INSERT INTO People (FirstName, LastName, Over18, IsParent, IsChild, PasswordHash, Role, PermissionID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $over18 = false; // Children are not over 18
        $isChild = true; // Mark as child
        $hashedPassword = ''; // No password for children
        $role = 'Member'; // Default role for children
        $permissionID = 1; // Default to Member permissions

        $stmt->bind_param("ssssissi", $childFirstName, $childLastName, $over18, $isParent, $isChild, $hashedPassword, $role, $permissionID);
        $stmt->execute();
    }
}

// Return a success response
echo json_encode(['status' => 'success', 'message' => 'Registration successful']);

// Close the database connection
$connect->close();
?>
