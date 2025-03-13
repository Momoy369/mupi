<?php

include '../include/koneksi.php';

// Check if there is a country ID received via the GET parameter
if (isset($_GET['country_id'])) {
    $idCountry = $_GET['country_id'];

    // Query to remove country from database
    $deleteCountryQuery = "DELETE FROM tbl_countries WHERE id_country = ?";
    $stmt = $conn->prepare($deleteCountryQuery);
    $stmt->bind_param("i", $idCountry);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect back to countries.php page
    header("Location: ../countries");
    exit();
} else {
    // If no country ID is received, redirect to the countries.php page
    header("Location: ../countries");
    exit();
}