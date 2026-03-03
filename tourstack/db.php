<?php
// ============================================================
// db.php — DATABASE CONNECTION FILE
//
// This file is included by every other PHP file that needs
// to talk to the MySQL database.
//
// HOW TO CONFIGURE:
//   1. Open this file
//   2. Change DB_HOST, DB_USER, DB_PASS, DB_NAME to match
//      your server settings (XAMPP default is shown below)
//   3. Save — all other PHP files will use this connection
// ============================================================

// --- YOUR DATABASE SETTINGS ---
define('DB_HOST', 'localhost');    // usually 'localhost' for XAMPP/WAMP
define('DB_USER', 'root');         // your MySQL username (XAMPP default: root)
define('DB_PASS', '');             // your MySQL password (XAMPP default: empty)
define('DB_NAME', 'tourstack_db'); // the database name we created in database.sql

// --- CREATE THE CONNECTION ---
// mysqli is PHP's built-in MySQL library
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// --- CHECK IF CONNECTION FAILED ---
// If credentials are wrong or MySQL is not running, this will catch it
if ($conn->connect_error) {
    // Send back a JSON error response (so JavaScript can display it)
    header('Content-Type: application/json');
    http_response_code(500); // HTTP 500 = server error
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit; // stop running the rest of the script
}

// Set character encoding to UTF-8 (supports all languages & emojis)
$conn->set_charset('utf8mb4');

// $conn is now available to any file that does: include 'db.php'
?>
