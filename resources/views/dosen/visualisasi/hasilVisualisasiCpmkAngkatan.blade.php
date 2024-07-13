@extends('dosen.template')
@section('content')
<?php $s = 0; ?>

@if (session()->has('failed'))
    <div class="alert alert-danger" role="alert" id="box">
        <div>{{ session('failed') }}</div>
    </div>
@elseif (session()->has('success'))
    <div class="alert greenAdd" role="alert" id="box">
        <div>{{ session('success') }}</div>
    </div>
@endif

<h3 class="px-4 pb-4 fw-bold text-center">Hasil Visualisasi CPMK {{$course}} Angkatan  {{$angkatan}}</h3>
{{-- {{dd($allAngkatan)}} --}}
{{-- card buat ganti ANGKATAN --}}
<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Form ini digunakan untuk mengecek angkatan lainnya. silahkan pilih angkatan yang ingin ditampilkan ketercapaian CPMK" 
                style="float:right;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                        </svg>
            </button>
            <h6 class="pb-4 ">Cek Angkatan Lainnya :</h6>
            <form id="visualCpmkAngkatan" method="POST" action="hasilvisualcpmk-angkatan" enctype="multipart/form-data">
                @csrf            
                <div class="form-group row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Angkatan<span class="text-danger">*</span></label>
                            {{-- @dd($allAngkatan) --}}
                            <select id="angkatan" class="form-control" name="angkatan">
                                <option selected="true" value="" disabled selected>Silahkan Pilih Angkatan</option>
                                @foreach($allAngkatan as $a)
                                    <option value="{{ $a->angkatan }}">{{ $a->angkatan }}</option>
                                @endforeach
                                <input type="hidden" name="allNpm"  class="visually-hidden">
                                <input type="hidden" name="allAngkatan" value="{{ json_encode($allAngkatan) }}">
                                <input type="text" name="course" class="visually-hidden" value="{{$course}}">
                                <input type="text" name="prodi" class="visually-hidden" value="{{$prodi}}">
                                <input type="text" name="universitasCPMK" class="visually-hidden" value="{{$universitas}}">
                                <input type="text" name="universitasImg" class="visually-hidden" value="{{$universitasImg}}">
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary mt-3" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
                    </div>
                    <div class="col-6">
                        <img id="universitas-img" src="{{ asset($universitasImg) }}" class="img img-responsive" style="max-width: 35%; margin-left: 100px;"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="mt-4">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">CPMK Batch</h5>
                                    <canvas id="radarChartAngkatan"></canvas>
                                    <h6 class="mt-4" style="text-align:justify">Summary :</h6>
                                    <p>
                                        Based on the generated CPMK, the {{ $angkatan }} batch has:
                                        <br>
                                        - The lowest value at:
                                        @foreach ($minSummary as $key => $value)
                                            CPMK {{ sprintf('%02d', $key) }} with a value of {{ $value }},
                                        @endforeach
                                        <br>
                                        - The highest value at:
                                        @foreach ($maxSummary as $key => $value)
                                            CPMK {{ sprintf('%02d', $key) }} with a value of {{ $value }},
                                        @endforeach
                                        <br>
                                        - The lowest average value at:
                                        @foreach ($minAvgSummary as $key => $value)
                                            CPMK {{ sprintf('%02d', $key) }} with a value of {{ $value }},
                                        @endforeach
                                        <br>
                                        - The highest average value at:
                                        @foreach ($maxAvgSummary as $key => $value)
                                            CPMK {{ sprintf('%02d', $key) }} with a value of {{ $value }}.
                                        @endforeach
                                    </p>
                                    <h6 class="keterangan mt-3">Descriptions :</h6>
                                    @foreach ($labelCpmk as $index => $label)
                                        <p>{{ $loop->iteration }}.{{ $label }}: {{ $judulCpmk[$index] }}</p>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-5">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Data :</h5>
                                    <ol>
                                        <li><h6 class="card-subtitle mt-2 text-black">Angkatan : {{ $angkatan }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Prodi : {{ $prodi }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Universitas : {{ $universitas }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Course : {{ $course }}</h6></li>
                                    </ol>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-body">
                                                <h6 class="fw-bold detail">Questions of CPMK Batch with the Lowest Average :</h6>
                                                <div class="table-responsive" >
                                                    <table id="tableDetail" class="table table-bordered table-hover mt-2 ">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Types of Assessment</th>
                                                                <th>Questions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($soalTerendah as $index => $data)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $data['Jenis'] }}</td>
                                                                    <td>
                                                                        @if(isset($data['idSoal']))
                                                                            <a href="http://skripsiilkom.my.id/dosen/soal/cetakSoal/{{ $data['idSoal'] }}" target="_blank">{{ $data['soal'] }}</a>
                                                                        @else
                                                                            {{ $data['soal'] }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
</div>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}



{{-- Validasi form pilih angkatan lain form --}}
<script>
$(document).ready(function () {
        $('#visualCpmkAngkatan').on('submit', function (event) {
            
            var angkatan = $('#angkatan').val();
            if (!angkatan) {
                alert('Mohon pilih/isi field yang kosong!');
                event.preventDefault(); 
            }
        });
    });

</script>


{{-- Pilih angkatan lain --}}
<script>
    $(document).ready(function(){
        $('#angkatan').on('change', function(){
            var angkatan = $(this).val();
            var prodi = $('input[name=prodi]').val();   
            // console.log("Berhasil: " + angkatan + "prodi: " + prodi);
            $.ajax({
                url: "{{ route('getAllNpmByAngkatan') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {angkatan:angkatan, prodi:prodi},
                dataType: 'json',
                success:function(response){
                    // console.log("SUKSES AJAX INI");
                     var allNpm  = response.result.allNpm;
                    var allNpmString = JSON.stringify(allNpm);
                  
                    $('input[name=allNpm]').val(allNpmString);
                }
            });
        });
    });
</script>

<script>
    
     // RADAR SEBELAH KANAN
    var canvas = document.getElementById('radarChartAngkatan');
    var ctx = canvas.getContext('2d');
    var radarChart = new Chart(ctx, {
                                type: 'radar',
                                data: {
                                    labels:  <?php echo json_encode($labelCpmk); ?>,
                                    datasets: [{
                                        label: 'Average CPMK',
                                        data: <?php echo json_encode($averageCpmkAngkatan); ?>,
                                        backgroundColor: 'rgba(192, 110, 75, 0)',
                                        borderColor: 'rgba(192, 110, 75, 0.3)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(192, 110, 75, 0.4)'
                                    },
                                    {
                                        label: 'Min CPMK',
                                        data: <?php echo json_encode($minCpmkAngkatan); ?>,
                                        backgroundColor: 'rgba(126, 0, 0, 0.2)',
                                        borderColor: 'rgba(216, 33, 33, 0.27)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(126, 0, 0, 0.4)'
                                    },
                                    {
                                        label: 'Max CPMK',
                                        data: <?php echo json_encode($maxCpmkAngkatan); ?>,
                                        backgroundColor: 'rgba(177, 255, 184, 0)',
                                        borderColor: 'rgba(33, 216, 95, 0.39)',
                                        borderWidth: 3,
                                        pointBackgroundColor: 'rgba(0, 0, 0, 1)'
                                    }
                                ]
                                },
                                
                            });
</script>


@endsection