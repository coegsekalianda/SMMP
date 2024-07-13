@extends('admin.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Universitas</h4>
            <!--<img src="https://skripsiilkom.my.id/assets/img/logo_unila.png" alt="logo">-->
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>logo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($universitas as $no=>$universitas)
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$universitas->nama}}</td>
                            <td>
                                <img src="{{ asset($universitas->img) }}" class="img img-responsive"/>
                            </td>
                            <td class="py-4 d-flex">
                                <form action="{{route('Universitas-delete',['id'=>$universitas->id])}}" method="post">
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
