<?php
session_start();
session_destroy();
$response = array('status' => 'success');

echo json_encode($response);
?>