<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MK;
use App\Models\RPS;
use App\Models\CPMK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CpmkController extends Controller
{
    public function create()
    {
        $rpss = RPS::all();
        $mks = collect();
        foreach ($rpss as $rps) {
            $mk = MK::where('kode', $rps->kode_mk)->firstOrFail();
            $mks->push($mk);
        }
        return view('admin.cpmk.add', compact('mks'));
    }

    public function store(Request $request)
    {
        try {
            for ($i = 0; $i < count($request->input('judul')); $i++) {
                if (isset($request->input('judul')[$i])) {
                    CPMK::create([
                        'kode_mk' => $request->kode_mk,
                        'judul' => $request->input('judul')[$i]
                    ]);
                }
            }
            return redirect('/admin/add-cpmk')->with('success', 'CPMK successfully added!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all)->with('error', "Terjadi kesalahan, silahkan periksa kembali data yang diinputkan!". $e);
        }
    }
    

    public function list()
    {
        $rpss = RPS::all();
        $cpmks = collect();
        foreach ($rpss as $rps) {
            $temp = cpmk::where('kode_mk', $rps->kode_mk)->get();
            foreach ($temp as $cpmk) {
                $mk = MK::where('kode', $cpmk->kode_mk)->firstOrFail();
                $cpmk->mk = $mk->nama;
                $cpmks->push($cpmk);
            }
        }
        return view('admin.cpmk.list', compact('cpmks'));
    }

    public function edit($id)
    {
        $ids = Crypt::decrypt($id);
        $cpmk = CPMK::find($ids);
        $mk = MK::where('kode', $cpmk->kode_mk)->firstOrFail();
        $cpmk->mk = $mk->nama;
        return view('admin.cpmk.edit', compact('cpmk'));
    }

    public function update(Request $request, $id)
    {
        $ids = Crypt::decrypt($id);
        $request->validate([
            'kode_mk' => 'required',
            'judul' =>
            ['required', 'string', 'regex:/^[a-zA-Z0-9., \/()&*%-=+_:;]+$/', 'max:255'],
        ]);
        $cpmk = CPMK::findOrFail($ids);
        $cpmk->update([
            'kode_mk' => $request->kode_mk,
            'judul' => $request->judul,
        ]);
        return redirect('/admin/list-cpmk')->with('success', 'CPMK successfully updated!');
    }
    public function delete($id)
    {
        $ids = Crypt::decrypt($id);
        CPMK::where('id', $ids)->delete();
        return redirect('/admin/list-cpmk')->with('success', 'CPMK successfully deleted!');
    }
}
