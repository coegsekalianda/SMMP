@extends('admin.template')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit CPL Prodi</h4>
            <form method="POST" action="{{encrypt($cpl->id)}}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group row">
                    <div class="col">
                        <label>Aspek <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="aspek" value="Sikap" {{ $cpl->aspek == "Sikap" ? 'checked' : '' }}>
                                Sikap
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="aspek" value="Pengetahuan" {{ $cpl->aspek == "Pengetahuan" ? 'checked' : '' }}>
                                Pengetahuan
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="aspek" value="Umum" {{ $cpl->aspek == "Umum" ? 'checked' : '' }}>
                                Umum
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="aspek" value="Keterampilan" {{ $cpl->aspek == "Keterampilan" ? 'checked' : '' }}>
                                Keterampilan
                            </label>
                        </div>
                        @error('aspek')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Tahun kurikulum <span class="text-danger">*</span></label>
                            <select class="js-example-basic-single w-100" required name="kurikulum">
                                <option selected="true" value="" disabled selected>Select...</option>
                                @foreach($kurikulums as $kurikulum)
                                <option value="{{$kurikulum->tahun}}" {{ $cpl->kurikulum == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                @endforeach
                            </select> @error('kurikulum')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            
                        </div>
                        <div class="form-group">
                            <label>Kode <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="codes" class="input-group-text">CPL-</span>
                                </div>
                                <input type="text" class="form-control" name="kode" placeholder="Kode" value="{{$cpl->nomor}}" autocomplete="off">
                            </div>
                            @error('kode')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Judul <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="judul" placeholder="Judul" value="{{$cpl->judul}}" autocomplete="off">
                    @error('judul')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <input type="submit" class="btn btn-primary me-2" value="Submit">
            </form>
        </div>
    </div>
</div>
<script src="{{asset('/node_modules/jquery/dist/jquery.min.js')}}"></script>
<!-- <script>
    $(document).ready(function() {
        if ($("input[name='aspek']:checked").val() == 'Sikap')
            $("#codes").text("S");
        else if ($("input[name='aspek']:checked").val() == 'Umum')
            $("#codes").text("KU");
        else if ($("input[name='aspek']:checked").val() == 'Pengetahuan')
            $("#codes").text("P");
        else
            $("#codes").text("KK");
        $("input[name='aspek']").change(function() {
            if ($("input[name='aspek']:checked").val() == 'Sikap')
                $("#codes").text("S");
            else if ($("input[name='aspek']:checked").val() == 'Umum')
                $("#codes").text("KU");
            else if ($("input[name='aspek']:checked").val() == 'Pengetahuan')
                $("#codes").text("P");
            else
                $("#codes").text("KK");
        });
    });
</script> -->
@endsection