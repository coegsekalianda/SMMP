<?php

namespace App\Http\Controllers\PenjaminMutu;

use App\Models\ProfilCpl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfilCplController extends Controller
{
    public function indexListProfilCpl()
    {   
        return view('penjamin-mutu.profil.listProfilCpl');
    }
    public function readListProfilCpl()
    {
        try {
            $listProfilCpl = DB::table('profil_cpl')
            ->join('profil_lulusan', 'profil_cpl.idProfil', '=', 'profil_lulusan.id')
            ->join('cpls', 'profil_cpl.idCpl', '=', 'cpls.id')
            ->select('profil_lulusan.namaProfil', 'cpls.kode', 'cpls.judul', 'profil_cpl.bobot','profil_cpl.id')
            ->get();

            return view('penjamin-mutu.profil.readListProfilCpl', ['listProfilCpl' => $listProfilCpl]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
    public function createListProfilCpl()
    {   
        $profil  = DB::table('profil_lulusan')
                ->select('id', 'namaProfil')
                ->distinct()
                ->get();  
        $cplData = DB::table('cpls')
                ->select('id','kode','judul')
                ->distinct()
                ->get();
        // return "Masuk sini";
        return view('penjamin-mutu.profil.createListProfilCpl',['profil'=>$profil, 'cplData'=>$cplData]);
    }

    public function storeListProfilCpl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idProfil' => 'required',
            'idCpl' => [
                'required',
                Rule::unique('profil_cpl')->where(function ($query) use ($request) {
                    return $query->where('idProfil', $request->idProfil);
                }),
            ],
            'bobot' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $totalBobot = ProfilCpl::where('idProfil', $request->idProfil)->sum('bobot');
                    if (($totalBobot + $value) > 1) {
                        $fail('Total bobot tidak boleh melebihi 1 untuk Profil ini.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Gagal menyimpan data', 'validation_errors' => $validator->errors()], 422);
        }

        try {
            ProfilCpl::create($request->all());
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data'], 422);
        }
    }


    public function showProfilCpl($id)
    {
        // return "Masuk sini kok";
        $profilCpl = ProfilCpl::findOrFail($id);

        $profil = DB::table('profil_lulusan')
                ->select('id', 'namaProfil')
                ->distinct()
                ->get();
        // return $profil;
        $cplData = DB::table('cpls')
                ->select('id', 'kode', 'judul')
                ->distinct()
                ->get();
        return view('penjamin-mutu.profil.editProfilCpl', ['profilCpl' => $profilCpl,
        'profil'=>$profil,'cplData'=>$cplData]);
    }

    public function updateProfilCpl(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'idProfil' => 'required',
            'idCpl' => [

                'required',
            ],
            'bobot' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Gagal menyimpan data', 'validation_errors' => $validator->errors()], 422);
        }

        try {
            $profilCpl = ProfilCpl::findOrFail($id);
            $profilCpl->idProfil = $request->idProfil;
            $profilCpl->idCpl = $request->idCpl;
            $profilCpl->bobot = $request->bobot;
            $profilCpl->save();

            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Gagal menyimpan data'], 422);
        }
    }

    public function deleteProfilCpl($id)
    {
        $profil = ProfilCpl::findOrFail($id);
        $profil->delete();
    }
}
