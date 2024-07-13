@extends('dosen.template')
@section('content')
<?php $s = 0; ?>

@if (session()->has('failed'))
    <div class="alert alert-danger" role="alert" id="box">
        <div>{{session('failed')}}</div>
    </div>
@elseif (session()->has('success'))
    <div class="alert greenAdd" role="alert" id="box">
        <div>{{session('success')}}</div>
    </div>
@endif

<style>
    .table-responsive table td.wrap-content {
        white-space: normal;
    }
</style>

<h3 class="px-4 pb-4 fw-bold text-center">Halaman Visualisasi Mahasiswa</h3>
<h6 class="px-4 pb-4 fw-bold text-center">Silahkan masukan data mahasiswa</h6>

<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
           <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Hasil dari form ini adalah visualisasi CPL per mahasiswa. Mohon pilih universitas diikuti dengan memilih angkatan serta npm untuk menampilkan hasil visualisasi CPL mahasiswa serta informasi ketercapaian CPL" style="float:right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                </svg>
           </button>
            <h6 class="pb-4 ">Per Mahasiswa</h6>
            <form id="hasilVisual" method="POST" action="hasilvisual-mahasiswa" enctype="multipart/form-data">
                        @csrf            
                        <div class="form-group row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Universitas<span class="text-danger">*</span></label>
                                    <select id="universitas" class="form-control" name="universitas">
                                        <option selected="true" value="" disabled>Silahkan Pilih Terlebih Dahulu</option>
                                        @foreach($universitas as $a)
                                            <option value="{{$a->universitas}}" data-img="{{ asset($a->img) }}">{{$a->universitas}}</option>
                                        @endforeach
                                    </select>
                                    <label>Angkatan<span class="text-danger mt-2">*</span></label>
                                    <select id="angkatan" class="form-control" name="angkatan">
                                        <!-- Opsi ditampilkan pake ajax -->
                                    </select>
                                    <label>NPM<span class="text-danger mt-2">*</span></label>
                                    <select id="npm" class="form-control" name="npm">
                                        <!-- Opsi ditampilkan pake ajax -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <img id="universitas-img" src="" class="img img-responsive" style="max-width: 35%; margin-left: 100px; display: none;"/>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
             </form>
        </div>
    </div>
</div>

{{-- Ini div yang mau dihilangkan jika belum ada data --}}
{{-- Hasil visualisasi --}}
<div id="visualContainer" style="display:none;">
    <div class="form-group stretch-card" id="tugas">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    {{-- kiri --}}
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- CARD 01 --}}
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Persentase Pencapaian CPL (%)</h5>
                                        <canvas id="radarChartCapaianCpl"></canvas>
                                        {{-- TODO: --}}
                                        {{-- <h6 class="mt-4" style="text-align:justify">Summary :</h6>
                                        <p id="summary"></p> --}}
                                        <h6 class="keterangan mt-4">Mata Kuliah Lulus :</h6>
                                        {{-- Dari AJAX --}}
                                        <div class="card">
                                            <div id="labelMkLulus" class="card-body overflow-auto p-3" style="max-height: 200px; overflow: auto;"> 
                                            </div>
                                        </div>
                                        <h6 class="keterangan mt-4">Pemetaan CPL :</h6>
                                        <div class="card">
                                            <div class="card-body" >
                                               <label>Pilih CPL <span class="text-danger mt-2">*</span></label>
                                                <select id="selectPemetaanCpl" class="form-control" name="selectPemetaanCpl">
                                                    
                                                </select>
                                                <div id="kontenPemetaanCpl" class="overflow-auto p-3" style="max-height: 200px;  overflow: auto;">

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- CARD 1 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">CPL</h5>
                                        <canvas id="radarChart"></canvas>
                                        {{-- TODO: --}}
                                        {{-- <h6 class="mt-4" style="text-align:justify">Summary :</h6>
                                        <p id="summary"></p> --}}
                                        <h6 class="keterangan mt-3">Descriptions :</h6>
                                        {{-- Dari AJAX --}}
                                        <div id="labelContainer">

                                        </div>
                                    </div>
                                </div>
                                {{-- CARD 2 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Mapping <span class="text-lowercase">the</span>  CPL batch <span class="text-lowercase">of</span>  students</h5>
                                        <canvas id="radarChartAngkatan"></canvas>
                                        
                                    </div>
                                </div>
                            </div>
                            {{-- Kiri bawah --}}
                            {{-- CARD 3 --}}
                            <div class="col-sm-6">

                                {{-- Card 7 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Hasil dari form ini adalah visualisasi CPMK per mahasiswa. Mohon pilih mata kuliah untuk menampilkan visualisasi CPMK mahasiswa serta informasi ketercapaian CPMK" style="float:right;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                                            </svg>
                                        </button>
                                        <h5 class="card-title">Lihat CPMK</h5>
                                        <form id="hasilvisualcpmk-mahasiswa" method="POST" action="hasilvisualcpmk-mahasiswa" enctype="multipart/form-data">
                                            @csrf
                                            <div id="dynamicAddRemoveTugas">
                                                <div class="form-group row">
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="course">Course<span class="text-danger">*</span></label>
                                                            <select class="form-control" name="course" id="courseSelect">
                                                                <!-- Dari ajax -->
                                                            </select>
                                                            @error('course')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                            <input type="text" name="npm" class="visually-hidden" value="">
                                                            <input type="text" name="nama" class="visually-hidden" value="">
                                                            <input type="text" name="angkatan" class="visually-hidden" value="">
                                                            <input type="text" name="prodi" class="visually-hidden" value="">
                                                            <input type="text" name="universitasImg" class="visually-hidden" value="">
                                                            <input type="text" name="universitasCPMK" class="visually-hidden" value="">
                                                            <input type="text" name="allNpm" class="visually-hidden" value="">     
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
                                        </form>
                                    </div>
                                </div>
                                {{-- CARD 3 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Questions <span class="text-lowercase">with</span> the Lowest CPL :</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="soalTerendahTable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Course Name</th>
                                                        <th>Types of Assessment</th>
                                                        <th>Questions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Td dari ajax --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                {{-- Card 4 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Data :</h5>
                                        <ol>
                                            <li><h6 id="nama" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="npmData" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="angkatanData" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="prodi" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="universitasData" class="card-subtitle mt-2 text-black"></h6></li>
                                        </ol>
                                        <h6 class="fw-bold">CPL Calculation Courses :</h6>
                                        <ol id="courseList">
                                           
                                        </ol>
                                    </div>
                                </div>

                                {{-- Card 5 --}}
                                <div class="card mt-3">
                                   <div class="card-body">
                                       <h5 class="card-title">Career Mapping Graph Based <span class="text-lowercase">on</span> CPL :</h5>
                                       <canvas id="profilChart"></canvas>
                                   </div>
                               </div>
                                 {{-- Card 6 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Career Mapping Details Based <span class="text-lowercase">on</span> CPL :</h5>
                                        <div class="table-responsive">
                                            <table id="hasilProfilTable" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Profile Name</th>
                                                        <th>Description</th>
                                                        <th>CPL</th>
                                                        <th>Profile Weight</th>
                                                        <th>CPL Result</th>
                                                        <th>Profile Weight*The CPL Result</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Batas div yang mau dihilangkan jika belum ada data --}}

<!-- Script jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

{{-- script select option --}}
<script>

    $(document).ready(function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        $('#universitas').on('change', function(){
            var universitas = $(this).val(); 

            var imgSrc = $(this).find(':selected').data('img');
            console.log('Image source:', imgSrc);
            // Update image based on selected universitas
            if (imgSrc) {
                $('#universitas-img').attr('src', imgSrc).show();
            } else {
                $('#universitas-img').hide();
            }


            $.ajax({
                url: "{{ route('getAngkatanByUniversitas') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {universitas: universitas},
                success:function(data){
                     console.log(data);
                    $('#angkatan').html(data);
                }
            });
        });
    });
</script>

{{-- MILIH PEMETAAN CPL --}}
<script>
$(document).ready(function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        $('#selectPemetaanCpl').on('change', function(){
            var idCpl = $(this).val(); 
            // console.log("ini:"+idCpl);
            $.ajax({
                url: "{{ route('getPemetaanCpl') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {
                    idCpl: idCpl,
                },
                success:function(data){
                    console.log(data);
                    var content = '<ol>';
                    data.forEach(function(item) {
                        content += '<li>' + item.kode_mk + '-' + item.nama + '</li>';
                    });
                    content += '</ol>';
                    $('#kontenPemetaanCpl').html(content);
                    }
            });
        });
    });

</script>

<script>
    $(document).ready(function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        $('#angkatan').on('change', function(){
            var angkatan = $(this).val(); 
            var universitas = $('#universitas').val(); // Ambil nilai dari elemen #universitas
            $.ajax({
                url: "{{ route('getNpmByAngkatan') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {
                    angkatan: angkatan,
                    universitas: universitas 
                },
                success:function(data){
                    console.log(data);
                    $('#npm').html(data);
                }
            });
        });
    });
</script>

{{-- script hide konten --}}
<script>
$(document).ready(function () {
    $('#hasilVisual').on('submit', function (event) {
        event.preventDefault();
        // console.log('Form submitted!');
        var universitas = $('#universitas').val();
        var angkatan = $('#angkatan').val();
        var imgSrc = $('#universitas-img').attr('src');
        var formData = $(this).serialize();
        // console.log(formData);
        if (imgSrc) {
            formData += '&imgSrc=' + encodeURIComponent(imgSrc);
        }
        // Kirim permintaan AJAX
        $.ajax({
            url: "{{ route('hasilvisual-mahasiswa') }}",
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log('AJAX success!');
                console.log(response);

                // Ambil data dari respons
                var nama = response.result.nama;
                var npm = response.result.npm;
                var angkatan = response.result.angkatan;
                var prodi = response.result.prodi;
                var imgSrc = response.result.imgSrc;
                var universitas = response.result.universitas;
                var countDataCpl = response.result.countDataCpl;
                var courseArray = response.result.courseArray;
                var courseCpmkRestrict = response.result.courseCpmkRestrict;
                var judulCpl = response.result.judulCpl;
                var labelCpl = response.result.labelCpl;
                var labelCplAngkatan = response.result.labelCplAngkatan;
                var soalTerendah = response.result.soalTerendah;
                var averageCplAngkatan = response.result.averageCplAngkatan;
                var minCplAngkatan = response.result.minCplAngkatan;
                var maxCplAngkatan = response.result.maxCplAngkatan;
                var allNpm = response.result.allNpm;
                var hasilFinalProfil = response.result.hasilFinalProfil;
                var chartDataProfil = response.result.chartDataProfil;

                // ABIS SEMHAS
                var nilaiMkLulus = response.result.nilaiMkLulus;
                var nilaiMkTidakLulus = response.result.nilaiMkTidakLulus;
                var persentaseTotalCplCapaian = response.result.persentaseTotalCplCapaian;
                var cplResultsAll = response.result.cplResultsAll;
                // console.log(persentaseLulusCpl);
                // var minAvgSummaryCpl = response.result.minAvgSummaryCpl;
                // var maxAvgSummaryCpl = response.result.maxAvgSummaryCpl;
                // var minSummaryCplWithCode = response.result.minSummaryCplWithCode;
                // var maxSummaryCplWithCode = response.result.maxSummaryCplWithCode;
                // console.log("ini:"+minSummaryCplWithCode);

                // SUMMARY
            //   var minKey = Object.keys(JSON.parse(minSummaryCplWithCode))[0];
            // var minValue = JSON.parse(minSummaryCplWithCode)[minKey];
            // var maxKey = Object.keys(JSON.parse(maxSummaryCplWithCode))[0];
            // var maxValue = JSON.parse(maxSummaryCplWithCode)[maxKey];

            // Menyusun kalimat berdasarkan template yang diberikan
            // var summaryText = "Berdasarkan CPL yang dihasilkan, mahasiswa " + nama + " memiliki nilai terendah pada " + minKey + " dengan nilai " + minValue + " dan nilai tertinggi pada " + maxKey + " dengan nilai " + maxValue + ". Rata-rata angkatan " + nama + " untuk nilai terendah adalah " + JSON.stringify(minAvgSummaryCpl) + ", sementara untuk nilai tertinggi adalah " + JSON.stringify(maxAvgSummaryCpl) + ".";

            // Memasukkan kalimat ke dalam elemen <p id="summary"></p>
            // document.getElementById("summary").innerText = summaryText;
                // Hasil profil
                 $('#hasilProfilTable tbody').empty();
                // Iterasi hasilFinalProfil
                var nomor = 1; 
               $.each(hasilFinalProfil, function (key, profil) {
                var totalRows = profil.CPLs.length;
                var row = '<tr>';
                row += '<td rowspan="' + totalRows + '">' + nomor + '</td>';
                row += '<td rowspan="' + totalRows + '">' + profil.NamaProfil + '</td>';
                row += '<td rowspan="' + totalRows + '" class="wrap-content">' + profil.Deskripsi + '</td>'; // Tambahkan kelas wrap-content di sini
            
                $.each(profil.CPLs, function (index, cpl) {
                    
                    row += '<td>' + cpl.CPL + '</td>';
                    row += '<td>' + cpl.Bobot + '</td>';
                    row += '<td>' + cpl.HasilCPL + '</td>';
                    row += '<td>' + (cpl.Bobot * cpl.HasilCPL).toFixed(3) + '</td>';

                    if (index === 0) {
                        row += '<td rowspan="' + totalRows + '">' + profil.TotalAkhir.toFixed(3) + '</td>';
                    }

                    row += '</tr>';

                    if (index < totalRows - 1) {
                        row += '<tr>';
                    }
                });
                nomor++;
                $('#hasilProfilTable tbody').append(row);
            });
                            // Tampilkan data di halaman 
                $('#nama').text('Nama: ' + nama);
                $('#npmData').text('NPM: ' + npm);
                $('#angkatanData').text('Angkatan: ' + angkatan);
                $('#prodi').text('Prodi: ' + prodi);
                $('#universitasData').text('Universitas: ' + universitas);

                // set di input hidden
                $('input[name="npm"]').val(npm);
                $('input[name="nama"]').val(nama);
                $('input[name="angkatan"]').val(angkatan);
                $('input[name="prodi"]').val(prodi);
                $('input[name="universitasImg"]').val(imgSrc);
                $('input[name="universitasCPMK"]').val(universitas);
                $('input[name="allNpm"]').val(JSON.stringify(allNpm));

                // isi label capaian cpl
                $('#labelMkLulus').html('');

                var indeksMK = 1;
                $.each(nilaiMkLulus, function(course, nilai) {
                    $('#labelMkLulus').append('<p>' + indeksMK + '. ' + course + '-' + nilai[1] +': '+'<strong>' + nilai[0] +'</strong>' +'</p>');
                    indeksMK += 1;
                });


                // isi option pemetaan cpl
                var $select = $('#selectPemetaanCpl');
                $select.empty(); 
                $.each(cplResultsAll, function(index, cpl) {
                    $select.append($('<option>', {
                        value: cpl.id,
                        text: cpl.kode
                    }));
                });

                // isi label container
                $('#labelContainer').html('');
                    for (var i = 0; i < labelCpl.length; i++) {
                        var labelCplHtml = '<p id="labelCpl' + (i + 1) + '">' +
                            (i + 1) + '. ' + labelCpl[i] + ': ' + judulCpl[i] +'</p>';
                        $('#labelContainer').append(labelCplHtml);
                    }
                // Isi tabel
                var tableBody = $('#soalTerendahTable tbody');
                tableBody.html('');
                for (var i = 0; i < soalTerendah.length; i++) {
                    var rowHtml = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + soalTerendah[i].namaCourse + '</td>' +
                        '<td>' + soalTerendah[i].Jenis + '</td>';

                    // Lakukan pengecekan apakah idSoal ada atau tidak
                    if (soalTerendah[i].idSoal) {
                        rowHtml += '<td><a href="http://skripsiilkom.my.id/dosen/soal/cetakSoal/' + soalTerendah[i].idSoal + '" target="_blank">' + soalTerendah[i].soal + '</a></td>';
                    } else {
                        rowHtml += '<td>' + soalTerendah[i].soal + '</td>';
                    }

                    rowHtml += '</tr>';
                    tableBody.append(rowHtml);
                }
                
                //course list ajax 
                var courseList = $('#courseList');
                courseList.html(''); 
                for (var course in courseArray) {
                    var listItem = '<li>' + course + ' - ' + courseArray[course] + '</li>';
                    courseList.append(listItem);
                }    

                // Untuk di form optional untuk pilih cpmk
                // Menambahkan opsi ke elemen <select>
                var selectElement = $('#courseSelect');
                selectElement.html('');

                var defaultOptionHtml = '<option value="">Select Course</option>';
                selectElement.append(defaultOptionHtml); 

                for (var course in courseCpmkRestrict) {
                    var optionHtml = '<option value="' + course + '-' +courseCpmkRestrict[course]+'">' + course + ' - ' + courseCpmkRestrict[course] + '</option>';
                    selectElement.append(optionHtml);
                }

                // Tampilkan konten
                $('#visualContainer').show();
                
                // CHART PERSENTASE CAPAIAN CPL
               var labelsCapaianCpl = persentaseTotalCplCapaian.map(item => item.kode_cpl);
               var dataCapaianCpl = persentaseTotalCplCapaian.map(item => item.persentase);
                
                // console.log(labelCapaianCpl);
                var canvas = document.getElementById('radarChartCapaianCpl');
                var ctx = canvas.getContext('2d');
                var radarChart = new Chart(ctx, {
                            type: 'radar',
                            data: {
                                labels: labelsCapaianCpl,
                                datasets: [{
                                    label: 'CPL',
                                    data: dataCapaianCpl,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                            ]
                            },
                            options: {
                                scale: {
                                    r: {
                                       max: 100,
                                       min: 0
                                
                                    }
                                }
                            }
                        });




                // AJAX dalam skrip Chart.js
                var canvas = document.getElementById('radarChart');
                var ctx = canvas.getContext('2d');
                var radarChart = new Chart(ctx, {
                            type: 'radar',
                            data: {
                                labels: labelCpl,
                                datasets: [{
                                    label: 'CPL',
                                    data: countDataCpl,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                            ]
                            },
                            options: {
                                scale: {
                                    r: {
                                       max: 100,
                                       min: 0
                                
                                    }
                                }
                            }
                        });
                
                    // RADAR SEBELAH KANAN
                    var canvas = document.getElementById('radarChartAngkatan');
                    var ctx = canvas.getContext('2d');
                    var radarChart = new Chart(ctx, {
                                type: 'radar',
                                data: {
                                    labels: labelCpl,
                                    datasets: [{
                                        label: 'Average CPL',
                                        data: averageCplAngkatan,
                                        backgroundColor: 'rgba(192, 110, 75, 0)',
                                        borderColor: 'rgba(192, 110, 75, 0.3)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(192, 110, 75, 0.4)'
                                    },
                                    {
                                        label: 'Min CPL',
                                        data: minCplAngkatan,
                                        backgroundColor: 'rgba(126, 0, 0, 0.2)',
                                        borderColor: 'rgba(216, 33, 33, 0.27)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(126, 0, 0, 0.4)'
                                    },
                                    {
                                        label: 'Max CPL',
                                        data: maxCplAngkatan,
                                        backgroundColor: 'rgba(177, 255, 184, 0)',
                                        borderColor: 'rgba(33, 216, 95, 0.39)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(0, 0, 0, 1)'
                                    }
                                ]
                                },
                                options: {
                                    elements: {
                                        line: {
                                            color: 'black', 
                                            borderWidth: 3 
                                        }
                                    }
                                }
                            });
                                         
                    // Karir chart
                   // Mendapatkan label dan data dari chartData
                    var labels = chartDataProfil.map(function(item) {
                        return item.label;
                    });
                    var data = chartDataProfil.map(function(item) {
                        return item.data;
                    }); 
                    var backgroundColors = chartDataProfil.map(function(_, index) {
                        var colors = ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 205, 86, 0.2)', 'rgba(54, 162, 235, 0.2)'];
                        return colors[index % colors.length];
                    });
                   var ctx = document.getElementById('profilChart').getContext('2d');
                   var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Persentase',
                                data: data,
                                backgroundColor: backgroundColors,
                                borderColor: backgroundColors.map(color => color.replace('0.2', '1')),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });   





            },
            error: function (xhr, status, error) {
                console.log('AJAX error:');
                console.log(xhr.responseText);
            }
        });
    });
});
</script>

{{-- coba validasi --}}
<script>
// Validasi CPL
     $(document).ready(function () {
        $('#hasilVisual').on('submit', function (event) {
            // Validasi select "course"
            var angkatan = $('#angkatan').val();
            var npm = $('#npm').val();

            if (!angkatan || !npm) {
                alert('Mohon pilih/isi field yang kosong!');
                event.preventDefault(); 
            }
        });
    });

// Validasi CPMK
 $(document).ready(function () {
        $('#hasilvisualcpmk-mahasiswa').on('submit', function (event) {
            // Validasi select "course"
            var selectedCourse = $('#courseSelect').val();

            if (!selectedCourse) {
                alert('Mohon pilih/isi field yang kosong!');
                event.preventDefault(); 
            }
        });
    });

</script>

@endsection
