<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset($_POST['submit'])) {

    // Ambil nilai dari inputan
    $movieSelect = $_POST['movie-select'];
    $languageSelect = $_POST['language-select'];

    // Mendapatkan id movie dari select option
    $idMovie = $movieSelect;

    // Mendapatkan judul movie dari database
    $movieQuery = "SELECT judul FROM tbl_movies WHERE id_movies = ?";
    $stmt = $conn->prepare($movieQuery);
    $stmt->bind_param("i", $idMovie);
    $stmt->execute();
    $result = $stmt->get_result();
    $movieData = $result->fetch_assoc();
    $judulMovie = $movieData['judul'];

    // Mendapatkan nama bahasa dari database
    $languageQuery = "SELECT language FROM tbl_language WHERE id_language = ?";
    $stmt = $conn->prepare($languageQuery);
    $stmt->bind_param("i", $languageSelect);
    $stmt->execute();
    $result = $stmt->get_result();
    $languageData = $result->fetch_assoc();
    $languageName = $languageData['language'];

    // Menyiapkan array untuk menyimpan data inputan
    $inputData = array(
        'Own Server' => $_FILES['direct']
    );

    // Loop melalui array input data
    foreach ($inputData as $hostingName => $directFile) {
        // Jika file telah dipilih untuk diunggah
        if (!empty($directFile['name'])) {
            // Tentukan direktori penyimpanan file
            $targetDirectory = "../files/subtitles/movies/";

            // Tentukan nama file yang akan disimpan
            $fileName = "subtitle_" . str_replace(' ', '_', $judulMovie) . "_" . str_replace(' ', '_', $languageName) . "." . pathinfo($directFile['name'], PATHINFO_EXTENSION);

            // Pindahkan file ke direktori target
            if (move_uploaded_file($directFile["tmp_name"], $targetDirectory . $fileName)) {
                // URL embed akan mengikuti base URL dan diikuti nama file
                $urlEmbed = $fileName;

                // Eksekusi query untuk menyimpan data ke database
                $query = "INSERT INTO tbl_movie_subtitles (movie_id, language_id, url_sub) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iis", $idMovie, $languageSelect, $urlEmbed);
                $stmt->execute();
                $stmt->close();
            } else {
                // Gagal memindahkan file, handle kesalahan di sini
                echo "Error: Failed to move file.";
            }
        }
    }

    // Tutup koneksi database
    $conn->close();

    // Redirect kembali ke halaman yang sesuai
    header("Location: ../movie-subtitles");
    exit();
}