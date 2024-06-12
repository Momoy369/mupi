<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['nama_kualitas']);

$query = "INSERT INTO tbl_kualitas (nama_kualitas) VALUES ('$nama')";

if (isset($_POST['submit'])) {
    mysqli_query($conn, $query);
    header("Location: ../qualities");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}