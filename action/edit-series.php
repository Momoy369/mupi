<?php

include '../include/koneksi.php';

if (isset($_POST['submit'])) {

    $id = $_POST['id_series']; // Make sure ID is available

    $judul = addslashes($_POST['judul']);
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Take the genres of the form and combine them into one string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $duration = $_POST['duration'];

    $episodes = $_POST['episodes'];
    $seasons = $_POST['seasons'];
    $series_status = $_POST['series_status'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);
    $permalink = $_POST['titleHidden'];

    $duration = $_POST['duration'];

    // Create a query to update movie data in the database
    $update = "UPDATE tbl_series SET judul = ?, jenis = ?, genre = ?, episodes = ?, seasons = ?, series_status = ?, tahun_rilis = ?, rating = ?, country = ?, overview = ?, duration = ?, trailer_url = ?, status = ?, permalink = ? WHERE id_series = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssssssssssssi", $judul, $jenis, $separatedValueGenre, $episodes, $seasons, $series_status, $tahun, $rating, $separatedValueCountry, $overview, $duration, $trailerUrl, $status, $permalink, $id);

    // Query execution
    if ($stmt->execute()) {
        // If the update is successful, redirect to the movies.php page
        header("Location: ../series");
        exit();
    } else {
        // If data update fails, display an error message
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
    // Close the database connection
    $conn->close();
} else {
    // If no form has been submitted, return to the movies.php page
    header("Location: ../series");
    exit();
}
