<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CPL;
use App\Models\MK;
use App\Models\RPS;
use App\Models\Prodi;

class Prodicontroller extends Controller
{
    public function Add()
    {
        return view('admin.prodi.add');
    }

    public function List()
    {
        $prodi = Prodi::all();
        
        return view('admin.prodi.list', compact('prodi'));
    }

    public function Store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $validator = Validator::make($data, [
            'nama' => 'required|unique:prodi', 
        ]);
        if ($validator->fails()) {
            // Handle validation errors
            return redirect()->back()->with('error', 'Nama telah digunakan');
        }
        $prodi = Prodi::create($data);
        
        return redirect(route('Prodi-add'))->with('success', 'Berhasil dibuat');
    }

    public function Delete($id)
    {
        Prodi::where('id', $id)->delete();
        return redirect(route('Prodi-list'))->with('success', 'Berhasil Dihapus');
    }
}