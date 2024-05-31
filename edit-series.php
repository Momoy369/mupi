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
                    <h1 class="h3 mb-2 text-gray-800">Edit Series</h1>
                    <p class="mb-4">Here you can edit series. Fill in the details in each field provided in the form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Edit Series</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/edit-series.php"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';

                                $id = $_GET['id_series'];
                                $queryEditSeries = "SELECT * FROM tbl_series WHERE id_series = '" . $id . "'";
                                $qes = mysqli_query($conn, $queryEditSeries);
                                $resultSeries = mysqli_fetch_assoc($qes);

                                $productionQuery = "SELECT GROUP_CONCAT(p.production SEPARATOR ', ') AS production_names 
                                    FROM tbl_production p
                                    JOIN tbl_series s ON FIND_IN_SET(p.id, s.production) 
                                    WHERE s.id_series = '" . $id . "'";
                                $productionResult = mysqli_query($conn, $productionQuery);
                                $productionData = mysqli_fetch_assoc($productionResult);
                                $productionNames = $productionData['production_names'];

                                ?>

                                <div class="col-md-12">
                                    <div class="fb-profile-block">
                                        <div class="fb-profile-block-thumb cover-container">
                                            <img src="<?php include 'images/baseurl.php';
                                            echo $image . $resultSeries['poster'] ? 'https://image.tmdb.org/t/p/w500' . $resultSeries['poster'] : ''; ?>"
                                                id="posterPreview3" alt="Poster Preview"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                            <input type="hidden" name="photo3" id="posterPreview3">
                                            <div class="profile-img">
                                                <a href="#">
                                                    <img src="<?php include 'images/baseurl.php';
                                                    echo $image . $resultSeries['poster'] ? 'https://image.tmdb.org/t/p/w500' . $resultSeries['poster'] : ''; ?>"
                                                        id="posterPreview" alt="Poster Preview">
                                                </a>
                                                <input type="hidden" name="photo2" id="posterPreview2">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">TMDB's Series ID</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="series_id"
                                                id="tmdbSeriesIdInput" placeholder="Insert TMDB's Series ID"
                                                value="<?= $resultSeries['api_url']; ?>" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="crawlBtn"
                                                    onclick="crawlTMDB()">Crawl</button>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-group" name="id_series"
                                            value="<?= $resultSeries['id_series']; ?>">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="directors">Directors</label>
                                        <?php
                                        include 'include/koneksi.php';
                                        $director_que = "SELECT * FROM tbl_director";
                                        $director_sel = mysqli_query($conn, $director_que);
                                        $director_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_series WHERE id_series = '" . $id . "'");
                                        $data_name = mysqli_fetch_assoc($director_sel_equals);

                                        ?>
                                        <input type="text" id="directors" name="directors"
                                            placeholder="Enter directors separated by comma" class="form-control"
                                            disabled>
                                        <!-- Input tersembunyi untuk menyimpan nilai directors -->
                                        <input type="hidden" id="directorsHidden" name="directorsHidden">
                                        <div class="row p-4" id="directorsDisplay">
                                            <?php

                                            $directorsIds = explode(',', $data_name['director']);

                                            $directors_query = "SELECT * FROM tbl_director WHERE director_id IN (" . implode(',', $directorsIds) . ")";
                                            $directors_result = mysqli_query($conn, $directors_query);

                                            $directors_count = mysqli_num_rows($directors_result);
                                            $first = true;

                                            while ($directors_row = mysqli_fetch_assoc($directors_result)) {
                                                if (!$first) {
                                                    echo ', ';
                                                }
                                                echo '<span class="director-item">' . $directors_row['nama_director'] . '</span>';
                                                $first = false;
                                            }

                                            ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Series Title</label>
                                        <input class="form-control" name="judul" id="judul"
                                            value="<?= stripslashes($resultSeries['judul']); ?>" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Category</label>
                                        <select class="form-control" aria-label="Select Jenis" name="jenis">
                                            <option value="TV Series">TV Series</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Choose Genre</label>
                                        <?php
                                        include 'include/koneksi.php';
                                        $genre_que = "SELECT * FROM tbl_genre";
                                        $genre_sel = mysqli_query($conn, $genre_que);
                                        $genre_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_series WHERE id_series = '" . $id . "'");
                                        $data_name = mysqli_fetch_assoc($genre_sel_equals);
                                        // Membuat array yang berisi genre yang sudah ada sebelumnya
                                        $selectedGenres = explode(',', $data_name['genre']);
                                        ?>
                                        <select class="form-control select2" multiple="multiple" name="genre[]"
                                            id="genre-series" required>
                                            <?php
                                            while ($data = mysqli_fetch_array($genre_sel)) {
                                                // Periksa apakah id genre saat ini ada di dalam array selectedGenres
                                                $selected = in_array($data['genre_id'], $selectedGenres) ? 'selected' : '';
                                                ?>
                                                <option value="<?= $data['genre_id']; ?>" <?= $selected; ?>>
                                                    <?= $data['nama_genre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Poster (if TMDB doesn't have)</label>
                                        <input class="form-control" type="file" id="formFile" name="photo"
                                            accept="image/*" disabled>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-4">
                                        <label for="">Number of Episodes</label>
                                        <input type="number" name="episodes" id="episodes" class="form-control"
                                            value="<?= $resultSeries['episodes']; ?>" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Number of Seasons</label>
                                        <input type="number" name="seasons" id="seasons" class="form-control"
                                            value="<?= $resultSeries['seasons']; ?>" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Series Status</label>
                                        <input type="text" name="series_status" id="series_status" class="form-control"
                                            value="<?= $resultSeries['series_status']; ?>">
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="actors">Actor/Actress</label>
                                        <input type="text" id="actors" name="actors"
                                            placeholder="Enter actor separated by comma" class="form-control" disabled>
                                        <!-- Input tersembunyi untuk menyimpan nilai actors -->
                                        <input type="hidden" id="actorsHidden" name="actorsHidden">
                                        <div class="row p-4" id="actorsDisplay">
                                            <?php
                                            // Mendapatkan tag dari kolom 'tags' dalam tabel 'tbl_movies'
                                            $actorIds = explode(',', $data_name['aktor']);

                                            // Query untuk mendapatkan nama tag berdasarkan ID dari tabel 'tbl_tags'
                                            $actor_query = "SELECT * FROM tbl_aktor WHERE id_aktor IN (" . implode(',', $actorIds) . ")";
                                            $actor_result = mysqli_query($conn, $actor_query);

                                            $first = true; // Penanda untuk menentukan apakah ini tag pertama atau bukan
                                            
                                            // Menampilkan nama tag
                                            while ($actor_row = mysqli_fetch_assoc($actor_result)) {
                                                // Jika ini bukan tag pertama, tambahkan tanda koma sebelum menampilkan tag
                                                if (!$first) {
                                                    echo ', ';
                                                }
                                                echo '<span class="actor-item">' . $actor_row['nama_aktor'] . '</span>';
                                                $first = false; // Setel penanda ke false setelah tag pertama ditampilkan
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Release Date</label>
                                        <input type="date" class="form-control" id="tahun_rilis" name="tahun_rilis"
                                            value="<?= $resultSeries['tahun_rilis']; ?>">
                                        <!-- Elemen option akan diisi oleh JavaScript -->
                                        </input>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Ratings</label>
                                        <input type="text" class="form-control" id="rating" name="rating"
                                            pattern="[0-9]+([\.][0-9]+)?" title="Enter a number or decimal value"
                                            value="<?= $resultSeries['rating']; ?>">
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="tags">Tags</label>
                                        <input type="text" id="tags" name="tags"
                                            placeholder="Enter tags separated by comma" class="form-control" disabled>
                                        <!-- Input tersembunyi untuk menyimpan nilai tag -->
                                        <input type="hidden" id="tagsHidden" name="tagsHidden">
                                        <div class="row p-4" id="tagsDisplay">
                                            <?php
                                            // Mendapatkan tag dari kolom 'tags' dalam tabel 'tbl_movies'
                                            $tagIds = explode(',', $data_name['tags']);

                                            // Query untuk mendapatkan nama tag berdasarkan ID dari tabel 'tbl_tags'
                                            $tag_query = "SELECT * FROM tbl_tag WHERE id IN (" . implode(',', $tagIds) . ")";
                                            $tag_result = mysqli_query($conn, $tag_query);

                                            $first = true; // Penanda untuk menentukan apakah ini tag pertama atau bukan
                                            
                                            // Menampilkan nama tag
                                            while ($tag_row = mysqli_fetch_assoc($tag_result)) {
                                                // Jika ini bukan tag pertama, tambahkan tanda koma sebelum menampilkan tag
                                                if (!$first) {
                                                    echo ', ';
                                                }
                                                echo '<span class="tag-item">' . $tag_row['tag'] . '</span>';
                                                $first = false; // Setel penanda ke false setelah tag pertama ditampilkan
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Country</label>
                                        <?php
                                        include 'include/koneksi.php';
                                        $country_que = "SELECT * FROM tbl_countries";
                                        $country_sel = mysqli_query($conn, $country_que);
                                        $country_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'");
                                        $country_data = mysqli_fetch_assoc($country_sel_equals);
                                        $selectedCountries = explode(',', $data_name['country']);
                                        ?>
                                        <select multiple="multiple" id="country" name="country[]"
                                            class="form-control select2" required>
                                            <?php
                                            while ($data = mysqli_fetch_array($country_sel)) {
                                                // Periksa apakah id genre saat ini ada di dalam array selectedGenres
                                                $selected = in_array($data['country_id'], $selectedCountries) ? 'selected' : '';
                                                ?>
                                                <option value="<?= $data['country_id']; ?>" <?= $selected; ?>>
                                                    <?= $data['country_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Production</label>
                                        <input type="text" class="form-control" name="production"
                                            id="productionCompanies" value="<?= $productionNames; ?>" disabled>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Duration</label>
                                        <input type="text" class="form-control" name="duration" id="duration"
                                            value="<?= $resultSeries['duration']; ?>" required>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Status</label>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label
                                                class="btn btn-success <?= ($resultSeries['status'] == 1) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="active" autocomplete="off"
                                                    <?= ($resultSeries['status'] == 1) ? 'checked' : ''; ?>>
                                                Active
                                            </label>
                                            <label
                                                class="btn btn-warning <?= ($resultSeries['status'] == 3) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="upcoming" autocomplete="off"
                                                    <?= ($resultSeries['status'] == 3) ? 'checked' : ''; ?>>
                                                Upcoming
                                            </label>
                                            <label
                                                class="btn btn-danger <?= ($resultSeries['status'] == 0) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="inactive" autocomplete="off"
                                                    <?= ($resultSeries['status'] == 0) ? 'checked' : ''; ?>>
                                                Inactive
                                            </label>
                                        </div>
                                        <!-- Input hidden untuk menyimpan nilai status yang akan dikirim -->
                                        <input type="hidden" name="statusInput" id="statusInput"
                                            value="<?= $resultSeries['status']; ?>">
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Overview</label>
                                        <textarea name="overview" id="overview" cols="30" rows="12" class="form-control"
                                            required><?= stripslashes($resultSeries['overview']); ?></textarea>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">The trailer will appear here</label>
                                        <!-- URL trailer akan diisi di sini -->
                                        <div id="trailer">
                                            <iframe src="<?= $resultSeries['trailer_url']; ?>" frameborder="0"
                                                class="embed-respomsive-item" width="560" height="300"></iframe>
                                        </div>
                                    </div>
                                    <input type="hidden" id="trailer-url" name="trailer-url"
                                        value="<?= $resultSeries['trailer_url']; ?>">

                                </div>

                            </div>
                            <div class="form-group mt-5">
                                <div class="col-lg-12 text-center p-4">
                                    <input type="submit" class="btn btn-primary" value="Save" name="submit">
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
        // Menangani perubahan pada radio button
        document.querySelectorAll('input[name="status"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                // Set nilai input hidden berdasarkan radio button yang dipilih
                if (this.id === 'active') {
                    document.getElementById('statusInput').value = 1; // Active = 1
                } else if (this.id === 'inactive') {
                    document.getElementById('statusInput').value = 0; // Inactive = 0
                } else if (this.id === 'upcoming') {
                    document.getElementById('statusInput').value = 3; // Upcoming = 3
                }
            });
        });
    </script>

    <script>
        // Fungsi untuk mengelola inputan tags, actors, dan directors
        function setupInput(inputId, hiddenInputId, displayId, dataArray) {
            var input = document.getElementById(inputId);
            var hiddenInput = document.getElementById(hiddenInputId);
            var data = [];

            input.addEventListener('keyup', function (event) {
                if (event.key === ',' || event.keyCode === 13) {
                    var item = this.value.trim();
                    if (item !== '') {
                        data.push(item);
                        this.value = ''; // Reset input value
                        updateDisplay(); // Update display
                        updateHiddenInput(); // Update hidden input
                    }
                }
            });

            function updateDisplay() {
                var display = document.getElementById(displayId);
                display.innerHTML = ''; // Clear display

                data.forEach(function (item, index) {
                    var element = document.createElement('span');
                    element.textContent = item;
                    display.appendChild(element);
                    if (index !== data.length - 1) {
                        // Add comma after each item except the last one
                        display.appendChild(document.createTextNode(' '));
                    }
                });
            }

            function updateHiddenInput() {
                hiddenInput.value = data.join(''); // Set hidden input value to the joined items
            }
        }

        // Panggil fungsi untuk mengelola input tags
        setupInput('tags', 'tagsHidden', 'tagsDisplay', tags);

        // Panggil fungsi untuk mengelola input actors
        setupInput('actors', 'actorsHidden', 'actorsDisplay', actors);

        // Panggil fungsi untuk mengelola input directors
        setupInput('directors', 'directorsHidden', 'directorsDisplay', directors);
    </script>

    <script>
        function crawlTMDB() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = JSON.parse(this.responseText);
                    var apiKey = response.api_key_tmdb;

                    var tmdbSeriesId = document.getElementById('tmdbSeriesIdInput').value;

                    var apiUrl = 'https://api.themoviedb.org/3/tv/';

                    fetch(apiUrl + tmdbSeriesId + '?api_key=' + apiKey)
                        .then(response => response.json())
                        .then(data => {
                            // Mengisi formulir dengan data yang diperoleh dari API TMDb
                            document.getElementById('judul').value = data.name || '';
                            document.getElementById('tahun_rilis').value = data.first_air_date || '';
                            document.getElementById('rating').value = data.vote_average || '';

                            // Mengambil nama genre dari setiap objek genre TMDb
                            const genresTmdb = data.genres.map(genre => genre.name);

                            // Mendapatkan opsi dropdown genre
                            const genreDropdown = document.getElementById('genre-series');

                            // Loop melalui setiap opsi dropdown genre
                            for (let i = 0; i < genreDropdown.options.length; i++) {
                                // Jika nama genre dalam dropdown ada dalam data genre TMDb, tandai sebagai terpilih
                                if (genresTmdb.includes(genreDropdown.options[i].text)) {
                                    genreDropdown.options[i].selected = true;
                                }
                            }

                            // Panggil metode trigger('change') untuk memperbarui tampilan plugin Select2
                            $(genreDropdown).trigger('change');

                            const countriesTmdb = data.production_countries.map(country => country.name);

                            const countryDropdown = document.getElementById('country');

                            for (let i = 0; i < countryDropdown.options.length; i++) {
                                if (countriesTmdb.includes(countryDropdown.options[i].text)) {
                                    countryDropdown.options[i].selected = true;
                                }
                            }

                            $(countryDropdown).trigger('change');

                            // Mengambil durasi rata-rata setiap episode
                            const episodeRuntime = data.episode_run_time.length > 0 ? data.episode_run_time[0] : 'Unknown';
                            document.getElementById('duration').value = episodeRuntime + ' minutes per episode';

                            // Mengambil jumlah episode dan musim
                            const numberOfEpisodes = data.number_of_episodes || 'Unknown';
                            const numberOfSeasons = data.number_of_seasons || 'Unknown';
                            const seriesStatus = data.status || 'Unknown';

                            document.getElementById('episodes').value = numberOfEpisodes;
                            document.getElementById('seasons').value = numberOfSeasons;
                            document.getElementById('series_status').value = seriesStatus;

                            // Mengisi input dengan ID 'overview' dengan nilai overview dari respons API TMDb
                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            // Mengatur atribut src dari elemen img untuk menampilkan gambar poster dari respons API TMDb
                            const posterPath = data.poster_path;
                            const posterUrl = posterPath ? 'https://image.tmdb.org/t/p/w500' + posterPath : 'https://t4.ftcdn.net/jpg/02/12/52/91/360_F_212529193_YRhcQCaJB9ugv5dFzqK25Uo9Ivm7B9Ca.jpg';
                            document.getElementById('posterPreview').src = posterUrl;

                            document.getElementById('posterPreview2').src = posterUrl || '';

                            document.getElementById('posterPreview3').src = posterUrl || '';

                            document.getElementById('posterPreview').value = posterUrl;

                            document.getElementById('posterPreview2').value = posterUrl || '';

                            document.getElementById('posterPreview3').value = posterUrl || '';

                            // Mengambil jaringan TV dari respons API TMDb dan mengisi formulir networks
                            const networks = data.networks.map(network => network.name);
                            const networksString = networks.join(', ');
                            document.getElementById('productionCompanies').value = networksString || '';


                            // Mengisi input dengan ID 'overview' dengan nilai overview dari respons API TMDb
                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            if (data.created_by && data.created_by.length > 0) {
                                // Mengambil nama pencipta dari data created_by TMDb
                                const creators = data.created_by.map(creator => creator.name);

                                // Menampilkan pencipta dalam elemen #creatorsDisplay
                                const directorsDisplay = document.getElementById('directorsDisplay');
                                directorsDisplay.innerHTML = ''; // Membersihkan konten sebelumnya

                                creators.forEach((creator, index) => {
                                    const creatorElement = document.createElement('span');
                                    creatorElement.textContent = creator;
                                    directorsDisplay.appendChild(creatorElement);

                                    // Tambahkan tanda koma setelah setiap nama pencipta kecuali yang terakhir
                                    if (index < creators.length - 1) {
                                        directorsDisplay.appendChild(document.createTextNode(', '));
                                    }
                                });

                                // Mengisi nilai input tersembunyi #creatorsHidden dengan nama pencipta yang dipisahkan oleh koma
                                document.getElementById('directorsHidden').value = creators.join(',');

                                // Panggil metode trigger('change') setelah nama pencipta ditampilkan
                                $('#directorsHidden').trigger('change');
                            } else {
                                console.error('Error: Creator data not found');
                            }

                            fetch(apiUrl + tmdbSeriesId + '/keywords?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(keywordsData => {
                                    // Pastikan data keywords tersedia
                                    if (keywordsData.results && keywordsData.results.length > 0) {
                                        // Mengambil keyword dari respons API TMDb
                                        const keywords = keywordsData.results.map(keyword => keyword.name);

                                        // Menampilkan keyword dalam elemen #tagsDisplay
                                        const tagsDisplay = document.getElementById('tagsDisplay');

                                        // Membersihkan konten sebelumnya dari elemen tagsDisplay
                                        tagsDisplay.innerHTML = '';

                                        // Menambahkan setiap keyword ke dalam elemen tagsDisplay
                                        keywords.forEach((keyword, index) => {
                                            const tagElement = document.createElement('span');
                                            tagElement.textContent = keyword; // Menambahkan nama keyword ke dalam elemen span
                                            tagsDisplay.appendChild(tagElement);

                                            // Tambahkan tanda koma setelah setiap kata kunci kecuali yang terakhir
                                            if (index < keywords.length - 1) {
                                                tagsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        // Mengisi nilai input tersembunyi #tagsHidden dengan keyword yang dipisahkan oleh koma
                                        document.getElementById('tagsHidden').value = keywords.join(',');

                                        // Panggil metode trigger('change') setelah keyword ditampilkan
                                        $('#tagsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Keywords data not found');
                                        // Handle error here
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat mengambil data keywords.');
                                });

                            // Ambil credit cast dan crew
                            fetch(apiUrl + tmdbSeriesId + '/credits?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(creditsData => {
                                    if (creditsData.cast && creditsData.cast.length > 0) {
                                        // Mengambil nama aktor dari setiap objek cast TMDb
                                        const actors = creditsData.cast.map(actor => actor.name);

                                        // Menampilkan aktor dalam elemen #actorsDisplay
                                        const actorsDisplay = document.getElementById('actorsDisplay');
                                        actorsDisplay.innerHTML = ''; // Membersihkan konten sebelumnya

                                        actors.forEach((actor, index) => {
                                            const actorElement = document.createElement('span');
                                            actorElement.textContent = actor;
                                            actorsDisplay.appendChild(actorElement);

                                            // Tambahkan tanda koma setelah setiap nama aktor kecuali yang terakhir
                                            if (index < actors.length - 1) {
                                                actorsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        // Mengisi nilai input tersembunyi #actorsHidden dengan nama aktor yang dipisahkan oleh koma
                                        document.getElementById('actorsHidden').value = actors.join(',');

                                        // Panggil metode trigger('change') setelah nama aktor ditampilkan
                                        $('#actorsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Actor data not found');
                                    }

                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat mengambil data cast dan direktur.');
                                });

                            // Ambil trailer
                            fetch(apiUrl + tmdbSeriesId + '/videos?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.results && data.results.length > 0) {
                                        // Ambil URL trailer pertama dari respons
                                        const trailerUrl = `https://www.youtube.com/embed/${data.results[0].key}`;

                                        // Buat elemen iframe untuk menampilkan trailer
                                        const iframe = document.createElement('iframe');
                                        iframe.setAttribute('class', 'embed-responsive-item');
                                        iframe.setAttribute('src', trailerUrl);
                                        iframe.setAttribute('width', '560');
                                        iframe.setAttribute('height', '300');
                                        iframe.setAttribute('frameborder', '0');
                                        iframe.setAttribute('allowfullscreen', '');

                                        // Dapatkan elemen div dengan ID 'trailer'
                                        const trailerDiv = document.getElementById('trailer');

                                        // Kosongkan isi elemen div jika sebelumnya sudah ada konten
                                        trailerDiv.innerHTML = '';

                                        // Sisipkan elemen iframe ke dalam elemen div 'trailer'
                                        trailerDiv.appendChild(iframe);

                                        // Ambil elemen input hidden untuk menyimpan URL trailer
                                        const hiddenTrailerInput = document.getElementById('trailer-url');

                                        // Set nilai URL trailer ke dalam input hidden
                                        hiddenTrailerInput.value = trailerUrl;
                                    } else {
                                        console.log('Trailer not available');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat mengambil trailer.');
                                });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengambil data.');
                        });
                }
            };
            xhr.open("GET", "api/get_api_key.php", true);
            xhr.send();
        }
    </script>


</body>

</html>