<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🎯 Auto-Detect environment (Local or Live)
$is_localhost = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) || $_SERVER['SERVER_NAME'] == 'localhost';

if ($is_localhost) {
    // ---- LOCALHOST SETTINGS ----
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbName = 'bestok';
    $site = "http://localhost/projects/bestok/";
} else {
    // ---- LIVE SERVER SETTINGS ----
    // Live par errors display nahi hone chahiye (Security aur layout bachane ke liye)
    error_reporting(0);
    ini_set('display_errors', 0);
    
    $host = 'localhost';
    $username = 'u799879276_bestok';
    $password = '?/2re@jw5Kf';
    $dbName = 'u799879276_bestok';
    $site = 'https://www.bestok.in/';
}

// Make `$site` global
global $site;

// Create Database Connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set Character Encoding to UTF-8
$conn->set_charset("utf8");
?>