<?php
// auth.php - basic session-based access control
session_start();

// Redirect to login if user is not authenticated
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Provide current user information to pages
$currentUser = [
    'id'       => $_SESSION['user_id'],
    'username' => $_SESSION['username'] ?? ''
];
