<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function truncate($text, $chars = 100)
{
    if (strlen($text) <= $chars) {
        return $text;
    }
    $truncated = substr($text, 0, $chars);
    return $truncated . '...';
}

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $series_id = $_POST['series_id'];

    $totalDataQuery = "SELECT COUNT(*) AS count FROM tbl_series_episodes WHERE series_id = $series_id";
    $totalDataResult = mysqli_query($conn, $totalDataQuery);
    $totalDataRow = mysqli_fetch_assoc($totalDataResult);
    $totalData = $totalDataRow['count'];

    $query = "SELECT * FROM tbl_series_episodes WHERE series_id = $series_id";

    if (!empty($search)) {
        $query .= " AND (title LIKE '%$search%' OR external_url LIKE '%$search%')";
    }

    $filteredDataQuery = $query;
    $totalFilteredResult = mysqli_query($conn, $filteredDataQuery);
    $totalFiltered = mysqli_num_rows($totalFilteredResult);

    $query .= " ORDER BY id DESC LIMIT $start, $length";

    $result = mysqli_query($conn, $query);

    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {

        if (startsWith($row['external_url'], 'series')) {
            $external_url = $baseurl . '/files/series-files/' . $row['external_url'];
        } else {
            $external_url = $row['external_url'];
        }

        if (startsWith($row['files'], 'series')) {
            $file_url = $baseurl . '/files/series-files/' . $row['files'];
        } else {
            $file_url = $baseurl . '/files/series-files/' . $row['files'];
        }

        $urlLink = '<a href="' . $external_url . '" target="_blank">Open</a>';
        $fileLink = '<a href="' . $file_url . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'series_id' => $row['series_id'],
            'title' => $row['title'],
            'external_url' => $urlLink,
            'files' => $fileLink,
            'short_desc' => truncate($row['short_desc'], 50),
            'action' => '<a href="action/delete-series-episodes-files?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\')"><i class="fa fa-trash"></i></a> ' .
                '<a href="series-episode-subtitles?episodes=' . $row['id'] . '&series=' . $row['series_id'] . '" class="btn btn-sm btn-info"><i class="fa fa-comments"></i></a>'
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