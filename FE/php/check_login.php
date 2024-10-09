<?php
session_start();
$response = array('isLoggedIn' => false);

if (isset($_SESSION['user'])) {
    $response['isLoggedIn'] = true;
}

echo json_encode($response);
?>
