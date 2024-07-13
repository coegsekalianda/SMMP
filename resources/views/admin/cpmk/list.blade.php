@extends('admin.template')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List CPMK</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama MK</th>
                            <th>Rincian CPMK</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpmks as $no=>$cpmk)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$cpmk->mk}}</td>
                            <td>{{$cpmk->judul}}</td>
                            <td class="py-4 d-flex">
                                <a href="/admin/edit-cpmk/{{encrypt($cpmk->id)}}" type="button" class="btn btn-warning me-2 btn-icon-text p-2">
                                    Edit
                                    <i class="ti-pencil btn-icon-append"></i>
                                </a>
                                
                                <form action="/admin/delete-cpmk/{{encrypt($cpmk->id)}}" method="post">
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