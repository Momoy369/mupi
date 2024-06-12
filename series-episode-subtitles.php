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
                    <h1 class="h3 mb-2 text-gray-800">Series Episode Subtitles</h1>
                    <p class="mb-4">View, add, change and delete existing series episode subtitles in the database.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-4 d-flex justify-content-between align-items-center">
                            <?php
                            $series_id = $_GET['series'];
                            $episodes_id = $_GET['episodes'];
                            $_SESSION['series_id_ses'] = $series_id;
                            $_SESSION['episodes_id_ses'] = $episodes_id;

                            $queryEditSeries = "SELECT * FROM tbl_series_episodes WHERE id = '" . $episodes_id . "'";
                            $qes = mysqli_query($conn, $queryEditSeries);
                            $resultSeries = mysqli_fetch_assoc($qes);

                            $queryEditSeries2 = "SELECT * FROM tbl_series WHERE id_series = '" . $series_id . "'";
                            $qes2 = mysqli_query($conn, $queryEditSeries2);
                            $resultSeries2 = mysqli_fetch_assoc($qes2);

                            ?>
                            <h5 class="m-0 font-weight-bold text-primary">
                                <?= htmlspecialchars($resultSeries2['judul']); ?> -
                                <?= htmlspecialchars($resultSeries['title']); ?> Subtitles
                            </h5>
                            <a href="add-series-episode-subtitles?series=<?= $series_id; ?>&episodes=<?= $episodes_id; ?>"
                                class="btn btn-sm btn-primary ml-auto">+ Subtitles</a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="movieList" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Series</th>
                                            <th>ID Episode</th>
                                            <th>Language</th>
                                            <th>Subtitle Url</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>ID Series</th>
                                            <th>ID Episode</th>
                                            <th>Language</th>
                                            <th>Subtitle Url</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

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

    <script>
        $(document).ready(function () {
            var seriesId = getUrlParameter('series');
            var episodesId = getUrlParameter('episodes');

            var movieListTable = $('#movieList').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: 'series-episode-subtitles_get.php',
                    type: 'POST',
                    data: {
                        series_id: seriesId,
                        episodes_id: episodesId
                    }
                },
                columns: [
                    { data: 'index', className: 'text-center' },
                    { data: 'series_id', className: 'text-center' },
                    { data: 'episodes_id', className: 'text-center' },
                    { data: 'language', className: 'text-center' },
                    { data: 'url_sub', className: 'text-center' },
                    { data: 'action', className: 'text-center', orderable: false }
                ],
                drawCallback: function () {
                    var api = this.api();
                    var startIndex = api.context[0]._iDisplayStart;
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });
                }
            });
        });

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };
    </script>

</body>

</html>