@extends('admin.template')
@section('content')
<style>

</style>
<div class="row flex-grow">
    <div class="col-md-5 col-lg-5 grid-margin">
        <div class="card card-rounded">
            <div class="card-body card-rounded">
                <h4 class="card-title">Add Kurikulum</h4>
                <form method="POST" action="add-kurikulum">
                    @csrf
                    <div class="form-group">
                        <label>Tahun Kurikulum <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tahun" placeholder="Tahun Kurikulum" autofocus autocomplete="off">
                        @error('tahun')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-lg-7 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body card-rounded">
                <h4 class="card-title">List Kurikulum</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kurikulums as $no=>$kurikulum)
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$kurikulum->tahun}}</td>
                                <td class="py-4">
                                    <div class="d-flex">
                                        <a type="button" id="btn-edit" href="edit-kurikulum/{{encrypt($kurikulum->tahun)}}" class="btn btn-warning btn-icon-text p-2" style="margin-right:7px">
                                            Edit
                                            <i class="ti-pencil btn-icon-append"></i>
                                        </a>
                                        <form action="delete-kurikulum/{{encrypt($kurikulum->tahun)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-icon-text p-2" onclick="return confirm('Are you sure to delete {{$kurikulum->tahun}}?')">
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