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

<h3 class="px-4 pb-4 fw-bold text-center">Halaman Visualisasi Mahasiswa Angkatan</h3>
<h6 class="px-4 pb-4 fw-bold text-center">Silahkan masukan Angkatan</h6>

<div class="form-group stretch-card" id="tugas">
    <div class="card">
         <div class="card-body">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Hasil dari form ini adalah visualisasi CPL per angkatan mahasiswa. Mohon pilih universitas diikuti dengan memilih prodi serta angkatan untuk menampilkan hasil visualisasi CPL per angkatan mahasiswa serta informasi ketercapaian CPL per angkatan mahasiswa" 
                style="float:right;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                        </svg>
                </button>
                <h6 class="pb-4 ">Per Angkatan</h6>
                    <form id="hasilVisual" method="POST" action="hasilvisual-mahasiswaAngkatan" enctype="multipart/form-data">
                        @csrf
                        <div id="dynamicAddRemoveTugas">
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
                                        <label>Prodi<span class="text-danger">*</span></label>
                                        <select id="prodiForm" class="form-control" name="prodi">
                                            <!-- Opsi ditampilkan pake ajax -->
                                        </select>
                                        <label>Angkatan<span class="text-danger">*</span></label>
                                        <select id="angkatanForm" class="form-control" name="angkatan">
                                            <!-- Opsi ditampilkan pake ajax -->    
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                   <img id="universitas-img" src="" class="img img-responsive" style="max-width: 35%; margin-left: 100px; display: none;"/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
                   </form>
            </div>
     </div>
</div>


{{-- Hasil visualisasi --}}
<div id="visualContainer"  style="display:none;" >
    <div class="form-group stretch-card" id="tugas">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    {{-- kiri --}}
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">CPL Batch</h5>
                                        <canvas id="radarChartAngkatan"></canvas>
                                        <h6 class="mt-4" style="text-align:justify">Summary :</h6>
                                        <p id="summary"></p>
                                        <h6 class="keterangan mt-3">Descriptions :</h6>
                                        {{-- Dari AJAX --}}
                                        <div id="labelContainer">

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            {{-- KIRI --}}
                            <div class="col-sm-6">
                                {{-- CARD 3 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Questions <span class="text-lowercase">with</span> the Lowest Average CPL :</h5>
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
                            {{-- Kanan --}}
                            <div class="col-sm-6">
                                {{-- Card 1 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Data :</h5>
                                        <ol>
                                            <li><h6 id="angkatanData" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="prodi" class="card-subtitle mt-2 text-black"></h6></li>
                                            <li><h6 id="universitasData" class="card-subtitle mt-2 text-black"></h6></li>
                                        </ol>
                                        <h6 class="fw-bold">CPL Calculation Courses :</h6>
                                        <ol id="courseList">
                                           {{-- Dari ajax --}}
                                        </ol>
                                    </div>
                                </div>
                                {{-- Card 2 --}}
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Hasil dari form ini adalah visualisasi CPMK per angkatan mahasiswa. Mohon pilih mata kuliah untuk menampilkan hasil visualisasi CPMK per angkatan mahasiswa serta informasi ketercapaian CPMK per angkatan mahasiswa" 
                                        style="float:right;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                                                </svg>
                                        </button>
                                        <h5 class="card-title">Lihat CPMK Angkatan</h5>
                                        <form method="POST" id="hasilvisualcpmk-angkatan" action="hasilvisualcpmk-angkatan" enctype="multipart/form-data">
                                            @csrf
                                            <div id="dynamicAddRemoveTugas">
                                                <div class="form-group row">
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label>Course<span class="text-danger">*</span></label>
                                                            <select class="form-control" name="course" id="courseSelect">
                                                                <!-- Dari ajax -->
                                                            </select>
                                                            <input type="text" name="angkatan" class="visually-hidden" value="">
                                                            <input type="text" name="prodi" class="visually-hidden" id="prodiHidden" value="">
                                                            <input type="text" name="universitasImg" class="visually-hidden" value="">
                                                            <input type="text" name="universitasCPMK" class="visually-hidden" value="">
                                                            <input type="text" name="allNpm" class="visually-hidden" value="">
                                                            <input type="text" name="allAngkatan" class="visually-hidden" value="">    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
                                        </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- AWAL FORM --}}
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
                url: "{{ route('getProdiByUniversitas') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {universitas: universitas},
                success:function(data){
                    console.log(data);
                    $('#prodiForm').html(data);
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
        
        $('#prodiForm').on('change', function(){
            var prodi = $(this).val(); 
            var universitas = $('#universitas').val(); // Ambil nilai dari elemen #universitas
            $.ajax({
                url: "{{ route('getAngkatanByProdiUniversitas') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {
                    prodi: prodi,
                    universitas: universitas 
                },
                success:function(data){
                    console.log(data);
                    $('#angkatanForm').html(data);
                }
            });
        });
    });
</script>

{{-- BUAT ANGKATAN YG CPMKNYA ADA DI MUTUS --}}
<script>

    $(document).ready(function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $('#courseSelect').on('change', function(){
            var course = $(this).val();
            var prodi = $('#prodiHidden').val();
            var universitas = $('#universitas').val();  
            console.log(universitas);
            $.ajax({
                url: "{{ route('getAllAngkatanCpmk') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {course: course, prodi: prodi, universitas : universitas},
                dataType: 'json',
                success:function(response){
                    console.log('AJAX suksess mantul!');
                    var allAngkatan = response.result.allAngkatan;
                    console.log(response);
                    $('input[name="allAngkatan"]').val(JSON.stringify(allAngkatan));
                    
                }
            });
        });
    });
</script>


<script>
$(document).ready(function () {
    $('#hasilVisual').on('submit', function (event) {
        event.preventDefault();
        // console.log('Form submitted!');
        var universitas = $('#universitas').val();
        var angkatan = $('#angkatanForm').val();
        var imgSrc = $('#universitas-img').attr('src');
        // console.log(imgSrc);
        var formData = $(this).serialize();
        if (imgSrc) {
            formData += '&imgSrc=' + encodeURIComponent(imgSrc);
        }
        // Kirim permintaan AJAX
        $.ajax({
            url: "{{ route('hasilvisual-mahasiswaAngkatan') }}",
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log('AJAX success!');
                console.log(response);

                // Ambil data dari respons
                // var nama = response.result.nama;
                // var npm = response.result.npm;
                var angkatan = response.result.angkatan;
                var prodi = response.result.prodi;
                var imgSrc = response.result.imgSrc;
                // console.log("ini:"+imgSrc);
                var universitas = response.result.universitas;
                var courseArray = response.result.courseArray;
                var judulCpl = response.result.judulCpl;
                var labelCpl = response.result.labelCpl;
                var soalTerendah = response.result.soalTerendah;
                var averageCplAngkatan = response.result.averageCplAngkatan;
                var minCplAngkatan = response.result.minCplAngkatan;
                var maxCplAngkatan = response.result.maxCplAngkatan;
                var allNpm = response.result.allNpm;


                // SUMMARY
                var minSummary = response.result.minSummary;
                var maxSummary = response.result.maxSummary;
                var minAvgSummary = response.result.minAvgSummary;
                var maxAvgSummary = response.result.maxAvgSummary;
                // console.log(minSummary);
                var min_codeAvg = Object.keys(minSummary)[0];
                var min_value = minSummary[min_codeAvg];
                var max_code = Object.keys(maxSummary)[0];
                var max_value = maxSummary[max_code];
                var min_codeAvg_avg = Object.keys(minAvgSummary)[0];
                var min_valueAvg = minAvgSummary[min_codeAvg_avg];
                var max_codeAvg = Object.keys(maxAvgSummary)[0];
                var max_valueAvg = maxAvgSummary[max_codeAvg];

                // console.log(min_codeAvg);

                // SUMMARY
                var summaryParagraph = document.getElementById("summary");
                summaryParagraph.innerHTML = "Based on the generated CPL, the " + angkatan + " batch has:<br>" +
                    "- The lowest value at: " + generateSummary(minSummary) + "<br>" +
                    "- The highest value at: " + generateSummary(maxSummary) + "<br>" +
                    "- The lowest average value at: " + generateSummary(minAvgSummary) + "<br>" +
                    "- The highest average value at: " + generateSummary(maxAvgSummary);

                function generateSummary(summary) {
                    var summaryText = "";
                    for (var key in summary) {
                        summaryText += key + " with a value of " + summary[key] + ", ";
                    }
                    return summaryText.slice(0, -2); // Remove the last comma and space
                }

                // // Tampilkan data di halaman jika perlu
                // $('#nama').text('Nama: ' + nama);
                // $('#npmData').text('NPM: ' + npm);
                $('#angkatanData').text('Angkatan: ' + angkatan);
                $('#prodi').text('Prodi: ' + prodi);
                $('#universitasData').text('Universitas: ' + universitas);

                // // set di input hidden
                $('input[name="angkatan"]').val(angkatan);
                $('input[name="prodi"]').val(prodi);
                $('input[name="universitasImg"]').val(imgSrc);
                $('input[name="universitasCPMK"]').val(universitas);
                $('input[name="allNpm"]').val(JSON.stringify(allNpm));
                // // isi label container
                $('#labelContainer').html('');
                    for (var i = 0; i < labelCpl.length; i++) {
                        var labelCplHtml = '<p id="labelCpl' + (i + 1) + '">' +
                            (i + 1) + '. ' + labelCpl[i] + ': ' + judulCpl[i] +'</p>';
                        $('#labelContainer').append(labelCplHtml);
                    }
                // // Isi tabel
                var tableBody = $('#soalTerendahTable tbody');
                tableBody.html('');
                for (var i = 0; i < soalTerendah.length; i++) {
                        var rowHtml = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + soalTerendah[i].namaCourse + '</td>' +
                            '<td>' + soalTerendah[i].Jenis + '</td>' ;
                            
                        if (soalTerendah[i].idSoal) {
                        rowHtml += '<td><a href="http://skripsiilkom.my.id/dosen/soal/cetakSoal/' + soalTerendah[i].idSoal + '" target="_blank">' + soalTerendah[i].soal + '</a></td>';
                        } else {
                            rowHtml += '<td>' + soalTerendah[i].soal + '</td>';
                        }

                    rowHtml += '</tr>';
                    tableBody.append(rowHtml);
                    }
                
                // //course list ajax 
                var courseList = $('#courseList');
                courseList.html(''); 
                for (var course in courseArray) {
                    var listItem = '<li>' + course + ' - ' + courseArray[course] + '</li>';
                    courseList.append(listItem);
                }    

                // // Untuk di form optional untuk pilih cpmk
                // // Menambahkan opsi ke elemen <select>
                var selectElement = $('#courseSelect');
                selectElement.html('');

                var defaultOptionHtml = '<option value="">Select Course</option>';
                selectElement.append(defaultOptionHtml);  

                for (var course in courseArray) {
                    var optionHtml = '<option value="' + course + '-' +courseArray[course]+'">' + course + ' - ' + courseArray[course] + '</option>';
                    selectElement.append(optionHtml);
                }

                // Tampilkan konten
                $('#visualContainer').show();
                
                // AJAX dalam skrip Chart.js
                
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
                                    scale: {
                                        r: {
                                        max: 100,
                                        min: 0
                                    
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

     $(document).ready(function () {
        // Validasi CPL
        $('#hasilVisual').on('submit', function (event) {
            // Validasi select "course"
            var angkatanForm = $('#angkatanForm').val();
            var prodiForm = $('#prodiForm').val();

            if (!angkatanForm || !prodiForm) {
                alert('Mohon pilih/isi field yang kosong!');
                event.preventDefault(); 
            }
        });

        // Validasi CPMK
        $('#hasilvisualcpmk-angkatan').on('submit', function (event) {
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