<?php
session_start();
function requireRole($roles = []) {
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles)) {
        header('Location: login.html'); // Redirect to login if not authorized
        exit();
    }
}
