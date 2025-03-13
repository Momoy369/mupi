<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset($_POST['submit'])) {

    // Retrieve id_movies from the form
    $id = $_POST['id_movies'];

    $judul = addslashes($_POST['judul']);
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Take the genres of the form and combine them into one string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $kualitas = $_POST['kualitas'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);

    $permalink = $_POST['titleHidden'];

    $duration = $_POST['duration'];

    // Create a query to update the movie data in the database (without changing the image and production)
    $update = "UPDATE tbl_movies SET judul = ?, jenis = ?, genre = ?, kualitas = ?, tahun_rilis = ?, rating = ?, country = ?, overview = ?, duration = ?, trailer_url = ?, status = ?, permalink = ? WHERE id_movies = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssssssssssi", $judul, $jenis, $separatedValueGenre, $kualitas, $tahun, $rating, $separatedValueCountry, $overview, $duration, $trailerUrl, $status, $permalink, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        // If the update is successful, redirect to the movies.php page
        header("Location: $baseurl/movies");
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
    header("Location: $baseurl/movies");
    exit();
}
