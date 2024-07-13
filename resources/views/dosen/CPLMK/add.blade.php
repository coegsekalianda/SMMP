@extends('dosen.template')
@section('content')

<style>
    li.select2-selection__choice {
        color: #646464;
        font-weight: bolder;
    }
</style>

<div class="container-fluid  mb-4">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Tambah CPLMK Mata Kuliah Baru</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <label for="mataKuliah"> Pilih Mata Kuliah <span class="text-danger"> *</span></label>
                <select id="mataKuliah" name="kode_mk"  class="form-select form-control-lg" aria-label="select Mata Kuliah" required>
                    <option selected disabled> - </option>
                    @foreach ($mks as $mk)
                    <option value="{{$mk->kode}}">{{$mk->kode}} - {{$mk->nama}}</option>
                    @endforeach
                </select>
                <div id="MKHelp" class="form-text mb-3">Silahkan pilih mata kuliah RPS.</div>
                <div class="form-group">
                    <label for="id_cpl"> Pilih CPL Prodi <span class="text-danger"> *</span></label>
                    <select name="id_cpl[]" class="js-example-basic-multiple form-select form-control-lg" multiple="multiple">
                        @foreach ($cpls as $cpl)
                        @if($cpl->aspek == "Pengetahuan" || $cpl->aspek == "Keterampilan" || $cpl->aspek == "Umum")
                        <option value="{{$cpl->id}}">{{$cpl->kurikulum}} - {{$cpl->kode}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div id="cplHelp" class="form-text mb-3">Silahkan pilih CPL Prodi.</div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-12 grid-margin stretch-card">
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
                        @if($cpl->aspek == "Pengetahuan" || $cpl->aspek == "Keterampilan" || $cpl->aspek == "Umum")
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('/assets/template/vendors/select2/select2.min.js')}}"></script>
<script src="{{ asset('/assets/template/js/select2.js')}}"></script>
<script>
    var i = 0;
    $("#dynamic-ar-sik").click(function() {
        ++i;
        $("#dynamicAddRemoveSik").append('<div class="form-group row clone"><div class="col-2"><div class="form-group"><label>Kurikulum <span class="text-danger">*</span></label><input type="text"class="form-control" name="kurikulum[' + i +
            ']"placeholder="Kurikulum" autocomplete="off"></div></div><div class="col-3"><div class="form-group"><label>Kode <span class="text-danger">*</span></label><div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">S</span></div><input type="text" class="form-control" name="kode[' + i +
            ']" placeholder="Nomor" autocomplete="off"></div></div></div><div class="col-5"><div class="form-group"><label>Judul <span class="text-danger">*</span></label><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul" autocomplete="off"></div></div><input hidden type="text" name="aspek" value="Keterampilan"><div class="col-2"><label>Action</label><div class="form-group"><button type="button" class="btn btn-sm btn-danger remove-input-field-sik">Delete</button></div></div></div>'
        );
    });
    $(document).on('click', '.remove-input-field-sik', function() {
        $(this).parents('.clone').remove();
    });
</script>
@endsection
