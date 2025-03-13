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
                    <h1 class="h3 mb-2 text-gray-800">Edit Movie Files</h1>
                    <p class="mb-4">Here you can edit movies files. Fill in the details in each field provided in the
                        form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Edit Movie Files</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/edit-movie-files.php"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="movie-select">Choose Movies</label>
                                        <?php include 'include/koneksi.php';

                                        $movies_que = "SELECT * FROM tbl_movies";
                                        $movies_sel = mysqli_query($conn, $movies_que);
                                        $movies_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movie_files WHERE id_movie = '" . $id . "'");

                                        $movies_data = mysqli_fetch_assoc($movies_sel_equals);
                                        ?>

                                        <select name="movie-select" id="movie-select" class="form-control" disabled>
                                            <?php while ($data = mysqli_fetch_assoc($movies_sel)) {
                                                if ($data['id_movies'] == $movies_data['id_movie']) { ?>
                                                    $tahun_rilis = date('Y', strtotime($data['tahun_rilis']));
                                                    <option selected="<?= $movies_data['id_movie']; ?>"
                                                        value="<?= $data['id_movies']; ?>">
                                                        <?= $data['judul'] . ' (' . $tahun_rilis . ')'; ?>
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="<?= $data['id_movies']; ?>"
                                                        data-api-url="<?= $data['api_url']; ?>">
                                                        <?= $data['judul'] . ' (' . $tahun_rilis . ')'; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                        <input type="hidden" id="api-url" value="<?= $data_name['api_url']; ?>">
                                        <!-- Input tersembunyi untuk menyimpan api_url -->
                                    </div>

                                    <div class="col-lg-6">
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
                                        <input type="text" class="form-control" name="vidsrc" id="vidsrc">
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

        // Dapatkan elemen select dan input
        const movieSelect = document.getElementById('movie-select');
        const vidsrcInput = document.getElementById('vidsrc');
        const apiUrlInput = document.getElementById('api-url'); // Dapatkan input tersembunyi untuk api_url

        // Tambahkan event listener untuk mendengarkan perubahan pada select
        movieSelect.addEventListener('change', function () {
            // Dapatkan nilai yang dipilih dari select
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = selectedOption.value;

            // Dapatkan api_url dari data-api-url pada opsi yang dipilih
            const apiUrl = selectedOption.getAttribute('data-api-url');
            // Jika tidak ada api_url dalam opsi yang dipilih, gunakan nilai dari input tersembunyi
            const finalApiUrl = apiUrl ? apiUrl : apiUrlInput.value;

            // Update nilai input VidSrc sesuai dengan nilai yang dipilih dari select dan api_url
            vidsrcInput.value = 'https://vidsrc.to/embed/movie/' + finalApiUrl;
        });

    </script>

</body>

</html>