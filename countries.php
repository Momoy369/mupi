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
                    <h1 class="h3 mb-2 text-gray-800">Countries</h1>
                    <p class="mb-4">View, add, change and delete existing countries in the database.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-4 d-flex justify-content-between align-items-center">
                            <h5 class="m-0 font-weight-bold text-primary">Country List</h5>
                            <a href="add-countries" class="btn btn-sm btn-primary ml-auto">+ Country</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="movieList" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Country Name</th>
                                            <!-- <th class="text-center">Image</th> -->
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Country Name</th>
                                            <!-- <th class="text-center">Image</th> -->
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $country = mysqli_query($conn, "SELECT * FROM tbl_countries");
                                        $countryId = mysqli_query($conn, "SELECT * FROM tbl_countries");
                                        $i = 1;

                                        while ($row = mysqli_fetch_array($country)) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $i++ ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row['country_name'] ?>
                                                </td>
                                                <!-- <td class="text-center">
                                                    <a href="<?php include 'images/baseurl.php'; ?><?= "images/countries/" . $row['country_image']; ?>"
                                                        id="example1" title="<?= $row['nama_genre'] ?>">
                                                        <img src="<?php include 'images/baseurl.php'; ?><?= "images/countries/" . $row['country_image']; ?>"
                                                            height="100" width="70" id="myImg" alt="">
                                                    </a>
                                                </td> -->
                                                <!-- <td class="text-center">

                                                    <a href="?country_id=<?= $row['country_id']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this country?')">
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