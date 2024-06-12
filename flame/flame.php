<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

define('movie_subtitle', $movie_subtitle);

header('Content-Type: application/json; charset=utf-8');
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["content"]) && !empty($_GET["id"])) {
            $content = $_GET["content"];
            $id = intval($_GET["id"]);
            get_data($content, $id);
        } else if (!empty($_GET["content"])) {
            $content = $_GET["content"];
            get_data($content);
        } else {
            echo json_encode(["status" => "error", "message" => "Content name is required"]);
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_data($content, $id = 0)
{
    global $conn;
    $valid_content = [
        'tbl_aktor',
        'tbl_countries',
        'tbl_director',
        'tbl_genre',
        'tbl_kualitas',
        'tbl_language',
        'tbl_movies',
        'tbl_movies_file',
        'tbl_movie_subtitles',
        'tbl_production',
        'tbl_series',
        'tbl_setting',
        'tbl_tag',
        'tbl_series_episodes',
        'tbl_series_subtitles'
    ];

    if (!in_array($content, $valid_content)) {
        echo json_encode(["status" => "error", "message" => "Invalid content name"]);
        return;
    }

    $primary_key = get_primary_key($content);
    if ($primary_key === null) {
        echo json_encode(["status" => "error", "message" => "Primary key not found for the content"]);
        return;
    }

    if ($content == 'tbl_movies') {
        $query = "
        SELECT 
            m.id_movies, 
            m.judul, 
            m.poster, 
            m.jenis, 
            GROUP_CONCAT(DISTINCT g.nama_genre) AS genre, 
            GROUP_CONCAT(DISTINCT k.nama_kualitas) AS kualitas, 
            GROUP_CONCAT(DISTINCT a.nama_aktor) AS aktor, 
            GROUP_CONCAT(DISTINCT d.nama_director) AS director, 
            DATE_FORMAT(m.tahun_rilis, '%d-%M-%Y') AS tahun_rilis, 
            m.rating, 
            m.api_url, 
            GROUP_CONCAT(DISTINCT t.tag) AS tags, 
            GROUP_CONCAT(DISTINCT c.country_name) AS country, 
            m.overview, 
            GROUP_CONCAT(DISTINCT p.production) AS production, 
            m.duration, 
            m.trailer_url, 
            m.status, 
            DATE_FORMAT(m.posted_at, '%d-%M-%Y') AS posted_at
        FROM tbl_movies m
        LEFT JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, m.genre)
        LEFT JOIN tbl_kualitas k ON FIND_IN_SET(k.id_kualitas, m.kualitas)
        LEFT JOIN tbl_aktor a ON FIND_IN_SET(a.id_aktor, m.aktor)
        LEFT JOIN tbl_director d ON FIND_IN_SET(d.id_director, m.director)
        LEFT JOIN tbl_countries c ON FIND_IN_SET(c.country_id, m.country)
        LEFT JOIN tbl_production p ON FIND_IN_SET(p.id, m.production)
        LEFT JOIN tbl_tag t ON FIND_IN_SET(t.id, m.tags)";

        if (!empty($_GET['genre'])) {
            $genre_name = $conn->real_escape_string($_GET['genre']);

            $genre_query = "SELECT genre_id FROM tbl_genre WHERE nama_genre = '$genre_name'";
            $genre_result = $conn->query($genre_query);

            if ($genre_result && $genre_result->num_rows > 0) {
                $genre_row = $genre_result->fetch_assoc();
                $genre_id = $genre_row['genre_id'];

                $query .= " WHERE FIND_IN_SET('$genre_id', m.genre) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Genre '$genre_name' not found"]);
                return;
            }
        } elseif (!empty($_GET['aktor'])) {
            $aktor = $conn->real_escape_string($_GET['aktor']);
            $query .= " WHERE FIND_IN_SET('$aktor', m.aktor) > 0";
        } elseif (!empty($_GET['director'])) {
            $director = $conn->real_escape_string($_GET['director']);
            $query .= " WHERE FIND_IN_SET('$director', m.director) > 0";
        } elseif (!empty($_GET['country'])) {
            $country = $conn->real_escape_string($_GET['country']);
            $query .= " WHERE FIND_IN_SET('$country', m.country) > 0";
        } elseif (!empty($_GET['kualitas'])) {
            $kualitas = $conn->real_escape_string($_GET['kualitas']);
            $query .= " WHERE FIND_IN_SET('$kualitas', m.kualitas) > 0";
        }

        if ($id != 0) {
            $query .= " AND m.id_movies = " . $id;
        } else {
            $query .= " GROUP BY m.id_movies";
        }
    } elseif ($content == 'tbl_series') {
        $query = "
        SELECT 
            s.id_series, 
            s.judul, 
            s.poster, 
            s.jenis, 
            GROUP_CONCAT(DISTINCT g.nama_genre) AS genre, 
            GROUP_CONCAT(DISTINCT a.nama_aktor) AS aktor, 
            GROUP_CONCAT(DISTINCT d.nama_director) AS director, 
            DATE_FORMAT(s.tahun_rilis, '%d-%M-%Y') AS tahun_rilis, 
            s.rating, 
            s.api_url, 
            GROUP_CONCAT(DISTINCT t.tag) AS tags, 
            GROUP_CONCAT(DISTINCT c.country_name) AS country, 
            s.overview, 
            GROUP_CONCAT(DISTINCT p.production) AS production, 
            s.duration, 
            s.trailer_url, 
            s.status, 
            DATE_FORMAT(s.posted_at, '%d-%M-%Y') AS posted_at
        FROM tbl_series s
        LEFT JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, s.genre)
        LEFT JOIN tbl_aktor a ON FIND_IN_SET(a.id_aktor, s.aktor)
        LEFT JOIN tbl_director d ON FIND_IN_SET(d.id_director, s.director)
        LEFT JOIN tbl_countries c ON FIND_IN_SET(c.country_id, s.country)
        LEFT JOIN tbl_production p ON FIND_IN_SET(p.id, s.production)
        LEFT JOIN tbl_tag t ON FIND_IN_SET(t.id, s.tags)";

        if (!empty($_GET['genre'])) {
            $genre_name = $conn->real_escape_string($_GET['genre']);

            $genre_query = "SELECT genre_id FROM tbl_genre WHERE nama_genre = '$genre_name'";
            $genre_result = $conn->query($genre_query);

            if ($genre_result && $genre_result->num_rows > 0) {
                $genre_row = $genre_result->fetch_assoc();
                $genre_id = $genre_row['genre_id'];

                $query .= " WHERE FIND_IN_SET('$genre_id', s.genre) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Genre '$genre_name' not found"]);
                return;
            }
        } elseif (!empty($_GET['aktor'])) {
            $aktor = $conn->real_escape_string($_GET['aktor']);
            $query .= " WHERE FIND_IN_SET('$aktor', s.aktor) > 0";
        } elseif (!empty($_GET['director'])) {
            $director = $conn->real_escape_string($_GET['director']);
            $query .= " WHERE FIND_IN_SET('$director', s.director) > 0";
        } elseif (!empty($_GET['country'])) {
            $country = $conn->real_escape_string($_GET['country']);
            $query .= " WHERE FIND_IN_SET('$country', s.country) > 0";
        }

        if ($id != 0) {
            $query .= " AND s.id_series = " . $id;
        } else {
            $query .= " GROUP BY s.id_series";
        }
    } else if ($content == 'tbl_series_episodes') {
        $query = "
    SELECT 
        se.id,
        se.title,
        se.external_url,
        se.files,
        se.short_desc,
        se.posted_at,
        GROUP_CONCAT(DISTINCT s.judul) AS series
    FROM tbl_series_episodes se
    LEFT JOIN tbl_series s ON se.series_id = s.id_series";
        if ($id != 0) {
            $query .= " WHERE se.id = " . $id . " LIMIT 1";
        } else {
            $query .= " GROUP BY se.id";
        }
    } else if ($content == 'tbl_series_subtitles') {
        $query = "
    SELECT 
        ss.id,
        s.judul AS series,
        se.title AS episode,
        l.language,
        ss.url_sub,
        ss.posted_at
    FROM tbl_series_subtitles ss
    LEFT JOIN tbl_series s ON ss.series_id = s.id_series
    LEFT JOIN tbl_series_episodes se ON ss.episodes_id = se.id
    LEFT JOIN tbl_language l ON ss.language_id = l.id_language";
        if ($id != 0) {
            $query .= " WHERE ss.id = " . $id . " LIMIT 1";
        } else {
            $query .= " GROUP BY ss.id";
        }
    } else if ($content == 'tbl_movie_subtitles') {
        $query = "
    SELECT 
        ms.id,
        m.judul AS movie,
        l.language,
        CONCAT('" . movie_subtitle . "', ms.url_sub) AS url_sub, 
        ms.posted_at
    FROM tbl_movie_subtitles ms
    LEFT JOIN tbl_movies m ON ms.movie_id = m.id_movies
    LEFT JOIN tbl_language l ON ms.language_id = l.id_language";
        if ($id != 0) {
            $query .= " WHERE ms.id = " . $id . " LIMIT 1";
        } else {
            $query .= " GROUP BY ms.id";
        }
    } else if ($content == 'tbl_movies_file') {
        $query = "
    SELECT 
        mf.id,
        m.judul AS movie,
        mf.hosting_name,
        mf.url_embed,
        mf.posted_at
    FROM tbl_movies_file mf
    LEFT JOIN tbl_movies m ON mf.id_movie = m.id_movies";
        if ($id != 0) {
            $query .= " WHERE mf.id = " . $id . " LIMIT 1";
        } else {
            $query .= " GROUP BY mf.id";
        }
    } else {
        $query = "SELECT * FROM $content";
        if ($id != 0) {
            $query .= " WHERE $primary_key = " . $id . " LIMIT 1";
        }
    }

    $response = array();
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        echo str_replace('\\/', '/', json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}

function get_primary_key($content)
{
    $primary_keys = [
        'tbl_aktor' => 'id_aktor',
        'tbl_countries' => 'country_id',
        'tbl_director' => 'id_director',
        'tbl_genre' => 'genre_id',
        'tbl_kualitas' => 'id_kualitas',
        'tbl_language' => 'id_language',
        'tbl_movies' => 'id_movies',
        'tbl_movies_file' => 'id',
        'tbl_movie_subtitles' => 'id',
        'tbl_production' => 'id',
        'tbl_series' => 'id_series',
        'tbl_setting' => 'id',
        'tbl_tag' => 'id',
        'tbl_series_episodes' => 'id',
        'tbl_series_subtitles' => 'id'
    ];
    return isset($primary_keys[$content]) ? $primary_keys[$content] : null;
}