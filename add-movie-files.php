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
                    <h1 class="h3 mb-2 text-gray-800">Add Movie Files</h1>
                    <p class="mb-4">Here you can add movies files. Fill in the details in each field provided in the
                        form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Add Movie Files</h5>
                        </div>
                        <?php
                        include 'include/koneksi.php';
                        $id = $_GET['movies'];
                        $_SESSION['movie_id_ses'] = $id;

                        $queryEditMovies = "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'";
                        $qem = mysqli_query($conn, $queryEditMovies);
                        $resultMovies = mysqli_fetch_assoc($qem);
                        ?>
                        <form class="form mt-4" role="form" method="POST" action="action/add-movie-files"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <div class="row p-4">

                                    <!-- <input type="hidden" value="<?= $resultMovies['api_url']; ?>" id="api_url"
                                        name="api_url"> -->

                                    <div class="col-lg-12">
                                        <label for="">Directly Upload to Own Server</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content to this server directly"></i>
                                        <input type="file" name="direct" id="direct" class="form-control"
                                            accept="video/*">
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">DoodStream URL</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content on https://doodstream.com/"></i>
                                        <input type="text" class="form-control" name="doodstream" id="doodstream">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="vidsrc">VidSrc (From TMDB Movie ID)
                                            <i class="fas fa-info-circle ml-1" data-toggle="tooltip"
                                                data-placement="right"
                                                title="To use subtitles manually, change the VidSrc URL to https://vidsrc.to/embed/movie/{id}?sub.file={sub_file}&sub.label={sub_label}. Example: https://vidsrc.to/embed/movie/550?sub.file=sub.com/subs.vtt&sub.label=Indonesia"></i>
                                        </label>
                                        <input type="text" class="form-control" name="vidsrc" id="vidsrc"
                                            value="https://vidsrc.to/embed/movie/<?= $resultMovies['api_url']; ?>">
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Abyss.to or Hydrax Movie URL</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content on https://abyss.to"></i>
                                        <input type="text" class="form-control" name="hydrax" id="hydrax"
                                            placeholder="Paste Embed URL">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">URL Google Drive</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content on https://drive.google.com/"></i>
                                        <input type="text" class="form-control" name="gdrive" id="gdrive">
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

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>


</body>

</html>