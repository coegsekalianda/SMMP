@extends('dosen.template')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
        <form action="{{ route('filterSoal')}}" method="get">
                @csrf
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label for="" class="form-table">Nama MK</label>

                        <input name="course" type="text" class="form-control" value="{{isset($_GET['course']) ? $_GET['course'] : ''}}">
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary mt-4">Search</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Tabel ini berisi nilai mahasiswa yang telah di import" style="float:right;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
            <h4 class="card-title">List Soal</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama MK</th>
                            <th>Pertanyaan</th>
                            <th>Bobot Soal</th>
                            <th>Minggu ke-</th>
                            <th>Jenis Ujian</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        // $kode_mk = 0;
                        foreach($soals as $no=>$soal):
                        // foreach($mks as $mk):
                        // if($soal->kode_mk == $mk->kode)
                        // $kode_mk = $mk->kode;
                        // $nama_mk = $mk->nama;
                        // endforeach
                        @endphp
                        <tr>
                            <td class="py-4">{{$no+1}}</td>
                            <td>{{$soal->mk->nama}}</td>
                            <td><a href="cetakSoal/{{$soal->id}}" target="_blank">Klik untuk melihat soal</a></td>
                            <td>{{$soal->bobotSoal}}</td>
                            <td>{{$soal->minggu}}</td>
                            <td>{{$soal->jenis}}</td>
                            @if($soal->status == 'Belum')
                            <td>Menunggu validasi</td>
                            @elseif($soal->status == 'Valid')
                            <td>Soal telah tervalidasi</td>
                            @else
                            <td class="py-4">
                                <div class="me-2">Soal ditolak</div>
                            </td>
                            @endif
                            @if($soal->status == 'Valid')
                            <td class="py-4 d-flex">
                                <a href="/dosen/print-soal/{{encrypt($soal->id)}}" target="_blank" type="button" class="btn btn-info btn-icon-text p-2">
                                    <i class="ti-download btn-icon"></i>
                                </a>
                            </td>
                            @elseif($soal->status == 'Tolak')
                            <td class="py-4 d-flex">
                                 <a href="/dosen/soal/edit-soal/{{$soal->id}}" type="button" class="btn btn-warning me-2 btn-icon-text p-2">
                                    <i class="ti-pencil btn-icon"></i>
                                </a>
                                <form action="delete-rps/{{$soal->id}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete soal ?')">
                                        <i class="ti-trash btn-icon"></i>
                                    </button>
                                </form>
                                <button class="btn btn-warning btn-icon-text p-2" data-bs-toggle="modal" data-bs-target="#exampleModal{{$soal->id}}">
                                    <i class="ti-alert btn-icon"></i>
                                </button>
                            </td>
                            @else
                            <td class="py-4 d-flex">
                                <a href="/dosen/soal/edit-soal/{{$soal->id}}" type="button" class="btn btn-warning me-2 btn-icon-text p-2">
                                    <i class="ti-pencil btn-icon"></i>
                                </a>
                                <form action="delete-soal/{{$soal->id}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-icon-text p-2 me-2" onclick="return confirm('Are you sure to delete soal ?')">
                                        <i class="ti-trash btn-icon"></i>
                                    </button>
                                </form>
                                </a>

                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pagination">
            {{ $soals->links() }}
        </div>
    </div>
</div>
@foreach($soals as $soal)
<div class="modal fade" id="exampleModal{{$soal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{$soal->komentar}}
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
