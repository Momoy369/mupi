<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

$nama = addslashes($_POST['nama_genre']);
$permalink = $_POST['permalinkHidden'];

$insert = "INSERT INTO tbl_genre (nama_genre, permalink) VALUES ('$nama', '$permalink')";

if (mysqli_query($conn, $insert)) {
    // If saving is successful, navigate back to the genres.php page
    header("Location: $baseurl/genres");
    exit();
} else {
    // If it fails to save data, displays an error message
    echo "Error: " . mysqli_error($conn);
}
