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
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_movies_file"));

    $query = "SELECT tbl_movies_file.*, tbl_movies.judul, tbl_movies.tahun_rilis, tbl_movies.poster, tbl_movies_file.hosting_name, tbl_movies_file.url_embed 
          FROM tbl_movies_file 
          INNER JOIN tbl_movies ON tbl_movies_file.id_movie = tbl_movies.id_movies";


    // Jika ada kriteria pencarian, tambahkan kondisi pencarian ke query
    if (!empty($search)) {
        $query .= " AND (judul LIKE '%$search%' OR genre LIKE '%$search%')";
    }

    // Hitung total data setelah difilter
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Tambahkan limit dan offset ke query
    $query .= " ORDER BY id DESC LIMIT $length OFFSET $start";

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

        // Cek apakah url_embed didahului dengan kata 'movie'
        if (startsWith($row['url_embed'], 'movie')) {
            $url_embed = $baseurl . '/files/movies-files/' . $row['url_embed'];
        } else {
            $url_embed = $row['url_embed'];
        }

        // Membuat teks "Open" dengan link ke $url_embed yang dibuka di tab baru
        $urlLink = '<a href="' . $url_embed . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'hosting_name' => $row['hosting_name'],
            'movie' => $judul, // Menggunakan judul yang sudah termasuk tahun rilis
            'poster' => '<a href="' . $posterImage . '" id ="example1" title="' . $row['judul'] . '">' .
                '<img src="' . $posterImage . '" height="100" width ="80" id="myImg">' .
                '</a>',
            'posted_at' => $tanggal,
            'url_embed' => $urlLink,
            'action' => '<a href="action/delete-movie-files.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this movie file?\')"><i class="fa fa-trash"></i></a>'
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
    // Jika tidak ada permintaan AJAX, redirect ke halaman movies.php
    header("Location: movie-files");
    exit();
}
