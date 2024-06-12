<?php

include '../include/koneksi.php';

// Function to delete episodes of a series along with related files
function deleteSeriesEpisode($episode_id)
{
    global $conn;

    // Retrieve file information associated with the episode to be deleted
    $query_file = "SELECT files, series_id FROM tbl_series_episodes WHERE id = ?";
    $stmt_file = mysqli_prepare($conn, $query_file);
    mysqli_stmt_bind_param($stmt_file, 'i', $episode_id);
    mysqli_stmt_execute($stmt_file);
    mysqli_stmt_bind_result($stmt_file, $file_name, $series_id);
    mysqli_stmt_fetch($stmt_file);
    mysqli_stmt_close($stmt_file);

    // Create a query to delete episodes from the table
    $query = "DELETE FROM tbl_series_episodes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to query
    mysqli_stmt_bind_param($stmt, 'i', $episode_id);

    // Query execution
    if (mysqli_stmt_execute($stmt)) {
        // If deletion is successful, delete related files as well
        if ($file_name) {
            $file_path = "../files/series-files/" . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path); // Delete files from the directory
            }
        }
        // Return series_id to return to the episode list page
        return $series_id;
    } else {
        // If an error occurs, return false
        return false;
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Check if there is an episode ID received via the GET parameter
if (isset($_GET['id'])) {
    $episode_id = $_GET['id'];

    // Call the function to delete an episode from the series
    $series_id = deleteSeriesEpisode($episode_id);
    if ($series_id !== false) {
        // Redirect back to the appropriate page
        header("Location: ../series-episodes?episodes=" . $series_id);
        exit();
    } else {
        echo "Error: Failed to delete episode.";
    }
} else {
    // If no episode ID is received, redirect to the appropriate page
    header("Location: ../series-episodes?episodes=" . $series_id);
    exit();
}
