<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CPMK;
use Illuminate\Http\Request;
use App\Models\MK;
use App\Models\RPS;

class CPMKcontroller extends Controller
{
    public function Add()
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $mks = collect();
        foreach ($rpss as $rps) {
            $mk = MK::where('kode', $rps->kode_mk)->firstOrFail();
            $mks->push($mk);
        }
        return view('dosen.CPMK.add', compact('mks'));
    }

    public function Store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required',
        ]);
        try {
            for ($i = 0; $i < count($request->input('judul')); $i++) {
                if (isset($request->input('judul')[$i])) {
                    CPMK::create([
                        'kode_mk' => $request->kode_mk,
                        'judul' => $request->input('judul')[$i],
                    ]);
                }
            }
            return redirect(route('cpmk-list'))->with('success', 'New CPMK successfully added!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all)->with('error', "Terjadi kesalahan, silahkan periksa kembali data yang diinputkan!" . $e);
        }
    }

    public function List()
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $cpmks = collect();
        foreach ($rpss as $rps) {
            $kode_mk = $rps->kode_mk;
            $temp = cpmk::where('kode_mk', $kode_mk)->get();
            foreach ($temp as $cpmk) {
                $mk = MK::where('kode', $cpmk->kode_mk)->firstorfail();
                $cpmk->mk = $mk->nama;
                $cpmks->push($cpmk);
            }
        }
        return view('dosen.CPMK.list', compact('cpmks'));
    }

    public function Edit($id)
    {
        $cpmk = CPMK::find($id);
        $mk = MK::firstWhere('kode',$cpmk->kode_mk);
        $cpmk->mk = $mk->nama;
        // dd($cpmk);
        return view('dosen.CPMK.edit', compact('cpmk'));
    }

    public function Update(Request $request, $id)
    {
        $request->validate([
            'kode_mk' => 'required',
            'judul' =>
            ['required', 'string', 'regex:/^[a-zA-Z0-9., \/()&*%-=+_:;]+$/', 'max:255'],
        ]);
        $cpmk = CPMK::findOrFail($id);
        $cpmk->update([
            'kode_mk' => $request->kode_mk,
            'judul' => $request->judul,
        ]);
        return redirect(route('cpmk-list'))->with('success', 'CPMK successfully updated!');
    }
    public function Delete($id)
    {
        CPMK::where('id', $id)->delete();
        return redirect(route('cpmk-list'))->with('success', 'CPMK successfully deleted!');
    }
}
