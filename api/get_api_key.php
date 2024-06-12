<?php
include '../include/koneksi.php';

// Query to retrieve API key from settings table
$query = "SELECT api_key_tmdb FROM tbl_setting WHERE id = 1"; // Adapt it to your table structure

// Query execution
$result = mysqli_query($conn, $query);

// Check whether the query was executed successfully
if ($result) {
    // Retrieve the result rows as an associative array
    $row = mysqli_fetch_assoc($result);

    // Retrieve the API key value
    $api_key_tmdb = $row['api_key_tmdb'];

    // Create a JSON response
    $response = array('api_key_tmdb' => $api_key_tmdb);

    // Output the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If the query fails to execute, issue an error message
    echo "Error: " . mysqli_error($conn);
}

// Close the connection to the database
mysqli_close($conn);