<?php

if (isset($_SERVER['HTTP'])) {

    $baseurl = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi';

    $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/';
    $image = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/poster/';
    $image_actor = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/actors/';
    $image_director = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/directors/';
    $subtitle_movie = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/files/subtitles/movies/';

} else {
    $baseurl = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi';

    $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/';
    $image = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/poster/';
    $image_actor = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/actors/';
    $image_director = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/images/directors/';
    $subtitle_movie = 'http://' . $_SERVER['SERVER_NAME'] . '/mupi/files/subtitles/movies/';
}