<?php
include '../include/koneksi.php';

$query = "SELECT api_key_tmdb FROM tbl_setting WHERE id = 1";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    $api_key_tmdb = $row['api_key_tmdb'];

    $response = array('api_key_tmdb' => $api_key_tmdb);

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);