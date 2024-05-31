<?php
include '../include/koneksi.php';

// Query untuk mengambil kunci API dari tabel setting
$query = "SELECT api_key_tmdb FROM tbl_setting WHERE id = 1"; // Sesuaikan dengan struktur tabel Anda

// Eksekusi query
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dieksekusi
if ($result) {
    // Ambil baris hasil sebagai array asosiatif
    $row = mysqli_fetch_assoc($result);

    // Ambil nilai kunci API
    $api_key_tmdb = $row['api_key_tmdb'];

    // Buat respons JSON
    $response = array('api_key_tmdb' => $api_key_tmdb);

    // Keluarkan respons sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Jika query gagal dieksekusi, keluarkan pesan kesalahan
    echo "Error: " . mysqli_error($conn);
}

// Tutup koneksi ke database
mysqli_close($conn);