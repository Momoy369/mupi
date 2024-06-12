<?php

include '../include/koneksi.php';

$nama = addslashes($_POST['nama_aktor']);
$photo_tmp = $_FILES['photo']['tmp_name'];
$photo = $_FILES['photo']['name'];
$strphoto = str_replace(" ", "", $photo);
$strphoto_temp = str_replace(" ", "", $photo_tmp);

$md5photo = time() . "_" . $strphoto;

if (move_uploaded_file($strphoto_temp, "../images/actors/" . $md5photo)) {
    $insert = "INSERT INTO tbl_aktor (nama_aktor, foto_aktor) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // If saving is successful, navigate back to the movies.php page
        header("Location: $baseurl/mupi/actors");
        exit();
    } else {
        // If it fails to save data, displays an error message
        echo "Error: " . mysqli_error($conn);
    }

} else {
    // If no form has been submitted, return to the movies.php page
    // header("Location: $baseurl/mupi/actors");
    // exit();

    $insert = "INSERT INTO tbl_aktor (nama_aktor, foto_aktor) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // If the saving is successful, redirect to the movies.php page
        header("Location: ../actors");
        exit();
    } else {
        // If it fails to save data, displays an error message
        echo "Error: " . mysqli_error($conn);
    }
}