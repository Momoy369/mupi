<?php

include '../include/koneksi.php';

// Function to delete movie files and related data
function deleteMovieFile($idMovieFile)
{
    global $conn;

    // Retrieve the file information associated with the movie file to be deleted
    $query_file = "SELECT url_embed, id_movie FROM tbl_movies_file WHERE id = ?";
    $stmt_file = $conn->prepare($query_file);
    $stmt_file->bind_param("i", $idMovieFile);
    $stmt_file->execute();
    $stmt_file->bind_result($file_name, $id_movie);
    $stmt_file->fetch();
    $stmt_file->close();

    // Delete files from local storage if hosting is 'Own Server'
    if ($file_name && strpos($file_name, 'movie_') === 0) {
        $filePath = "../files/movies-files/" . $file_name;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete files from local storage
        }
    }

    // Delete movie file data from database
    $deleteQuery = "DELETE FROM tbl_movies_file WHERE id = ?";
    $stmt_delete = $conn->prepare($deleteQuery);
    $stmt_delete->bind_param("i", $idMovieFile);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Return id_movie to return to the movie file list page
    return $id_movie;
}

// Check if there is a movie file ID received via the GET parameter
if (isset($_GET['id'])) {
    $idMovieFile = $_GET['id'];

    // Call the function to delete the movie file
    $idMovie = deleteMovieFile($idMovieFile);
    if ($idMovie !== false) {
        // Redirect back to the appropriate page
        header("Location: ../movie-files?movies=" . $idMovie);
        exit();
    } else {
        echo "Error: Failed to delete movie file.";
    }
} else {
    // If no movie file ID is received, redirect to the appropriate page
    header("Location: ../movie-files?movies=" . $idMovie);
    exit();
}
