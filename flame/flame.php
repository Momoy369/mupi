<?php

include '../include/koneksi.php';
include '../images/baseurl.php';

header('Content-Type: application/json');
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
        if ($id != 0) {
            $query .= " WHERE m.id_movies = " . $id . " LIMIT 1";
        }
        $query .= " GROUP BY m.id_movies";
    } else if ($content == 'tbl_series') {
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
        if ($id != 0) {
            $query .= " WHERE s.id_series = " . $id . " LIMIT 1";
        }
        $query .= " GROUP BY s.id_series";
    } else {
        $query = "SELECT * FROM $content";
        if ($id != 0) {
            $query .= " WHERE $primary_key = " . $id . " LIMIT 1";
        }
    }

    $response = array();
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
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
