<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CPL;
use App\Models\MK;
use App\Models\RPS;
use App\Models\Universitas;

class Universitascontroller extends Controller
{
    public function Add()
    {
        return view('admin.universitas.add');
    }

    public function List()
    {
        $universitas = Universitas::all();
        
        return view('admin.universitas.list', compact('universitas'));
    }

    public function Store(Request $request)
    {
        $requestData = $request->all();
        // dd($data);
        $fileName = time().$request->file('img')->getClientOriginalName();
        $path = $request->file('img')->storeAs('images', $fileName, 'public');
        $requestData["img"] = '/storage/'.$path;

        $validator = Validator::make($requestData, [
            'nama' => 'required|unique:universitas', 
        ]);
        if ($validator->fails()) {
            // Handle validation errors
            return redirect()->back()->with('error', 'Nama telah digunakan');
        }
        $universitas = Universitas::create($requestData);

        return redirect(route('Universitas-add'))->with('success', 'Berhasil dibuat');
    }

    public function Delete($id)
    {
        Universitas::where('id', $id)->delete();
        return redirect(route('Universitas-list'))->with('success', 'Berhasil Dihapus');
    }
}