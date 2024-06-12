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
                    <h1 class="h3 mb-2 text-gray-800">Add Movie Subtitles</h1>
                    <p class="mb-4">Here you can add movies subtitles. Fill in the details in each field provided in the
                        form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Add Movie Subtitles</h5>
                        </div>
                        <?php
                        $id = $_GET['movies'];
                        $_SESSION['movie_id_ses'] = $id;
                        ?>
                        <form class="form mt-4" role="form" method="POST" action="action/add-movie-subtitles"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';

                                $language_que = "SELECT * FROM tbl_language";
                                $language_sel = mysqli_query($conn, $language_que);

                                $language_sel_equals = mysqli_query($conn, $language_que);
                                $language_data = mysqli_fetch_assoc($language_sel_equals);

                                ?>

                                <div class="row p-4">

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

                                    <div class="col-lg-6">
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