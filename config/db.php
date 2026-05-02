<?php
// Prevent direct access
if (!defined('DB_INCLUDED')) {
    define('DB_INCLUDED', true);
}

// Database Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'rafael_mortillo2008****';
$db_name = 'school_db';

// Create Connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check Connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Optional: Uncomment to verify connection (for debugging)
// echo "✅ Database connected successfully!<br>";
?>