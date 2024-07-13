@extends('dosen.template')
@section('content')
<?php $s = 0; ?>

<style>
    .tooltipa {
        position: relative;
    }

    .tooltiptext {
    visibility: hidden;
    width: 160px; /* Increased width for better readability */
    background-color: rgba(0, 0, 0, 0.8); /* Use rgba for semi-transparent background */
    color: white;
    text-align: center;
    border-radius: 8px; /* Slightly increased border radius for a softer look */
    padding: 8px; /* Increased padding for better spacing */
    position: absolute;
    z-index: 1;
    bottom: 80%; /* Increased distance from the parent element */
    left: 50%;
    transform: translateX(-50%); /* Center the tooltip horizontally */
    transition: visibility 0.3s ease-in-out; /* Add smooth transition */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .tooltipa:hover .tooltiptext {
        visibility: visible;
    }
</style>

@if (session()->has('failed'))
    <div class="alert alert-danger" role="alert" id="box">
        <div>{{session('failed')}}</div>
    </div>
@elseif (session()->has('success'))
    <div class="alert greenAdd" role="alert" id="box">
        <div>{{session('success')}}</div>
    </div>
@endif
<div>
<button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Form ini digunakan untuk membuat template penilaian. Soal yang muncul akan sesuai dengan mata kuliah dan jenis yang dipilih" style="float:right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                </svg>
            </button>
<h3 class="px-4 pb-4 fw-bold text-center">Download Template Penilaian</h3>
</div>
<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('excel')}}" enctype="multipart/form-data">
                @csrf
                <div id="templatePenilaian">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label>Universitas <span class="text-danger">*</span></label>
                                <select class="form-control" name="univ" id="univ" required>
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach ($universitas as $universitas)
                                    <option value="{{$universitas->nama}}">{{$universitas->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Semester<span class="text-danger">*</span></label>
                                <select class="form-control" name="semester" id="semester" required>
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    <option value="Ganjil"></option>
                                    <option value="Genap"></option>
                                </select>
                                @error('semester')
                                    <div class="alert alert-danger">salah</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Prodi <span class="text-danger">*</span></label>
                                <select class="form-control" name="prodi" id="prodi" required>
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach ($prodi as $prodi )
                                    <option value="{{$prodi->nama}}">{{$prodi->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Nama MK <span class="text-danger">*</span></label>
                                <select class="form-control" name="kode_mk" id="kode_mk" required>
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach ($rpss as $rps)
                                    <option value="{{$rps->kode_mk}}">{{$rps->kode_mk}} - {{$rps->mk->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Jenis <span class="text-danger">*</span></label>
                                <select class="form-control" name="jenis" id="jenis" required>
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach ($komponen as $komponen)
                                    <option value="{{$komponen->jenis}}">{{$komponen->jenis}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="jumlah_soal">Jumlah Soal:</label>
                                <input type="number" class="form-control" id="jumlah_soal" min="1" value="1">
                            </div>
                            <button type="button" class="btn btn-primary mb-3" id="generateSoalBtn">Generate Dropdown Soal</button>

                            <div id="soalDropdowns"></div>
                        </div>
                    </div>
                </div>
                <!-- <div id="selectedText"></div> -->
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Download">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#generateSoalBtn').click(function() {
            var jumlahSoal = $('#jumlah_soal').val();
            var dropdownHtml = '';
                        
            for (var i = 1; i <= jumlahSoal; i++) {
                dropdownHtml += '<div class="row">' +
                                '<label>Soal<span class="text-danger">*</span></label>' +
                                '<select class="form-control pertanyaan" id="soal" name="soal[' + (i - 1) + ']" required>' +
                                '</select>' +
                                '<label>Bobot Soal<span class="text-danger">*</span></label>' +
                                '<input class="form-control" type="number" min="1" max="100" name="bobot[' + (i - 1) + ']" id="bobot" required></input>' +
                                '<div class="form-text mb-3"></div></div></div>';
            }

            $('#soalDropdowns').html(dropdownHtml);
        });
    });
</script>
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
<script>
    var currentYear = new Date().getFullYear();

    // Menambahkan tahun saat ini ke setiap opsi
    var options = document.getElementById("semester").getElementsByTagName("option");
    for (var i = 1; i < options.length; i++) {
        options[i].innerText = options[i].value + " " + currentYear;
    }
</script>
<script>
    $(document).ready(function(){
        $('#kode_mk, #jenis').on('change', function(){
            var kode_mk = $('#kode_mk').val(); 
            var jenis = $('#jenis').val(); 
            $.ajax({
                url: "{{ route('getSoalBykode_mk') }}", 
                method: 'GET',
                data: { kode_mk: kode_mk,
                        jenis : jenis
                 },
                success: function(data){
                    $('.pertanyaan').html(data);
                }
            });
        });
    });
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    var soalDropdownsContainer = document.getElementById('soalDropdowns');

    soalDropdownsContainer.addEventListener('change', function(event) {
        var select = event.target;
        if (select && select.classList.contains('pertanyaan')) {
            var selectedOption = select.options[select.selectedIndex];
            var selectedText = select.nextElementSibling; // Assuming this is where you want to show the selected text
            if (selectedText) {
                selectedText.innerHTML = '<a href="soal/cetakSoal/' + selectedOption.text + '" target="_blank">Klik untuk melihat soal</a>';
            }
        }
    });
});
</script> -->
@endsection