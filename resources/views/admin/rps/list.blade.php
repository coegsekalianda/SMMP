@extends('admin.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List RPS</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Tanggal Penyusunan</th>
                            <th>Nomor</th>
                            <th>Semester</th>
                            <th>Pengembang RPS</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        foreach($rpss as $no=>$rps):
                        @endphp
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$rps->kode_mk}}</td>
                            <td>{{date("d-m-Y",strtotime($rps->created_at))}}</td>
                            <td>{{$rps->nomor}}</td>
                            <td>{{$rps->semester}}</td>
                            <td>{{$rps->pengembang}}</td>
                            <td class="py-4 d-flex">
                                <a type="button" href="edit-rps/{{$rps->kode_mk}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px; width:35px; height:35px">
                                    <i class="ti-pencil btn-icon"></i>
                                </a>
                                <form action="delete-rps/{{encrypt($rps->id)}}" method="post">
                                    @csrf
                                    
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text me-2 p-2" style="width:35px; height:35px" onclick="return confirm('Are you sure to delete RPS {{$rps->kode_mk}}?')">
                                        <i class="ti-trash btn-icon"></i>
                                    </button>
                                </form>
                                <a href="/admin/print-rps/{{$rps->kode_mk}}" style="width:35px; height:35px" target="_blank" type="button" class="btn btn-info btn-icon-text p-2">
                                    <i class="ti-download btn-icon"></i>
                                </a>
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