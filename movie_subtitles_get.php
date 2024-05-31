<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

// Periksa jika permintaan AJAX ada
if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    // Query untuk menghitung total data tanpa filter
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_movie_subtitles"));

    $query = "SELECT tbl_movie_subtitles.*, tbl_movies.judul, tbl_movies.tahun_rilis, tbl_movies.poster, tbl_language.language, tbl_movie_subtitles.url_sub, tbl_movie_subtitles.posted_at 
                FROM tbl_movie_subtitles 
                INNER JOIN tbl_movies ON tbl_movie_subtitles.movie_id = tbl_movies.id_movies 
                INNER JOIN tbl_language ON tbl_movie_subtitles.language_id = tbl_language.id_language";

    // Jika ada kriteria pencarian, tambahkan kondisi pencarian ke query
    if (!empty($search)) {
        $query .= " WHERE (tbl_movies.judul LIKE '%$search%' OR tbl_language.language LIKE '%$search%' OR tbl_movie_subtitles.url_sub LIKE '%$search%')";
    }

    // Hitung total data setelah difilter
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Tambahkan limit dan offset ke query
    $query .= " ORDER BY tbl_movie_subtitles.posted_at DESC LIMIT $length OFFSET $start";

    // Eksekusi query
    $result = mysqli_query($conn, $query);

    // Format data yang akan dikirim sebagai response JSON
    $data = array();
    $i = $start + 1;

    function startsWith($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $posterImage = startsWith($row['poster'], 'poster') ? $image . $row['poster'] : $row['poster'];

        // Mengambil tanggal dengan format yang diinginkan (tanggal, bulan, tahun)
        $tanggal = date('d F Y', strtotime($row['posted_at']));

        // Mendapatkan tahun rilis
        $tahun_rilis = date('Y', strtotime($row['tahun_rilis']));

        // Menambahkan tahun rilis ke judul
        $judul = ucwords($row['judul']) . ' (' . $tahun_rilis . ')';

        // Membuat teks "Open" dengan link ke $url_sub yang dibuka di tab baru
        $urlLink = '<a href="' . $subtitle_movie . $row['url_sub'] . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'language' => $row['language'],
            'movie' => $judul, // Menggunakan judul yang sudah termasuk tahun rilis
            'poster' => '<a href="' . $posterImage . '" id ="example1" title="' . $row['judul'] . '">' .
                '<img src="' . $posterImage . '" height="100" width ="80" id="myImg">' .
                '</a>',
            'url_sub' => $urlLink,
            'posted_at' => $tanggal,
            'action' => '<a href="action/delete-movie-subtitles.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this subtitles?\')"><i class="fa fa-trash"></i></a>'
        );

        $i++;
    }

    // Response JSON
    $response = array(
        'draw' => intval($draw),
        'recordsTotal' => $totalData,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    );

    // Set header sebagai JSON
    header('Content-Type: application/json');
    // Encode response ke format JSON dan tampilkan
    echo json_encode($response);
} else {
    // Jika tidak ada permintaan AJAX, redirect ke halaman movie-subtitles.php
    header("Location: movie-subtitles");
    exit();
}