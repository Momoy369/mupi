<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['nama_genre']);

$insert = "INSERT INTO tbl_genre (nama_genre) VALUES ('$nama')";

if (mysqli_query($conn, $insert)) {
    // If saving is successful, navigate back to the genres.php page
    header("Location: ../genres");
    exit();
} else {
    // If it fails to save data, displays an error message
    echo "Error: " . mysqli_error($conn);
}
