<?php
// db.php
// INTERNAL: PDO connection. Use this in other PHP files with `require 'db.php';`

$DB_HOST = 'localhost';
$DB_NAME = 'dbmvurzslbtuph';
$DB_USER = 'ud89fw4spumtd';
$DB_PASS = 'dpnpg9ge2uey';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (Exception $e) {
    // Minimal error output (in production you might log instead)
    http_response_code(500);
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
