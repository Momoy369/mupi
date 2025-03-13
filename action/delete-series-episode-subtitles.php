<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

function deleteSeriesSubtitle($subtitle_id)
{
    global $conn;

    $query_file = "SELECT url_sub, series_id, episodes_id FROM tbl_series_subtitles WHERE id = ?";
    $stmt_file = mysqli_prepare($conn, $query_file);
    mysqli_stmt_bind_param($stmt_file, 'i', $subtitle_id);
    mysqli_stmt_execute($stmt_file);
    mysqli_stmt_bind_result($stmt_file, $file_name, $series_id, $episodes_id);
    mysqli_stmt_fetch($stmt_file);
    mysqli_stmt_close($stmt_file);

    $query = "DELETE FROM tbl_series_subtitles WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, 'i', $subtitle_id);

    if (mysqli_stmt_execute($stmt)) {

        if ($file_name) {
            $file_path = "../files/subtitles/series/" . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path); // Delete files from the directory
            }
        }
        mysqli_stmt_close($stmt);
        return array($series_id, $episodes_id);
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

if (isset($_GET['id'])) {
    $subtitle_id = $_GET['id'];

    // Call the function to remove subtitles from the series
    list($series_id, $episodes_id) = deleteSeriesSubtitle($subtitle_id);
    if ($series_id !== false && $episodes_id !== false) {
        // Redirect back to the appropriate page
        header("Location: $baseurl/series-episode-subtitles?series=" . $series_id . "&episodes=" . $episodes_id);
        exit();
    } else {
        echo "Error: Failed to delete subtitle.";
    }
} else {
    // If no subtitle ID is received, redirect to the appropriate page
    header("Location: $baseurl/series-episode-subtitles?series=" . $series_id . "&episodes=" . $episodes_id);
    exit();
}
