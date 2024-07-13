@extends('admin.template')
@section('content')
<style>
    .card-sum:hover {
        background-color: rgba(0, 123, 255, .75) !important;
        cursor: pointer;
        transform: scale(.95);
    }

    .card-two:hover {
        cursor: pointer;
        transform: scale(.95);
    }
</style>
<!-- <div class="statistics-details d-flex align-items-center justify-content-evenly form-group">
    <a href="/admin/list-user" style="text-decoration:none; color: black">
        <div class="row">
            <div class="col text-end">
                <i class="h1 mdi mdi-account-multiple"></i>
            </div>
            <div class="col">
                <p class="statistics-title" style="color: #8D8D8D;">Jumlah User</p>
                @php
                $users = DB::table('users')->get();
                $sum = 0;
                foreach($users as $user) :
                $sum+=1;
                endforeach
                @endphp
                <h3 class="rate-percentage">{{$sum}}</h3>
            </div>
        </div>
    </a>
    <a href="/admin/list-kurikulum" style="text-decoration:none; color: black">
        <div class="row">
            <div class="col text-end">
                <i class="h1 mdi mdi-file-multiple"></i>
            </div>
            <div class="col">
                <p class="statistics-title" style="color: #8D8D8D;">Jumlah Kurikulum</p>
                @php
                $kurikulums = DB::table('kurikulums')->get();
                $sum = 0;
                foreach($kurikulums as $kurikulum) :
                $sum+=1;
                endforeach
                @endphp
                <h3 class="rate-percentage">{{$sum}}</h3>
            </div>
        </div>
    </a>
</div> -->
<div class="form-group">
    <div class="d-grid justify-content-between w-100" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));gap: 1rem;">
        <a class="card card-two mx-1 w-100 text-decoration-none" style="color:black;" href="/admin/list-user">
            <div class="card-body pb-0 row">
                <div class="col">
                    <h4 class="card-title card-title-dash mb-4">Jumlah User</h4>
                    <p class="status-summary-ight-white mb-1" style="color:black">Dosen & Penjamin Mutu</p>
                    @php
                    $users = DB::table('users')->get();
                    $sum = 0;
                    foreach($users as $user) :
                    $sum+=1;
                    endforeach
                    @endphp
                    <h2 class="" style="color:gray">{{$sum}}</h2>
                </div>
                <div class="col text-end">
                    <i class="h1 mdi mdi-account-multiple" style="font-size:100px; opacity:0.4"></i>
                </div>
            </div>
        </a>
        <a class="card card-two mx-1 w-100 text-decoration-none" style="color: black" href="/admin/list-kurikulum">
            <div class="card-body row pb-4">
                <div class="col">
                    <h4 class="card-title card-title-dash mb-4" style="color: black">Jumlah Kurikulum</h4>
                    <p class="status-summary-ight-white mb-1" style="color: black">Tahun Kurikulum</p>
                    @php
                    $kurikulums = DB::table('kurikulums')->get();
                    $sum = 0;
                    foreach($kurikulums as $kurikulum) :
                    $sum+=1;
                    endforeach
                    @endphp
                    <h2 class="" style="color:gray">{{$sum}}</h2>
                </div>
                <div class="col text-end">
                    <i class="h1 mdi mdi-folder-multiple" style="font-size:100px; opacity:0.4;"></i>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="container-fluid">
    <div class="row justify-content-start">
        <div class="form-group col-3">
            <label for="filter" class="form-label">FILTER KURIKULUM</label>
            <select name="kurikulum" id="filter" class="form-select text-center">
                <option value="all"> Semua </option>
                @foreach($kurikulums as $kur)
                <option value="{{$kur->tahun}}">{{$kur->tahun}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="d-grid justify-content-between w-100" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap: 1rem;">
        <a class="card card-sum mx-1 w-100 bg-primary text-white text-decoration-none" href="/admin/list-cpl">
            <div class="card-body pb-0">
                <h4 class="card-title card-title-dash text-white mb-4">Jumlah CPL</h4>
                <div class="row">
                    <div class="col">
                        <p id="kur-cpl" class="status-summary-ight-white mb-1">Semua Kurikulum</p>
                        <h2 class="text-info" id="jumlah-cpl"></h2>
                    </div>
                    <div class="col text-end p-4">
                        <i class="h1 mdi mdi-view-list"></i>
                    </div>
                </div>
            </div>
        </a>
        <a class="card card-sum mx-1 w-100 bg-primary text-white text-decoration-none" href="/admin/list-mk">
            <div class="card-body pb-0">
                <h4 class="card-title card-title-dash text-white mb-4">Jumlah Mata Kuliah</h4>
                <div class="row">
                    <div class="col">
                        <p id="kur-mk" class="status-summary-ight-white mb-1">Semua Kurikulum</p>
                        <h2 class="text-info" id="jumlah-mk"></h2>
                    </div>
                    <div class="col text-end p-4">
                        <i class="h1 mdi mdi-book-open"></i>
                    </div>
                </div>
            </div>
        </a>
        <a class="card card-sum mx-1 w-100 bg-primary text-white text-decoration-none" href="/admin/list-rps">
            <div class="card-body pb-0">
                <h4 class="card-title card-title-dash text-white mb-4">Jumlah RPS</h4>
                <div class="row">
                    <div class="col">
                        <p id="kur-rps" class="status-summary-ight-white mb-1">Semua Kurikulum</p>
                        <h2 class="text-info" id="jumlah-rps"></h2>
                    </div>
                    <div class="col text-end p-4">
                        <i class="h1 mdi mdi-note-text"></i>
                    </div>
                </div>
            </div>
        </a>
        <a class="card card-sum mx-1 w-100 bg-primary text-white text-decoration-none" href="/admin/list-rps">
            <div class="card-body pb-0">
                <h4 class="card-title card-title-dash text-white mb-4">Jumlah Soal</h4>
                <div class="row">
                    <div class="col">
                        <p id="kur-soal" class="status-summary-ight-white mb-1">Semua Kurikulum</p>
                        <h2 class="text-info" id="jumlah-soal"></h2>
                    </div>
                    <div class="col text-end p-4">
                        <i class="h1 mdi mdi-book"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CPL Umum</h4>
                    <canvas id="barChartU"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CPL Pengetahuan</h4>
                    <canvas id="barChartP"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CPL Keterampilan</h4>
                    <canvas id="barChartK"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    var canvas = document.getElementsByTagName('canvas')[0];
    var canvass = document.getElementsByTagName('canvas')[1];
    var canvasss = document.getElementsByTagName('canvas')[2];
    canvas.height = 80;
    canvass.height = 80;
    canvasss.height = 80;

    $(document).ready(function() {
        showChart('all')
    })

    $(document).ready(function() {
        showCard('all')
    })

    $('#filter').change(function() {
        // console.log($('#filter option:selected').val())
        if ($('#filter option:selected').val() != 'all') {
            document.getElementById('kur-mk').innerText = 'Kurikulum ' + $('#filter option:selected').val();
            document.getElementById('kur-cpl').innerText = 'Kurikulum ' + $('#filter option:selected').val();
            document.getElementById('kur-rps').innerText = 'Kurikulum ' + $('#filter option:selected').val();
            document.getElementById('kur-soal').innerText = 'Kurikulum ' + $('#filter option:selected').val();
        } else {
            document.getElementById('kur-mk').innerText = 'Semua Kurikulum';
            document.getElementById('kur-cpl').innerText = 'Semua Kurikulum';
            document.getElementById('kur-rps').innerText = 'Semua Kurikulum';
            document.getElementById('kur-soal').innerText = 'Semua Kurikulum';
        }

        showChart($('#filter option:selected').val());
        showCard($('#filter option:selected').val());
    })

    function showCard(url) {
        $.ajax({
            url: `/admin/dashboard-card/${url}`,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $("#jumlah-mk").empty();
                $("#jumlah-mk").append(data.sum_mk);
                $("#jumlah-cpl").empty();
                $("#jumlah-cpl").append(data.sum_cpl);
                $("#jumlah-rps").empty();
                $("#jumlah-rps").append(data.sum_rps);
                $("#jumlah-soal").empty();
                $("#jumlah-soal").append(data.sum_soal);
            }
        });
    }

    function showChart(url) {
        $.ajax({
            url: `/admin/dashboard-chart/${url}`,
            type: "GET",
            dataType: "json",
            success: function(dt) {
                var dataP = {
                    labels: dt.pengetahuan,
                    datasets: [{
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
                        data: dt.jumlahk,
                        backgroundColor: dt.warnak,
                        borderColor: dt.borderk,
                        borderWidth: 1,
                        fill: false
                    }]
                };
                var dataU = {
                    labels: dt.umum,
                    datasets: [{
                        data: dt.jumlahu,
                        backgroundColor: dt.warnau,
                        borderColor: dt.borderu,
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
                    events: []
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
                if ($("#barChartU").length) {
                    var barChartCanvas = $("#barChartU").get(0).getContext("2d");
                    // This will get the first returned node in the jQuery collection.
                    var barChart = new Chart(barChartCanvas, {
                        type: 'bar',
                        data: dataU,
                        options: options
                    });
                }
            }
        });
    }
</script>
@endsection