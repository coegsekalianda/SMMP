@extends('admin.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Soal</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Minggu ke-</th>
                            <th>Jenis Ujian</th>
                            <th>Dosen</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $kode_mk = 0;
                        foreach($soals as $no=>$soal):
                        foreach($mks as $mk):
                        if($soal->kode_mk == $mk->kode)
                        $nama_mk = $mk->nama;
                        endforeach
                        @endphp
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$soal->kode_mk}}</td>
                            <td>{{$nama_mk}}</td>
                            <td>{{$soal->minggu}}</td>
                            <td>{{$soal->jenis}}</td>
                            <td>{{$soal->dosen}}</td>
                            @if($soal->status == 'Belum')
                            <td>Menunggu validasi</td>
                            @elseif($soal->status == 'Valid')
                            <td>Soal telah tervalidasi</td>
                            @else
                            <td class="py-4">
                                <div class="me-2">Soal ditolak</div>
                            </td>
                            @endif
                            <td class="py-4 d-flex">
                                @if($soal->status == 'Valid')
                                <a href="/admin/print-soal/{{encrypt($soal->id)}}" target="_blank" type="button" class="btn btn-info btn-icon p-2 me-2">
                                    <i class="ti-download btn-icon"></i>
                                </a>
                                @elseif($soal->status == 'Tolak')
                                <button class="btn btn-warning btn-icon p-2 me-2" data-bs-toggle="modal" data-bs-target="#tolakModal{{$soal->id}}">
                                    <i class="ti-alert btn-icon"></i>
                                </button>
                                @else
                                <button class="btn btn-secondary btn-icon p-2 me-2" disabled>
                                    <i class="ti-download btn-icon"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-list btn-inverse-info btn-icon p-2" data-bs-toggle="modal" data-bs-target="#exampleModal{{$soal->id}}">
                                    <i class="ti-bar-chart-alt btn-icon"></i>
                                </button>
                                <div class="modal fade" id="exampleModal{{$soal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header" style="display: block;">
                                                <button type="button" class="btn-close row mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <h4>
                                                    Grafik CPMK pada Soal
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="main">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>CPMK</th>
                                                                            <th>Judul</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i = 1; ?>
                                                                        @foreach($cpmks as $mk=>$cpmk)
                                                                        @if($mk==$soal->kode_mk)
                                                                        @foreach($cpmk as $cp)
                                                                        <tr>
                                                                            <td class="text-center">{{$i}}</td>
                                                                            <td>{{$cp->judul}}</td>
                                                                        </tr>
                                                                        <?php $i++; ?>
                                                                        @endforeach
                                                                        @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class=" col-4">
                                                            <canvas id="barChart"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                                            <script>
                                                var i = <?= json_encode($soal->kode_mk) ?>;
                                                $(document).ready(function() {
                                                    showChart(i)
                                                })

                                                function showChart(url) {
                                                    $.ajax({
                                                        url: `/admin/soal-chart/${url}`,
                                                        type: "GET",
                                                        dataType: "json",
                                                        success: function(dt) {
                                                            console.log(dt);
                                                            var data = {
                                                                labels: dt.kode_cpmk,
                                                                datasets: [{
                                                                    data: dt.jumlah,
                                                                    backgroundColor: dt.warna,
                                                                    borderColor: dt.border,
                                                                    borderWidth: 1,
                                                                    fill: false
                                                                }]
                                                            };

                                                            var options = {
                                                                scales: {
                                                                    yAxes: [{
                                                                        ticks: {
                                                                            beginAtZero: true,
                                                                            userCallback: function(label, index, labels) {
                                                                                // when the floored value is the same as the value we have a whole number
                                                                                if (Math.floor(label) === label) {
                                                                                    return label;
                                                                                }

                                                                            },
                                                                        }
                                                                    }]
                                                                },
                                                                legend: {
                                                                    display: false
                                                                },
                                                                elements: {
                                                                    point: {
                                                                        radius: 0
                                                                    }
                                                                },
                                                            };
                                                            if ($("#barChart").length) {
                                                                var barChartCanvas = $("#barChart").get(0).getContext("2d");
                                                                // This will get the first returned node in the jQuery collection.
                                                                var barChart = new Chart(barChartCanvas, {
                                                                    type: 'bar',
                                                                    data: data,
                                                                    options: options
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@foreach($soals as $soal)
<div class="modal fade" id="tolakModal{{$soal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{$soal->komentar}}
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection