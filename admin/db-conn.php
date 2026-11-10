<?php
error_reporting(E_ALL);
// session_start();


// Database Configuration
$local = false; // Set to false for live server

if ($local) {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbName = 'u799879276_bestok';
    $site = "http://localhost/projects/bestok/";
} else {
    $host = 'localhost';
    $username = 'u799879276_bestok';
    $password = '?/2re@jw5Kf';
    $dbName = 'u799879276_bestok';
    $site = 'https://www.bestok.in/';
}
// Create Database Connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set Character Encoding to UTF-8
$conn->set_charset("utf8");

?>