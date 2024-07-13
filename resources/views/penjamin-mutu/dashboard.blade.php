@extends('penjamin-mutu.template')
@section('content')

<style>
    .stats:hover {
        background-color: rgba(0, 123, 255, .75) !important;
        cursor: pointer;
        transform: scale(.95);
    }
</style>
{{-- {{dd(auth()->user()->otoritas);}} --}}
<div class="container d-flex justify-content-between w-100">
    <a class="card stats mx-1 w-100 bg-primary text-white text-decoration-none" href="list-soal">
        <div class="card-title m-0">
            <p class="p-4 pb-1 h3 m-0 text-white">JUMLAH SOAL</p>
        </div>
        <div class="card-body w-100">
            <p class="h1 text-end pe-3 jumlah">
                {{$soal->count()}}
            </p>
        </div>
    </a>
    <a class="card stats mx-1 w-100 bg-primary text-white text-decoration-none" href="list-soal">
        <div class=" card-title m-0">
            <p class="p-4 pb-1 h3 m-0 text-white">SOAL VALID</p>
        </div>
        <div class="card-body w-100">
            <p class="h1 text-end pe-3 jumlah">{{$valid->count()}}</p>
        </div>
    </a>
    <a class="card stats mx-1 w-100 bg-primary text-white text-decoration-none" href="list-soal">
        <div class="card-title m-0">
            <p class="p-4 pb-1 h3 m-0 text-white">SOAL DITOLAK</p>
        </div>
        <div class="card-body w-100">
            <p class="h1 text-end pe-3 jumlah">{{$tolak->count()}}</p>
        </div>
    </a>
    <a class="card stats mx-1 w-100 bg-primary text-white text-decoration-none" href="list-soal">
        <div class="card-title m-0">
            <p class="p-4 pb-1 h3 m-0 text-white">SOAL BELUM DIPERIKSA</p>
        </div>
        <div class="card-body w-100">
            <p class="h1 text-end pe-3 jumlah">{{$belum->count()}}</p>
        </div>
    </a>
</div>
{{-- <div class="container-fluid my-3">
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CPL Pengetahuan Terpakai</h4>
                    <canvas id="barChartP"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CPL Keterampilan Terpakai</h4>
                    <canvas id="barChartK"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $.ajax({
        url: '/dosen/dashboard-chart',
        type: "GET",
        dataType: "json",
        success: function(dt) {
            var dataS = {
                labels: dt.sikap,
                datasets: [{
                    label: 'Jumlah MK',
                    data: dt.jumlahs,
                    backgroundColor: dt.warnas,
                    borderColor: dt.borders,
                    borderWidth: 1,
                    fill: false
                }]
            };
            var dataU = {
                labels: dt.umum,
                datasets: [{
                    label: 'Jumlah MK',
                    data: dt.jumlahu,
                    backgroundColor: dt.warnau,
                    borderColor: dt.borderu,
                    borderWidth: 1,
                    fill: false
                }]
            };
            var dataP = {
                labels: dt.pengetahuan,
                datasets: [{
                    label: 'Jumlah MK',
                    data: dt.jumlahp,
                    backgroundColor: dt.warnap,
                    borderColor: dt.borderp,
                    borderWidth: 1,
                    fill: false
                }]
            };
            var dataK = {
                labels: dt.keterampilan,
                datasets: [{
                    label: 'Jumlah MK',
                    data: dt.jumlahk,
                    backgroundColor: dt.warnak,
                    borderColor: dt.borderk,
                    borderWidth: 1,
                    fill: false
                }]
            };
            var options = {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
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
                }

            };
            if ($("#barChartP").length) {
                var barChartCanvas = $("#barChartP").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                var barChart = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: dataP,
                    options: options
                });
            }
            if ($("#barChartK").length) {
                var barChartCanvas = $("#barChartK").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                var barChart = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: dataK,
                    options: options
                });
            }
        }
    });
</script> --}}
@endsection
