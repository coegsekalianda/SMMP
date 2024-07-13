@extends('dosen.template')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Activities</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>nama MK</th>
                            <th>nomor RPS</th>
                            <th>Minggu</th>
                            <th>Sub CPMK</th>
                            <th>Indikator</th>
                            <th>Kriteria</th>
                            <th>Metode Luring</th>
                            <th>Metode Daring</th>
                            <th>Materi</th>
                            <th>Bobot</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $no=>$activity)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$activity->rps->mk->nama}}</td>
                            <td>{{$activity->rps->nomor}}</td>
                            <td>{{$activity->minggu}}</td>
                            <td>{{$activity->sub_cpmk}}</td>
                            <td><?= $activity->indikator ?></td>
                            <td>{{$activity->kriteria}}</td>
                            <td>{{$activity->metode_luring}}</td>
                            <td>{{$activity->metode_daring}}</td>
                            <td>{{$activity->materi}}</td>
                            <td>{{$activity->bobot}}</td>
                            <td class="py-4 d-flex">
                                <a href="{{route('activity-edit',['id'=>$activity->id])}}" type="button" class="btn btn-warning me-2 btn-icon-text p-2">
                                    Edit
                                    <i class="ti-pencil btn-icon-append"></i>
                                </a>
                                <form action="{{route('activity-delete',['id'=>$activity->id])}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete this ?')">
                                        Delete
                                        <i class="ti-trash btn-icon-append"></i>
                                    </button>
                                </form>
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
