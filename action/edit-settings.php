<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset($_POST['submit'])) {
    $appName = $_POST['app_name'];
    $apiKey = $_POST['api_key_tmdb'];

    $query = "UPDATE tbl_setting SET app_name = '" . $appName . "', api_key_tmdb = '" . $apiKey . "' WHERE id = 1";
    mysqli_query($conn, $query);

    if ($query) {
        header("Location: $baseurl/settings");
        exit();
    } else {
        header("Location: $baseurl/settings");
        exit();
    }
}