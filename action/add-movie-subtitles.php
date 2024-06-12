<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    // Take the value from the input
    session_start();
    $movie_id = $_SESSION['movie_id_ses'];
    $languageSelect = $_POST['language-select'];

    // Get movie titles from the database
    $movieQuery = "SELECT judul FROM tbl_movies WHERE id_movies = ?";
    $stmt_movie = $conn->prepare($movieQuery);
    $stmt_movie->bind_param("i", $movie_id);
    $stmt_movie->execute();
    $result_movie = $stmt_movie->get_result();
    $movieData = $result_movie->fetch_assoc();
    $judulMovie = $movieData['judul'];
    $stmt_movie->close();

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

    // Prepare an array to store input data
    $inputData = array(
        'Own Server' => $_FILES['direct']
    );

    // Loop through the input data array
    foreach ($inputData as $hostingName => $directFile) {
        // If a file has been selected for upload
        if (!empty($directFile['name'])) {
            // Specify the file storage directory
            $targetDirectory = "../files/subtitles/movies/";

            // Specify the name of the file to save
            $fileName = "subtitle_" . str_replace(' ', '_', $judulMovie) . "_" . str_replace(' ', '_', $languageName) . "_" . str_replace(' ', '_', $languageCode) . "." . pathinfo($directFile['name'], PATHINFO_EXTENSION);

            // Move the files to the target directory
            if (move_uploaded_file($directFile["tmp_name"], $targetDirectory . $fileName)) {
                // The embed URL will follow the base URL followed by the file name
                $urlEmbed = $fileName;

                // Execute queries to save data to the database
                $query = "INSERT INTO tbl_movie_subtitles (movie_id, language_id, url_sub) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($query);
                $stmt_insert->bind_param("iis", $movie_id, $languageSelect, $urlEmbed);
                $stmt_insert->execute();
                $stmt_insert->close();
            } else {
                // Failed to move file, error handle here
                echo "Error: Failed to move file.";
            }
        }
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the appropriate page
    header("Location: ../movies-subtitles?movies=" . $movie_id);
    exit();
}
