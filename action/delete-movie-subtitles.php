<?php
// Include file koneksi database
include '../include/koneksi.php';

// Fungsi untuk menghapus data berdasarkan ID
function deleteData($table, $id)
{
    global $conn;
    // Query SQL untuk menghapus data
    $query = "DELETE FROM $table WHERE id = ?";
    // Persiapkan statement SQL
    $stmt = $conn->prepare($query);
    // Bind parameter ID
    $stmt->bind_param("i", $id);
    // Eksekusi statement
    $stmt->execute();
    // Tutup statement
    $stmt->close();
}

// Fungsi untuk menghapus file dari local storage
function deleteFileFromStorage($fileName)
{
    $filePath = "../files/subtitles/movies/" . $fileName; // Sesuaikan dengan path storage Anda
    if (file_exists($filePath)) {
        unlink($filePath); // Hapus file dari local storage jika ada
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Dapatkan nama file dari database sebelum menghapus entri
    $query = "SELECT url_sub FROM tbl_movie_subtitles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($fileName);
    $stmt->fetch();
    $stmt->close();

    // Hapus file dari local storage
    deleteFileFromStorage($fileName);

    // Panggil fungsi delete untuk menghapus entri dari database
    deleteData('tbl_movie_subtitles', $id);

    // Redirect ke halaman setelah penghapusan data
    header("Location: ../movie-subtitles");
    exit(); // Pastikan untuk keluar setelah redirect
}