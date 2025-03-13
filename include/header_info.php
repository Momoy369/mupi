<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
            <?php
            if (!isset($_SESSION['admin_name'])) {
                header("Location: login.php");
                exit();
            }

            $nama_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_admin FROM tbl_admin WHERE email='{$_SESSION['admin_name']}'"))['nama_admin'] ?? null;

            if ($nama_admin === null) {
                header("Location: $baseurl/index");
                exit();
            }

            echo $nama_admin;
            ?>
        </span>
        <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
        </a>
    </div>
</li>