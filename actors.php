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
                    <h1 class="h3 mb-2 text-gray-800">Actors/Actress</h1>
                    <p class="mb-4">View, add, change and delete existing actor or actress in the database.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-4 d-flex justify-content-between align-items-center">
                            <h5 class="m-0 font-weight-bold text-primary">Actor/Actress List</h5>
                            <a href="add-actors" class="btn btn-sm btn-primary ml-auto">+ Actor</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="actorList" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Actor Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Actor Name</th>
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

    <?php include 'include/top.php'; ?>
    <?php include 'include/logout_components.php'; ?>
    <?php include 'include/components.php'; ?>
    <?php include 'include/addition.php'; ?>

    <script>
        $(document).ready(function () {
            var actorListTable = $('#actorList').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: 'actors_get.php',
                    type: 'POST',
                    data: function (d) {
                        d.page = (d.start / d.length) + 1;
                    }
                },
                columns: [
                    { data: 'index', className: 'text-center' },
                    { data: 'nama_aktor', className: 'text-center' },
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
    </script>

</body>

</html>