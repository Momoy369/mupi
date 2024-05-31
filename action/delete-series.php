<?php

include '../include/koneksi.php';

// Periksa apakah ada ID yang diterima melalui parameter GET
if (isset($_GET['id_series'])) {
    $idSeries = $_GET['id_series'];

    // Query untuk mengambil URL gambar poster seri yang akan dihapus
    $getPosterQuery = "SELECT poster FROM tbl_series WHERE id_series = ?";
    $stmt = $conn->prepare($getPosterQuery);
    $stmt->bind_param("i", $idSeries);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ada hasil yang ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $posterFilename = $row['poster'];

        // Hapus file gambar poster dari local storage jika ada
        if (!empty($posterFilename)) {
            $posterFilePath = "../images/poster/" . $posterFilename;
            if (file_exists($posterFilePath)) {
                unlink($posterFilePath); // Hapus file gambar poster
            }
        }
    }

    // Query untuk menghapus seri dari database
    $deleteSeriesQuery = "DELETE FROM tbl_series WHERE id_series = ?";
    $stmt = $conn->prepare($deleteSeriesQuery);
    $stmt->bind_param("i", $idSeries);
    $stmt->execute();

    // Tutup prepared statement
    $stmt->close();

    // Redirect kembali ke halaman series.php
    header("Location: ../series");
    exit();
} else {
    // Jika tidak ada ID yang diterima, redirect ke halaman series.php
    header("Location: ../series");
    exit();
}