@extends('admin.template')
@section('content')
<h3 class="px-4 pb-4 fw-bold text-center">List CPL Prodi</h3>
<div class="row stretch-card mx-auto">
    <div class="col form-group">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Sikap</h4>
                <div class="table-responsive">
                    <table class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Tahun kurikulum</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sikaps as $sikap)
                            <tr>
                                <td>{{$sikap->kurikulum}}</td>
                                <td class="py-4">{{$sikap->kode}}</td>
                                <td><?= $sikap->judul ?></td>
                                <td class="py-4">
                                    <div class="d-flex">
                                        <a type="button" href="edit-cpl/{{encrypt($sikap->id)}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                            Edit
                                            <i class="ti-pencil btn-icon-append"></i>
                                        </a>
                                        <form action="delete-cpl/{{encrypt($sikap->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$sikap->kode}}?')">
                                                Delete
                                                <i class="ti-trash btn-icon-append"></i>
                                            </button>
                                        </form>
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
    <div class="col form-group">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Umum</h4>
                <div class="table-responsive">
                    <table class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Tahun kurikulum</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($umums as $umum)
                            <tr>
                                <td>{{$umum->kurikulum}}</td>
                                <td>{{$umum->kode}}</td>
                                <td><?= $umum->judul ?></td>
                                <td class="py-4">
                                    <div class="d-flex">
                                        <a type="button" href="edit-cpl/{{encrypt($umum->id)}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                            Edit
                                            <i class="ti-pencil btn-icon-append"></i>
                                        </a>
                                        <form action="delete-cpl/{{encrypt($umum->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$umum->kode}}?')">
                                                Delete
                                                <i class="ti-trash btn-icon-append"></i>
                                            </button>
                                        </form>
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
</div>
<div class="row stretch-card mx-auto">
    <div class="col form-group">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pengetahuan</h4>
                <div class="table-responsive">
                    <table class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Tahun kurikulum</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengetahuans as $pengetahuan)
                            <tr>
                                <td>{{$pengetahuan->kurikulum}}</td>
                                <td class="py-4">{{$pengetahuan->kode}}</td>
                                <td><?= $pengetahuan->judul ?></td>
                                <td class="py-4">
                                    <div class="d-flex">
                                        <a type="button" href="edit-cpl/{{encrypt($pengetahuan->id)}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                            Edit
                                            <i class="ti-pencil btn-icon-append"></i>
                                        </a>
                                        <form action="delete-cpl/{{encrypt($pengetahuan->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$pengetahuan->kode}}?')">
                                                Delete
                                                <i class="ti-trash btn-icon-append"></i>
                                            </button>
                                        </form>
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
    <div class="col form-group">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keterampilan</h4>
                <div class="table-responsive">
                    <table class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Tahun kurikulum</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach($keterampilans as $keterampilan)
                            <tr>
                                <td>{{$keterampilan->kurikulum}}</td>
                                <td>{{$keterampilan->kode}}</td>
                                <td><?= $keterampilan->judul ?></td>
                                <td class="py-4">
                                    <div class="d-flex">
                                        <a type="button" href="edit-cpl/{{encrypt($keterampilan->id)}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                            Edit
                                            <i class="ti-pencil btn-icon-append"></i>
                                        </a>
                                        <form action="delete-cpl/{{encrypt($keterampilan->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$keterampilan->kurikulum}}-{{$keterampilan->kode}}?')">
                                                Delete
                                                <i class="ti-trash btn-icon-append"></i>
                                            </button>
                                        </form>
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
</div>
@endsection