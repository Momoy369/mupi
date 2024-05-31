<?php

include '../include/koneksi.php';

// Periksa apakah ada ID negara yang diterima melalui parameter GET
if (isset($_GET['country_id'])) {
    $idCountry = $_GET['country_id'];

    // Query untuk menghapus negara dari database
    $deleteCountryQuery = "DELETE FROM tbl_countries WHERE id_country = ?";
    $stmt = $conn->prepare($deleteCountryQuery);
    $stmt->bind_param("i", $idCountry);
    $stmt->execute();

    // Tutup prepared statement
    $stmt->close();

    // Redirect kembali ke halaman countries.php
    header("Location: $baseurl/mupi/countries");
    exit();
} else {
    // Jika tidak ada ID negara yang diterima, redirect ke halaman countries.php
    header("Location: $baseurl/mupi/countries");
    exit();
}