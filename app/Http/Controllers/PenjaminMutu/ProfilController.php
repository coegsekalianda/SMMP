<?php

namespace App\Http\Controllers\PenjaminMutu;

use App\Http\Controllers\Controller;
use App\Models\ProfilLulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{

    public function indexListProfil()
    {
        return view('penjamin-mutu.profil.listProfilLulusan');
    }
    public function readListProfil(){
        $listProfil = ProfilLulusan::all();

        return view ('penjamin-mutu.profil.readListProfil', ['listProfil' => $listProfil]);
    }
    public function createListProfil(){
        return view('penjamin-mutu.profil.createListProfil');
    }
    public function storeListProfil(Request $request){
        // Validasi
        $validator = Validator::make($request->all(), [
            'namaProfil' => 'required|string|unique:profil_lulusan',
            'deskripsi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Gagal menyimpan data', 'validation_errors' => $validator->errors()], 422);
        }
        try {
            ProfilLulusan::create($request->all());
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data'], 422);
        }

    }

    public function showProfil($id){
       
        $profil = ProfilLulusan::findOrFail($id);
        return view('penjamin-mutu.profil.editProfil', ['profil' => $profil]);
    }

    public function updateProfil(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'namaProfil' => 'required|string|unique:profil_lulusan,namaProfil,' . $id,
            'deskripsi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Gagal menyimpan data', 'validation_errors' => $validator->errors()], 422);
        }

        try {
            $profil = ProfilLulusan::findOrFail($id);
            $profil->namaProfil = $request->namaProfil;
            $profil->deskripsi = $request->deskripsi;
            $profil->save();

            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        } catch (\Exception $e) {
           
            return response()->json(['error' => 'Gagal menyimpan data'], 422);
        }
    }

    public function deleteProfil($id){
        $profil = ProfilLulusan::findOrFail($id);
        $profil->delete();
    }
   
}
