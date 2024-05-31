<?php

include '../include/koneksi.php';

if (isset($_POST['submit'])) {

    // Ambil id_movies dari formulir
    $id = $_POST['id_movies'];

    $judul = addslashes($_POST['judul']);
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Ambil genre dari formulir dan gabungkan menjadi satu string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $kualitas = $_POST['kualitas'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);

    $duration = $_POST['duration'];

    // Buat query untuk memperbarui data film di dalam database (tanpa mengubah gambar dan production)
    $update = "UPDATE tbl_movies SET judul = ?, jenis = ?, genre = ?, kualitas = ?, tahun_rilis = ?, rating = ?, trailer_url = ?, country = ?, overview = ?, duration = ?, status = ? WHERE id_movies = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssssssssi", $judul, $jenis, $separatedValueGenre, $kualitas, $tahun, $rating, $trailerUrl, $separatedValueCountry, $overview, $duration, $status, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika pembaruan berhasil, arahkan kembali ke halaman movies.php
        header("Location: ../movies");
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
    header("Location: ../movies");
    exit();
}
