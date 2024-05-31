<?php include 'include/header.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'include/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <?php include 'include/header_info.php'; ?>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Genres</h1>
                    <p class="mb-4">View, add, change and delete existing genres in the database.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-4 d-flex justify-content-between align-items-center">
                            <h5 class="m-0 font-weight-bold text-primary">Genre List</h5>
                            <a href="add-genres" class="btn btn-sm btn-primary ml-auto">+ Genre</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                if (isset($_GET['genre_id'])) {
                                    $deleteGenre = $_GET['genre_id'];
                                    $genreDel = "DELETE FROM tbl_genre WHERE genre_id = '" . $deleteGenre . "'";
                                    $genreDelPath = "SELECT * FROM tbl_genre WHERE genre_id = '" . $deleteGenre . "'";
                                    $queDelPath = mysqli_query($conn, $genreDelPath);
                                    $datas = mysqli_fetch_assoc($queDelPath);

                                    if ($datas['gambar_genre'] != "") {
                                        unlink('images/genres/' . $datas['gambar_genre']);
                                    }

                                    $queDel = mysqli_query($conn, $genreDel);

                                } ?>
                                <table class="table table-bordered" id="movieList" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Genre</th>
                                            <!-- <th class="text-center">Image</th> -->
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Genre</th>
                                            <!-- <th class="text-center">Image</th> -->
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $genre = mysqli_query($conn, "SELECT * FROM tbl_genre");
                                        $genreId = mysqli_query($conn, "SELECT * FROM tbl_genre");
                                        $i = 1;

                                        while ($row = mysqli_fetch_array($genre)) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $i++ ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row['nama_genre'] ?>
                                                </td>
                                                <!-- <td class="text-center">
                                                    <a href="<?php include 'images/baseurl.php'; ?><?= "images/genres" . $row['gambar_genre']; ?>"
                                                        id="example1" title="<?= $row['nama_genre'] ?>">
                                                        <img src="<?php include 'images/baseurl.php'; ?><?= "images/genres/" . $row['gambar_genre']; ?>"
                                                            height="100" width="70" id="myImg" alt="">
                                                    </a>
                                                </td> -->
                                                <!-- <td class="text-center">
                                                    <a href="edit-genre?genre_id=<?= $row['genre_id']; ?>"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <a href="?genre_id=<?= $row['genre_id']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this genre?')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>

                                                </td> -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include 'include/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include (ROOT_PATH . 'include/top.php'); ?>
    <?php include (ROOT_PATH . 'include/logout_components.php'); ?>
    <?php include (ROOT_PATH . 'include/components.php'); ?>
    <?php include (ROOT_PATH . 'include/addition.php'); ?>

</body>

</html>