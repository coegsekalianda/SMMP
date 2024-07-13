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


<h3 class="px-4 pb-4 fw-bold text-center">Hasil Visualisasi CPMK {{ $completeCourseFormat }} Mahasiswa {{$nama}}</h3>

{{-- card buat ganti npm --}}
<div class="form-group stretch-card" id="tugas">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Form ini digunakan untuk mengecek mahasiswa lainnya. silahkan pilih npm mahasiswa yang ingin ditampilkan ketercapaian CPMK" 
                style="float:right;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                        </svg>
            </button>
            <h6 class="pb-4 ">Cek Mahasiswa Lainnya :</h6>
            <form id="visualCpmkMahasiswa" method="POST" action="hasilvisualcpmk-mahasiswa" enctype="multipart/form-data">
                @csrf            
                <div class="form-group row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>NPM<span class="text-danger">*</span></label>
                            <select id="npm" class="form-control" name="npm">
                                <option value="">Pilih NPM</option>
                               @foreach($allNamaNpmData as $data)
                                    <option value="{{ $data->NPM }}">{{ $data->NPM }} - {{ $data->Nama }}</option>
                                @endforeach
                                <input type="hidden" name="allNpm" value="{{ json_encode($allNpm) }}">
                                <input type="text" name="course" class="visually-hidden" value="{{$completeCourseFormat}}">
                                <input type="text" name="nama" class="visually-hidden">
                                <input type="text" name="angkatan" class="visually-hidden" value="{{$angkatan}}">
                                <input type="text" name="universitasCPMK" class="visually-hidden" value="{{$universitas}}">
                                <input type="text" name="universitasImg" class="visually-hidden" value="{{$universitasImg}}">
                                <input type="text" name="prodi" class="visually-hidden" value="{{$prodi}}">
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
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">CPMK</h5>
                                    <canvas id="radarChart"></canvas>
                                    <h6 class="keterangan">Descriptions :</h6>
                                    @foreach ($labelCpmk as $index => $label)
                                        <p>{{ $loop->iteration }}.{{ $label }}: {{ $judulCpmk[$index] }}</p>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">

                             <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Mapping <span class="text-lowercase">the</span> CPMK Batch of Student</h5>
                                        <canvas id="radarChartAngkatan"></canvas>
                                        
                                    </div>
                                </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">Data :</h5>
                                    <ol>
                                        <li><h6 class="card-subtitle mt-2 text-black">Nama : {{ $nama }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">NPM : {{ $npm }} </h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Angkatan : {{ $angkatan }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Prodi: {{ $prodi }}</h6></li>
                                        <li><h6 class="card-subtitle mt-2 text-black">Universitas: {{ $universitas }}</h6></li>
                                    </ol>
                                </div>
                            </div>


                        </div>

                        <div class="col-sm-12 mt-3">
                            <!-- Full-width Detail Card -->
                            <div class="card">
                                <div class="card-body">
                                                <h5 class="card-title">Details :</h5>
                                                <div class="table-responsive" >
                                                    <table id="tableDetail" class="table table-bordered table-hover mt-2 ">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Titles of CPMK</th>
                                                                <th style="word-wrap: break-word;">Titles of CPMK</th>
                                                                <th>Questions</th>
                                                                <th>The CPMK Results</th>
                                                                <th>The Average CPMK of the Generation</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $rowNumber = 1;
                                                        @endphp

                                                        @foreach ($cpmk as $item)
                                                            @php
                                                                $titles = explode(',', $item->DaftarJenis);
                                                                $results = explode(',', $item->DaftarSoal);
                                                                $rowCount = count($titles);
                                                            @endphp

                                                            @for ($i = 0; $i < $rowCount; $i++)
                                                                <tr>
                                                                    @if ($i === 0)
                                                                        <td rowspan="{{ $rowCount }}">{{ $rowNumber }}</td>
                                                                        <td rowspan="{{ $rowCount }}">{{ $item->judul }}</td>
                                                                    @endif
                                                                    <td>{{ $titles[$i] }}</td>
                                                                    <td>
                                                                        @php
                                                                            $resultContent = $results[$i];
                                                                        @endphp

                                                                        @if (ctype_digit($resultContent))
                                                                            <a href="http://skripsiilkom.my.id/dosen/soal/cetakSoal/{{ $resultContent }}" target="_blank">{{ "Lihat Soal" }}</a>
                                                                        @else
                                                                            {{ $resultContent }}
                                                                        @endif
                                                                    </td>
                                                                    @if ($i === 0)
                                                                        <td rowspan="{{ $rowCount }}">{{ $item->HasilCpmk }}</td>
                                                                        <td rowspan="{{ $rowCount }}">{{ $item->averageCpmkFormat }}</td>
                                                                    @endif
                                                                </tr>
                                                            @endfor

                                                            @php
                                                                $rowNumber++;
                                                            @endphp
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





{{-- Validasi form mahasiswa lainnya biar npm --}}
<script>
$(document).ready(function () {
        $('#visualCpmkMahasiswa').on('submit', function (event) {
            
            var npm = $('#npm').val();
            if (!npm) {
                alert('Mohon pilih/isi field yang kosong!');
                event.preventDefault(); 
            }
        });
    });

</script>


{{-- Pilih mahasiswa lain --}}
<script>
    $(document).ready(function(){
        $('#npm').on('change', function(){
            var npm = $(this).val(); 
            // console.log("Berhasil: " + npm);
            $.ajax({
                url: "{{ route('getNamaByNpm') }}", // route untuk kirim ke kontroler
                method: 'GET',
                data: {npm: npm},
                dataType: 'json',
                success:function(response){
                     var nama  = response.result.namaData;
                    //  console.log(nama);
                    $('input[name=nama]').val(nama);
                }
            });
        });
    });
</script>

<script>
    var ctx = document.getElementById('radarChart').getContext('2d');
    var radarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: <?php echo json_encode($labelCpmk); ?>,
            datasets: [{
                label: 'CPMK',
                data: <?php echo json_encode($countDataCpmk); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
    });


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