<?php
header('Content-Type: applicaiton/json');
include 'dp.php'; // API for connecting to database.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Prevent SQL Injection
    $username = $conn->real_escape_string($username);

    // Check if user exists
    $sql = "SELECT * FROM People WHERE Email = '$username' OR Username = '$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hash = $row['PasswordHash'];

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

$conn->close();
?>