@extends('dosen.template')
@section('content')

<style>
    #container {
                width: 900px;
                margin: 20px auto;
            }
    .ck-editor__editable[role="textbox"] {
                /* Editing area */
                min-height: 200px;
            }
    .ck-content .image {
                /* Block images */
                max-width: 80%;
                margin: 20px auto;
            }
    li.select2-selection__choice {
        color: #646464;
        font-weight: bolder;
    }
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Ubah Soal Mata Kuliah</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{$soal->id}}" method="post">
                @method('put')
                @csrf
                <!-- <div class="form-floating">
                    <select id="prodi" name="prodi"  class="form-select form-control-lg" aria-label="select Prodi" required>
                        <option disabled> - </option>
                        <option {{$soal->prodi == 'S1 - Ilmu Komputer'?'selected':''}} value="S1 - Ilmu Komputer">S1 - Ilmu Komputer</option>
                        <option {{$soal->prodi == 'D3 - Manajemen Informatika'?'selected':''}} value="D3 - Manajemen Informatika">D3 - Manajemen Informatika</option>
                    </select>
                    <label for="prodi"> Pilih Prodi <span class="text-danger"> *</span></label>
                    <div class="form-text mb-3"></div>
                </div> -->

                <div class="form-floating">
                    <select id="kurikulum" name="kurikulum"  class="form-select form-control-lg" aria-label="select kurikulum" required>
                        <option selected disabled> - </option>
                        @foreach ($kurikulum as $kur )
                        <option {{$soal->kurikulum == $kur->tahun?'selected':''}} value="{{$kur->tahun}}">{{$kur->tahun}}</option>
                        @endforeach
                    </select>
                    <label for="kurikulum"> Pilih Kurikulum <span class="text-danger"> *</span></label>
                    <div class="form-text mb-3"></div>
                </div>

                <div class="form-floating">
                    <select id="mataKuliah" name="kode_mk"  class="form-select form-control-lg" aria-label="select Mata Kuliah" required>
                        <option selected disabled> - </option>
                        @foreach ($rpss as $rps)
                        <option {{$soal->kode_mk == $rps->kode_mk?'selected':''}} value="{{$rps->kode_mk}}">{{$rps->mk->nama}}</option>
                        @endforeach
                    </select>
                    <label for="mataKuliah"> Pilih Mata Kuliah <span class="text-danger"> *</span></label>
                    <div class="form-text mb-3"></div>
                </div>

                <div class="form-floating">
                    <input type="number" name="minggu" min="1" max="16" value="{{ $soal->minggu}}" class="form-control" placeholder="minggu" autocomplete="off">
                    <label for="minggu">Minggu <span class="text-danger">*</span></label>
                    @error('minggu')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="form-text mb-3"></div>
                </div>

                <div class="form-floating">
                    <select id="jenis" name="jenis"  class="form-select form-control-lg" aria-label="select jenis" required>
                        <option disabled> - </option>
                        @foreach ($komponen as $komponen)
                            <option value="{{$komponen->jenis}}">{{$komponen->jenis}}</option>
                        @endforeach
                    </select>
                    <label for="jenis"> Pilih Jenis <span class="text-danger"> *</span></label>
                    <div class="form-text mb-3"></div>
                </div>

                <div id="container">
                    <textarea name="pertanyaan" id="pertanyaan" class="form-control" placeholder="insert pertanyaan" style="height: 100px">{{$soal->pertanyaan}}</textarea>
                    @error('pertanyaan')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="form-text mb-3"></div>
                </div>

                <div class="form-floating">
                    <input name="bobotSoal" type="number" min="1" max="100" class="form-control" autocomplete="off" placeholder="BobotSoal" value="{{$soal->bobotSoal}}">
                    <label>Bobot Soal (%)<span class="text-danger ">*</span></label>
                    <div class="form-text mb-3"></div>
                </div>

                <!-- <div class="form-floating">
                    <select class="form-control" name="cpl">
                        <option selected="true" value="" disabled selected>-</option>
                        @foreach ($rpss as $cpl)
                        <option value="{{$cpl->id}}">{{$cpl->kode}}</option>
                        @endforeach
                    </select>
                    <label>CPL <span class="text-danger">*</span></label>
                    <div class="form-text mb-3"></div>
                </div>

                <div class="form-floating">
                    <select name="cpmk" class="form-control">
                        @foreach ($rpss as $rps)
                        @foreach ($rps->mk->cpmk as $cpmk)
                        <option {{$soal->cpmk()->wherePivot('id_cpmk', $cpmk->id)->first()? 'selected':''}} value="{{$cpmk->id}}">{{$cpmk->judul}}</option>
                        @endforeach
                        @endforeach
                    </select>
                    <label for="id_cpl"> Pilih CPMK <span class="text-danger"> *</span></label>
                    <div class="form-text mb-3"></div>
                </div> -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('/assets/template/vendors/select2/select2.min.js')}}"></script>
<script src="{{ asset('/assets/template/js/select2.js')}}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#pertanyaan' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection
