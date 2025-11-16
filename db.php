<?php
// db.php
// Default XAMPP MySQL settings: host 127.0.0.1, port 3306, user root, empty password.
$host = 'localhost';
$db   = 'crud_app';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// If your MySQL runs on a non-default port, add ;port=PORT_NUMBER here.
$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // During development, show the full error message to diagnose issues.
    // In production, log the error and show a generic message instead.
    die('Database connection failed: ' . $e->getMessage());
}
