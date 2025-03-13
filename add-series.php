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
                    <h1 class="h3 mb-2 text-gray-800">Add Series</h1>
                    <p class="mb-4">Here you can add series. Fill in the details in each field provided in the form
                        below.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-4">
                            <h5 class="m-0 font-weight-bold text-primary">Add Series</h5>
                        </div>
                        <form class="form mt-4" role="form" method="POST" action="action/add-series"
                            enctype="multipart/form-data">
                            <div class="form-group">

                                <?php include 'include/koneksi.php';
                                $genre_que_movies = "SELECT * FROM tbl_genre";
                                $genre_sel_movies = mysqli_query($conn, $genre_que_movies);

                                $genre_que_list = "SELECT m.genre, g.genre_id, g.nama_genre FROM tbl_movies m INNER JOIN tbl_genre g ON FIND_IN_SET(g.genre_id, m.genre) > 0";
                                $genre_sel_list = mysqli_query($conn, $genre_que_list);

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
                                            <img src="https://via.placeholder.com/400" id="posterPreview3"
                                                alt="Poster Preview"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                            <input type="hidden" name="photo3" id="posterPreview3">
                                            <div class="profile-img">
                                                <a href="#">
                                                    <img src="https://via.placeholder.com/200" id="posterPreview"
                                                        alt="Poster Preview">
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
                                                id="tmdbSeriesIdInput" placeholder="Insert TMDB's Series ID">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="crawlBtn"
                                                    onclick="crawlTMDB()">Crawl</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="directors">Directors</label>
                                        <input type="text" id="directors" name="directors"
                                            placeholder="Enter directors separated by comma" class="form-control">
                                        <!-- Hidden input to store directors value -->
                                        <input type="hidden" id="directorsHidden" name="directorsHidden">
                                        <div class="row p-4" id="directorsDisplay"></div>
                                    </div>

                                </div>
                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Series Title</label>
                                        <input class="form-control" name="judul" id="judul" required>
                                        <input type="hidden" id="titleHidden" name="titleHidden">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Category</label>
                                        <select class="form-control" aria-label="Select Category" name="jenis">
                                            <option value="TV Series">TV Series</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-6">
                                        <label for="">Choose Genre</label>
                                        <select class="form-control select2" multiple="multiple" name="genre[]"
                                            id="genre-series">
                                            <?php while ($genre = mysqli_fetch_array($genre_sel_movies)) { ?>
                                                <option value="<?= $genre['genre_id']; ?>">
                                                    <?= $genre['nama_genre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Poster (if TMDB doesn't have)</label>
                                        <input class="form-control" type="file" id="formFile" name="photo"
                                            accept="image/*">
                                    </div>
                                </div>

                                <div class="row p-4">
                                    <div class="col-lg-4">
                                        <label for="">Number of Episodes</label>
                                        <input type="number" name="episodes" id="episodes" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Number of Seasons</label>
                                        <input type="number" name="seasons" id="seasons" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Series Status</label>
                                        <input type="text" name="series_status" id="series_status" class="form-control">
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

    <?php include (ROOT_PATH . 'include/top.php'); ?>
    <?php include (ROOT_PATH . 'include/logout_components.php'); ?>
    <?php include (ROOT_PATH . 'include/components.php'); ?>
    <?php include (ROOT_PATH . 'include/addition-select.php'); ?>

    <script>
        // Handle changes to radio buttons
        document.querySelectorAll('input[name="status"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                // Set hidden input value based on selected radio button
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

                    var tmdbSeriesId = document.getElementById('tmdbSeriesIdInput').value;

                    var apiUrl = 'https://api.themoviedb.org/3/tv/';

                    fetch(apiUrl + tmdbSeriesId + '?api_key=' + apiKey)
                        .then(response => response.json())
                        .then(data => {
                            // Fill out the form with data obtained from the TMDb API
                            document.getElementById('judul').value = data.name || '';
                            updatePermalink();
                            document.getElementById('tahun_rilis').value = data.first_air_date || '';
                            document.getElementById('rating').value = data.vote_average || '';

                            // Retrieves the genre name of each TMDb genre object
                            const genresTmdb = data.genres.map(genre => genre.name);

                            // Gets the genre dropdown option
                            const genreDropdown = document.getElementById('genre-series');

                            // Loop through each genre dropdown option
                            for (let i = 0; i < genreDropdown.options.length; i++) {
                                // If the genre name in the dropdown is in the TMDb genre data, mark it as selected
                                if (genresTmdb.includes(genreDropdown.options[i].text)) {
                                    genreDropdown.options[i].selected = true;
                                }
                            }

                            // Call the trigger('change') method to update the Select2 plugin display
                            $(genreDropdown).trigger('change');

                            const countriesTmdb = data.production_countries.map(country => country.name);

                            const countryDropdown = document.getElementById('country');

                            for (let i = 0; i < countryDropdown.options.length; i++) {
                                if (countriesTmdb.includes(countryDropdown.options[i].text)) {
                                    countryDropdown.options[i].selected = true;
                                }
                            }

                            $(countryDropdown).trigger('change');

                            // Takes the average duration of each episode
                            const episodeRuntime = data.episode_run_time.length > 0 ? data.episode_run_time[0] : 'Unknown';
                            document.getElementById('duration').value = episodeRuntime + ' minutes per episode';

                            // Takes the number of episodes and seasons
                            const numberOfEpisodes = data.number_of_episodes || 'Unknown';
                            const numberOfSeasons = data.number_of_seasons || 'Unknown';
                            const seriesStatus = data.status || 'Unknown';

                            document.getElementById('episodes').value = numberOfEpisodes;
                            document.getElementById('seasons').value = numberOfSeasons;
                            document.getElementById('series_status').value = seriesStatus;

                            // Populates the input with ID 'overview' with the overview value from the TMDb API response
                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            // Set the src attribute of the img element to display the poster image from the TMDb API response
                            const posterPath = data.poster_path;
                            const posterUrl = posterPath ? 'https://image.tmdb.org/t/p/w500' + posterPath : 'https://t4.ftcdn.net/jpg/02/12/52/91/360_F_212529193_YRhcQCaJB9ugv5dFzqK25Uo9Ivm7B9Ca.jpg';
                            document.getElementById('posterPreview').src = posterUrl;

                            document.getElementById('posterPreview2').src = posterUrl || '';

                            document.getElementById('posterPreview3').src = posterUrl || '';

                            document.getElementById('posterPreview').value = posterUrl;

                            document.getElementById('posterPreview2').value = posterUrl || '';

                            document.getElementById('posterPreview3').value = posterUrl || '';

                            // Retrieves TV networks from the TMDb API response and populates the networks form
                            const networks = data.networks.map(network => network.name);
                            const networksString = networks.join(', ');
                            document.getElementById('productionCompanies').value = networksString || '';


                            // Populates the input with ID 'overview' with the overview value from the TMDb API response
                            document.getElementById('overview').value = data.overview || 'Overview not available';

                            if (data.created_by && data.created_by.length > 0) {
                                // Retrieves the name of the creator from the TMDb created_by data
                                const creators = data.created_by.map(creator => creator.name);

                                // Show creators in the #creatorsDisplay element
                                const directorsDisplay = document.getElementById('directorsDisplay');
                                directorsDisplay.innerHTML = ''; // Cleaning up previous content

                                creators.forEach((creator, index) => {
                                    const creatorElement = document.createElement('span');
                                    creatorElement.textContent = creator;
                                    directorsDisplay.appendChild(creatorElement);

                                    // Add a comma after each creator's name except the last one
                                    if (index < creators.length - 1) {
                                        directorsDisplay.appendChild(document.createTextNode(', '));
                                    }
                                });

                                // Populates the hidden input value #creatorsHidden with creator names separated by commas
                                document.getElementById('directorsHidden').value = creators.join(',');

                                // Call the trigger('change') method after the creator name is displayed
                                $('#directorsHidden').trigger('change');
                            } else {
                                console.error('Error: Creator data not found');
                            }

                            fetch(apiUrl + tmdbSeriesId + '/keywords?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(keywordsData => {
                                    // Make sure keyword data is available
                                    if (keywordsData.results && keywordsData.results.length > 0) {
                                        // Retrieves keywords from the TMDb API response
                                        const keywords = keywordsData.results.map(keyword => keyword.name);

                                        // Displays keywords in the #tagsDisplay element
                                        const tagsDisplay = document.getElementById('tagsDisplay');

                                        // Clears the previous content of the Display tag element
                                        tagsDisplay.innerHTML = '';

                                        // Add each keyword to the Display tag element
                                        keywords.forEach((keyword, index) => {
                                            const tagElement = document.createElement('span');
                                            tagElement.textContent = keyword; // Add keyword names to the span element
                                            tagsDisplay.appendChild(tagElement);

                                            // Add a comma after each keyword except the last one
                                            if (index < keywords.length - 1) {
                                                tagsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        // Fills the hidden input value #tagsHidden with keywords separated by commas
                                        document.getElementById('tagsHidden').value = keywords.join(',');

                                        // Call the trigger('change') method after the keyword is displayed
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

                            // Take credit cast and crew
                            fetch(apiUrl + tmdbSeriesId + '/credits?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(creditsData => {
                                    if (creditsData.cast && creditsData.cast.length > 0) {
                                        // Retrieves the actor name of each TMDb cast object
                                        const actors = creditsData.cast.map(actor => actor.name);

                                        // Displays actors in the #actorsDisplay element
                                        const actorsDisplay = document.getElementById('actorsDisplay');
                                        actorsDisplay.innerHTML = ''; // Cleaning up previous content

                                        actors.forEach((actor, index) => {
                                            const actorElement = document.createElement('span');
                                            actorElement.textContent = actor;
                                            actorsDisplay.appendChild(actorElement);

                                            // Add a comma after each actor's name except the last one
                                            if (index < actors.length - 1) {
                                                actorsDisplay.appendChild(document.createTextNode(', '));
                                            }
                                        });

                                        // Populates the hidden input value #actorsHidden with actor names separated by commas
                                        document.getElementById('actorsHidden').value = actors.join(',');

                                        // Call the trigger('change') method after the actor name is displayed
                                        $('#actorsHidden').trigger('change');
                                    } else {
                                        console.error('Error: Actor data not found');
                                    }

                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while retrieving cast and director data.');
                                });

                            // Take the trailer
                            fetch(apiUrl + tmdbSeriesId + '/videos?api_key=' + apiKey)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.results && data.results.length > 0) {
                                        // Take the URL of the first trailer from the response
                                        const trailerUrl = `https://www.youtube.com/embed/${data.results[0].key}`;

                                        // Create an iframe element to display the trailer
                                        const iframe = document.createElement('iframe');
                                        iframe.setAttribute('class', 'embed-responsive-item');
                                        iframe.setAttribute('src', trailerUrl);
                                        iframe.setAttribute('width', '560');
                                        iframe.setAttribute('height', '300');
                                        iframe.setAttribute('frameborder', '0');
                                        iframe.setAttribute('allowfullscreen', '');

                                        // Get div element with ID 'trailer'
                                        const trailerDiv = document.getElementById('trailer');

                                        // Empty the contents of the div element if it already has content
                                        trailerDiv.innerHTML = '';

                                        // Insert iframe element into 'trailer' div element
                                        trailerDiv.appendChild(iframe);

                                        // Take a hidden input element to store the trailer URL
                                        const hiddenTrailerInput = document.getElementById('trailer-url');

                                        // Set the trailer URL value to the hidden input
                                        hiddenTrailerInput.value = trailerUrl;
                                    } else {
                                        console.log('Trailer not available');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while retrieving the trailer.');
                                });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while retrieving the data.');
                        });
                }
            };
            xhr.open("GET", "api/get_api_key.php", true);
            xhr.send();
        }
    </script>



</body>

</html>