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
                    <h1 class="h3 mb-2 text-gray-800">Edit Movie Subtitles</h1>
                    <p class="mb-4">Here you can edit movies subtitles. Fill in the details in each field provided in
                        the
                        form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Edit Movie Subtitles</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/edit-movie-subtitles.php"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';

                                $id = $_GET['id'];
                                $queryEditMovieSubtitles = "SELECT * FROM tbl_movie_subtitles WHERE id = '" . $id . "'";
                                $qems = mysqli_query($conn, $queryEditMovieSubtitles);
                                $resultMovieSubtitles = mysqli_fetch_assoc($qems);

                                ?>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="movie-select">Choose Movies</label>
                                        <?php
                                        include 'include/koneksi.php';
                                        $movies_que = "SELECT * FROM tbl_movies";
                                        $movies_sel = mysqli_query($conn, $movies_que);
                                        $movies_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movie_subtitles WHERE id_movie = '" . $id . "'");
                                        $movies_data = mysqli_fetch_assoc($movie_sel_equals);
                                        ?>
                                        <select name="movie-select" id="movie-select" class="form-control" required>
                                            <option value="">-- Select a movie --</option>
                                            <?php while ($data = mysqli_fetch_assoc($movies_sel)) {
                                                if ($data['id_movies'] == $movies_data['id_movie']) { ?>
                                                    <option selected="<?= $movies_data['id_movie']; ?>"
                                                        value="<?= $data['id_movies']; ?>">
                                                        <?= $data['judul']; ?>
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="<?= $data['id_movies']; ?>">
                                                        <?= $data['judul']; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Choose Language</label>
                                        <select name="language-select" id="language-select" class="form-control">
                                            <option value="" selected>-- Select a language --</option>
                                            <?php
                                            while ($data = mysqli_fetch_assoc($language_sel)) {
                                                $selected = ($data['id_language'] == $language_data['id_language']) ? '' : '';
                                                ?>
                                                <option value="<?= $data['id_language']; ?>" <?= $selected; ?>>
                                                    <?= $data['language']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-12">
                                        <label for="">Directly Upload to Own Server</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content to this server directly"></i>
                                        <input type="file" name="direct" id="direct" class="form-control"
                                            accept=".vtt,.srt">
                                    </div>
                                </div>
                                <div class="form-group mt-5">
                                    <div class="col-lg-12 text-center p-4">
                                        <input type="submit" class="btn btn-primary" value="Save" name="submit">
                                    </div>
                                </div>
                            </div>
                        </form>
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
    <?php include (ROOT_PATH . 'include/addition-select.php'); ?>

</body>

</html>