<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class KurikulumController extends Controller
{
    public function list()
    {
        $kurikulums = Kurikulum::orderBy('tahun', 'desc')->get();
        return view('admin.kurikulum.list', compact('kurikulums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => ['required', 'integer', 'digits:4'],
        ]);

        try {
            Kurikulum::create([
                'tahun' => $request->tahun,

            ]);
            return redirect('/admin/list-kurikulum')->with('success', 'Kurikulum successfully added!');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {

            }
            return redirect('/admin/list-kurikulum')->with('error', 'Terjadi kesalahan, tahun kurikulum ' . $request->tahun . ' sudah ada');
        }
    }

    public function edit($tahun)
    {
        $kurikulums = Kurikulum::all();
        $tahuns = Crypt::decrypt($tahun);
        $kurikulum = Kurikulum::where('tahun', $tahuns)->firstOrFail();
        return view('admin.kurikulum.edit', compact('kurikulums', 'kurikulum'));
    }

    public function update(Request $request, $tahun)
    {
        
        $request->validate([
            'tahun' => ['required', 'integer', 'digits:4'],
        ]);
        $tahuns = Crypt::decrypt($tahun);
        $kurikulum = Kurikulum::where('tahun', $tahuns)->firstOrFail();
        $kurikulum->update([
            'tahun' => $request->tahun,
        ]);

        return redirect('/admin/list-kurikulum')->with('success', 'Kurikulum successfully edited!');
    }

    public function delete($tahun)
    {
        $tahuns = Crypt::decrypt($tahun);
        try {
            Kurikulum::where('tahun', $tahuns)->delete();
            return redirect('/admin/list-kurikulum')->with('success', 'Kurikulum successfully deleted!');
        } catch (\Illuminate\Database\QueryException $e) {
            $kur = Kurikulum::where('tahun', $tahuns)->get();
            return redirect('/admin/list-kurikulum')->with('error', 'Terjadi kesalahan, tahun kurikulum ' . $kur[0]['tahun'] . ' masih memiliki CPL/MK');
        }
    }
}
