<div class="p2">
    <div class="form-group">
        <label for="namaProfil">Profile Name :</label>
        <input type="text" value="{{old('namaProfil')}}"  name="namaProfil" id="namaProfil" class="form-control" placeholder="Profile / Profession Name">
        @error('namaProfil')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="deskripsi">Profile Description :</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" style="height: 100px" placeholder="Profile Description">{{old('deskripsi')}}</textarea>
         @error('deskripsi')
            <div class="alert alert-danger">
                 {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success mt-2" onclick="store()">Tambah Profil Lulusan</button>
    </div>
</div>
