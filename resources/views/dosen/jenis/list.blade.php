@extends('dosen.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Jenis</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenis as $no=>$jenis)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$jenis->jenis}}</td>
                            <td class="py-4 d-flex">
                                <form action="{{route('Jenis-delete',['id'=>$jenis->id])}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete ?')">
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
