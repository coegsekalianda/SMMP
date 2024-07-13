@extends('admin.template')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <p class="card-description">
                (Dosen & Penjamin Mutu)
            </p>
            <form method="POST" action="{{ route('add-user') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="Name" :value="old('name')" autofocus autocomplete="off">
                    @error('name')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" placeholder="Email" :value="old('email')" autocomplete="off">
                    @error('email')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password">
                    @error('password')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Otoritas <span class="text-danger">*</span></label>
                    <select class="js-example-basic-single w-100" name="otoritas" id="otoritas">
                        <option selected="true" value="" disabled selected>Select...</option>
                        <option value="Dosen" {{ old('otoritas') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Penjamin Mutu" {{ old('otoritas') == 'Penjamin Mutu' ? 'selected' : '' }}>Penjamin Mutu</option>
                    </select>
                    @error('otoritas')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Jabatan<span class="text-danger">*Wajib Diisi jika memiliki jabatan*</span></label>
                    <select class="js-example-basic-single w-100" name="jabatan" id="jabatan">
                        <option selected="true" value="" disabled selected>Select...</option>
                        <option value="">Tidak Ada Jabatan</option>
                        <option value="Kaprodi" {{ old('jabatan') == 'Kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                    </select>
                    @error('jabatan')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
               <div class="form-group" id="prodi-form" >
                    <label>Prodi<span class="text-danger">*</span></label>
                    <select class="js-example-basic-single w-100" name="prodi" required>
                        <option selected="true" value="" disabled>Select...</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id }}" {{ old('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group" id="universitas-form" >
                    <label>Universitas<span class="text-danger">*</span></label>
                    <select class="js-example-basic-single w-100" name="universitas" required>
                        <option selected="true" value="" disabled>Select...</option>
                        @foreach($universitas as $u)
                            <option value="{{ $u->id }}" {{ old('universitas') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                    @error('universitas')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" accept="image/png, image/jpeg" name="img" class="form-control" style="padding-bottom: +27px">
                    @error('img')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <button class="btn btn-primary me-2">{{ __('Register') }}</button>
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
