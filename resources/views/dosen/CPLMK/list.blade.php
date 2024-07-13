@extends('dosen.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List CPLMK</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Tanggal</th>
                            <th>Kode CPL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $kode_mk = 0;
                        $kode_cpl = 0;
                        $nama_mk = '';
                        foreach($cplmks as $no=>$cplmk):
                        foreach($mks as $mk):
                        if($cplmk->kode_mk == $mk->kode):
                        $kode_mk = $mk->kode;
                        $nama_mk = $mk->nama;
                        endif;
                        endforeach;
                        foreach($cpls as $cpl):
                        if($cplmk->id_cpl == $cpl->id)
                        $kode_cpl = $cpl->kode;
                        endforeach
                        @endphp
                        <tr>

                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$kode_mk}}</td>
                            <td>{{$nama_mk}}</td>
                            <td>{{date("d-m-Y",strtotime($cplmk->created_at))}}</td>
                            <td>{{$kode_cpl}}</td>
                            <td class="py-4 d-flex">
                                <form action="/admin/delete-cplmk/{{$cplmk->id}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete CPL {{$kode_cpl}} from CPLMK {{$kode_mk}} ?')">
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
