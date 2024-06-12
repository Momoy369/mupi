<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Take the value from the input
    session_start();
    $movie_id = $_SESSION['movie_id_ses'];
    $doodstream = $_POST['doodstream'];
    $vidsrc = $_POST['vidsrc'];
    $hydrax = $_POST['hydrax'];
    $gdrive = $_POST['gdrive'];

    // Gets the movie id from the session
    $idMovie = $movie_id;

    // Gets movie titles from database
    $judulQuery = "SELECT judul FROM tbl_movies WHERE id_movies = ?";
    $stmt_judul = $conn->prepare($judulQuery);
    $stmt_judul->bind_param("i", $idMovie);
    $stmt_judul->execute();
    $result_judul = $stmt_judul->get_result();
    $judulData = $result_judul->fetch_assoc();
    $judul = $judulData['judul'];
    $stmt_judul->close();

    // Prepare an array to store input data
    $inputData = array(
        array('hosting_name' => 'Own Server', 'url_embed' => ''),
        array('hosting_name' => 'DoodStream', 'url_embed' => $doodstream),
        array('hosting_name' => 'VidSrc', 'url_embed' => $vidsrc),
        array('hosting_name' => 'HydraX', 'url_embed' => $hydrax),
        array('hosting_name' => 'GDrive', 'url_embed' => $gdrive)
    );

    // Loop through the input data array
    foreach ($inputData as $data) {
        $hostingName = $data['hosting_name'];
        $urlEmbed = $data['url_embed'];

        // If the hosting name is Own Server and url_embed is not empty
        if ($hostingName === 'Own Server' && !empty($urlEmbed)) {
            // Execute queries to save data to the database
            $query = "INSERT INTO tbl_movies_file (id_movie, hosting_name, url_embed) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $idMovie, $hostingName, $urlEmbed);
            $stmt->execute();
            $stmt->close();
        } elseif ($hostingName !== 'Own Server' && !empty($urlEmbed)) {
            // Execute queries to save data to the database
            $query = "INSERT INTO tbl_movies_file (id_movie, hosting_name, url_embed) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $idMovie, $hostingName, $urlEmbed);
            $stmt->execute();
            $stmt->close();
        } elseif ($hostingName === 'Own Server' && !empty($_FILES['direct']['name'])) {
            // Retrieve the uploaded file
            $directFile = $_FILES['direct'];

            // Specify the file storage directory
            $targetDirectory = "../files/movies-files/";

            // Specify the name of the file to save
            $targetFileName = $targetDirectory . "movie_" . str_replace(' ', '_', $judul) . "." . pathinfo($directFile['name'], PATHINFO_EXTENSION);

            // Move the files to the target directory
            if (move_uploaded_file($directFile["tmp_name"], $targetFileName)) {
                // The embed URL will follow the base URL followed by the file name
                $urlEmbed = "movie_" . str_replace(' ', '_', $judul) . "." . pathinfo($directFile['name'], PATHINFO_EXTENSION);

                // Execute queries to save data to the database
                $query = "INSERT INTO tbl_movies_file (id_movie, hosting_name, url_embed) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $idMovie, $hostingName, $urlEmbed);
                $stmt->execute();
                $stmt->close();
            } else {
                // Failed to move file, error handle here
                echo "Error: Failed to move file.";
            }
        }
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the appropriate page
    header("Location: ../movie-files?movies=" . $movie_id);
    exit();
}
