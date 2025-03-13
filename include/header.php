<?php include 'koneksi.php';
$query = "SELECT * FROM tbl_setting WHERE id = 1";
$ms = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($ms);
?>
<?php include 'sessions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        <?= $result['app_name']; ?> - Dashboard
    </title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script src="assets/vendor/jquery/jquery.min.js"></script>


    <!-- CSS for adjusting the width of select boxes -->
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 38px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 5px;
            margin-right: 5px;
            padding: 3px 10px;
            font-size: 12px;
        }

        .fb-profile-block {
            margin: auto;
            position: relative;
            width: 100%;
        }

        .cover-container {
            background: #1E90FF;
            background: -webkit-radial-gradient(bottom, #73D6F5 12%, #1E90FF);
            background: radial-gradient(at bottom, #73D6F5 12%, #1E90FF)
        }

        .fb-profile-block-thumb {
            display: block;
            height: 400px;
            overflow: hidden;
            text-decoration: none;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
            justify-content: center;
        }

        .fb-profile-block-menu {
            border: 1px solid #d3d6db;
            border-radius: 0 0 3px 3px;
        }

        .profile-img a {
            bottom: 40px;
            box-shadow: none;
            display: block;
            left: 43%;
            padding: 1px;
            position: absolute;
            height: 160px;
            /* width: 160px; */
            background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
            z-index: 9;
        }

        .profile-img img {
            background-color: #fff;
            border-radius: 2px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.07);
            height: 200px;
            padding: 5px;
            width: 150px;
        }
    </style>

</head>