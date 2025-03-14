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
                    <h1 class="h3 mb-2 text-gray-800">Add Movie</h1>
                    <p class="mb-4">Here you can add movies. Fill in the details in each field provided in the form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Add Movie</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/add-movies"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';
                                $genre_que_movies = "SELECT * FROM tbl_genre";
                                $genre_sel_movies = mysqli_query($conn, $genre_que_movies);

                                $genre_que_list = "SELECT m.genre, g.genre_id, g.nama_genre FROM tbl_movies m INNER JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, m.genre) > 0";
                                $genre_sel_list = mysqli_query($conn, $genre_que_list);

                                $kualitas_que = "SELECT * FROM tbl_kualitas";
                                $kualitas_sel = mysqli_query($conn, $kualitas_que);

                                $aktor_que_movies = "SELECT * FROM tbl_aktor";
                                $aktor_sel_movies = mysqli_query($conn, $aktor_que_movies);

                                $director_que_movies = "SELECT * FROM tbl_director";
                                $director_sel_movies = mysqli_query($conn, $director_que_movies);

                                $country_que_movies = "SELECT * FROM tbl_countries";
                                $country_sel_movies = mysqli_query($conn, $country_que_movies);

                                ?>

                                <div class="col-md-12">
                                    <div class="fb-profile-block">
                                        <div class="fb-profile-block-thumb cover-container">
                                            <img src="https://placehold.co/1607x400" id="posterPreview3"
                                                alt="Poster Preview"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                            <input type="hidden" name="photo3" id="posterPreview3">
                                            <div class="profile-img">
                                                <a href="#">
                                                    <img src="https://placehold.co/150x200" id="posterPreview"
                                                        alt="Poster Preview">
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
                                                id="tmdbMovieIdInput" placeholder="Insert TMDB's Movie ID">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="crawlBtn"
                                                    onclick="crawlTMDB()">Crawl</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="directors">Film Directors</label>
                                        <input type="text" id="directors" name="directors"
                                            placeholder="Enter directors separated by comma" class="form-control">
                                        <!-- Hidden input to store directors value -->
                                        <input type="hidden" id="directorsHidden" name="directorsHidden">
                                        <div class="row p-4" id="directorsDisplay"></div>
                                    </div>

                                </div>
                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Movie Title</label>
                                        <input class="form-control" name="judul" id="judul" required>
                                        <input type="hidden" id="titleHidden" name="titleHidden">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Category</label>
                                        <select class="form-control" aria-label="Select Category" name="jenis">
                                            <option value="Movies">Movie</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Choose Genre</label>
                                        <select class="form-control select2" multiple="multiple" name="genre[]"
                                            id="genre-movies">
                                            <?php while ($genre = mysqli_fetch_array($genre_sel_movies)) { ?>
                                                <option value="<?= $genre['genre_id']; ?>">
                                                    <?= $genre['nama_genre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Select Playback Quality</label>
                                        <select name="kualitas" class="form-control" required>
                                            <?php while ($data = mysqli_fetch_assoc($kualitas_sel)) {
                                                if ($data['id_kualitas'] == $data_name['id_kualitas']) { ?>
                                                    <option selected="<?= $data_name['id_kualitas']; ?>"
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
                                            accept="image/*">
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="actors">Actor/Actress</label>
                                        <input type="text" id="actors" name="actors"
                                            placeholder="Enter actor separated by comma" class="form-control">
                                        <!-- Hidden input to store actors values -->
                                        <input type="hidden" id="actorsHidden" name="actorsHidden">
                                        <div class="row p-4" id="actorsDisplay"></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Release Date</label>
                                        <input type="date" class="form-control" id="tahun_rilis" name="tahun_rilis">
                                        <!-- The option element will be filled in by JavaScript -->
                                        </input>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Ratings</label>
                                        <input type="text" class="form-control" id="rating" name="rating"
                                            pattern="[0-9]+([\.][0-9]+)?" title="Enter a number or decimal value">
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="tags">Tags</label>
                                        <input type="text" id="tags" name="tags"
                                            placeholder="Enter tags separated by comma" class="form-control">
                                        <!-- Hidden input to store tag values -->
                                        <input type="hidden" id="tagsHidden" name="tagsHidden">
                                        <div class="row p-4" id="tagsDisplay"></div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Country</label>
                                        <select multiple="multiple" id="country" name="country[]"
                                            class="form-control select2" required>
                                            <?php while ($country = mysqli_fetch_array($country_sel_movies)) { ?>
                                                <option value="<?= $country['country_id']; ?>">
                                                    <?= $country['country_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Production</label>
                                        <input type="text" class="form-control" name="production"
                                            id="productionCompanies">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Duration</label>
                                        <input type="text" class="form-control" name="duration" id="duration" required>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Status</label>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-success active">
                                                <input type="radio" name="status" id="active" autocomplete="off"> Active
                                            </label>
                                            <label class="btn btn-warning">
                                                <input type="radio" name="status" id="upcoming" autocomplete="off">
                                                Upcoming
                                            </label>
                                            <label class="btn btn-danger">
                                                <input type="radio" name="status" id="inactive" autocomplete="off">
                                                Inactive
                                            </label>
                                        </div>
                                        <!-- Hidden input to store the status value to be sent -->
                                        <input type="hidden" name="statusInput" id="statusInput" value="1">
                                    </div>

                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Overview</label>
                                        <textarea name="overview" id="overview" cols="30" rows="12" class="form-control"
                                            required></textarea>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">The trailer will appear here</label>
                                        <!-- The trailer URL will be filled in here -->
                                        <div id="trailer"></div>
                                    </div>
                                    <input type="hidden" id="trailer-url" name="trailer-url">

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

    <?php include(ROOT_PATH . 'include/top.php'); ?>
    <?php include(ROOT_PATH . 'include/logout_components.php'); ?>
    <?php include(ROOT_PATH . 'include/components.php'); ?>
    <?php include(ROOT_PATH . 'include/addition-select.php'); ?>

    <script>

        document.querySelectorAll('input[name="status"]').forEach(function (radio) {
            radio.addEventListener('change', function () {

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

        function setupInput(inputId, hiddenInputId, displayId, dataArray) {
            var input = document.getElementById(inputId);
            var hiddenInput = document.getElementById(hiddenInputId);
            var data = [];

            input.addEventListener('keyup', function (event) {
                if (event.key === ',' || event.keyCode === 13) {
                    var item = this.value.trim();
                    if (item !== '') {
                        data.push(item);
                        this.value = '';
                        updateDisplay();
                        updateHiddenInput();
                    }
                }
            });

            function updateDisplay() {
                var display = document.getElementById(displayId);
                display.innerHTML = '';

                data.forEach(function (item, index) {
                    var element = document.createElement('span');
                    element.textContent = item;
                    display.appendChild(element);
                    if (index !== data.length - 1) {

                        display.appendChild(document.createTextNode(' '));
                    }
                });
            }

            function updateHiddenInput() {
                hiddenInput.value = data.join('');
            }
        }

        setupInput('tags', 'tagsHidden', 'tagsDisplay', tags);

        setupInput('actors', 'actorsHidden', 'actorsDisplay', actors);

        setupInput('directors', 'directorsHidden', 'directorsDisplay', directors);
    </script>

    <script>

        function convertToPermalink(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
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

        function setJudulValue(value) {
            const judulInput = document.getElementById('judul');
            judulInput.value = value;
            updatePermalink();
        }

        function crawlTMDB() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = JSON.parse(this.responseText);
                    var apiKey = response.api_key_tmdb;

                    var tmdbMovieId = document.getElementById('tmdbMovieIdInput').value;

                    var apiUrl = 'https://api.themoviedb.org/3/movie/';

                    fetch(apiUrl + tmdbMovieId, {
                        headers: {
                            'Authorization': 'Bearer ' + apiKey
                        }
                    })
                        .then(response => response.json())
                        .then(data => {

                            document.getElementById('judul').value = data.original_title || '';
                            updatePermalink();
                            document.getElementById('tahun_rilis').value = data.release_date || '';
                            document.getElementById('rating').value = data.vote_average || '';

                            const genresTmdb = data.genres.map(genre => genre.name);

                            const genreDropdown = document.getElementById('genre-movies');

                            for (let i = 0; i < genreDropdown.options.length; i++) {

                                if (genresTmdb.includes(genreDropdown.options[i].text)) {
                                    genreDropdown.options[i].selected = true;
                                }
                            }

                            $(genreDropdown).trigger('change');

                            const productionCompanies = data.production_companies.map(company => company.name);

                            const productionCompaniesString = productionCompanies.join(', ');

                            document.getElementById('productionCompanies').value = productionCompaniesString || '';

                            const countriesTmdb = data.production_countries.map(country => country.name);

                            const countryDropdown = document.getElementById('country');

                            for (let i = 0; i < countryDropdown.options.length; i++) {
                                if (countriesTmdb.includes(countryDropdown.options[i].text)) {
                                    countryDropdown.options[i].selected = true;
                                }
                            }

                            $(countryDropdown).trigger('change');

                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            const posterPath = data.poster_path;
                            const posterUrl = posterPath ? 'https://image.tmdb.org/t/p/w500' + posterPath : 'https://t4.ftcdn.net/jpg/02/12/52/91/360_F_212529193_YRhcQCaJB9ugv5dFzqK25Uo9Ivm7B9Ca.jpg';
                            document.getElementById('posterPreview').src = posterUrl;
                            document.getElementById('posterPreview3').src = posterUrl;


                            document.getElementById('posterPreview2').value = posterUrl || '';

                            document.getElementById('posterPreview3').value = posterUrl || '';

                            const duration = data.runtime;
                            document.getElementById('duration').value = duration ? duration + ' minutes' : 'Duration not available';

                            fetch(apiUrl + tmdbMovieId + '/keywords', {
                                headers: {
                                    'Authorization': 'Bearer ' + apiKey
                                }
                            })
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
                                    alert('Terjadi kesalahan saat mengambil data keywords.');
                                });

                            fetch(apiUrl + tmdbMovieId + '/videos', {
                                headers: {
                                    'Authorization': 'Bearer ' + apiKey
                                }
                            })
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

                            fetch(apiUrl + tmdbMovieId + '/credits', {
                                headers: {
                                    'Authorization': 'Bearer ' + apiKey
                                }
                            })
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