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
<button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Form ini digunakan untuk membuat template penilaian dengan soal yang tidak di input di bank soal" style="float:right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                </svg>
            </button>
<h3 class="px-4 pb-4 fw-bold text-center">Download Template Penilaian</h3>
</div>
<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('ExcelTanpaSoal')}}" enctype="multipart/form-data">
                @csrf
                <div id="templateTanpaSoal">
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
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Soal<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="soal[0]" id="pertanyaan" required></textarea>
                                <div class="form-text mb-3"></div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Bobot Soal<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="1" max="100" name="bobot[0]" id="bobot" required></input>
                                <div class="form-text mb-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Download">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group tooltipa">
                            <button type="button" name="add" id="tambahTugas" class="btn btn-sm btn-primary" >Tambah Field</button>
                            <span class="tooltiptext">Tambah soal</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var i = <?= json_encode($s) ?>;
    $(document).ready(function(){
        i++;
        $("#tambahTugas").click(function() {
            $("#templateTanpaSoal").append('<div class="row"><div class="col-5"><textarea class="form-control filter" name="soal[' + i +
                ']"></textarea><div class="form-text mb-3"></div></div><div class="col-5"><input class="form-control filter" name="bobot[' + i +
                ']"></input><div class="form-text mb-3"></div></div></div>'
            );
            i++
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
@endsection