<?php

include '../include/koneksi.php';

// Check if there is an ID received via the GET parameter
if (isset($_GET['id_series'])) {
    $idSeries = $_GET['id_series'];

    // Query to retrieve the URL of the series poster image to be deleted
    $getPosterQuery = "SELECT poster FROM tbl_series WHERE id_series = ?";
    $stmt = $conn->prepare($getPosterQuery);
    $stmt->bind_param("i", $idSeries);
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

    // Query to delete a series from the database
    $deleteSeriesQuery = "DELETE FROM tbl_series WHERE id_series = ?";
    $stmt = $conn->prepare($deleteSeriesQuery);
    $stmt->bind_param("i", $idSeries);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect back to the series.php page
    header("Location: ../series");
    exit();
} else {
    // If no ID is received, redirect to the series.php page
    header("Location: ../series");
    exit();
}