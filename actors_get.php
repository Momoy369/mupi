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
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_aktor"));

    // Query dasar untuk mengambil data
    $query = "SELECT * FROM tbl_aktor";

    // Jika ada kriteria pencarian, tambahkan kondisi pencarian ke query
    if (!empty($search)) {
        $query .= " WHERE (nama_aktor LIKE '%$search%')";
    }

    // Hitung total data setelah difilter
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Tambahkan limit dan offset ke query
    $query .= " ORDER BY id_aktor DESC LIMIT $length OFFSET $start";

    // Eksekusi query
    $result = mysqli_query($conn, $query);

    // Format data yang akan dikirim sebagai response JSON
    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'index' => $i,
            'nama_aktor' => ucwords($row['nama_aktor']),
            'action' => '<a href="action/delete-actors.php?id_aktor=' . $row['id_aktor'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this actor?\')"><i class="fa fa-trash"></i></a>'
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
    header("Location: $baseurl/mupi/actors.php");
    exit();
}
