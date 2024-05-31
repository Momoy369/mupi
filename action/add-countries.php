<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['country_name']);

$insert = "INSERT INTO tbl_countries (country_name) VALUES ('$nama')";

if (mysqli_query($conn, $insert)) {
    // Jika penyimpanan berhasil, arahkan kembali ke halaman genres.php
    header("Location: $baseurl/mupi/countries.php");
    exit();
} else {
    // Jika gagal menyimpan data, tampilkan pesan kesalahan
    echo "Error: " . mysqli_error($conn);
}
