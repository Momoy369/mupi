<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

$nama = addslashes($_POST['nama_director']);
$photo_tmp = $_FILES['photo']['tmp_name'];
$photo = $_FILES['photo']['name'];
$strphoto = str_replace(" ", "", $photo);
$strphoto_temp = str_replace(" ", "", $photo_tmp);

$md5photo = time() . "_" . $strphoto;

if (move_uploaded_file($strphoto_temp, "$baseurl/images/directors/" . $md5photo)) {
    $insert = "INSERT INTO tbl_director (nama_director, photo_director) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // If saving is successful, navigate back to the movies.php page
        header("Location: $baseurl/directors");
        exit();
    } else {
        // If it fails to save data, displays an error message
        echo "Error: " . mysqli_error($conn);
    }

} else {
    // If no form has been submitted, return to the movies.php page
    // header("Location: $baseurl/mupi/directors");
    // exit();

    $insert = "INSERT INTO tbl_director (nama_director, photo_director) VALUES ('$nama', '$md5photo')";

    if (mysqli_query($conn, $insert)) {
        // If saving is successful, navigate back to the movies.php page
        header("Location: $baseurl/directors");
        exit();
    } else {
        // If it fails to save data, displays an error message
        echo "Error: " . mysqli_error($conn);
    }
}