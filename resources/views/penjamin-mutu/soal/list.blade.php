@extends('penjamin-mutu.template')
@section('content')
<style>
    .btn-list {
        width: 35px;
        height: 35px;
    }
</style>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Soal</h4>
            <div class="table-responsive">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Minggu ke-</th>
                            <th>Jenis Ujian</th>
                            <th>Dosen</th>
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
                            <td>{{$soal->mk->kode}}</td>
                            <td>{{$soal->mk->nama}}</td>
                            <td>{{$soal->minggu}}</td>
                            <td>{{$soal->jenis}}</td>
                            <td>{{$soal->dosen}}</td>
                            @if($soal->status == 'Belum')
                            <td class="py-4 d-flex">
                                <button type="button" class="btn btn-list btn-info btn-icon-text p-2 me-2" data-bs-toggle="modal" data-bs-target="#exampleModal{{$soal->id}}">
                                    <i class="ti-eye btn-icon"></i>
                                </button>
                                {{-- @foreach($soals as $soal) --}}
                                <div class="modal fade" id="exampleModal{{$soal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header" style="display: block;">
                                                <button type="button" class="btn-close row mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <table style="width:100%; height:3cm; border: 1px solid black; border-collapse: collapse;">
                                                    <tr class="blue header" style="vertical-align: top; background-color: rgb(221, 235, 247)">
                                                        <th style="border: 1px solid black; border-collapse: collapse;"><img style="width:1.91cm; height:1.91cm" src="{{asset('/assets/img/logo_unila.png')}}" alt=""></th>
                                                        <th style="border: 1px solid black; border-collapse: collapse;">
                                                            <div>UNIVERSITAS LAMPUNG</div>
                                                            <div>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</div>
                                                            <div>JURUSAN ILMU KOMPUTER</div>
                                                            <div>PRODI <?= strtoupper($soal->prodi) ?></div>
                                                        </th>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-body">
                                                <div class="main">
                                                    <div style="font-weight:700; text-align:center; margin: 15px 0 45px 0"><?= strtoupper($soal->jenis . " " . $soal->mk->nama) ?></div>
                                                    <table class="non-border" style="border-collapse: collapse;">
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse;">Kode Mata Kuliah</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse;">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">{{$soal->mk->kode}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse;">Nama Mata Kuliah</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse;">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">{{$soal->mk->nama}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse; vertical-align:top">CPMK</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse; vertical-align:top">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">
                                                                @php
                                                                $sl = App\Models\Soal::findOrFail($soal->id);
                                                                $mks = App\Models\MK::all();
                                                                $cpmks = collect();
                                                                $soals = collect();
                                                                $cpmk_soals = collect();
                                                                foreach ($mks as $mk) {
                                                                if ($sl->kode_mk == $mk->kode) {
                                                                    $soalss = App\Models\Soal::where('kode_mk', $mk->kode)->where('jenis', $sl->jenis)->orderBy('id', 'asc')->get();
                                                                    }
                                                                }
                                                                foreach ($soalss as $s) $soals->push($s);
                                                                foreach ($soals as $sl) {
                                                                $temp = DB::table('cpmk_soals')->select(DB::raw('id_cpmk, id_soal'))->groupBy('id_cpmk')->orderBy('id_cpmk', 'asc')->get();
                                                                $cpmk_s = $temp->where('id_soal', $sl->id);
                                                                foreach ($cpmk_s as $cpmk) $cpmk_soals->push($cpmk);
                                                                }
                                                                foreach ($cpmk_soals as $c_s) {
                                                                $cpmkss = App\Models\CPMK::where('id', $c_s->id_cpmk)->get();
                                                                foreach ($cpmkss as $cp) $cpmks->push($cp);
                                                                }
                                                                @endphp
                                                                <ol>
                                                                    @foreach($cpmks as $cpmk)
                                                                    <li>{{$cpmk->judul}}</li>
                                                                    @endforeach
                                                                </ol>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse;">Minggu ke-</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse;">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">{{$soal->minggu}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse;">Jenis Ujian</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse;">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">{{$soal->jenis}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="non-border" style="padding-left: 85px; border-collapse: collapse;">Dosen</td>
                                                            <td class="non-border" style="padding-left: 40px; border-collapse: collapse;">:</td>
                                                            <td class="non-border" style="padding-left: 13px; border-collapse: collapse;">{{$soal->dosen}}</td>
                                                        </tr>
                                                    </table>
                                                    <div style="padding-left:75px; margin-top:35px;">
                                                        <table style="width:100%; text-align:left; border: 1px solid black; border-collapse: collapse;">
                                                            <tr>
                                                                <th class="border: 1px solid black; border-collapse: collapse;">Deskripsi Kuis: </th>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:15px">
                                                                    @foreach($cpmk_soals as $no=>$cs)
                                                                    @php
                                                                    $id_soals = DB::table('cpmk_soals')->where('id_cpmk', $cs->id_cpmk)->get();
                                                                    $soalforcpmks = collect();
                                                                    foreach($id_soals as $is) {
                                                                    $soalforcpmk = DB::table('soals')->where('id', $is->id_soal)->get();
                                                                    foreach ($soalforcpmk as $sfor) $soalforcpmks->push($sfor);
                                                                    }
                                                                    @endphp
                                                                    @if($no == 0)
                                                                    <div class="desc-contain">Soal CPMK {{$no+1}}:</div>
                                                                    <ol class="start">
                                                                        @foreach($soalforcpmks as $sfc)
                                                                        <li>{{$sfc->pertanyaan}}</li>
                                                                        @endforeach
                                                                    </ol>
                                                                    @else
                                                                    <div class="desc-contain" style="margin-top: 15px">Soal CPMK {{$no+1}}</div>
                                                                    <ol class="start">
                                                                        @foreach($soalforcpmks as $sfc)
                                                                        <li>{{$sfc->pertanyaan}}</li>
                                                                        @endforeach
                                                                    </ol>
                                                                    @endif
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endforeach --}}
                                <form action="/penjamin-mutu/validasi-soal/{{$soal->id}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-list btn-success me-2 btn-icon-text p-2">
                                        <i class="ti-check btn-icon"></i>
                                    </button>
                                </form>
                                <button class="btn btn-list btn-danger btn-icon-text p-2" data-bs-toggle="modal" data-bs-target="#example1Modal{{$soal->id}}">
                                    <i class="ti-close btn-icon"></i>
                                </button>
                                {{-- @foreach($soals as $soal) --}}
                                <div class="modal fade" id="example1Modal{{$soal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Tidak Valid</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="/penjamin-mutu/tolak-soal/{{encrypt($soal->id)}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                    <textarea required class="form-control" name="komentar" id="message-text" style="height: 100px;"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" style="width:95.98px; height:44px" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endforeach --}}
                            </td>
                        </tr>
                        {{-- @dd($soal->status) --}}
                        @elseif($soal->status == 'Valid')
                        <td>Telah tervalidasi</td>
                        @else
                        <td>Telah ditolak, menunggu perbaikan</td>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
