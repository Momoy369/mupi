<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset($_POST['submit'])) {
    // Retrieve data from the form
    $judul = addslashes($_POST['judul']);
    $url = $_POST['series_id'];
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Take the genres of the form and combine them into one string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $production = $_POST['production'];
    $duration = $_POST['duration'];

    $episodes = $_POST['episodes'];
    $seasons = $_POST['seasons'];
    $series_status = $_POST['series_status'];

    // Take the actors from the form and combine them into one string
    $actorsString = $_POST['actorsHidden'];

    // Take the directors from the form and combine them into one string
    $directorsString = $_POST['directorsHidden'];

    // Retrieve tags from hidden input
    $tagsString = $_POST['tagsHidden'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);

    $permalink = $_POST['titleHidden'];

    $duration = $_POST['duration'];

    // Break the string tagsString into individual tags
    $tags = explode(',', $tagsString);

    // Set up an array to store the newly created tag IDs
    $newTagIds = array();

    // Loop through each tag
    foreach ($tags as $tag) {
        // Remove spaces at the beginning and end of the tag
        $tag = trim($tag);

        // Check whether the tag already exists in the tbl_tag table
        $checkTagQuery = "SELECT id FROM tbl_tag WHERE tag = ?";
        $checkTagStmt = $conn->prepare($checkTagQuery);
        $checkTagStmt->bind_param("s", $tag);
        $checkTagStmt->execute();
        $checkTagResult = $checkTagStmt->get_result();

        // If the tag already exists, retrieve its ID
        if ($checkTagResult->num_rows > 0) {
            $tagRow = $checkTagResult->fetch_assoc();
            $tagId = $tagRow['id'];
        } else {
            // If the tag doesn't already exist, add a new tag and retrieve its ID
            $insertTagQuery = "INSERT INTO tbl_tag (tag) VALUES (?)";
            $insertTagStmt = $conn->prepare($insertTagQuery);
            $insertTagStmt->bind_param("s", $tag);
            $insertTagStmt->execute();

            // Save the new tag ID
            $tagId = $insertTagStmt->insert_id;
        }

        // Save tag IDs into an array
        $newTagIds[] = $tagId;
    }

    // Bind tag IDs into comma-separated strings
    $tagIdsString = implode(',', $newTagIds);

    // Break the string production into individual productions
    $productions = explode(',', $production);

    // Set up an array to store the newly created production ID
    $newProductionIds = array();

    // Loop through each production
    foreach ($productions as $productionItem) {
        // Remove spaces at the beginning and end of production
        $productionItem = trim($productionItem);

        // Check whether production already exists in the tbl_production table
        $checkProductionQuery = "SELECT id FROM tbl_production WHERE production = ?";
        $checkProductionStmt = $conn->prepare($checkProductionQuery);
        $checkProductionStmt->bind_param("s", $productionItem);
        $checkProductionStmt->execute();
        $checkProductionResult = $checkProductionStmt->get_result();

        // If production already exists, take the ID
        if ($checkProductionResult->num_rows > 0) {
            $productionRow = $checkProductionResult->fetch_assoc();
            $productionId = $productionRow['id'];
        } else {
            // If production doesn't exist yet, add a new production and get its ID
            $insertProductionQuery = "INSERT INTO tbl_production (production) VALUES (?)";
            $insertProductionStmt = $conn->prepare($insertProductionQuery);
            $insertProductionStmt->bind_param("s", $productionItem);
            $insertProductionStmt->execute();

            // Save the new production ID
            $productionId = $insertProductionStmt->insert_id;
        }

        // Save the production ID into an array
        $newProductionIds[] = $productionId;
    }

    // Bind the production ID into a comma separated string
    $productionIdsString = implode(',', $newProductionIds);

    // Take the actors from the form and combine them into one string
    $actors = explode(',', $actorsString);

    // Set up an array to store the newly created actor IDs
    $newActorIds = array();

    // Loop through each actor
    foreach ($actors as $actor) {
        // Remove spaces at the beginning and end of the actor
        $actor = trim($actor);

        // Check whether the actor already exists in the tbl_actor table
        $checkActorQuery = "SELECT id_aktor FROM tbl_aktor WHERE nama_aktor = ?";
        $checkActorStmt = $conn->prepare($checkActorQuery);
        $checkActorStmt->bind_param("s", $actor);
        $checkActorStmt->execute();
        $checkActorResult = $checkActorStmt->get_result();

        // If the actor already exists, take his ID
        if ($checkActorResult->num_rows > 0) {
            $actorRow = $checkActorResult->fetch_assoc();
            $actorId = $actorRow['id_aktor'];
        } else {
            // If the actor doesn't exist yet, add a new actor and retrieve its ID
            $insertActorQuery = "INSERT INTO tbl_aktor (nama_aktor) VALUES (?)";
            $insertActorStmt = $conn->prepare($insertActorQuery);
            $insertActorStmt->bind_param("s", $actor);
            $insertActorStmt->execute();

            // Save the new actor ID
            $actorId = $insertActorStmt->insert_id;
        }

        // Save the actor IDs into an array
        $newActorIds[] = $actorId;
    }

    // Bind actor IDs into comma-separated strings
    $actorIdsString = implode(',', $newActorIds);

    // Take the directors from the form and combine them into one string
    $directors = explode(',', $directorsString);

    // Set up an array to store the newly created director IDs
    $newDirectorIds = array();

    // Loop through each director
    foreach ($directors as $director) {
        // Remove spaces at the beginning and end of the director
        $director = trim($director);

        // Check whether the director already exists in the tbl_director table
        $checkDirectorQuery = "SELECT id_director FROM tbl_director WHERE nama_director = ?";
        $checkDirectorStmt = $conn->prepare($checkDirectorQuery);
        $checkDirectorStmt->bind_param("s", $director);
        $checkDirectorStmt->execute();
        $checkDirectorResult = $checkDirectorStmt->get_result();

        // If the director is already there, take his ID
        if ($checkDirectorResult->num_rows > 0) {
            $directorRow = $checkDirectorResult->fetch_assoc();
            $directorId = $directorRow['id_director'];
        } else {
            // If the director doesn't exist yet, add a new director and retrieve his ID
            $insertDirectorQuery = "INSERT INTO tbl_director (nama_director) VALUES (?)";
            $insertDirectorStmt = $conn->prepare($insertDirectorQuery);
            $insertDirectorStmt->bind_param("s", $director);
            $insertDirectorStmt->execute();

            // Save the new director ID
            $directorId = $insertDirectorStmt->insert_id;
        }

        // Save director IDs into an array
        $newDirectorIds[] = $directorId;
    }

    // Bind the director ID into a comma separated string
    $directorIdsString = implode(',', $newDirectorIds);

    // Check if there are any image files uploaded
    if (!empty($_FILES['photo']['tmp_name'])) {
        // If there is an image file uploaded, save the image
        $file = $_FILES['photo']['tmp_name'];
        $filename = $_FILES['photo']['name'];
        $target_directory = "../images/poster/";
        $new_poster = "poster" . time() . "_" . str_replace(" ", "", $filename);
        $target_filepath = $target_directory . $new_poster;
        $poster = $new_poster;

        // Move the image file to the target directory
        if (move_uploaded_file($file, $target_filepath)) {
            // Save the URL of the uploaded poster image into the database
            $posterUrl = $poster;
        } else {
            echo "Error: Files cannot be moved.";
            exit();
        }
    } else {
        // If no image file has been uploaded, use the poster image URL from TMDb
        // Poster image URL from TMDb
        $posterUrl = isset($_POST['photo2']) ? $_POST['photo2'] : '';
    }

    // Create a query to save film data along with poster image URLs into the database
    $insert = "INSERT INTO tbl_series(judul, poster, jenis, genre, episodes, seasons, series_status, aktor, director, tahun_rilis, rating, api_url, tags, country, overview, production, duration, trailer_url, status, permalink) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("ssssssssssssssssssss", $judul, $posterUrl, $jenis, $separatedValueGenre, $episodes, $seasons, $series_status, $actorIdsString, $directorIdsString, $tahun, $rating, $url, $tagIdsString, $separatedValueCountry, $overview, $productionIdsString, $duration, $trailerUrl, $status, $permalink);

    // Query execution
    if ($stmt->execute()) {
        // If the save is successful, redirect to the movies.php page
        header("Location: ../series");
        exit();
    } else {
        // If it fails to save data, display an error message
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
    // Close the database connection
    $conn->close();
} else {
    // If no form has been submitted, return to the movies.php page
    header("Location: ../series");
    exit();
}