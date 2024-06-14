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
            m.permalink, 
            DATE_FORMAT(m.posted_at, '%d-%M-%Y') AS posted_at
        FROM tbl_movies m
        LEFT JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, m.genre)
        LEFT JOIN tbl_kualitas k ON FIND_IN_SET(k.id_kualitas, m.kualitas)
        LEFT JOIN tbl_aktor a ON FIND_IN_SET(a.id_aktor, m.aktor)
        LEFT JOIN tbl_director d ON FIND_IN_SET(d.id_director, m.director)
        LEFT JOIN tbl_countries c ON FIND_IN_SET(c.country_id, m.country)
        LEFT JOIN tbl_production p ON FIND_IN_SET(p.id, m.production)
        LEFT JOIN tbl_tag t ON FIND_IN_SET(t.id, m.tags)";

        $where_conditions = [];

        if (!empty($_GET['genre'])) {
            $genre_name = urldecode($_GET['genre']);
            $genre_name = str_replace('-', ' ', $conn->real_escape_string($_GET['genre']));
            $genre_query = "SELECT genre_id FROM tbl_genre WHERE nama_genre = '$genre_name'";
            $genre_result = $conn->query($genre_query);
            if ($genre_result && $genre_result->num_rows > 0) {
                $genre_row = $genre_result->fetch_assoc();
                $genre_id = $genre_row['genre_id'];
                $where_conditions[] = "FIND_IN_SET('$genre_id', m.genre) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Genre '$genre_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['actor'])) {
            $actor_name = urldecode($_GET['actor']);
            $actor_name = str_replace('-', ' ', $conn->real_escape_string($_GET['actor']));
            $actor_query = "SELECT id_aktor FROM tbl_aktor WHERE nama_aktor = '$actor_name'";
            $actor_result = $conn->query($actor_query);
            if ($actor_result && $actor_result->num_rows > 0) {
                $actor_row = $actor_result->fetch_assoc();
                $actor_id = $actor_row['id_aktor'];
                $where_conditions[] = "FIND_IN_SET('$actor_id', m.aktor) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Actor '$actor_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['director'])) {
            $director_name = urldecode($_GET['director']);
            $director_name = str_replace('-', ' ', $conn->real_escape_string($_GET['director']));
            $director_query = "SELECT id_director FROM tbl_director WHERE nama_director = '$director_name'";
            $director_result = $conn->query($director_query);
            if ($director_result && $director_result->num_rows > 0) {
                $director_row = $director_result->fetch_assoc();
                $director_id = $director_row['id_director'];
                $where_conditions[] = "FIND_IN_SET('$director_id', m.director) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Director '$director_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['country'])) {
            $country_name = urldecode($_GET['country']);
            $country_name = str_replace('-', ' ', $conn->real_escape_string($_GET['country']));
            $country_query = "SELECT country_id FROM tbl_countries WHERE country_name = '$country_name'";
            $country_result = $conn->query($country_query);
            if ($country_result && $country_result->num_rows > 0) {
                $country_row = $country_result->fetch_assoc();
                $country_id = $country_row['country_id'];
                $where_conditions[] = "FIND_IN_SET('$country_id', m.country) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Country '$country_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['quality'])) {
            $quality_name = urldecode($_GET['quality']);
            $quality_name = $conn->real_escape_string($_GET['quality']);
            $quality_query = "SELECT id_kualitas FROM tbl_kualitas WHERE nama_kualitas = '$quality_name'";
            $quality_result = $conn->query($quality_query);
            if ($quality_result && $quality_result->num_rows > 0) {
                $quality_row = $quality_result->fetch_assoc();
                $quality_id = $quality_row['id_kualitas'];
                $where_conditions[] = "FIND_IN_SET('$quality_id', m.kualitas) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Quality '$quality_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['tags'])) {
            $tags_name = urldecode($_GET['tags']);
            $tags_name = str_replace('-', ' ', $conn->real_escape_string($_GET['tags']));
            $tags_query = "SELECT id FROM tbl_tag WHERE tag = '$tags_name'";
            $tags_result = $conn->query($tags_query);
            if ($tags_result && $tags_result->num_rows > 0) {
                $tags_row = $tags_result->fetch_assoc();
                $tags_id = $tags_row['id'];
                $where_conditions[] = "FIND_IN_SET('$tags_id', m.tags) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Tags '$tags_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['judul'])) {
            $permalink = urldecode($_GET['judul']);
            $permalink = $conn->real_escape_string($permalink);
            $where_conditions[] = "m.permalink LIKE '%$permalink%'";
        }

        if ($id != 0) {
            $where_conditions[] = "m.id_movies = " . $id;
        }

        if (count($where_conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $where_conditions);
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
            s.permalink, 
            DATE_FORMAT(s.posted_at, '%d-%M-%Y') AS posted_at
        FROM tbl_series s
        LEFT JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, s.genre)
        LEFT JOIN tbl_aktor a ON FIND_IN_SET(a.id_aktor, s.aktor)
        LEFT JOIN tbl_director d ON FIND_IN_SET(d.id_director, s.director)
        LEFT JOIN tbl_countries c ON FIND_IN_SET(c.country_id, s.country)
        LEFT JOIN tbl_production p ON FIND_IN_SET(p.id, s.production)
        LEFT JOIN tbl_tag t ON FIND_IN_SET(t.id, s.tags)";

        $where_conditions = [];

        if (!empty($_GET['genre'])) {
            $genre_name = urldecode($_GET['genre']);
            $genre_name = str_replace('-', ' ', $conn->real_escape_string($_GET['genre']));
            $genre_query = "SELECT genre_id FROM tbl_genre WHERE nama_genre = '$genre_name'";
            $genre_result = $conn->query($genre_query);
            if ($genre_result && $genre_result->num_rows > 0) {
                $genre_row = $genre_result->fetch_assoc();
                $genre_id = $genre_row['genre_id'];
                $where_conditions[] = "FIND_IN_SET('$genre_id', s.genre) > 0";
            } else {
                echo json_encode(["status" => "error", "message" => "Genre '$genre_name' not found"]);
                return;
            }
        }

        if (!empty($_GET['aktor'])) {
            $aktor = $conn->real_escape_string($_GET['aktor']);
            $where_conditions[] = "FIND_IN_SET('$aktor', s.aktor) > 0";
        }

        if (!empty($_GET['director'])) {
            $director = $conn->real_escape_string($_GET['director']);
            $where_conditions[] = "FIND_IN_SET('$director', s.director) > 0";
        }

        if (!empty($_GET['country'])) {
            $country = $conn->real_escape_string($_GET['country']);
            $where_conditions[] = "FIND_IN_SET('$country', s.country) > 0";
        }

        if (!empty($_GET['judul'])) {
            $permalink = urldecode($_GET['judul']);
            $permalink = $conn->real_escape_string($permalink);
            $where_conditions[] = "s.permalink LIKE '%$permalink%'";
        }

        if ($id != 0) {
            $where_conditions[] = "s.id_series = " . $id;
        }

        if (count($where_conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $where_conditions);
        }

        $query .= " GROUP BY s.id_series";
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