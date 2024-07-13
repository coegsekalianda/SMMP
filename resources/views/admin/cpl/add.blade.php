@extends('admin.template')
@section('content')
<?php $s = 0; ?>
<h3 class="px-4 pb-4 fw-bold text-center">Add CPL Prodi</h3>
<div class="form-group stretch-card" id="sikap">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Sikap</h4>
            <form method="POST" action="add-cpl" enctype="multipart/form-data">
                @csrf
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemoveSik">
                    <div class="form-group row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>Tahun kurikulum <span class="text-danger">*</span></label>
                                <select class="form-control" name="kurikulum[0]">
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach($kurikulums as $kurikulum)
                                    <option value="{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Kode <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">CPL-</span>
                                    </div>
                                    <input type="text" class="form-control" name="kode[0]" placeholder="Nomor" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul[0]" placeholder="Judul" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-2">
                            <label>Action</label>
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar-sik" class="btn btn-sm btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a = 0;?>
                @else
                <div id="dynamicAddRemoveSik">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if ((old('kurikulum.' . $i) != null || old('kode.' . $i) != null || old('judul.' . $i) != null) && old('aspek') == 'Sikap') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-sik" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                            $s = 1;
                        } elseif ((old('kurikulum.' . ($i + 1)) != null || old('kode.' . ($i + 1)) != null || old('judul.' . ($i + 1)) != null) && old('aspek') == 'Sikap') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-sik" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                            $s = 1;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                <input hidden type="text" name="aspek" value="Sikap">
                <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
            </form>
        </div>
    </div>
</div>
<div class="form-group stretch-card" id="umum">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Umum</h4>
            <form method="POST" action="add-cpl" enctype="multipart/form-data">
                @csrf
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemoveUmm">
                    <div class="form-group row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>Tahun kurikulum <span class="text-danger">*</span></label>
                                <select class="form-control" name="kurikulum[0]">
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach($kurikulums as $kurikulum)
                                    <option value="{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Kode <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">CPL-</span>
                                    </div>
                                    <input type="text" class="form-control" name="kode[0]" placeholder="Nomor" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul[0]" placeholder="Judul" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-2">
                            <label>Action</label>
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar-umm" class="btn btn-sm btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a = 0; ?>
                @else
                <div id="dynamicAddRemoveUmm">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if ((old('kurikulum.' . $i) != null || old('kode.' . $i) != null || old('judul.' . $i) != null) && old('aspek') == 'Umum') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-umm" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                            $s = 2;
                        } elseif ((old('kurikulum.' . ($i + 1)) != null || old('kode.' . ($i + 1)) != null || old('judul.' . ($i + 1)) != null) && old('aspek') == 'Umum') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-umm" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                            $s = 2;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                <input hidden type="text" name="aspek" value="Umum">
                <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
            </form>
        </div>
    </div>
</div>
<div class="form-group stretch-card" id="pengetahuan">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pengetahuan</h4>
            <form method="POST" action="add-cpl" enctype="multipart/form-data">
                @csrf
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemovePeng">
                    <div class="form-group row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>Tahun kurikulum <span class="text-danger">*</span></label>
                                <select class="form-control" name="kurikulum[0]">
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach($kurikulums as $kurikulum)
                                    <option value="{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Kode <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">CPL-</span>
                                    </div>
                                    <input type="text" class="form-control" name="kode[0]" placeholder="Nomor" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul[0]" placeholder="Judul" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-2">
                            <label>Action</label>
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar-peng" class="btn btn-sm btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a = 0; ?>
                @else
                <div id="dynamicAddRemovePeng">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if ((old('kurikulum.' . $i) != null || old('kode.' . $i) != null || old('judul.' . $i) != null) && old('aspek') == 'Pengetahuan') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-peng" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                            $s = 3;
                        } elseif ((old('kurikulum.' . ($i + 1)) != null || old('kode.' . ($i + 1)) != null || old('judul.' . ($i + 1)) != null) && old('aspek') == "Pengetahuan") { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-peng" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                            $s = 3;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                <input hidden type="text" name="aspek" value="Pengetahuan">
                <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
            </form>
        </div>
    </div>
</div>
<div class="form-group stretch-card" id="keterampilan">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Keterampilan</h4>
            <form method="POST" action="add-cpl" enctype="multipart/form-data">
                @csrf
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemoveKet">
                    <div class="form-group row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>Tahun kurikulum <span class="text-danger">*</span></label>
                                <select class="form-control" name="kurikulum[0]">
                                    <option selected="true" value="" disabled selected>Select...</option>
                                    @foreach($kurikulums as $kurikulum)
                                    <option value="{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Kode <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">CPL</span>
                                    </div>
                                    <input type="text" class="form-control" name="kode[0]" placeholder="Nomor" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul[0]" placeholder="Judul" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-2">
                            <label>Action</label>
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar-ket" class="btn btn-sm btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a = 0;
                $aspek = 0; ?>
                @else
                <div id="dynamicAddRemoveKet">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if ((old('kurikulum.' . $i) != null || old('kode.' . $i) != null || old('judul.' . $i) != null) && old('aspek') == 'Keterampilan') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-ket" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                            $s = 4;
                        } elseif ((old('kurikulum.' . ($i + 1)) != null || old('kode.' . ($i + 1)) != null || old('judul.' . ($i + 1)) != null) && old('aspek') == 'Keterampilan') { ?>
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tahun Kurikulum<span class="text-danger">*</span></label>
                                        <select class="form-control" name="kurikulum[<?= $i ?>]">
                                            <option selected="true" value="" disabled selected>Select...</option>
                                            @foreach($kurikulums as $kurikulum)
                                            <option value="{{$kurikulum->tahun}}" {{ old('kurikulum.'.$i) == $kurikulum->tahun ? 'selected' : '' }}>{{$kurikulum->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Kode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">CPL-</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode[<?= $i ?>]" value="{{old('kode.'.$i)}}" placeholder="Nomor" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul" autocomplete="off">
                                    </div>
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar-ket" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                            $s = 4;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                <input hidden type="text" name="aspek" value="Keterampilan">
                <input type="submit" class="btn btn-primary" style="margin-top:-4%; margin-bottom:-1%" value="Submit">
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    var i = <?= json_encode($a) ?>;
    var s = <?= json_encode($s) ?>;
    if (s == 1) {
        document.getElementById("umum").hidden = "hidden";
        document.getElementById("pengetahuan").hidden = "hidden";
        document.getElementById("keterampilan").hidden = "hidden";
    } else if (s == 2) {
        document.getElementById("sikap").hidden = "hidden";
        document.getElementById("pengetahuan").hidden = "hidden";
        document.getElementById("keterampilan").hidden = "hidden";
    } else if (s == 3) {
        
        document.getElementById("sikap").hidden = "hidden";
        document.getElementById("umum").hidden = "hidden";
        document.getElementById("keterampilan").hidden = "hidden";
    }
    else if (s == 4) {
        document.getElementById("sikap").hidden = "hidden";
        document.getElementById("umum").hidden = "hidden";
        document.getElementById("pengetahuan").hidden = "hidden";
    } else {
        
    }

    $("#dynamic-ar-sik").click(function() {
        ++i;
        $("#dynamicAddRemoveSik").append('<div class="form-group row clone"><div class="col-2"><div class="form-group"><label>Kurikulum <span class="text-danger">*</span></label><select class="form-control" name="kurikulum[' + i +
            ']"><option selected = "true" value = "" disabled selected> Select... </option> @foreach($kurikulums as $kurikulum) <option value="{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>@endforeach</select></div></div><div class="col-3"><div class="form-group"><label>Kode <span class="text-danger">*</span></label><div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">CPL-</span></div><input type="text" class="form-control" name="kode[' + i +
            ']" placeholder="Nomor" autocomplete="off"></div></div></div><div class="col-5"><div class="form-group"><label>Judul <span class="text-danger">*</span></label><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul" autocomplete="off"></div></div><input hidden type="text" name="aspek" value="Sikap">'
        );
    });


    $("#dynamic-ar-umm").click(function() {
        ++i;
        $("#dynamicAddRemoveUmm").append('<div class="form-group row clone"><div class="col-2"><div class="form-group"><label>Kurikulum <span class="text-danger">*</span></label><select class="form-control" name="kurikulum[' + i +
            ']"><option selected = "true" value = "" disabled selected> Select... </option> @foreach($kurikulums as $kurikulum) <option value = "{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>@endforeach</select></div></div><div class="col-3"><div class="form-group"><label>Kode <span class="text-danger">*</span></label><div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">CPL-</span></div><input type="text" class="form-control" name="kode[' + i +
            ']" placeholder="Nomor" autocomplete="off"></div></div></div><div class="col-5"><div class="form-group"><label>Judul <span class="text-danger">*</span></label><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul" autocomplete="off"></div></div><input hidden type="text" name="aspek" value="Umum">'
        );
    });

    $("#dynamic-ar-peng").click(function() {
        ++i;
        $("#dynamicAddRemovePeng").append('<div class="form-group row clone"><div class="col-2"><div class="form-group"><label>Kurikulum <span class="text-danger">*</span></label><select class="form-control" name="kurikulum[' + i +
            ']"><option selected = "true" value = "" disabled selected> Select... </option> @foreach($kurikulums as $kurikulum) <option value = "{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>@endforeach</select></div></div><div class="col-3"><div class="form-group"><label>Kode <span class="text-danger">*</span></label><div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">CPL-</span></div><input type="text" class="form-control" name="kode[' + i +
            ']" placeholder="Nomor" autocomplete="off"></div></div></div><div class="col-5"><div class="form-group"><label>Judul <span class="text-danger">*</span></label><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul" autocomplete="off"></div></div><input hidden type="text" name="aspek" value="Pengetahuan">'
        );
    });

    $("#dynamic-ar-ket").click(function() {
        ++i;
        $("#dynamicAddRemoveKet").append('<div class="form-group row clone"><div class="col-2"><div class="form-group"><label>Kurikulum <span class="text-danger">*</span></label><select class="form-control" name="kurikulum[' + i +
            ']"><option selected = "true" value = "" disabled selected> Select... </option> @foreach($kurikulums as $kurikulum) <option value = "{{$kurikulum->tahun}}">{{$kurikulum->tahun}}</option>@endforeach</select></div></div><div class="col-3"><div class="form-group"><label>Kode <span class="text-danger">*</span></label><div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">CPL-</span></div><input type="text" class="form-control" name="kode[' + i +
            ']" placeholder="Nomor" autocomplete="off"></div></div></div><div class="col-5"><div class="form-group"><label>Judul <span class="text-danger">*</span></label><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul" autocomplete="off"></div></div><input hidden type="text" name="aspek" value="Keterampilan"><div class="col-2">'
        );
    });
</script>
@endsection