@extends('dosen.template')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Tambah CPMK Baru</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <div class="form-floating">
                    <select id="mataKuliah" name="kode_mk" class="form-select form-control-lg" aria-label="select Mata Kuliah">
                        <option selected disabled> </option>
                        @foreach ($mks as $mk)
                        <option value="{{$mk->kode}}">{{$mk->kode}} - {{$mk->nama}}</option>
                        @endforeach
                    </select>
                    <label for="mataKuliah">Mata Kuliah <span style="color:red">*</span></label>
                    @error('kode_mk')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div id="MKHelp" class="form-text mb-3">Silahkan pilih mata kuliah.</div>
                </div>
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemove">
                    <div class="form-group row mb-2">
                        <div class="col-10 form-floating">
                            <input type="text" name="judul[0]" class="form-control" id="judul" placeholder="judul" aria-describedby="judulHelp">
                            <label for="judul" class="form-label ms-3">Judul Rincian CPMK <span style="color:red">*</span></label>
                        </div>

                        <div class="col-2">
                            {{-- <label>Action</label> --}}
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar" class="btn btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                    @error('judul')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div id="judulHelp" class="form-text mb-3">Silahkan masukkan rincian CPMK.</div>
                </div>
                <?php $a = 0; ?>
                @else
                <div id="dynamicAddRemove">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if (old('judul.' . $i) != null) { ?>
                            <div class="form-group row mb-2">
                                <div class="col-10 form-floating">
                                    <input type="text" id='judul' class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul rincian CPMK" autocomplete="off" aria-describedby="judulHelp">
                                    <label for="judul" class="form-label ms-3">Judul rincian CPMK <span style="color:red">*</span></label>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    {{-- <label>Action</label> --}}
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar" class="btn btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                        } elseif (old('judul.' . ($i + 1)) != null) { ?>
                            <div class="form-group row mb-2">
                                <div class="col-10 form-floating">
                                    <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul rincian CPMK" autocomplete="off" aria-describedby="judulHelp">
                                    <label class="form-label">Judul rincian CPMK <span class="text-danger">*</span></label>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    {{-- <label>Action</label> --}}
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar" class="btn btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                {{-- <div class="form-floating mb-3">
                    <input type="text" name="judul" class="form-control" id="judul" placeholder="judul" aria-describedby="judulHelp">
                    <label for="judul" class="form-label">Judul Rincian CPMK <span style="color:red">*</span></label>
                    @error('judul')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div id="judulHelp" class="form-text">Silahkan masukkan rincian CPMK.</div>
                </div> --}}
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    var i = <?= json_encode($a) ?>;
    $("#dynamic-ar").click(function() {
        ++i;
        $("#dynamicAddRemove").append('<div class="form-group row clone"><div class="col-10"><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul rincian CPMK" autocomplete="off"></div></div>'
        );
    });
</script>
@endsection
