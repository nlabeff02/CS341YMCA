<?php
// login script
$servername = "localhost";
$username = "ymca_user";
$password = "123qwe";
$db = "ymcadb";

// Create connection.
$connect = new mysqli($servername, $username, $password, $db);

// Check connection.
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
?>