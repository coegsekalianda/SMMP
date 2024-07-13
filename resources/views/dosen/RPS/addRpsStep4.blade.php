@extends('dosen.template')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Tambah RPS Baru</h3>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="add-rpsStep4" enctype="multipart/form-data">
                @csrf
                <div class="form-floating mb-3">
                    <button type="button" class="btn btn-secondary btn-sm mb-3" data-bs-toggle="tooltip" data-bs-placement="left" title="Gilbert Strang, Introduction to Linear Algebra (Wellesley-Cambridge Press, Wellesley, MA, 2020)" style="float:right;"> Contoh Pengisian
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                    </svg>
                    </button>
                    <label for="pustaka_utama" class="form-label">Pustaka utama <span class="text-danger">*</span></label>
                    <textarea name="pustaka_utama" class="form-control" style="height:100px" placeholder="Pustaka utama">{{ old('pustaka_utama', session('step4_dataRps.pustaka_utama')) }}</textarea>
                    @error('pustaka_utama')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <textarea name="pustaka_pendukung" class="form-control" style="height:100px" placeholder="Pustaka pendukung">{{ old('pustaka_pendukung', session('step4_dataRps.pustaka_pendukung')) }}</textarea>
                    <label for="pustaka_pendukung">Pustaka pendukung</label>
                    @error('pustaka_pendukung')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="button" class="btn btn-primary" onclick="location.href='{{ route('addRpsStep3') }}'">Previous</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
     $(document).ready(function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
    });
</script>
@endsection
