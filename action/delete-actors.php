<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

// Check if there is an ID received via the GET parameter
if (isset($_GET['id_aktor'])) {
    $idAktor = $_GET['id_aktor'];

    // Query to retrieve the name of the actor image file to be deleted
    $getFotoQuery = "SELECT foto_aktor FROM tbl_aktor WHERE id_aktor = ?";
    $stmt = $conn->prepare($getFotoQuery);
    $stmt->bind_param("i", $idAktor);
    $stmt->execute();
    $result = $stmt->get_result();

    // If any results are found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fotoFilename = $row['foto_aktor'];

        // Delete the actor image file from local storage if there is one
        if (!empty($fotoFilename)) {
            $fotoFilePath = "$baseurl/images/actors/" . $fotoFilename;
            if (file_exists($fotoFilePath)) {
                unlink($fotoFilePath); // Delete actor image files
            }
        }
    }

    // Query to delete actors from the database
    $deleteAktorQuery = "DELETE FROM tbl_aktor WHERE id_aktor = ?";
    $stmt = $conn->prepare($deleteAktorQuery);
    $stmt->bind_param("i", $idAktor);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect back to actors.php page
    header("Location: $baseurl/actors");
    exit();
} else {
    // If no ID is received, redirect to the actors.php page
    header("Location: $baseurl/actors");
    exit();
}