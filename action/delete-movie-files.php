<?php

include '../include/koneksi.php';

// Periksa apakah ada ID yang diterima melalui parameter GET
if (isset($_GET['id'])) {
    $idMovie = $_GET['id'];

    // Ambil data file film yang akan dihapus
    $query = "SELECT * FROM tbl_movies_file WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop melalui hasil query
    while ($row = $result->fetch_assoc()) {
        // Hapus file dari local storage jika hosting adalah 'Own Server'
        if ($row['hosting_name'] === 'Own Server') {
            $filePath = "../files/movies-files/" . $row['url_embed'];
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file dari local storage
            }
        }
    }

    // Hapus data file film dari database
    $deleteQuery = "DELETE FROM tbl_movies_file WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();
    $stmt->close();

    // Tutup koneksi database
    $conn->close();

    // Redirect kembali ke halaman yang sesuai
    header("Location: ../movie-files");
    exit();
} else {
    // Jika tidak ada ID yang diterima, redirect ke halaman yang sesuai
    header("Location: ../movie-files");
    exit();
}