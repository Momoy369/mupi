<?php include 'koneksi.php';
$query = "SELECT * FROM tbl_setting WHERE id = 1";
$ms = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($ms);
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <?= $result['app_name']; ?> <sup>ADMINS</sup>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="home">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMovies" aria-expanded="true"
            aria-controls="collapseMovies">
            <i class="fas fa-fw fa-film"></i>
            <span>Movies</span>
        </a>
        <div id="collapseMovies" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Movies Menu:</h6>
                <a class="collapse-item" href="movies">Movie List</a>
                <!-- <a class="collapse-item" href="movie-files">Movie Files</a> -->
                <!-- <a class="collapse-item" href="movie-subtitles">Movie Subtitles</a> -->
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeries" aria-expanded="true"
            aria-controls="collapseSeries">
            <i class="fas fa-fw fa-play"></i>
            <span>Series</span>
        </a>
        <div id="collapseSeries" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Series Menu:</h6>
                <a class="collapse-item" href="series">Series List</a>
                <!-- <a class="collapse-item" href="series-subtitles">Series Subtitles</a> -->
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="genres">
            <i class="fas fa-fw fa-list"></i>
            <span>Genres</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="actors">
            <i class="fas fa-fw fa-users"></i>
            <span>Actors/Actress</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="directors">
            <i class="fas fa-fw fa-user"></i>
            <span>Directors</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="qualities">
            <i class="fas fa-fw fa-folder"></i>
            <span>Qualities</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="countries">
            <i class="fas fa-fw fa-flag"></i>
            <span>Countries</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="settings">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->