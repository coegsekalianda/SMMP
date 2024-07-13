@extends('admin.template')
@section('content')

<div class="col-lg-12 grid-margin stretch-card mb-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add CPMK</h4>
            <form action="add-cpmk" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="matakuliah">Mata Kuliah <span style="color:red">*</span></label>
                    <select id="matakuliah" name="kode_mk" class="js-example-basic-single w-100">
                        <option selected="true" value="" disabled selected>Select...</option>
                        @foreach ($mks as $mk)
                        <option value="{{$mk->kode}}" {{old('kode_mk') == $mk->kode ? 'selected' : ''}}>{{$mk->nama}}</option>
                        @endforeach
                    </select>
                </div>
                @if(session()->getOldInput() == null)
                <div id="dynamicAddRemove">
                    <div class="form-group row">
                        <div class="col-10">
                            <label for="judul" class="form-label">Judul rincian CPMK <span style="color:red">*</span></label>
                            <input type="text" name="judul[0]" class="form-control" placeholder="Judul rincian CPMK" autocomplete="off">
                        </div>

                        <div class="col-2">
                            <label>Action</label>
                            <div class="form-group">
                                <button type="button" name="add" id="dynamic-ar" class="btn btn-sm btn-primary">Add Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a = 0; ?>
                @else
                <div id="dynamicAddRemove">
                    <?php $x = 0;
                    $i = 0;
                    while ($x == 0) {
                        if (old('judul.' . $i) != null) { ?>
                            <div class="form-group row">
                                <div class="col-10">
                                    <label for="judul" class="form-label">Judul rincian CPMK <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul rincian CPMK" autocomplete="off">
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        <?php ++$i;
                        } elseif (old('judul.' . ($i + 1)) != null) { ?>
                            <div class="form-group row">
                                <div class="col-10">
                                    <label>Judul rincian CPMK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="judul[<?= $i ?>]" value="{{old('judul.'.$i)}}" placeholder="Judul rincian CPMK" autocomplete="off">
                                </div>
                                @if($i == 0)
                                <div class="col-2">
                                    <label>Action</label>
                                    <div class="form-group">
                                        <button type="button" name="add" id="dynamic-ar" class="btn btn-sm btn-primary">Add Field</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                    <?php ++$i;
                        } else {
                            $x = 1;
                            $a = $i - 1;
                        }
                    } ?>
                </div>
                @endif
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    var i = <?= json_encode($a) ?>;
    $("#dynamic-ar").click(function() {
        ++i;
        $("#dynamicAddRemove").append('<div class="form-group row clone"><div class="form-group col-10"><input type="text" class="form-control"name="judul[' + i +
            ']" placeholder="Judul rincian CPMK" autocomplete="off"></div></div>'
        );
    });
</script>
@endsection
