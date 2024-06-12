<?php

include '../include/koneksi.php';

// Check if there is an ID received via the GET parameter
if (isset($_GET['id_movies'])) {
    $idMovie = $_GET['id_movies'];

    // Query to retrieve the URL of the movie poster image to be deleted
    $getPosterQuery = "SELECT poster FROM tbl_movies WHERE id_movies = ?";
    $stmt = $conn->prepare($getPosterQuery);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();
    $result = $stmt->get_result();

    // If any results are found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $posterFilename = $row['poster'];

        // Delete the poster image file from local storage if there is one
        if (!empty($posterFilename)) {
            $posterFilePath = "../images/poster/" . $posterFilename;
            if (file_exists($posterFilePath)) {
                unlink($posterFilePath); // Delete the poster image file
            }
        }
    }

    // Query to delete a movie from the database
    $deleteMovieQuery = "DELETE FROM tbl_movies WHERE id_movies = ?";
    $stmt = $conn->prepare($deleteMovieQuery);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect back to the movies.php page
    header("Location: ../movies");
    exit();
} else {
    // If no ID is received, redirect to the movies.php page
    header("Location: ../movies");
    exit();
}