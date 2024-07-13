@extends('admin.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Summary Soal</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>CPMK</th>
                            <th>Jumlah Soal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1  ?>
                        @foreach($cpmks as $mk=>$cpmk)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$mk}}</td>
                            <td>
                                <table class="table table-stripped">
                                    @foreach($cpmk as $cp)
                                    <tr>
                                        <td>{{$cp->judul}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table table-stripped">
                                    @foreach($cpmk as $cp)
                                    <tr>
                                        <td>{{$cp->soal->count()}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection