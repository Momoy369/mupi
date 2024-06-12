<?php
session_start();
include ("../include/koneksi.php");

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if (empty($email) || empty($password)) {
    $_SESSION['msg'] = "Email and password must be filled in.";
    header("Location: ../index");
    exit;
}

// Hash passwords with SHA256
$hashed_password = hash('sha256', $password);

$qry = "SELECT * FROM tbl_admin WHERE email='$email'";
$result = mysqli_query($conn, $qry);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Checks whether passwords match after hashing
    if ($row['password'] === $hashed_password) {
        $_SESSION['id'] = $row['id_admin'];
        $_SESSION['admin_name'] = $row['email'];
        header("Location: ../home");
        exit;
    } else {
        $_SESSION['msg'] = "Incorrect email or password.";
        header("Location: ../index");
        exit;
    }
} else {
    $_SESSION['msg'] = "Incorrect email or password.";
    header("Location: ../index");
    exit;
}