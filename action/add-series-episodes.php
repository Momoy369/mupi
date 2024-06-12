<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

// Check whether the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Retrieve data from the form
    session_start();
    $series_id = $_SESSION['series_id_ses'];
    $title = addslashes($_POST['title']);
    $external_url = $_POST['external'];
    $short_desc = addslashes($_POST['short_desc']);
    $file_name = null;

    // Get the series title from tbl_series
    $query_series = "SELECT judul FROM tbl_series WHERE id_series = ?";
    $stmt_series = mysqli_prepare($conn, $query_series);
    mysqli_stmt_bind_param($stmt_series, 'i', $series_id);
    mysqli_stmt_execute($stmt_series);
    mysqli_stmt_bind_result($stmt_series, $series_title);
    mysqli_stmt_fetch($stmt_series);
    mysqli_stmt_close($stmt_series);

    // Check if the file is uploaded
    if (isset($_FILES['files']) && $_FILES['files']['error'] == 0) {
        $target_dir = "../files/series-files/";
        $original_file_name = basename($_FILES["files"]["name"]);
        $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
        $file_name = "series-" . preg_replace("/[^a-zA-Z0-9]+/", "-", strtolower($series_title)) . "-" . preg_replace("/[^a-zA-Z0-9]+/", "-", strtolower($title)) . "." . $file_extension;
        $target_file = $target_dir . $file_name;

        // Move the uploaded files to the target directory
        if (!move_uploaded_file($_FILES["files"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Create a query to enter data into the database
    $query = "INSERT INTO tbl_series_episodes (series_id, title, external_url, files, short_desc) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to query
    mysqli_stmt_bind_param($stmt, 'issss', $series_id, $title, $external_url, $file_name, $short_desc);

    // Query execution
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to the success page or another desired page
        header("Location: ../series-episodes?episodes=" . $series_id);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}