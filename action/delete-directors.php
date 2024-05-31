<?php

include '../include/koneksi.php';

// Periksa apakah ada ID direktur yang diterima melalui parameter GET
if (isset($_GET['director_id'])) {
    $idDirector = $_GET['director_id'];

    // Query untuk mendapatkan nama file gambar direktur berdasarkan ID
    $getDirectorPhotoQuery = "SELECT photo_director FROM tbl_director WHERE director_id = ?";
    $stmt = $conn->prepare($getDirectorPhotoQuery);
    $stmt->bind_param("i", $idDirector);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika direktur ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $directorPhoto = $row['photo_director'];

        // Hapus file gambar dari local storage
        $filePath = "../images/directors/" . $directorPhoto;
        if (file_exists($filePath)) {
            unlink($filePath); // Hapus file gambar dari local storage
        }

        // Query untuk menghapus direktur dari database
        $deleteDirectorQuery = "DELETE FROM tbl_director WHERE director_id = ?";
        $stmt = $conn->prepare($deleteDirectorQuery);
        $stmt->bind_param("i", $idDirector);
        $stmt->execute();

        // Tutup prepared statement
        $stmt->close();
    }

    // Redirect kembali ke halaman directors.php
    header("Location: $baseurl/mupi/directors");
    exit();
} else {
    // Jika tidak ada ID direktur yang diterima, redirect ke halaman directors.php
    header("Location: $baseurl/mupi/directors");
    exit();
}