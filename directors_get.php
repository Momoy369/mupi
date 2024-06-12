<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_director"));

    $query = "SELECT * FROM tbl_director";

    if (!empty($search)) {
        $query .= " WHERE (nama_director LIKE '%$search%')";
    }

    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    $query .= " ORDER BY id_director DESC LIMIT $length OFFSET $start";

    $result = mysqli_query($conn, $query);

    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'index' => $i,
            'nama_director' => ucwords($row['nama_director']),
            // 'photo_director' => '<a href="' . $image_actor . $row['photo_director'] . '" id ="example1" title="' . $row['nama_director'] . '">' .
            //     '<img src="' . $image_actor . $row['photo_director'] . '" height="100" width ="80" id="myImg">' .
            //     '</a>',
            'action' => '<a href="action/delete-directors?id_director=' . $row['id_director'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this director?\')"><i class="fa fa-trash"></i></a>'
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

    // Set header as JSON
    header('Content-Type: application/json');

    echo json_encode($response);
} else {

    header("Location: $baseurl/mupi/directors.php");
    exit();
}
