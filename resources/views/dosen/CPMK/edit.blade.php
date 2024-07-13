@extends('dosen.template')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Edit CPMK</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{$cpmk->id}}" method="post">
                @csrf
                @method('put')
                <div class="form-floating mb-3">
                    <select id="mataKuliah" name="kode_mk" class="form-select form-control-lg" aria-label="select Mata Kuliah">
                        <option selected value="{{$cpmk->kode_mk}}">{{$cpmk->mk}}</option>
                    </select>
                    <label for="mataKuliah">Mata Kuliah</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="judul" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"> {{$cpmk->judul}} </textarea>
                    <label for="floatingTextarea2">Rincian CPMK <span class="text-danger">*</span></label>
                </div>
                @error('judul')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
                @enderror
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
