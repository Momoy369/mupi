<?php

include '../include/koneksi.php';

// Check if there is a director ID received via the GET parameter
if (isset($_GET['id_director'])) {
    $idDirector = $_GET['id_director'];

    // Query to get director image file name based on ID
    $getDirectorPhotoQuery = "SELECT photo_director FROM tbl_director WHERE id_director = ?";
    $stmt = $conn->prepare($getDirectorPhotoQuery);
    $stmt->bind_param("i", $idDirector);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the director is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $directorPhoto = $row['photo_director'];

        // Delete image files from local storage
        $filePath = "../images/directors/" . $directorPhoto;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete image files from local storage
        }

        // Query to delete a director from the database
        $deleteDirectorQuery = "DELETE FROM tbl_director WHERE id_director = ?";
        $stmt = $conn->prepare($deleteDirectorQuery);
        $stmt->bind_param("i", $idDirector);
        $stmt->execute();

        // Close the prepared statement
        $stmt->close();
    }

    // Redirect back to directors.php page
    header("Location: ../directors");
    exit();
} else {
    // If no director ID is received, redirect to the directors.php page
    header("Location: ../directors");
    exit();
}