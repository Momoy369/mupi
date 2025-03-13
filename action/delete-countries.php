<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset($_GET['country_id'])) {
    $idCountry = $_GET['country_id'];

    $deleteCountryQuery = "DELETE FROM tbl_countries WHERE id_country = ?";
    $stmt = $conn->prepare($deleteCountryQuery);
    $stmt->bind_param("i", $idCountry);
    $stmt->execute();

    $stmt->close();

    header("Location: $baseurl/countries");
    exit();
} else {

    header("Location: $baseurl/countries");
    exit();
}