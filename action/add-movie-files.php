<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

if (isset ($_POST['submit'])) {

    // Ambil nilai dari inputan
    $movieSelect = $_POST['movie-select'];
    $doodstream = $_POST['doodstream'];
    $vidsrc = $_POST['vidsrc'];
    $hydrax = $_POST['hydrax'];
    $gdrive = $_POST['gdrive'];

    // Mendapatkan id movie dari select option
    $idMovie = $movieSelect;

    // Menyiapkan array untuk menyimpan data inputan
    $inputData = array(
        array('hosting_name' => 'Own Server', 'url_embed' => ''),
        array('hosting_name' => 'DoodStream', 'url_embed' => $doodstream),
        array('hosting_name' => 'VidSrc', 'url_embed' => $vidsrc),
        array('hosting_name' => 'HydraX', 'url_embed' => $hydrax),
        array('hosting_name' => 'GDrive', 'url_embed' => $gdrive)
    );

    // Loop melalui array input data
    foreach ($inputData as $data) {
        $hostingName = $data['hosting_name'];
        $urlEmbed = $data['url_embed'];

        // Jika url_embed atau nama file tidak kosong, lanjutkan ke input data berikutnya
        if (empty ($urlEmbed) && empty ($_FILES['direct']['name'])) {
            continue;
        }

        // Jika hosting name adalah Own Server
        if ($hostingName === 'Own Server') {
            // Ambil file yang diunggah
            $directFile = $_FILES['direct'];

            // Jika file telah dipilih untuk diunggah
            if (!empty ($directFile['name'])) {
                // Tentukan direktori penyimpanan file
                $targetDirectory = "../files/movies-files/";

                // Tentukan nama file yang akan disimpan
                $targetFileName = $targetDirectory . "movie_" . basename($directFile["name"]);

                // Pindahkan file ke direktori target
                if (move_uploaded_file($directFile["tmp_name"], $targetFileName)) {
                    // URL embed akan mengikuti base URL dan diikuti nama file
                    $urlEmbed = "movie_" . basename($directFile["name"]);
                } else {
                    // Gagal memindahkan file, handle kesalahan di sini
                    echo "Error: Failed to move file.";
                    continue; // Lanjutkan ke input data berikutnya jika ada kesalahan
                }
            }
        }

        // Eksekusi query untuk menyimpan data ke database
        $query = "INSERT INTO tbl_movies_file (id_movie, hosting_name, url_embed) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $idMovie, $hostingName, $urlEmbed);
        $stmt->execute();
        $stmt->close();
    }

    // Tutup koneksi database
    $conn->close();

    // Redirect kembali ke halaman yang sesuai
    header("Location: ../movie-files.php");
    exit();
}