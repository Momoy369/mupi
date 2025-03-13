<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    // Take the value from the input
    session_start();
    $series_id = $_SESSION['series_id_ses'];
    $episodes_id = $_SESSION['episodes_id_ses'];
    $languageSelect = $_POST['language-select'];

    // Get the series title from the database
    $seriesQuery = "SELECT judul FROM tbl_series WHERE id_series = ?";
    $stmt_series = $conn->prepare($seriesQuery);
    $stmt_series->bind_param("i", $series_id);
    $stmt_series->execute();
    $result_series = $stmt_series->get_result();
    $seriesData = $result_series->fetch_assoc();
    $judulSeries = $seriesData['judul'];
    $stmt_series->close();

    // Gets the episode title from the database
    $episodesQuery = "SELECT title FROM tbl_series_episodes WHERE id = ?";
    $stmt_episodes = $conn->prepare($episodesQuery);
    $stmt_episodes->bind_param("i", $episodes_id);
    $stmt_episodes->execute();
    $result_episodes = $stmt_episodes->get_result();
    $episodesData = $result_episodes->fetch_assoc();
    $judulEpisodes = $episodesData['title'];
    $stmt_episodes->close();

    // Gets the language name from the database
    $languageQuery = "SELECT language, language_code FROM tbl_language WHERE id_language = ?";
    $stmt_language = $conn->prepare($languageQuery);
    $stmt_language->bind_param("i", $languageSelect);
    $stmt_language->execute();
    $result_language = $stmt_language->get_result();
    $languageData = $result_language->fetch_assoc();
    $languageName = $languageData['language'];
    $languageCode = $languageData['language_code'];
    $stmt_language->close();

    // Specify the file storage directory
    $targetDirectory = "../files/subtitles/series/";

    // Specify the name of the file to save
    $fileName = "subtitle_" . str_replace(' ', '_', $judulSeries) . "_" . str_replace(' ', '_', $judulEpisodes) . "_" . str_replace(' ', '_', $languageName) . "_" . str_replace(' ', '_', $languageCode) . "." . pathinfo($_FILES['direct']['name'], PATHINFO_EXTENSION);

    // Pindahkan file ke direktori target
    if (move_uploaded_file($_FILES['direct']['tmp_name'], $targetDirectory . $fileName)) {
        // The embed URL will follow the base URL followed by the file name
        $urlEmbed = $fileName;

        // Execute queries to save data to the database
        $query = "INSERT INTO tbl_series_subtitles (series_id, episodes_id, language_id, url_sub, posted_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($query);
        $stmt_insert->bind_param("iiis", $series_id, $episodes_id, $languageSelect, $urlEmbed);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Close the database connection
        $conn->close();

        // Redirect back to the appropriate page
        header("Location: ../series-episode-subtitles?series=" . $series_id . "&episodes=" . $episodes_id);
        exit();
    } else {
        // Failed to move file, error handle here
        echo "Error: Failed to move file.";
    }
}
