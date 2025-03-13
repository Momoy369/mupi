<?php

include '../include/koneksi.php';

// Function to delete subtitles from a series and related files
function deleteSeriesSubtitle($subtitle_id)
{
    global $conn;

    // Retrieve file information related to the subtitles to be deleted
    $query_file = "SELECT url_sub, series_id, episodes_id FROM tbl_series_subtitles WHERE id = ?";
    $stmt_file = mysqli_prepare($conn, $query_file);
    mysqli_stmt_bind_param($stmt_file, 'i', $subtitle_id);
    mysqli_stmt_execute($stmt_file);
    mysqli_stmt_bind_result($stmt_file, $file_name, $series_id, $episodes_id);
    mysqli_stmt_fetch($stmt_file);
    mysqli_stmt_close($stmt_file);

    // Create a query to remove subtitles from the table
    $query = "DELETE FROM tbl_series_subtitles WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to query
    mysqli_stmt_bind_param($stmt, 'i', $subtitle_id);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // If deletion is successful, delete related files as well
        if ($file_name) {
            $file_path = "../files/subtitles/series/" . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path); // Delete files from the directory
            }
        }
        // Return series_id and episodes_id to return to the appropriate page
        return array($series_id, $episodes_id);
    } else {
        // If an error occurs, return false
        return false;
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Check if there is a subtitle ID received via the GET parameter
if (isset($_GET['id'])) {
    $subtitle_id = $_GET['id'];

    // Call the function to remove subtitles from the series
    list($series_id, $episodes_id) = deleteSeriesSubtitle($subtitle_id);
    if ($series_id !== false && $episodes_id !== false) {
        // Redirect back to the appropriate page
        header("Location: ../series-episode-subtitles?series=" . $series_id . "&episodes=" . $episodes_id);
        exit();
    } else {
        echo "Error: Failed to delete subtitle.";
    }
} else {
    // If no subtitle ID is received, redirect to the appropriate page
    header("Location: ../series-episode-subtitles?series=" . $series_id . "&episodes=" . $episodes_id);
    exit();
}
