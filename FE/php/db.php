<?php
// Fetch database credentials from environment variables
$servername = "localhost";
$username = "root";
$password = "";
$db = "ymca";

// Create connection
$connect = new mysqli($servername, $username, $password, $db);

// Check connection
if ($connect->connect_error) {
    error_log("Connection failed: " . $connect->connect_error);
    die("There was a problem connecting to the database. Please try again later.");
}

// Close connection (optional to include at the end of script where relevant)
// $connect->close();
