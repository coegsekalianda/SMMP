<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CPL;
use App\Models\MK;
use App\Models\RPS;
use App\Models\CPLMK;
use Illuminate\Http\Request;

class CplmkController extends Controller
{
    public function create()
    {
        $rpss = RPS::all();
        $mks = collect();
        foreach ($rpss as $rps) {
            $mk = MK::where('kode', $rps->kode_mk)->firstOrFail();
            $mks->push($mk);
        }
        $cpls = CPL::orderBy('aspek', 'desc')->get();
        return view('admin.cplmk.add', compact('mks', 'cpls'));
    }

    public function list()
    {
        $rpss = RPS::all();
        $mks = collect();
        foreach ($rpss as $rps) {
            $mk = MK::where('kode', $rps->kode_mk)->firstOrFail();
            $mks->push($mk);
        }
        $cplmks = collect();
        foreach ($mks as $mk) {
            $kode_mk = $mk->kode;
            $temp = CPLMK::where('kode_mk', $kode_mk)->get();
            foreach ($temp as $cplmk) {
                $cplmks->push($cplmk);
            }
        }
        
        $cpls = CPL::all();
        return view('admin.cplmk.list', compact('cplmks', 'mks', 'cpls'));
    }

    public function store(Request $request)
    {   
        for ($i = 0; $i < count($request->input('id_cpl')); $i++) {
            // dd($request->kode_mk);
            CPLMK::create([
                'kode_mk' => $request->kode_mk,
                'id_cpl' => $request->input('id_cpl')[$i],
            ]);
        }
        return redirect('/admin/list-cplmk')->with('success', 'CPL successfully added!');
    }

    public function delete($id)
    {
        CPLMK::where('id', $id)->delete();
        return redirect('/admin/list-cplmk')->with('success', 'Kode CPL successfully removed!');
    }
}
