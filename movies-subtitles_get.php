<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $movie_id = $_POST['movie_id'];

    $totalDataQuery = "SELECT COUNT(*) AS count FROM tbl_movie_subtitles WHERE movie_id = ?";
    $stmt_total = mysqli_prepare($conn, $totalDataQuery);
    mysqli_stmt_bind_param($stmt_total, 'i', $movie_id);
    mysqli_stmt_execute($stmt_total);
    mysqli_stmt_bind_result($stmt_total, $totalData);
    mysqli_stmt_fetch($stmt_total);
    mysqli_stmt_close($stmt_total);

    $query = "SELECT tbl_movie_subtitles.*, tbl_language.language, tbl_movie_subtitles.url_sub, tbl_movie_subtitles.posted_at 
              FROM tbl_movie_subtitles 
              INNER JOIN tbl_movies ON tbl_movie_subtitles.movie_id = tbl_movies.id_movies 
              INNER JOIN tbl_language ON tbl_movie_subtitles.language_id = tbl_language.id_language 
              WHERE tbl_movie_subtitles.movie_id = ?";

    if (!empty($search)) {
        $query .= " AND (tbl_language.language LIKE ? OR tbl_movie_subtitles.url_sub LIKE ?)";
        $search_param = "%$search%";
    }

    $query .= " ORDER BY tbl_movie_subtitles.id DESC LIMIT ?, ?";

    $stmt = mysqli_prepare($conn, $query);

    if (!empty($search)) {
        mysqli_stmt_bind_param($stmt, 'issii', $movie_id, $search_param, $search_param, $start, $length);
    } else {
        mysqli_stmt_bind_param($stmt, 'iii', $movie_id, $start, $length);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        if (startsWith($row['url_sub'], 'subtitle')) {
            $url_sub = $baseurl . '/files/subtitles/movies/' . $row['url_sub'];
        } else {
            $url_sub = $row['url_sub'];
        }

        $urlLink = '<a href="' . $url_sub . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'movie_id' => $row['movie_id'],
            'language' => $row['language'],
            'url_sub' => $urlLink,
            'action' => '<a href="action/delete-movie-subtitles?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this subtitles file?\')"><i class="fa fa-trash"></i></a> '
        );

        $i++;
    }

    $totalFiltered = mysqli_num_rows($result);

    $response = array(
        'draw' => intval($draw),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header("Location: movies");
    exit();
}
