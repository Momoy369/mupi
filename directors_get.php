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
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_director"));

    // Query dasar untuk mengambil data
    $query = "SELECT * FROM tbl_director";

    // Jika ada kriteria pencarian, tambahkan kondisi pencarian ke query
    if (!empty($search)) {
        $query .= " WHERE (nama_director LIKE '%$search%')";
    }

    // Hitung total data setelah difilter
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Tambahkan limit dan offset ke query
    $query .= " ORDER BY director_id DESC LIMIT $length OFFSET $start";

    // Eksekusi query
    $result = mysqli_query($conn, $query);

    // Format data yang akan dikirim sebagai response JSON
    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'index' => $i,
            'nama_director' => ucwords($row['nama_director']),
            // 'photo_director' => '<a href="' . $image_actor . $row['photo_director'] . '" id ="example1" title="' . $row['nama_director'] . '">' .
            //     '<img src="' . $image_actor . $row['photo_director'] . '" height="100" width ="80" id="myImg">' .
            //     '</a>',
            'action' => '<a href="action/delete-directors.php?director_id=' . $row['director_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this actor?\')"><i class="fa fa-trash"></i></a>'
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
    // Jika tidak ada permintaan AJAX, redirect ke halaman actors.php
    header("Location: $baseurl/mupi/directors.php");
    exit();
}
