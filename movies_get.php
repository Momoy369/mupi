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
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_movies"));

    // Query dasar untuk mengambil data
    $query = "SELECT * FROM tbl_movies 
          JOIN tbl_genre ON tbl_movies.genre = tbl_genre.genre_id 
          JOIN tbl_kualitas ON tbl_movies.kualitas = tbl_kualitas.id_kualitas WHERE jenis = 'Movies'";

    // Jika ada kriteria pencarian, tambahkan kondisi pencarian ke query
    if (!empty($search)) {
        $query .= " AND (judul LIKE '%$search%' OR genre LIKE '%$search%')";
    }

    // Hitung total data setelah difilter
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Tambahkan limit dan offset ke query
    $query .= " ORDER BY id_movies DESC LIMIT $length OFFSET $start";

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

        // Menentukan warna dan teks label berdasarkan nilai status
        $status_label = '';
        if ($row['status'] == 0) {
            $status_label = '<div class="alert alert-danger">Inactive</div>';
        } elseif ($row['status'] == 1) {
            $status_label = '<div class="alert alert-success">Active</div>';
        } elseif ($row['status'] == 3) {
            $status_label = '<div class="alert alert-warning">Upcoming</div>';
        }

        // Mengambil tanggal dengan format yang diinginkan (tanggal, bulan, tahun)
        $tanggal = date('d F Y', strtotime($row['tanggal']));

        // Mengambil tahun dari tanggal rilis
        $tahun_rilis = date('Y', strtotime($row['tahun_rilis']));

        // Menambahkan tahun rilis ke judul
        $judul = ucwords($row['judul']) . ' (' . $tahun_rilis . ')';

        $data[] = array(
            'index' => $i,
            'judul' => $judul, // Menggunakan judul yang sudah termasuk tahun rilis
            'poster' => '<a href="' . $posterImage . '" id ="example1" title="' . $row['judul'] . '">' .
                '<img src="' . $posterImage . '" height="100" width ="80" id="myImg">' .
                '</a>',
            'genre' => $row['nama_genre'],
            'kualitas' => $row['nama_kualitas'],
            'rating' => $row['rating'],
            'tanggal' => $tanggal, // Menggunakan variabel $tanggal yang hanya berisi tanggal, bulan, dan tahun
            'status' => $status_label, // Menggunakan variabel $status_label
            'action' => '<a href="edit-movies?id_movies=' . $row['id_movies'] . '" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a> ' .
                '<a href="action/delete-movies.php?id_movies=' . $row['id_movies'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this movie?\')"><i class="fa fa-trash"></i></a>'
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
    header("Location: $baseurl/mupi/movies.php");
    exit();
}
