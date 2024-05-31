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
                    <h1 class="h3 mb-2 text-gray-800">Movie Qualities</h1>
                    <p class="mb-4">View, add, change, and delete existing qualities in the database.
                    </p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-4 d-flex justify-content-between align-items-center">
                            <h5 class="m-0 font-weight-bold text-primary">Quality List</h5>
                            <a href="add-qualities" class="btn btn-sm btn-primary ml-auto">+ Quality</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                if (isset($_GET['id_kualitas'])) {
                                    $deleteQualities = $_GET['id_kualitas'];
                                    $qualitiesDel = "DELETE FROM tbl_kualitas WHERE id_kualitas = '" . $deleteQualities . "'";
                                    $qualitiesDelPath = "SELECT * FROM tbl_kualitas WHERE id_kualitas = '" . $deleteQualities . "'";
                                    $queDelPath = mysqli_query($conn, $qualitiesDelPath);

                                } ?>
                                <table class="table table-bordered" id="movieList" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Quality</th>
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Quality</th>
                                            <!-- <th class="text-center">Action</th> -->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $genre = mysqli_query($conn, "SELECT * FROM tbl_kualitas");
                                        $genreId = mysqli_query($conn, "SELECT * FROM tbl_kualitas");
                                        $i = 1;

                                        while ($row = mysqli_fetch_array($genre)) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $i++ ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row['nama_kualitas'] ?>
                                                </td>
                                                <!-- <td class="text-center">
                                                    <a href="edit-quality?id_kualitas=<?= $row['id_kualitas']; ?>"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <a href="?id_kualitas=<?= $row['id_kualitas']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this quality?')">
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