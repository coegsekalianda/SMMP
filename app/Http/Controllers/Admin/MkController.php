<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MK;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\Crypt;

class MkController extends Controller
{
    public function create()
    {
        $mks = MK::all();
        $kurikulums = Kurikulum::all();
        return view('admin.mk.add', compact('mks', 'kurikulums'));
    }

    public function list()
    {
        $mks = MK::orderBy('kurikulum', 'desc')->get();
        return view('admin.mk.list', compact('mks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => ['required', 'alpha_num', 'min:9'],
            'nama' => ['required', 'string', 'regex:/^[\/a-zA-Z -]+$/', 'max:255'],
            'rumpun' => 'required',
            'prasyarat' => ['nullable', 'string', 'max:255'],
            'kurikulum' => ['required', 'integer', 'digits:4'],
            'deskripsi' => 'required',
            'bobot_teori' => ['required', 'integer', 'digits:1'],
            'bobot_praktikum' => ['nullable', 'integer', 'digits:1'],
        ]);
        if ($request->prasyarat == null) {
            $p = 'Tidak ada';
        } else {
            $p = $request->prasyarat;
        }
        if ($request->bobot_praktikum == null) {
            $bp = '0';
        } else {
            $bp = $request->bobot_praktikum;
        }
        try {
            MK::create([
                'kode' => strtoupper($request->kode),
                'nama' => $request->nama,
                'rumpun' => $request->rumpun,
                'prasyarat' => $p,
                'kurikulum' => $request->kurikulum,
                'deskripsi' => $request->deskripsi,
                'bobot_teori' => $request->bobot_teori,
                'bobot_praktikum' => $bp,
            ]);
            return redirect('/admin/list-mk')->with('success', 'MK successfully added!');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            return redirect()->back()->withInput($request->all)->with('error', 'Kode mata kuliah '. $request->kode. ' sudah ada');
        }
    }

    public function edit($kode) {
        $mk = MK::where('kode', $kode)->firstOrFail();
        $mks = MK::all();
        $kurikulums = Kurikulum::all();
        return view('admin.mk.edit', compact('mk', 'mks', 'kurikulums'));
    }
    

    public function update(Request $request, $kode) {
        $request->validate([
            'kode' => ['required', 'alpha_num', 'min:9'],
            'nama' => ['required', 'string', 'regex:/^[\/a-zA-Z -]+$/', 'max:255'],
            'rumpun' => 'required',
            'prasyarat' => ['nullable', 'string', 'max:255'],
            'kurikulum' => ['required', 'integer', 'digits:4'],
            'deskripsi' => 'required',
            'bobot_teori' => ['required', 'integer', 'digits:1'],
            'bobot_praktikum' => ['nullable', 'integer', 'digits:1'],
        ]);
        if ($request->prasyarat == null) {
            $p = 'Tidak ada';
        } else {
            $p = $request->prasyarat;
        }
        if ($request->bobot_praktikum == null) {
            $bp = '0';
        } else {
            $bp = $request->bobot_praktikum;
        }
        $mk = MK::where('kode', $kode)->firstOrFail();
        $mk->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'rumpun' => $request->rumpun,
            'prasyarat' => $p,
            'kurikulum' => $request->kurikulum,
            'deskripsi' => $request->deskripsi,
            'bobot_teori' => $request->bobot_teori,
            'bobot_praktikum' => $bp
        ]);

        return redirect('/admin/list-mk')->with('success', 'MK successfully edited!');
    }

    public function delete($kode)
    {
        MK::where('kode', $kode)->delete();
        return redirect('/admin/list-mk');
    }
}
