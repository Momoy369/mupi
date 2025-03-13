<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $id_movie = $_POST['id_movie'];

    $totalDataQuery = "SELECT COUNT(*) AS count FROM tbl_movies_file WHERE id_movie = ?";
    $stmt_total = mysqli_prepare($conn, $totalDataQuery);
    mysqli_stmt_bind_param($stmt_total, 'i', $id_movie);
    mysqli_stmt_execute($stmt_total);
    mysqli_stmt_bind_result($stmt_total, $totalData);
    mysqli_stmt_fetch($stmt_total);
    mysqli_stmt_close($stmt_total);

    $query = "SELECT tbl_movies_file.*, tbl_movies.judul, tbl_movies.tahun_rilis, tbl_movies.poster, tbl_movies_file.hosting_name, tbl_movies_file.url_embed 
              FROM tbl_movies_file 
              INNER JOIN tbl_movies ON tbl_movies_file.id_movie = tbl_movies.id_movies 
              WHERE tbl_movies_file.id_movie = ?";

    if (!empty($search)) {
        $query .= " AND (tbl_movies.judul LIKE ? OR tbl_movies_file.hosting_name LIKE ?)";
        $searchParam = "%" . $search . "%";
    }

    $stmt_filtered = mysqli_prepare($conn, $query);
    if (!empty($search)) {
        mysqli_stmt_bind_param($stmt_filtered, 'iss', $id_movie, $searchParam, $searchParam);
    } else {
        mysqli_stmt_bind_param($stmt_filtered, 'i', $id_movie);
    }
    mysqli_stmt_execute($stmt_filtered);
    mysqli_stmt_store_result($stmt_filtered);
    $totalFiltered = mysqli_stmt_num_rows($stmt_filtered);

    $query .= " ORDER BY tbl_movies_file.id DESC LIMIT ? OFFSET ?";
    $stmt_paged = mysqli_prepare($conn, $query);
    if (!empty($search)) {
        mysqli_stmt_bind_param($stmt_paged, 'issii', $id_movie, $searchParam, $searchParam, $length, $start);
    } else {
        mysqli_stmt_bind_param($stmt_paged, 'iii', $id_movie, $length, $start);
    }
    mysqli_stmt_execute($stmt_paged);
    $result = mysqli_stmt_get_result($stmt_paged);

    $data = array();
    $i = $start + 1;

    function startsWith($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $posterImage = startsWith($row['poster'], 'poster') ? $baseurl . $row['poster'] : $row['poster'];

        $tanggal = date('d F Y', strtotime($row['posted_at']));

        $tahun_rilis = date('Y', strtotime($row['tahun_rilis']));

        $judul = ucwords($row['judul']) . ' (' . $tahun_rilis . ')';

        if (startsWith($row['url_embed'], 'movie')) {
            $url_embed = $baseurl . '/files/movies-files/' . $row['url_embed'];
        } else {
            $url_embed = $row['url_embed'];
        }

        $urlLink = '<a href="' . $url_embed . '" target="_blank">Open</a>';

        $data[] = array(
            'index' => $i,
            'hosting_name' => $row['hosting_name'],
            'movie' => $judul,
            'poster' => '<a href="' . $posterImage . '" id="example1" title="' . $row['judul'] . '">' .
                '<img src="' . $posterImage . '" height="100" width="80" id="myImg">' .
                '</a>',
            'posted_at' => $tanggal,
            'url_embed' => $urlLink,
            'action' => '<a href="action/delete-movie-files?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this movie file?\')"><i class="fa fa-trash"></i></a>'
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
    header("Location: movie-files");
    exit();
}
