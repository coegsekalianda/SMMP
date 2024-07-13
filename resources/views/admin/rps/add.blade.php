@extends('admin.template')
@section('content')
<div class="col-12 grild-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex" style="justify-content:space-between">
                <h4 class="card-title">Add RPS</h4>
                <div class="btn-wrapper">
                    <a download class="btn btn-inverse-primary" href="{{asset('assets/xlsx_template/template_rps.xlsx')}}">Template</a>
                    <button class="btn btn-primary text-white" onclick="document.getElementById('excel').click()">Import</i></button>
                    <form id="form-import" method="post" enctype="multipart/form-data" action="add-rps-wfile">
                        @csrf
                        <input accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"" style=" display:none" type="file" name="excel" id="excel">
                    </form>
                </div>
            </div>
            <form method="POST" action="add-rps" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nomor">Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="nomor" value="{{old('nomor')}}" class="form-control" placeholder="Nomor" autocomplete="off">
                    @error('nomor')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="prodi">Program studi <span style="color:red">*</span></label>
                    <select class="js-example-basic-single w-100" name="prodi">
                        <option selected="true" value="" disabled selected>Select...</option>
                        <option value="S1-Ilmu Komputer" {{old('prodi') == 'S1-Ilmu Komputer' ? 'selected' : ''}}>S1 - Ilmu Komputer</option>
                        <option value="D3-Manajemen Informatika" {{old('prodi') == 'D3-Manajemen Informatika' ? 'selected' : ''}}>D3 - Manajemen Informatika</option>
                    </select>
                    @error('prodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="matakuliah">Mata kuliah <span style="color:red">*</span></label>
                    <select id="matakuliah" name="matakuliah" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($mks as $mk)
                        <option value="{{$mk->kode}}" {{old('matakuliah') == $mk->kode ? 'selected' : ''}}>{{$mk->nama}}</option>
                        @endforeach
                    </select>
                    @error('matakuliah')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Semester <span class="text-danger">*</span></label>
                    <select class="js-example-basic-single w-100" name="semester" id="semester">
                        <option selected="true" value="" disabled selected>Select...</option>
                        <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ old('semester') == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ old('semester') == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ old('semester') == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ old('semester') == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ old('semester') == 7 ? 'selected' : '' }}>7</option>
                        <option value="8" {{ old('semester') == 8 ? 'selected' : '' }}>8</option>
                    </select>
                    @error('semester')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="pengembang">Pengembang RPS <span style="color:red">*</span></label>
                    <select name="pengembang" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{old('pengembang') == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('pengembang')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="koordinator">Koordinator RMK <span style="color:red">*</span></label>
                    <select name="koordinator" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{old('koordinator') == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('koordinator')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="dosen">Dosen pengampu <span style="color:red">*</span></label>
                    <select name="dosen" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{old('dosen') == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('dosen')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kaprodi">Kepala program studi <span style="color:red">*</span></label>
                    <select name="kaprodi" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{old('kaprodi') == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('kaprodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="materi_mk">Materi MK <span class="text-danger">*</span></label>
                    <textarea name="materi_mk" class="form-control" placeholder="Materi MK" style="height: 100px">{{old('materi_mk')}}</textarea>
                    @error('materi_mk')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kontrak">Kontrak kuliah <span class="text-danger">*</span></label>
                    <textarea name="kontrak" class="form-control" placeholder="Kontrak kuliah" style="height: 100px">{{old('kontrak')}}</textarea>
                    @error('kontrak')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="pustaka_utama">Pustaka utama <span class="text-danger">*</span></label>
                        <textarea name="pustaka_utama" class="form-control" style="height:100px" placeholder="Pustaka utama">{{old('pustaka_utama')}}</textarea>
                        @error('pustaka_utama')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="pustaka_pendukung">Pustaka pendukung</label>
                            <textarea name="pustaka_pendukung" class="form-control" style="height:100px" placeholder="Pustaka pendukung">{{old('pustaka_pendukung')}}</textarea>
                            @error('pustaka_pendukung')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById("excel").onchange = function() {
        document.getElementById("form-import").submit();
    };
</script>
@endsection