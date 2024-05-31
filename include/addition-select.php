<script>
    $(document).ready(function () {
        $('#genre-movies, #selected-genre').select2();
    });
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>

<!-- <script>
    // Mendapatkan elemen select tahun
    var selectTahun = document.getElementById("tahun_rilis");
    
    // Mendapatkan tahun saat ini
    var tahunSaatIni = new Date().getFullYear();
    
    // Loop untuk mengisi daftar tahun mulai dari tahunSaatIni hingga 50 tahun ke belakang
    for (var tahun = tahunSaatIni; tahun >= tahunSaatIni - 50; tahun--) {
        // Buat elemen option baru
        var option = document.createElement("option");
        // Set nilai dan teks opsi sesuai dengan tahun
        option.value = tahun;
        option.textContent = tahun;
        // Tambahkan opsi ke elemen select
        selectTahun.appendChild(option);
    }
</script> -->

<!-- Include Select2 library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>