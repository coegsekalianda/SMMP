@extends('admin.template')
@section('content')
<div class="col-12 grild-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit RPS</h4>
            <form method="POST" action="{{$rps->kode_mk}}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="nomor">Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="nomor" value="{{$rps->nomor}}" class="form-control" placeholder="Nomor" autocomplete="off">
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
                        <option value="S1-Ilmu Komputer" {{$rps->prodi == 'S1-Ilmu Komputer' ? 'selected' : ''}}>S1 - Ilmu Komputer</option>
                        <option value="D3-Manajemen Informatika" {{$rps->prodi == 'D3-Manajemen Informatika' ? 'selected' : ''}}>D3 - Manajemen Informatika</option>
                    </select>
                    @error('prodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Semester <span class="text-danger">*</span></label>
                    <select class="js-example-basic-single w-100" name="semester" id="semester">
                        <option selected="true" value="" disabled selected>Select...</option>
                        <option value="1" {{ $rps->semester == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $rps->semester == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $rps->semester == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $rps->semester == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $rps->semester == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ $rps->semester == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ $rps->semester == 7 ? 'selected' : '' }}>7</option>
                        <option value="8" {{ $rps->semester == 8 ? 'selected' : '' }}>8</option>
                    </select>
                    @error('semester')
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
                        <option value="{{$mk->kode}}" {{$rps->kode_mk == $mk->kode ? 'selected' : ''}}>{{$mk->nama}}</option>
                        @endforeach
                    </select>
                    @error('matakuliah')
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
                        <option value="{{$user->name}}" {{$rps->pengembang == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
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
                        <option value="{{$user->name}}" {{$rps->koordinator == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
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
                        <option value="{{$user->name}}" {{$rps->dosen == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
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
                        <option value="{{$user->name}}" {{$rps->kaprodi == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('kaprodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipe">Jenis pengajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tipe" value="{{$rps->tipe}}" class="form-control" placeholder="Jenis pengajaran" autocomplete="off">
                    @error('tipe')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="waktu">Workload <span class="text-danger">*</span></label>
                        <textarea name="waktu" class="form-control" placeholder="Workload" style="height: 100px">{{$rps->waktu}}</textarea>
                        @error('waktu')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 form-group">
                        <label for="kontrak">Kontrak kuliah <span class="text-danger">*</span></label>
                        <textarea name="kontrak" class="form-control" placeholder="Kontrak kuliah" style="height: 100px">{{$rps->kontrak}}</textarea>
                        @error('kontrak')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="materi_mk">Materi MK <span class="text-danger">*</span></label>
                    <textarea name="materi_mk" class="form-control" placeholder="Materi MK" style="height: 100px">{{$rps->materi_mk}}</textarea>
                    @error('materi_mk')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="syarat_ujian">Syarat ujian <span class="text-danger">*</span></label>
                            <textarea name="syarat_ujian" class="form-control" style="height: 100px" placeholder="Syarat ujian">{{$rps->syarat_ujian}}</textarea>
                            @error('syarat_ujian')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 form-group">
                        <label for="syarat_studi">Syarat studi <span class="text-danger">*</span></label>
                        <textarea name="syarat_studi" class="form-control" placeholder="Syarat studi" style="height: 100px">{{$rps->syarat_studi}}</textarea>
                        @error('syarat_studi')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="media">Media pembelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="media" value="{{$rps->media}}" class="form-control" placeholder="Media pembelajaran" autocomplete="off">
                    @error('media')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="pustaka_utama">Pustaka utama <span class="text-danger">*</span></label>
                        <textarea name="pustaka_utama" class="form-control" style="height:100px" placeholder="Pustaka utama">{{$rps->pustaka_utama}}</textarea>
                        @error('pustaka_utama')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 form-group">
                        <label for="pustaka_pendukung">Pustaka pendukung</label>
                        <textarea name="pustaka_pendukung" class="form-control" style="height:100px" placeholder="Pustaka pendukung">{{$rps->pustaka_pendukung}}</textarea>
                        @error('pustaka_pendukung')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection