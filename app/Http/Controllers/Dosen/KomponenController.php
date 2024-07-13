<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CPL;
use App\Models\MK;
use App\Models\RPS;
use App\Models\Komponen;

class Komponencontroller extends Controller
{
    public function Add()
    {
        $rpss = RPS::where('dosen', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');

        return view('dosen.jenis.add', compact('rpss'));
    }

    public function List()
    {
        $jenis = Komponen::all();
        
        return view('dosen.jenis.list', compact('jenis'));
    }

    public function Store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $jenis = $request->input('jenis');
        // $validator = Validator::make($data, [
        //     'jenis' => 'required|unique:komponen', 
        // ]);
        // if ($validator->fails()) {
        //     // Handle validation errors
        //     return redirect()->back()->with('error', 'Nama telah digunakan');
        // }
        Komponen::create([
            "jenis" => $jenis,
        ]);
        
        return redirect(route('Jenis-add'))->with('success', 'Berhasil dibuat');
    }

    public function Delete($id)
    {
        Komponen::where('id', $id)->delete();
        return redirect(route('Jenis-list'))->with('success', 'Berhasil Dihapus');
    }
}