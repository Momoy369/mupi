<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['country_name']);

$insert = "INSERT INTO tbl_countries (country_name) VALUES ('$nama')";

if (mysqli_query($conn, $insert)) {
    // If saving is successful, navigate back to the genres.php page
    header("Location: ../countries");
    exit();
} else {
    // If it fails to save data, displays an error message
    echo "Error: " . mysqli_error($conn);
}
