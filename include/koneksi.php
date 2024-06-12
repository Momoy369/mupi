<?php

define('ROOT_PATH', dirname(__DIR__) . '/');

error_reporting(0);
session_start();
date_default_timezone_set("Asia/Jakarta");

// Database configuration
$host = "localhost"; // Hostname
$username = "root"; // Database user name
$password = ""; // Database password
$database = "mupi"; // Database name

// Making connections
$conn = new mysqli($host, $username, $password, $database);

// Checking connections
// if ($conn->connect_error) {
//     die("Connection fail: " . $conn->connect_error);
// } else {
//     echo "Connection successful!";
// }