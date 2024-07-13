<div class="table-responsive" >
        <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Profile Name</th>
                            <th scope="col">CPL Code</th>
                            <th scope="col">Titles of CPL</th>
                            <th scope="col">Profile Weights</th>
                           @if(auth()->user()->otoritas === 'Penjamin Mutu')
                            <th>Action</th>
                           @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if($listProfilCpl->isEmpty())
                        <tr>
                            <td colspan="{{ auth()->user()->otoritas === 'Penjamin Mutu' ? 6 : 5 }}" style="text-align: center;">Tidak ada data</td>
                        </tr>
                        @else
                            @foreach ($listProfilCpl as $index => $profilCpl)
                                <tr>
                                    <td scope="row">{{ $index + 1 }}</td>
                                    <td>{{ ucfirst($profilCpl->namaProfil) }}</td>
                                    <td>{{ $profilCpl->kode }}</td>
                                    <td>{{ $profilCpl->judul }}</td>
                                    <td>{{ $profilCpl->bobot }}</td>
                                    @if(auth()->user()->otoritas === 'Penjamin Mutu')
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-warning" onclick="showProfilCpl({{$profilCpl->id}})">Edit</button>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="deleteProfilCpl({{$profilCpl->id}})">Delete</button>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
        </table>
</div>