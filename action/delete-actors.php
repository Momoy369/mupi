<?php

include '../include/koneksi.php';

// Periksa apakah ada ID yang diterima melalui parameter GET
if (isset($_GET['id_aktor'])) {
    $idAktor = $_GET['id_aktor'];

    // Query untuk mengambil nama file gambar aktor yang akan dihapus
    $getFotoQuery = "SELECT foto_aktor FROM tbl_aktor WHERE id_aktor = ?";
    $stmt = $conn->prepare($getFotoQuery);
    $stmt->bind_param("i", $idAktor);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ada hasil yang ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fotoFilename = $row['foto_aktor'];

        // Hapus file gambar aktor dari local storage jika ada
        if (!empty($fotoFilename)) {
            $fotoFilePath = "../images/actors/" . $fotoFilename;
            if (file_exists($fotoFilePath)) {
                unlink($fotoFilePath); // Hapus file gambar aktor
            }
        }
    }

    // Query untuk menghapus aktor dari database
    $deleteAktorQuery = "DELETE FROM tbl_aktor WHERE id_aktor = ?";
    $stmt = $conn->prepare($deleteAktorQuery);
    $stmt->bind_param("i", $idAktor);
    $stmt->execute();

    // Tutup prepared statement
    $stmt->close();

    // Redirect kembali ke halaman actors.php
    header("Location: $baseurl/mupi/actors");
    exit();
} else {
    // Jika tidak ada ID yang diterima, redirect ke halaman actors.php
    header("Location: $baseurl/mupi/actors");
    exit();
}