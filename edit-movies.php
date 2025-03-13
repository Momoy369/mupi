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
                    <h1 class="h3 mb-2 text-gray-800">Edit Movie</h1>
                    <p class="mb-4">Here you can edit movies. Fill in the details in each field provided in the form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Edit Movie</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/edit-movies"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';

                                $id = $_GET['id_movies'];
                                $queryEditMovies = "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'";
                                $qem = mysqli_query($conn, $queryEditMovies);
                                $resultMovies = mysqli_fetch_assoc($qem);

                                $productionQuery = "SELECT GROUP_CONCAT(p.production SEPARATOR ', ') AS production_names 
                                    FROM tbl_production p
                                    JOIN tbl_movies m ON FIND_IN_SET(p.id, m.production) 
                                    WHERE m.id_movies = '" . $id . "'";
                                $productionResult = mysqli_query($conn, $productionQuery);
                                $productionData = mysqli_fetch_assoc($productionResult);
                                $productionNames = $productionData['production_names'];

                                ?>

                                <div class="col-md-12">
                                    <div class="fb-profile-block">
                                        <div class="fb-profile-block-thumb cover-container">
                                            <img src="<?php include 'images/baseurl.php';
                                            echo $image . $resultMovies['poster'] ? 'https://image.tmdb.org/t/p/w500' . $resultMovies['poster'] : ''; ?>"
                                                id="posterPreview3" alt="Poster Preview"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                            <input type="hidden" name="photo3" id="posterPreview3">
                                            <div class="profile-img">
                                                <a href="#">
                                                    <img src="<?php include 'images/baseurl.php';
                                                    echo $image . $resultMovies['poster'] ? 'https://image.tmdb.org/t/p/w500' . $resultMovies['poster'] : ''; ?>"
                                                        id="posterPreview" alt="Poster Preview">
                                                </a>
                                                <input type="hidden" name="photo2" id="posterPreview2">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">TMDB's Movie ID</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="movie_id"
                                                id="tmdbMovieIdInput" placeholder="Insert TMDB's Movie ID"
                                                value="<?= $resultMovies['api_url']; ?>" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="crawlBtn"
                                                    onclick="crawlTMDB()">Crawl</button>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-group" name="id_movies"
                                            value="<?= $resultMovies['id_movies']; ?>">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="directors">Film Directors</label>
                                        <?php
                                        include 'include/koneksi.php';
                                        $director_que = "SELECT * FROM tbl_director";
                                        $director_sel = mysqli_query($conn, $director_que);
                                        $director_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'");
                                        $data_name = mysqli_fetch_assoc($director_sel_equals);

                                        ?>
                                        <input type="text" id="directors" name="directors"
                                            placeholder="Enter directors separated by comma" class="form-control"
                                            disabled>
                                        <!-- Hidden input to store directors value -->
                                        <input type="hidden" id="directorsHidden" name="directorsHidden">
                                        <div class="row p-4" id="directorsDisplay">
                                            <?php

                                            $directorsIds = explode(',', $data_name['director']);

                                            $directors_query = "SELECT * FROM tbl_director WHERE id_director IN (" . implode(',', $directorsIds) . ")";
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
                                        <label for="">Movie Title</label>
                                        <input class="form-control" name="judul" id="judul"
                                            value="<?= $resultMovies['judul']; ?>" required>
                                        <input type="hidden" id="titleHidden" name="titleHidden"
                                            value="<?= $resultMovies['permalink']; ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Category</label>
                                        <select class="form-control" aria-label="Select Jenis" name="jenis">
                                            <option value="Movies">Movie</option>
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
                                        $genre_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'");
                                        $data_name = mysqli_fetch_assoc($genre_sel_equals);
                                        // Creates an array containing pre-existing genres
                                        $selectedGenres = explode(',', $data_name['genre']);
                                        ?>
                                        <select class="form-control select2" multiple="multiple" name="genre[]"
                                            id="genre-movies" required>
                                            <?php
                                            while ($data = mysqli_fetch_array($genre_sel)) {
                                                // Check if the current genre id is in the selectedGenres array
                                                $selected = in_array($data['genre_id'], $selectedGenres) ? 'selected' : '';
                                                ?>
                                                <option value="<?= $data['genre_id']; ?>" <?= $selected; ?>>
                                                    <?= $data['nama_genre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Select Playback Quality</label>
                                        <?php
                                        include 'include/koneksi.php';

                                        $playback_que = "SELECT * FROM tbl_kualitas";
                                        $playback_sel = mysqli_query($conn, $playback_que);
                                        $playback_sel_equals = mysqli_query($conn, "SELECT * FROM tbl_movies WHERE id_movies = '" . $id . "'");
                                        $playback_data = mysqli_fetch_assoc($playback_sel_equals);
                                        ?>
                                        <select name="kualitas" class="form-control" required>
                                            <?php while ($data = mysqli_fetch_assoc($playback_sel)) {
                                                if ($data['id_kualitas'] == $playback_data['kualitas']) { ?>
                                                    <option selected="<?= $playback_data['kualitas']; ?>"
                                                        value="<?= $data['id_kualitas']; ?>">
                                                        <?= $data['nama_kualitas']; ?>
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="<?= $data['id_kualitas']; ?>">
                                                        <?= $data['nama_kualitas']; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Poster (if TMDB doesn't have)</label>
                                        <input class="form-control" type="file" id="formFile" name="photo"
                                            accept="image/*" disabled>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="actors">Actor/Actress</label>
                                        <input type="text" id="actors" name="actors"
                                            placeholder="Enter actor separated by comma" class="form-control" disabled>
                                        <!-- Hidden input to store actors values -->
                                        <input type="hidden" id="actorsHidden" name="actorsHidden">
                                        <div class="row p-4" id="actorsDisplay">
                                            <?php
                                            // Getting tags from 'tags' column in 'tbl_movies' table
                                            $actorIds = explode(',', $data_name['aktor']);

                                            // Query to get tag name based on ID from table 'tbl_tags'
                                            $actor_query = "SELECT * FROM tbl_aktor WHERE id_aktor IN (" . implode(',', $actorIds) . ")";
                                            $actor_result = mysqli_query($conn, $actor_query);

                                            $first = true; // Marker to determine whether this is the first tag or not
                                            
                                            // Displays the tag name
                                            while ($actor_row = mysqli_fetch_assoc($actor_result)) {
                                                // If it's not the first tag, add a comma before displaying the tag
                                                if (!$first) {
                                                    echo ', ';
                                                }
                                                echo '<span class="actor-item">' . $actor_row['nama_aktor'] . '</span>';
                                                $first = false; // Set the tag to false after the first tag is displayed
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Release Date</label>
                                        <input type="date" class="form-control" id="tahun_rilis" name="tahun_rilis"
                                            value="<?= $resultMovies['tahun_rilis']; ?>">
                                        <!-- The option element will be filled in by JavaScript -->
                                        </input>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Ratings</label>
                                        <input type="text" class="form-control" id="rating" name="rating"
                                            pattern="[0-9]+([\.][0-9]+)?" title="Enter a number or decimal value"
                                            value="<?= $resultMovies['rating']; ?>">
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="tags">Tags</label>
                                        <input type="text" id="tags" name="tags"
                                            placeholder="Enter tags separated by comma" class="form-control" disabled>
                                        <!-- Hidden input to store tag values -->
                                        <input type="hidden" id="tagsHidden" name="tagsHidden">
                                        <div class="row p-4" id="tagsDisplay">
                                            <?php
                                            // Getting tags from 'tags' column in 'tbl_movies' table
                                            $tagIds = explode(',', $data_name['tags']);

                                            // Query to get tag name based on ID from table 'tbl_tags'
                                            $tag_query = "SELECT * FROM tbl_tag WHERE id IN (" . implode(',', $tagIds) . ")";
                                            $tag_result = mysqli_query($conn, $tag_query);

                                            $first = true; // Marker to determine whether this is the first tag or not
                                            
                                            // Menampilkan nama tag
                                            while ($tag_row = mysqli_fetch_assoc($tag_result)) {
                                                // If it's not the first tag, add a comma before displaying the tag
                                                if (!$first) {
                                                    echo ', ';
                                                }
                                                echo '<span class="tag-item">' . $tag_row['tag'] . '</span>';
                                                $first = false; // Set the tag to false after the first tag is displayed
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
                                                // Check if the current genre id is in the selectedGenres array
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
                                            value="<?= $resultMovies['duration']; ?>" required>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Status</label>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label
                                                class="btn btn-success <?= ($resultMovies['status'] == 1) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="active" autocomplete="off"
                                                    <?= ($resultMovies['status'] == 1) ? 'checked' : ''; ?>>
                                                Active
                                            </label>
                                            <label
                                                class="btn btn-warning <?= ($resultMovies['status'] == 3) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="upcoming" autocomplete="off"
                                                    <?= ($resultMovies['status'] == 3) ? 'checked' : ''; ?>>
                                                Upcoming
                                            </label>
                                            <label
                                                class="btn btn-danger <?= ($resultMovies['status'] == 0) ? 'active' : ''; ?>">
                                                <input type="radio" name="status" id="inactive" autocomplete="off"
                                                    <?= ($resultMovies['status'] == 0) ? 'checked' : ''; ?>>
                                                Inactive
                                            </label>
                                        </div>
                                        <!-- Hidden input to store the status value to be sent -->
                                        <input type="hidden" name="statusInput" id="statusInput"
                                            value="<?= $resultMovies['status']; ?>">
                                    </div>


                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Overview</label>
                                        <textarea name="overview" id="overview" cols="30" rows="12" class="form-control"
                                            required><?= stripslashes($resultMovies['overview']); ?></textarea>
                                    </div>


                                    <div class="col-lg-6">
                                        <label for="">The trailer will appear here</label>
                                        <!-- The trailer URL will be filled in here -->
                                        <div id="trailer">
                                            <iframe src="<?= $resultMovies['trailer_url']; ?>" frameborder="0"
                                                class="embed-respomsive-item" width="560" height="300"></iframe>
                                        </div>
                                    </div>
                                    <input type="hidden" id="trailer-url" name="trailer-url"
                                        value="<?= $resultMovies['trailer_url']; ?>">

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
        // Handle changes to radio buttons
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
        // Function for managing input tags, actors, and directors
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

        // Call the function to manage input tags
        setupInput('tags', 'tagsHidden', 'tagsDisplay', tags);

        // Call the function to manage input actors
        setupInput('actors', 'actorsHidden', 'actorsDisplay', actors);

        // Call the function to manage input directors
        setupInput('directors', 'directorsHidden', 'directorsDisplay', directors);
    </script>

    <script>

        function convertToPermalink(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Menghapus karakter non-alfanumerik kecuali spasi dan tanda hubung
                .trim()
                .replace(/\s+/g, '-') // Mengganti satu atau lebih spasi dengan tanda hubung
                .replace(/-+/g, '-'); // Mengganti tanda hubung ganda dengan satu tanda hubung
        }

        function updatePermalink() {
            const judulInput = document.getElementById('judul');
            const titleHiddenInput = document.getElementById('titleHidden');
            titleHiddenInput.value = convertToPermalink(judulInput.value);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const judulInput = document.getElementById('judul');
            judulInput.addEventListener('input', updatePermalink);
            judulInput.addEventListener('change', updatePermalink);
        });

        // Example function to programmatically set the value of the 'judul' input
        function setJudulValue(value) {
            const judulInput = document.getElementById('judul');
            judulInput.value = value;
            updatePermalink(); // Ensure the hidden input is updated
        }

        function crawlTMDB() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = JSON.parse(this.responseText);
                    var apiKey = response.api_key_tmdb;

                    var tmdbMovieId = document.getElementById('tmdbMovieIdInput').value;

                    var apiUrl = 'https://api.themoviedb.org/3/movie/';

                    fetch(apiUrl + tmdbMovieId + '?api_key=' + apiKey)
                        .then(response => response.json())
                        .then(data => {
                            // Fill out the form with data obtained from the TMDb API
                            document.getElementById('judul').value = data.original_title || '';
                            updatePermalink();
                            document.getElementById('tahun_rilis').value = data.release_date || '';
                            document.getElementById('rating').value = data.vote_average || '';

                            // Retrieves the genre name of each TMDb genre object
                            const genresTmdb = data.genres.map(genre => genre.name);

                            // Gets the genre dropdown option
                            const genreDropdown = document.getElementById('genre-movies');

                            // Loop through each genre dropdown option
                            for (let i = 0; i < genreDropdown.options.length; i++) {
                                // If the genre name in the dropdown is in the TMDb genre data, mark it as selected
                                if (genresTmdb.includes(genreDropdown.options[i].text)) {
                                    genreDropdown.options[i].selected = true;
                                }
                            }

                            // Call the trigger('change') method to update the Select2 plugin display
                            $(genreDropdown).trigger('change');

                            // Takes the name of the production company from each production company object
                            const productionCompanies = data.production_companies.map(company => company.name);
                            // Combines production company names into one string
                            const productionCompaniesString = productionCompanies.join(', ');
                            // Fill out the production companies form
                            document.getElementById('productionCompanies').value = productionCompaniesString || '';

                            const countriesTmdb = data.production_countries.map(country => country.name);

                            const countryDropdown = document.getElementById('country');

                            for (let i = 0; i < countryDropdown.options.length; i++) {
                                if (countriesTmdb.includes(countryDropdown.options[i].text)) {
                                    countryDropdown.options[i].selected = true;
                                }
                            }

                            $(countryDropdown).trigger('change');

                            // Populates the input with ID 'overview' with the overview value from the TMDb API response
                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            // Set the src attribute of the img element to display the poster image from the TMDb API response
                            const posterPath = data.poster_path;
                            const posterUrl = posterPath ? 'https://image.tmdb.org/t/p/w500' + posterPath : 'https://t4.ftcdn.net/jpg/02/12/52/91/360_F_212529193_YRhcQCaJB9ugv5dFzqK25Uo9Ivm7B9Ca.jpg';
                            document.getElementById('posterPreview').src = posterUrl;
                            document.getElementById('posterPreview3').src = posterUrl;


                            document.getElementById('posterPreview2').value = posterUrl || '';

                            document.getElementById('posterPreview3').value = posterUrl || '';

                            // Retrieves the duration of the movie (duration) from the TMDb API response and fills the element with the ID 'duration'
                            const duration = data.runtime;
                            document.getElementById('duration').value = duration ? duration + ' minutes' : 'Duration not available';

                            fetch(apiUrl + tmdbMovieId + '/keywords?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(keywordsData => {

                                    if (keywordsData.keywords && keywordsData.keywords.length > 0) {

                                        const keywords = keywordsData.keywords.map(keyword => keyword.name);

                                        const tagsDisplay = document.getElementById('tagsDisplay');
                                        tagsDisplay.innerHTML = '';

                                        keywords.forEach((keyword, index) => {
                                            const tagElement = document.createElement('span');
                                            tagElement.textContent = keyword;
                                            tagsDisplay.appendChild(tagElement);

                                            if (index < keywords.length - 1) {
                                                tagsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        document.getElementById('tagsHidden').value = keywords.join(',');

                                        $('#tagsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Keywords data not found');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while retrieving keyword data.');
                                });

                            fetch(apiUrl + tmdbMovieId + '/videos?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.results && data.results.length > 0) {
                                        const trailerUrl = `https://www.youtube.com/embed/${data.results[0].key}`;

                                        const iframe = document.createElement('iframe');
                                        iframe.setAttribute('class', 'embed-responsive-item');
                                        iframe.setAttribute('src', trailerUrl);
                                        iframe.setAttribute('width', '560');
                                        iframe.setAttribute('height', '300');
                                        iframe.setAttribute('frameborder', '0');
                                        iframe.setAttribute('allowfullscreen', '');

                                        const trailerDiv = document.getElementById('trailer');

                                        trailerDiv.innerHTML = '';

                                        trailerDiv.appendChild(iframe);

                                        const hiddenTrailerInput = document.getElementById('trailer-url');

                                        hiddenTrailerInput.value = trailerUrl;
                                    } else {
                                        console.log('Trailer not available');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while retrieving the trailer.');
                                });

                            // Take credits cast
                            fetch(apiUrl + tmdbMovieId + '/credits?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(creditsData => {
                                    if (creditsData.cast && creditsData.cast.length > 0) {

                                        const actors = creditsData.cast.map(actor => actor.name);

                                        const actorsDisplay = document.getElementById('actorsDisplay');
                                        actorsDisplay.innerHTML = '';

                                        actors.forEach((actor, index) => {
                                            const actorElement = document.createElement('span');
                                            actorElement.textContent = actor;
                                            actorsDisplay.appendChild(actorElement);

                                            if (index < actors.length - 1) {
                                                actorsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        document.getElementById('actorsHidden').value = actors.join(',');

                                        $('#actorsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Actor data not found');
                                    }

                                    if (creditsData.crew && creditsData.crew.length > 0) {

                                        const directors = creditsData.crew.filter(member => member.job === 'Director').map(director => director.name);

                                        const directorsDisplay = document.getElementById('directorsDisplay');
                                        directorsDisplay.innerHTML = '';

                                        directors.forEach((director, index) => {
                                            const directorElement = document.createElement('span');
                                            directorElement.textContent = director;
                                            directorsDisplay.appendChild(directorElement);

                                            if (index < directors.length - 1) {
                                                directorsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        document.getElementById('directorsHidden').value = directors.join(',');

                                        $('#directorsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Director data not found');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while retrieving cast and director data.');
                                });


                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while retrieving data.');
                        });
                }
            };
            xhr.open("GET", "api/get_api_key.php", true);
            xhr.send();

        }

    </script>



</body>

</html>