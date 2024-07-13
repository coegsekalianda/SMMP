<style>
    .table-responsive table td.wrap-content {
        white-space: normal;
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Profile Name</th>
                <th>Descriptions</th>
                @if(auth()->user()->otoritas === 'Penjamin Mutu')
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if($listProfil->isEmpty())
                <tr>
                    <td colspan="{{ auth()->user()->otoritas === 'Penjamin Mutu' ? 4 : 3 }}" style="text-align: center;">Tidak ada data</td>
                </tr>
            @else
                @foreach($listProfil as $key => $profil)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucfirst($profil->namaProfil) }}</td>
                        <td class="wrap-content">{{ ucfirst($profil->deskripsi) }}</td>
                        @if(auth()->user()->otoritas === 'Penjamin Mutu')
                            <td>
                                <button type="submit" class="btn btn-sm btn-warning" onclick="showProfil({{$profil->id}})">Edit</button>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="deleteProfil({{$profil->id}})">Delete</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>