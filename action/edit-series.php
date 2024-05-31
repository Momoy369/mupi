<?php

include '../include/koneksi.php';

if (isset($_POST['submit'])) {

    $id = $_POST['id_series']; // Pastikan ID tersedia

    $judul = addslashes($_POST['judul']);
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Ambil genre dari formulir dan gabungkan menjadi satu string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $duration = $_POST['duration'];

    $episodes = $_POST['episodes'];
    $seasons = $_POST['seasons'];
    $series_status = $_POST['series_status'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);

    $duration = $_POST['duration'];

    // Buat query untuk memperbarui data film di dalam database
    $update = "UPDATE tbl_series SET judul = ?, jenis = ?, genre = ?, episodes = ?, seasons = ?, series_status = ?, tahun_rilis = ?, rating = ?, country = ?, overview = ?, duration = ?, trailer_url = ?, status = ? WHERE id_series = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssssssssssi", $judul, $jenis, $separatedValueGenre, $episodes, $seasons, $series_status, $tahun, $rating, $separatedValueCountry, $overview, $duration, $trailerUrl, $status, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika pembaruan berhasil, arahkan kembali ke halaman movies.php
        header("Location: ../series");
        exit();
    } else {
        // Jika gagal memperbarui data, tampilkan pesan kesalahan
        echo "Error: " . $stmt->error;
    }

    // Tutup prepared statement
    $stmt->close();
    // Tutup koneksi database
    $conn->close();
} else {
    // Jika tidak ada form yang disubmit, kembali ke halaman movies.php
    header("Location: ../series");
    exit();
}
