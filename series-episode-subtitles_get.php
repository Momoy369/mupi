<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $series_id = $_POST['series_id'];
    $episodes_id = $_POST['episodes_id'];

    $totalDataQuery = "SELECT COUNT(*) AS count FROM tbl_series_subtitles WHERE series_id = ? AND episodes_id = ?";
    $stmt_total = mysqli_prepare($conn, $totalDataQuery);
    mysqli_stmt_bind_param($stmt_total, 'ii', $series_id, $episodes_id);
    mysqli_stmt_execute($stmt_total);
    mysqli_stmt_bind_result($stmt_total, $totalData);
    mysqli_stmt_fetch($stmt_total);
    mysqli_stmt_close($stmt_total);

    $query = "SELECT tbl_series_subtitles.*, tbl_series.judul, tbl_series.tahun_rilis, tbl_series.poster, tbl_language.language, tbl_series_subtitles.url_sub, tbl_series_subtitles.posted_at 
              FROM tbl_series_subtitles 
              INNER JOIN tbl_series ON tbl_series_subtitles.series_id = tbl_series.id_series 
              INNER JOIN tbl_language ON tbl_series_subtitles.language_id = tbl_language.id_language 
              WHERE tbl_series_subtitles.series_id = ? AND tbl_series_subtitles.episodes_id = ?";

    if (!empty($search)) {
        $query .= " AND (tbl_series.judul LIKE '%$search%' OR tbl_language.language LIKE '%$search%' OR tbl_series_subtitles.url_sub LIKE '%$search%')";
    }

    $stmt_filtered = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt_filtered, 'ii', $series_id, $episodes_id);
    mysqli_stmt_execute($stmt_filtered);
    mysqli_stmt_store_result($stmt_filtered);
    $totalFiltered = mysqli_stmt_num_rows($stmt_filtered);
    mysqli_stmt_close($stmt_filtered);

    $query .= " ORDER BY tbl_series_subtitles.posted_at DESC LIMIT ? OFFSET ?";
    $stmt_final = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt_final, 'iiii', $series_id, $episodes_id, $length, $start);
    mysqli_stmt_execute($stmt_final);
    $result = mysqli_stmt_get_result($stmt_final);

    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $posterImage = $row['poster'];

        $tanggal = date('d F Y', strtotime($row['posted_at']));

        $tahun_rilis = date('Y', strtotime($row['tahun_rilis']));

        $judul = ucwords($row['judul']) . ' (' . $tahun_rilis . ')';

        $urlLink = '<a href="' . $subtitle_movie . $row['url_sub'] . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'series_id' => $row['series_id'],
            'episodes_id' => $row['episodes_id'],
            'language' => $row['language'],
            'url_sub' => $urlLink,
            'action' => '<a href="action/delete-series-episode-subtitles?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i></a>'
        );

        $i++;
    }

    $response = array(
        'draw' => intval($draw),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'data' => $data
    );

    echo json_encode($response);
}
