<?php
header('Content-Type: application/json');
include __DIR__ . '/db.php';

// Check if the connection to the database was successful
if ($connect->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Prevent SQL Injection for username
    $username = $connect->real_escape_string($username);

    // Check if user exists
    $sql = "SELECT * FROM People WHERE Email = '$username' OR Username = '$username' LIMIT 1";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hash = $row['PasswordHash'];

        // Verify password
        if (password_verify($password, $hash)) {
            // Success: Return user information in JSON format
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
}

$connect->close();
?>