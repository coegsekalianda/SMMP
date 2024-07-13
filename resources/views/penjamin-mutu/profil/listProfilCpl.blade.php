@extends(auth()->user()->otoritas === 'Dosen' ? 'dosen.template' : 'penjamin-mutu.template')
@section('content')
@if (session()->has('failed'))
    <div class="alert alert-danger" role="alert" id="box">
        <div>{{session('failed')}}</div>
    </div>
@elseif (session()->has('success'))
    <div class="alert greenAdd" role="alert" id="box">
        <div>{{session('success')}}</div>
    </div>
@endif



<h3 class="px-4 pb-4 fw-bold text-center">Halaman List Profil Kompetensi</h3>
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->otoritas === 'Penjamin Mutu')
                    <button type="submit" class="btn btn-success" onclick="create()">Add Competency Profile</button>
                @endif
                <div id="read" class="mt-3"></div>
            </div>
        </div>
    </div>

{{-- MODAL UNTUK TAMBAH DATA --}}
 
<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div id="page" class="p-2"></div>
      </div>
    </div>
  </div>
</div>


<script>
     $(document).ready(function(){
        read();
    });
    // Read
    function read(){
        $.get("{{url('readListProfilCpl')}}",{},function(data,status){
            console.log(data);
            $('#read').html(data);
        });
    }

    // MODAL HALAMAN  CREATE
    function create(){
            $.get("{{url('createListProfilCpl')}}",{},function(data,status){
                $("#exampleModalLabel").html("Add Competency Profile").css("font-weight", "bold");
                // console.log(data);
                $('#page').html(data);
                $('#tambahModal').modal('show');
            });
        }

    // PROSES SIMPAN
    function store() {
        var idProfil  = $("#idProfil").val();
        var idCpl = $("#idCpl").val();
        var bobot = $("#bobot").val();
        // console.log(bobot);

        $.ajax({
            type: "GET",
            url: "{{url('storeListProfilCpl')}}",
            data : {'idProfil': idProfil,'idCpl': idCpl, 'bobot': bobot},
            success: function (data) {
                    // console.log("Sukesess");
                    // console.log(data);
                    // otomatis tombol close di klik
                    $('.btn-close').click();

                    //TODO: otomatis reload halaman ini aktifikn lagi
                    read();
                },
        error: function (xhr, status, error) {
            if (xhr.status === 422) {
                // Tangkap pesan kesalahan validasi dari respons JSON
                var validationErrors = xhr.responseJSON.validation_errors;
                var errorMessage = "Gagal menyimpan data. Kesalahan Anda:\n";
                // Tampilkan pesan-pesan kesalahan validasi
                for (var key in validationErrors) {
                    errorMessage += "- " + validationErrors[key][0] + "\n";
                }

                alert(errorMessage);
            } else {
                
                alert("Gagal menyimpan data");
            }
        }
        });
    }

     //proses edit (munculin modal edit)
    function showProfilCpl(id) {
        $.get("{{ url('showProfilCpl') }}/" + id, {}, function(data, status) {
            console.log(data);
            $("#exampleModalLabel").html("Edit Competency Profile").css("font-weight", "bold");
            $('#page').html(data);
            $('#tambahModal').modal('show');
        });
    }


    // SIMPAN EDIT
    function updateProfilCpl(id) {
        var idProfil  = $("#idProfil").val();
        var idCpl = $("#idCpl").val();
        var bobot = $("#bobot").val();
        // console.log(idProfil, idCpl,bobot);
        $.ajax({
            type: "GET",
            url: "{{url('updateProfilCpl')}}/"+id,
            data : {'idProfil': idProfil,'idCpl': idCpl,'bobot': bobot},
            success: function (data) {
                    // console.log(data);
                    // otomatis tombol close di klik
                    $('.btn-close').click();
                    // otomatis reload halaman
                    read();
                },
        error: function (xhr, status, error) {
            if (xhr.status === 422) {
                // Tangkap pesan kesalahan validasi dari respons JSON
                var validationErrors = xhr.responseJSON.validation_errors;
                var errorMessage = "Gagal menyimpan data. Kesalahan Anda:\n";
                // Tampilkan pesan-pesan kesalahan validasi
                for (var key in validationErrors) {
                    errorMessage += "- " + validationErrors[key][0] + "\n";
                }

                alert(errorMessage);
            } else {
                
                alert("Gagal menyimpan data");
            }
        }
        });
    }

     // Untuk hapus data
    function deleteProfilCpl(id) {
        confirm("Apakah yakin ingin hapus data?")
        $.ajax({
            type: "GET",
            url: "{{url('deleteProfilCpl')}}/"+id,
            success: function (data) {
                    // console.log(data);
                    // otomatis tombol close di klik
                    $('.btn-close').click();
                    // otomatis reload halaman
                    read();
                }
        });}
</script>

@endsection