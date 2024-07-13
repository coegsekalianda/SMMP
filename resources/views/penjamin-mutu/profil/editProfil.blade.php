<div class="p2">
    <div class="form-group">
        <label for="namaProfil">Profile Name :</label>
        <input type="text" value="{{$profil->namaProfil}}"  name="namaProfil" id="namaProfil" class="form-control" placeholder="Profile / Profession Name">
        @error('namaProfil')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="deskripsi">Profile Description :</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" style="height: 100px" placeholder="Profile Description">{{$profil->deskripsi}}</textarea>
         @error('deskripsi')
            <div class="alert alert-danger">
                 {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-warning mt-2" onclick="updateProfil({{$profil->id}})">Update</button>
    </div>
</div>
