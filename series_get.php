<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_series"));

    $query = "SELECT * FROM tbl_series 
          JOIN tbl_genre ON tbl_series.genre = tbl_genre.genre_id WHERE jenis = 'TV Series'";

    if (!empty($search)) {
        $query .= " AND (judul LIKE '%$search%' OR genre LIKE '%$search%')";
    }

    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    $query .= " ORDER BY id_series DESC LIMIT $length OFFSET $start";

    $result = mysqli_query($conn, $query);

    $data = array();
    $i = $start + 1;

    function startsWith($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $posterImage = startsWith($row['poster'], 'poster') ? $image . $row['poster'] : $row['poster'];

        $status_label = '';
        if ($row['status'] == 0) {
            $status_label = '<div class="alert alert-danger">Inactive</div>';
        } elseif ($row['status'] == 1) {
            $status_label = '<div class="alert alert-success">Active</div>';
        } elseif ($row['status'] == 3) {
            $status_label = '<div class="alert alert-warning">Upcoming</div>';
        }

        $tanggal = date('d F Y', strtotime($row['posted_at']));

        $tahun_rilis = date('Y', strtotime($row['tahun_rilis']));

        $judul = ucwords(stripslashes($row['judul'])) . ' (' . $tahun_rilis . ')';

        $data[] = array(
            'index' => $i,
            'judul' => $judul,
            'poster' => '<a href="' . $posterImage . '" id ="example1" title="' . $row['judul'] . '">' .
                '<img src="' . $posterImage . '" height="100" width ="80" id="myImg">' .
                '</a>',
            'genre' => $row['nama_genre'],
            'episodes' => $row['episodes'],
            'seasons' => $row['seasons'],
            'tanggal' => $tanggal,
            'status' => $status_label,
            'action' => '<a href="edit-series?id_series=' . $row['id_series'] . '" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a> ' .
                '<a href="series-episodes?episodes=' . $row['id_series'] . '" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></a> ' .
                '<a href="action/delete-series?id_series=' . $row['id_series'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this series?\')"><i class="fa fa-trash"></i></a>'
        );

        $i++;
    }


    $response = array(
        'draw' => intval($draw),
        'recordsTotal' => $totalData,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header("Location: series");
    exit();
}
