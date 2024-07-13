@extends('admin.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Mata Kuliah</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Rumpun</th>
                            <th>Tahun kurikulum</th>
                            <th>Bobot</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mks as $no=>$mk)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$mk->kode}}</td>
                            <td>{{$mk->nama}}</td>
                            <td>MK {{$mk->rumpun}}</td>
                            <td>{{$mk->kurikulum}}</td>
                            <td><?= (int)$mk->bobot_teori + $mk->bobot_praktikum ?> SKS</td>
                            <td class="py-4">
                                
                                <div class="d-flex">
                                    <a type="button" href="edit-mk/{{$mk->kode}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                        Edit
                                        <i class="ti-pencil btn-icon-append"></i>
                                    </a>
                                    <form action="delete-mk/{{$mk->kode}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$mk->nama}}?')">
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
@endsection