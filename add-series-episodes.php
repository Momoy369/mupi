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
                    <h1 class="h3 mb-2 text-gray-800">Add Series Episodes</h1>
                    <p class="mb-4">Here you can add series episodes files. Fill in the details in each field provided
                        in the
                        form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Add Series Episodes</h5>
                        </div>
                        <?php
                        $id = $_GET['series'];
                        $_SESSION['series_id_ses'] = $id;
                        ?>
                        <form class="form mt-4" role="form" method="POST" action="action/add-series-episodes"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <div class="row p-4">

                                    <div class="col-lg-6">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" name="title" id="title">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">External URL</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Paste external url for content"></i>
                                        <input type="text" class="form-control" name="external" id="external">
                                    </div>
                                </div>

                                <div class="row p-4">

                                    <div class="col-lg-6">
                                        <label for="">Directly Upload to Own Server</label>
                                        <i class="fas fa-info-circle ml-1" data-toggle="tooltip" data-placement="right"
                                            title="Upload your content to this server directly"></i>
                                        <input type="file" name="files" id="files" class="form-control"
                                            accept="video/*">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Short Description</label>
                                        <textarea name="short_desc" id="short_desc" class="form-control"></textarea>
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

        // Get select and input elements
        const movieSelect = document.getElementById('movie-select');
        const vidsrcInput = document.getElementById('vidsrc');
        const apiUrlInput = document.getElementById('api-url'); // Get hidden input for api_url

        // Add an event listener to listen for changes to select
        movieSelect.addEventListener('change', function () {
            // Get the selected value from select
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = selectedOption.value;

            // Get api_url from data-api-url on selected option
            const apiUrl = selectedOption.getAttribute('data-api-url');
            // If there is no api_url in the selected options, use the value from the hidden input
            const finalApiUrl = apiUrl ? apiUrl : apiUrlInput.value;

            // Update the VidSrc input value according to the selected value from select and api_url
            vidsrcInput.value = 'https://vidsrc.to/embed/movie/' + finalApiUrl;
        });

    </script>

</body>

</html>