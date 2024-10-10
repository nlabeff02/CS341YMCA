<?php
session_start();
session_destroy();

// Redirect to the parent folder's index.html
header("Location: ../index.html");
exit();
?>