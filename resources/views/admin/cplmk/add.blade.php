@extends('admin.template')
@section('content')

<style>
    li.select2-selection__choice {
        color: #646464;
        font-weight: bolder;
    }
</style>

<div class="col-lg-12 grid-margin stretch-card mb-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add CPLMK</h4>
            <form action="" method="post">
                @csrf
                <div class="form-group">
                    <label for="matakuliah">Mata Kuliah <span class="text-danger"> *</span></label>
                    <select id="matakuliah" name="kode_mk" class="js-example-basic-single w-100" required>
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($mks as $mk)
                        <option value="{{$mk->kode}}">{{$mk->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_cpl">CPL Prodi <span class="text-danger"> *</span></label>
                    <select name="id_cpl[]" class="js-example-basic-multiple w-100" multiple="multiple">
                        @foreach ($cpls as $cpl)
                        {{-- @if($cpl->aspek != 'Sikap') --}}
                        <option value="{{$cpl->id}}">{{$cpl->kurikulum}} - {{$cpl->kode}}</option>
                        {{-- @endif --}}
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card mb-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List CPL Prodi</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aspek</th>
                            <th>Nomor</th>
                            <th>Kurikulum</th>
                            <th>Kode</th>
                            <th>Judul</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpls as $no=>$cpl)
                        @if($cpl->aspek != 'Sikap')
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$cpl->aspek}}</td>
                            <td>{{$cpl->nomor}}</td>
                            <td>{{$cpl->kurikulum}}</td>
                            <td>{{$cpl->kode}}</td>
                            <td>{{$cpl->judul}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assets/template/vendors/select2/select2.min.js')}}"></script>
<script src="{{ asset('/assets/template/js/select2.js')}}"></script>
@endsection