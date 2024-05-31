<?php

include '../include/koneksi.php';

// Periksa apakah ada ID yang diterima melalui parameter GET
if (isset($_GET['id_movies'])) {
    $idMovie = $_GET['id_movies'];

    // Query untuk mengambil URL gambar poster film yang akan dihapus
    $getPosterQuery = "SELECT poster FROM tbl_movies WHERE id_movies = ?";
    $stmt = $conn->prepare($getPosterQuery);
    $stmt->bind_param("i", $idMovie);
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

    // Query untuk menghapus film dari database
    $deleteMovieQuery = "DELETE FROM tbl_movies WHERE id_movies = ?";
    $stmt = $conn->prepare($deleteMovieQuery);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();

    // Tutup prepared statement
    $stmt->close();

    // Redirect kembali ke halaman movies.php
    header("Location: ../movies");
    exit();
} else {
    // Jika tidak ada ID yang diterima, redirect ke halaman movies.php
    header("Location: ../movies");
    exit();
}