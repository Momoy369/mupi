<?php
include("images/baseurl.php");
if (!isset($_SESSION['admin_name'])) {
    session_destroy();
    header("Location:$baseurl/index");
    exit;
}