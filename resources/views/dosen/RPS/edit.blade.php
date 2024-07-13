@extends('dosen.template')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Edit RPS</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{$rps->id}}" method="POST">
                @csrf
                @method('put')
                <div class="form-floating mb-3">
                    <input type="text" name="nomor" class="form-control" id="nomor" placeholder="nomor" value="{{$rps->nomor}}" aria-describedby="nomorHelp">
                    <label for="nomor" class="form-label">nomor <span class="text-danger">*</span></label>
                    @error('nomor')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div id="nomorHelp" class="form-text">Silahkan masukkan nomor RPS.</div>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select form-control-lg" name="prodi">
                        <option selected="true" value="" disabled selected> </option>
                        <option value="S1-Ilmu Komputer" {{$rps->prodi == 'S1-Ilmu Komputer' ? 'selected' : ''}}>S1 - Ilmu Komputer</option>
                        <option value="D3-Manajemen Informatika" {{$rps->prodi == 'D3-Manajemen Informatika' ? 'selected' : ''}}>D3 - Manajemen Informatika</option>
                    </select>
                    <label for="prodi">Program studi <span style="color:red">*</span></label>
                    @error('prodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select form-control-lg" name="semester" id="semester">
                        <option selected="true" value="" disabled selected> </option>
                        <option value="1" {{ $rps->semester == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $rps->semester == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $rps->semester == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $rps->semester == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $rps->semester == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ $rps->semester == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ $rps->semester == 7 ? 'selected' : '' }}>7</option>
                        <option value="8" {{ $rps->semester == 8 ? 'selected' : '' }}>8</option>
                    </select>
                    <label>Semester <span class="text-danger">*</span></label>
                    @error('semester')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select id="matakuliah" name="matakuliah" class="form-select form-control-lg">
                        <option selected="true" value="" disabled selected> </option>
                        @foreach ($mks as $mk)
                        <option value="{{$mk->kode}}" {{$rps->kode_mk == $mk->kode ? 'selected' : ''}}>{{$mk->nama}}</option>
                        @endforeach
                    </select>
                    <label for="matakuliah">Mata kuliah <span style="color:red">*</span></label>
                    @error('matakuliah')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select name="pengembang" class="form-select form-control-lg">
                        <option selected="true" value="" disabled selected> </option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{$rps->pengembang == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    <label for="pengembang">Pengembang RPS <span style="color:red">*</span></label>
                    @error('pengembang')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select name="koordinator" class="form-select form-control-lg">
                        <option selected="true" value="" disabled selected> </option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{$rps->koordinator == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    <label for="koordinator">Koordinator RMK </label>
                    @error('koordinator')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select name="dosen" class="form-select form-control-lg">
                        <option selected="true" value="" disabled selected> </option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{$rps->dosen == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    <label for="dosen">Dosen pengampu <span style="color:red">*</span></label>
                    @error('dosen')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select name="kaprodi" class="form-select form-control-lg">
                        <option selected="true" value="" disabled selected> </option>
                        @foreach ($users as $user)
                        <option value="{{$user->name}}" {{$rps->kaprodi == $user->name ? 'selected' : ''}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                    <label for="kaprodi">Kepala program studi <span style="color:red">*</span></label>
                    @error('kaprodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="tipe" value="{{$rps->tipe}}" class="form-control" placeholder="Jenis pengajaran" autocomplete="off">
                    <label for="tipe">Jenis pengajaran <span class="text-danger">*</span></label>
                    @error('tipe')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 form-floating mb-3">
                        <textarea name="waktu" class="form-control" placeholder="Workload" style="height: 100px">{{$rps->waktu}}</textarea>
                        <label for="waktu" class="form-label ms-3">Workload <span class="text-danger">*</span></label>
                        @error('waktu')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 form-floating mb-3">
                        <textarea name="kontrak" class="form-control" placeholder="Kontrak kuliah" style="height: 100px">{{$rps->kontrak}}</textarea>
                        <label  class="form-label ms-3" for="kontrak">Kontrak kuliah <span class="text-danger">*</span></label>
                        @error('kontrak')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <textarea name="materi_mk" class="form-control" placeholder="Materi MK" style="height: 100px">{{$rps->materi_mk}}</textarea>
                    <label for="materi_mk">Materi MK <span class="text-danger">*</span></label>
                    @error('materi_mk')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <textarea name="syarat_ujian" class="form-control" style="height: 100px" placeholder="Syarat ujian">{{$rps->syarat_ujian}}</textarea>
                            <label for="syarat_ujian">Syarat ujian <span class="text-danger">*</span></label>
                            @error('syarat_ujian')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 form-floating mb-3">
                        <textarea name="syarat_studi" class="form-control" placeholder="Syarat studi" style="height: 100px">{{$rps->syarat_studi}}</textarea>
                        <label class="form-label ms-3" for="syarat_studi">Syarat studi <span class="text-danger">*</span></label>
                        @error('syarat_studi')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="media" value="{{$rps->media}}" class="form-control" placeholder="Media pembelajaran" autocomplete="off">
                    <label for="media">Media pembelajaran <span class="text-danger">*</span></label>
                    @error('media')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 form-floating mb-3">
                        <textarea name="pustaka_utama" class="form-control" style="height:100px" placeholder="Pustaka utama">{{$rps->pustaka_utama}}</textarea>
                        <label class="form-label ms-3" for="pustaka_utama">Pustaka utama <span class="text-danger">*</span></label>
                        @error('pustaka_utama')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 form-floating mb-3">
                        <textarea name="pustaka_pendukung" class="form-control" style="height:100px" placeholder="Pustaka pendukung">{{$rps->pustaka_pendukung}}</textarea>
                        <label class="form-label ms-3" for="pustaka_pendukung">Pustaka pendukung</label>
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
