@extends('dosen.template')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="fw-bold">
                <h3>Tambah Aktifitas Mingguan Baru</h3>
            </div>
            <div class="btn-wrapper">
                <a download class="btn btn-inverse-primary" href="{{asset('assets/excel/template.xlsx')}}">Template</a>
                <button class="btn btn-primary text-white" onclick="document.getElementById('excel').click()">Import</i></button>
                <form id="form-import" method="post" enctype="multipart/form-data" action="{{route('activities-wfile')}}">
                    @csrf
                    <input accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="display:none" type="file" name="excel" id="excel">
                </form>
                <br>
                <div style="font-size:11px; margin-top:-8%; "><i class="mdi mdi-information-outline"></i> Note: import excel min. 2 activities</div>
            </div>

        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <div class="form-group row mb-0">
                    <div class="form-group w-50 mb-0">
                        <div class="form-floating">
                            <select id="rps" name="id_rps" class="form-select form-control-lg" aria-label="select RPS">
                                <option disabled> </option>
                                @foreach ($rpss as $rps)
                                <option {{old('id_rps') == $rps->id ? 'selected' : ''}} value="{{$rps->id}}">{{$rps->nomor}} - {{$rps->kode_mk}} - {{$rps->mk->nama}}</option>
                                @endforeach
                            </select>
                            <label for="rps">Nomor RPS <span style="color:red">*</span></label>
                            @error('rps')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div id="MKHelp" class="form-text mb-3">Silahkan pilih Nomor RPS.</div>
                        </div>
                    </div>
                    <div class="form-group w-50 mb-0">
                        <div class="form-floating">
                            <input type="text" name="minggu" value="{{old('minggu')}}" class="form-control" id="minggu" placeholder="minggu" aria-describedby="mingguHelp">
                            <label for="minggu" class="form-label">Minggu Ke- <span style="color:red">*</span></label>
                            @error('minggu')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="form-group w-50">
                        <div class="form-floating">
                            <input type="number" value="{{old('bobot')}}" min="0" name="bobot" class="form-control" id="bobot" placeholder="bobot" aria-describedby="bobotHelp">
                            <label for="bobot" class="form-label">Bobot</label>
                            @error('bobot')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group w-50">
                        <div class="form-floating">
                            <input type="text" value="{{old('kriteria')}}" name="kriteria" class="form-control" id="kriteria" placeholder="kriteria" aria-describedby="kriteriaHelp">
                            <label for="kriteria" class="form-label">Kriteria <span style="color:red">*</span></label>
                            @error('kriteria')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="form-group w-50">
                        <div class="form-floating h-100">
                            <textarea name="sub_cpmk" id="sub_cpmk" class="form-control" style="height: 85%" placeholder="insert sub_cpmk">{{old('sub_cpmk')}}</textarea>
                            <label for="sub_cpmk" class="form-label">Rincian Sub CPMK <span style="color:red">*</span></label>
                            @error('sub_cpmk')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div id="sub_cpmkHelp" class="form-text">Silahkan masukkan rincian Sub CPMK.</div>
                        </div>
                    </div>
                    <div class="form-group w-50">
                        <div id="dynamic-metode_luring" class="form-group mb-1">
                                <div class="form-floating">
                                    <input type="text" value="{{old('metode_luring')}}" name="metode_luring" class="form-control" id="metode_luring" placeholder="metode_luring">
                                    <label for="metode_luring" class="form-label"> Metode Luring </label>
                                </div>
                            @error('metode_luring')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div id="luringHelp" class="form-text">Silahkan masukkan metode pembelajaran luring.</div>
                        </div>
                        <div id="dynamic-metode_daring" class="form-group mb-1">
                                <div class="form-floating">
                                    <input type="text" value="{{old('metode_daring')}}" name="metode_daring" class="form-control" id="metode_daring" placeholder="metode_daring">
                                    <label for="metode_daring" class="form-label"> Metode Daring </label>
                                </div>
                            @error('metode_daring')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div id="daringHelp" class="form-text">Silahkan masukkan metode pembelajaran daring.</div>
                        </div>
                    </div>
                </div>

                <div id="dynamic-indikator" class="form-group">
                    <div class="form-group row">
                        <div class="form-floating col-10">
                            <input type="text" name="indikator[0]" class="form-control" id="indikator" placeholder="indikator" required>
                            <label for="indikator" class="form-label ps-4"> Indikator <span style="color:red">*</span></label>
                        </div>
                        <div class="col-2">
                            <button type="button" name="add" id="dynamic-btn-indikator" class="ms-3 mt-2 btn btn-primary">Add Field</button>
                        </div>
                    </div>
                    @error('indikator')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                     <textarea name="materi" id="materi" class="form-control" placeholder="insert materi" style="height: 100px">{{old('materi')}}</textarea>
                    <label for="materi" class="form-label">Materi <span style="color:red">*</span></label>
                    @error('materi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                    <div id="materiHelp" class="form-text">Silahkan masukkan Materi Pembelajaran.</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    var i = 0;
    document.getElementById("excel").onchange = function() {
        document.getElementById("form-import").submit();
    };
    $("#dynamic-btn-indikator").click(function() {
        ++i;
        $("#dynamic-indikator").append('<div class="form-group row clone"><div class="form-floating mb-3 col-10">' +
            '<input type="text" name="indikator[' + i + ']" class="form-control" id="indikator[' + i + ']" placeholder="indikator[' + i + ']">' +
            '<label for="indikator" class="form-label ps-4"> Indikator</label></div>' +
            '<div class="col-2"><button type="button" id="remove-indikator'+[i]+'" class="btn ms-3 mt-2 btn-danger remove-input-indikator d-none">Delete</button></div></div>');
    });


</script>
@endsection
