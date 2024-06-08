<?php

include '../include/koneksi.php';
include '../include/baseurl.php';

if (isset($_POST['submit'])) {
    // Ambil data dari formulir
    $judul = addslashes($_POST['judul']);
    $url = $_POST['series_id'];
    $jenis = $_POST['jenis'];
    $status = $_POST['statusInput'];

    // Ambil genre dari formulir dan gabungkan menjadi satu string
    $listOfGenre = $_POST['genre'];
    $separatedValueGenre = implode(',', $listOfGenre);

    $production = $_POST['production'];
    $duration = $_POST['duration'];

    $episodes = $_POST['episodes'];
    $seasons = $_POST['seasons'];
    $series_status = $_POST['series_status'];

    // Ambil aktor dari formulir dan gabungkan menjadi satu string
    $actorsString = $_POST['actorsHidden'];

    // Ambil direktur dari formulir dan gabungkan menjadi satu string
    $directorsString = $_POST['directorsHidden'];

    // Ambil tag dari input tersembunyi
    $tagsString = $_POST['tagsHidden'];

    $tahun = $_POST['tahun_rilis'];
    $rating = $_POST['rating'];

    $trailerUrl = $_POST['trailer-url'];

    $listOfCountry = $_POST['country'];
    $separatedValueCountry = implode(',', $listOfCountry);

    $overview = addslashes($_POST['overview']);

    $duration = $_POST['duration'];

    // Pecah string tagsString menjadi tag-tag individu
    $tags = explode(',', $tagsString);

    // Siapkan array untuk menyimpan ID tag yang baru dibuat
    $newTagIds = array();

    // Loop melalui setiap tag
    foreach ($tags as $tag) {
        // Hapus spasi di awal dan akhir tag
        $tag = trim($tag);

        // Cek apakah tag sudah ada di tabel tbl_tag
        $checkTagQuery = "SELECT id FROM tbl_tag WHERE tag = ?";
        $checkTagStmt = $conn->prepare($checkTagQuery);
        $checkTagStmt->bind_param("s", $tag);
        $checkTagStmt->execute();
        $checkTagResult = $checkTagStmt->get_result();

        // Jika tag sudah ada, ambil ID-nya
        if ($checkTagResult->num_rows > 0) {
            $tagRow = $checkTagResult->fetch_assoc();
            $tagId = $tagRow['id'];
        } else {
            // Jika tag belum ada, tambahkan tag baru dan ambil ID-nya
            $insertTagQuery = "INSERT INTO tbl_tag (tag) VALUES (?)";
            $insertTagStmt = $conn->prepare($insertTagQuery);
            $insertTagStmt->bind_param("s", $tag);
            $insertTagStmt->execute();

            // Simpan ID tag baru
            $tagId = $insertTagStmt->insert_id;
        }

        // Simpan ID tag ke dalam array
        $newTagIds[] = $tagId;
    }

    // Bind ID tag ke dalam string yang dipisahkan koma
    $tagIdsString = implode(',', $newTagIds);

    // Pecah string production menjadi production-production individu
    $productions = explode(',', $production);

    // Siapkan array untuk menyimpan ID production yang baru dibuat
    $newProductionIds = array();

    // Loop melalui setiap production
    foreach ($productions as $productionItem) {
        // Hapus spasi di awal dan akhir production
        $productionItem = trim($productionItem);

        // Cek apakah production sudah ada di tabel tbl_production
        $checkProductionQuery = "SELECT id FROM tbl_production WHERE production = ?";
        $checkProductionStmt = $conn->prepare($checkProductionQuery);
        $checkProductionStmt->bind_param("s", $productionItem);
        $checkProductionStmt->execute();
        $checkProductionResult = $checkProductionStmt->get_result();

        // Jika production sudah ada, ambil ID-nya
        if ($checkProductionResult->num_rows > 0) {
            $productionRow = $checkProductionResult->fetch_assoc();
            $productionId = $productionRow['id'];
        } else {
            // Jika production belum ada, tambahkan production baru dan ambil ID-nya
            $insertProductionQuery = "INSERT INTO tbl_production (production) VALUES (?)";
            $insertProductionStmt = $conn->prepare($insertProductionQuery);
            $insertProductionStmt->bind_param("s", $productionItem);
            $insertProductionStmt->execute();

            // Simpan ID production baru
            $productionId = $insertProductionStmt->insert_id;
        }

        // Simpan ID production ke dalam array
        $newProductionIds[] = $productionId;
    }

    // Bind ID production ke dalam string yang dipisahkan koma
    $productionIdsString = implode(',', $newProductionIds);

    // Ambil aktor dari formulir dan gabungkan menjadi satu string
    $actors = explode(',', $actorsString);

    // Siapkan array untuk menyimpan ID aktor yang baru dibuat
    $newActorIds = array();

    // Loop melalui setiap aktor
    foreach ($actors as $actor) {
        // Hapus spasi di awal dan akhir aktor
        $actor = trim($actor);

        // Cek apakah aktor sudah ada di tabel tbl_aktor
        $checkActorQuery = "SELECT id_aktor FROM tbl_aktor WHERE nama_aktor = ?";
        $checkActorStmt = $conn->prepare($checkActorQuery);
        $checkActorStmt->bind_param("s", $actor);
        $checkActorStmt->execute();
        $checkActorResult = $checkActorStmt->get_result();

        // Jika aktor sudah ada, ambil ID-nya
        if ($checkActorResult->num_rows > 0) {
            $actorRow = $checkActorResult->fetch_assoc();
            $actorId = $actorRow['id_aktor'];
        } else {
            // Jika aktor belum ada, tambahkan aktor baru dan ambil ID-nya
            $insertActorQuery = "INSERT INTO tbl_aktor (nama_aktor) VALUES (?)";
            $insertActorStmt = $conn->prepare($insertActorQuery);
            $insertActorStmt->bind_param("s", $actor);
            $insertActorStmt->execute();

            // Simpan ID aktor baru
            $actorId = $insertActorStmt->insert_id;
        }

        // Simpan ID aktor ke dalam array
        $newActorIds[] = $actorId;
    }

    // Bind ID aktor ke dalam string yang dipisahkan koma
    $actorIdsString = implode(',', $newActorIds);

    // Ambil direktur dari formulir dan gabungkan menjadi satu string
    $directors = explode(',', $directorsString);

    // Siapkan array untuk menyimpan ID direktur yang baru dibuat
    $newDirectorIds = array();

    // Loop melalui setiap direktur
    foreach ($directors as $director) {
        // Hapus spasi di awal dan akhir direktur
        $director = trim($director);

        // Cek apakah direktur sudah ada di tabel tbl_director
        $checkDirectorQuery = "SELECT id_director FROM tbl_director WHERE nama_director = ?";
        $checkDirectorStmt = $conn->prepare($checkDirectorQuery);
        $checkDirectorStmt->bind_param("s", $director);
        $checkDirectorStmt->execute();
        $checkDirectorResult = $checkDirectorStmt->get_result();

        // Jika direktur sudah ada, ambil ID-nya
        if ($checkDirectorResult->num_rows > 0) {
            $directorRow = $checkDirectorResult->fetch_assoc();
            $directorId = $directorRow['id_director'];
        } else {
            // Jika direktur belum ada, tambahkan direktur baru dan ambil ID-nya
            $insertDirectorQuery = "INSERT INTO tbl_director (nama_director) VALUES (?)";
            $insertDirectorStmt = $conn->prepare($insertDirectorQuery);
            $insertDirectorStmt->bind_param("s", $director);
            $insertDirectorStmt->execute();

            // Simpan ID direktur baru
            $directorId = $insertDirectorStmt->insert_id;
        }

        // Simpan ID direktur ke dalam array
        $newDirectorIds[] = $directorId;
    }

    // Bind ID direktur ke dalam string yang dipisahkan koma
    $directorIdsString = implode(',', $newDirectorIds);

    // Periksa apakah ada file gambar yang diunggah
    if (!empty($_FILES['photo']['tmp_name'])) {
        // Jika ada file gambar yang diunggah, simpan gambar tersebut
        $file = $_FILES['photo']['tmp_name'];
        $filename = $_FILES['photo']['name'];
        $target_directory = "../images/poster/";
        $new_poster = "poster" . time() . "_" . str_replace(" ", "", $filename);
        $target_filepath = $target_directory . $new_poster;
        $poster = $new_poster;

        // Pindahkan file gambar ke direktori target
        if (move_uploaded_file($file, $target_filepath)) {
            // Simpan URL gambar poster yang diunggah ke dalam database
            $posterUrl = $poster;
        } else {
            echo "Error: Files cannot be moved.";
            exit();
        }
    } else {
        // Jika tidak ada file gambar yang diunggah, gunakan URL gambar poster dari TMDb
        // URL gambar poster dari TMDb
        $posterUrl = isset($_POST['photo2']) ? $_POST['photo2'] : '';
    }

    // Buat query untuk menyimpan data film beserta URL gambar poster ke dalam database
    $insert = "INSERT INTO tbl_series(judul, poster, jenis, genre, episodes, seasons, series_status, aktor, director, tahun_rilis, rating, api_url, tags, country, overview, production, duration, trailer_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sssssssssssssssssss", $judul, $posterUrl, $jenis, $separatedValueGenre, $episodes, $seasons, $series_status, $actorIdsString, $directorIdsString, $tahun, $rating, $url, $tagIdsString, $separatedValueCountry, $overview, $productionIdsString, $duration, $trailerUrl, $status);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika penyimpanan berhasil, arahkan kembali ke halaman movies.php
        header("Location: ../series");
        exit();
    } else {
        // Jika gagal menyimpan data, tampilkan pesan kesalahan
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