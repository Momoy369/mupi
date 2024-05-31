<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['nama_genre']);

$insert = "INSERT INTO tbl_genre (nama_genre) VALUES ('$nama')";

if (mysqli_query($conn, $insert)) {
    // Jika penyimpanan berhasil, arahkan kembali ke halaman genres.php
    header("Location: $baseurl/mupi/genres.php");
    exit();
} else {
    // Jika gagal menyimpan data, tampilkan pesan kesalahan
    echo "Error: " . mysqli_error($conn);
}
