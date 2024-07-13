@extends('dosen.template')
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
                            <th>Mata Kuliah</th>
                            <th>Tanggal Penyusunan</th>
                            <th>Nomor</th>
                            <th>Semester</th>
                            <th>Pengembang RPS</th>
                            <th>Koordinator RMK</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rpss as $no=>$rps)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$rps->mk->nama}}</td>
                            <td>{{date("d-m-Y",strtotime($rps->created_at))}}</td>
                            <td>{{$rps->nomor}}</td>
                            <td>{{$rps->semester}}</td>
                            <td>{{$rps->pengembang}}</td>
                            <td>{{$rps->koordinator}}</td>
                            <td class="py-4 d-flex">
                                <a href="/dosen/rps/edit-rps/{{$rps->id}}" type="button" class="btn btn-warning me-2 btn-icon-text p-2">
                                    <i class="ti-pencil btn-icon"></i>
                                </a>
                                <form action="delete-rps/{{$rps->id}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete RPS no. {{$rps->nomor}} ?')">
                                        <i class="ti-trash btn-icon"></i>
                                    </button>
                                </form>
                                <a href="/dosen/rps/print-rps/{{encrypt($rps->id)}}" type="button" class="btn btn-info btn-icon-text p-2">
                                    <i class="ti-printer btn-icon"></i>
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
