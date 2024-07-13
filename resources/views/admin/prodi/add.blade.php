@extends('admin.template')
@section('content')

<style>
    li.select2-selection__choice {
        color: #646464;
        font-weight: bolder;
    }
</style>

<div class="container-fluid  mb-4">
    <div class="card">
        <div class="card-header">
            <div class="fw-bold">
                <h3>Tambah Prodi</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <label> Nama <span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="nama" name="nama" required>
                <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('nama').addEventListener('input', function() {
    var input = this;
    var words = input.value.split(' ');
    for (var i = 0; i < words.length; i++) {
        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
    }
    input.value = words.join(' ');
});
</script>
@endsection
