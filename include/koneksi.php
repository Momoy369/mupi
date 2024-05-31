<?php

define('ROOT_PATH', dirname(__DIR__) . '/');

error_reporting(0);
session_start();
date_default_timezone_set("Asia/Jakarta");

// Konfigurasi database
$host = "localhost"; // Hostname
$username = "root"; // Nama pengguna database
$password = ""; // Sandi database
$database = "mupimupi"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
// if ($conn->connect_error) {
//     die ("Koneksi gagal: " . $conn->connect_error);
// } else {
//     echo "Koneksi berhasil!";
// }