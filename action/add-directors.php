<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['nama_director']);
$photo_tmp = $_FILES['photo']['tmp_name'];
$photo = $_FILES['photo']['name'];
$strphoto = str_replace(" ", "", $photo);
$strphoto_temp = str_replace(" ", "", $photo_tmp);

$md5photo = time() . "_" . $strphoto;

if (move_uploaded_file($strphoto_temp, "../images/directors/" . $md5photo)) {
    $insert = "INSERT INTO tbl_director (nama_director, photo_director) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // Jika penyimpanan berhasil, arahkan kembali ke halaman movies.php
        header("Location: $baseurl/mupi/directors.php");
        exit();
    } else {
        // Jika gagal menyimpan data, tampilkan pesan kesalahan
        echo "Error: " . mysqli_error($conn);
    }

} else {
    // Jika tidak ada form yang disubmit, kembali ke halaman movies.php
    // header("Location: $baseurl/mupi/directors");
    // exit();

    $insert = "INSERT INTO tbl_director (nama_director, photo_director) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // Jika penyimpanan berhasil, arahkan kembali ke halaman movies.php
        header("Location: $baseurl/mupi/directors.php");
        exit();
    } else {
        // Jika gagal menyimpan data, tampilkan pesan kesalahan
        echo "Error: " . mysqli_error($conn);
    }
}