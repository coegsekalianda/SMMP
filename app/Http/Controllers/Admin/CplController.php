<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CPL;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\Crypt;

class CplController extends Controller
{
    public function create()
    {
        $sikaps = CPL::where('aspek', 'Sikap')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $umums = CPL::where('aspek', 'Umum')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $pengetahuans = CPL::where('aspek', 'Pengetahuan')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $keterampilans = CPL::where('aspek', 'Keterampilan')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $kurikulums = Kurikulum::all();
        return view('admin.cpl.add', compact('sikaps', 'umums', 'pengetahuans', 'keterampilans', 'kurikulums'));
    }

    public function list()
    {
        $sikaps = CPL::where('aspek', 'Sikap')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $umums = CPL::where('aspek', 'Umum')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $pengetahuans = CPL::where('aspek', 'Pengetahuan')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        $keterampilans = CPL::where('aspek', 'Keterampilan')->orderBy('kurikulum', 'desc')->orderBy('kode', 'asc')->get();
        return view('admin.cpl.list', compact('sikaps', 'umums', 'pengetahuans', 'keterampilans'));
    }

    public function store(Request $request)
    {
        if($request->input('kurikulum') == null) {
            return redirect()->back()->with('error', "Terjadi kesalahan, silahkan periksa kembali data yang diinputkan!");
        }
        $success[] = '';
        foreach ($request->input('kode') as $key => $value) {
            $data[$key] = 'CPL-' . $value;
        }
        try {
            for ($i = 0; $i < count($request->input('kurikulum')); $i++) {
                CPL::create([
                    'aspek' => $request->aspek,
                    'kurikulum' => $request->input('kurikulum')[$i],
                    'kode' => $data[$i],
                    'nomor' => $request->input('kode')[$i],
                    'judul' => $request->input('judul')[$i]
                ]);
                $success[] = $data[$i];
            }
            return redirect('/admin/list-cpl')->with('success', 'CPL successfully added!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all)->with('error', "Terjadi kesalahan, silahkan periksa kembali data yang diinputkan!");
        }
    }

    public function edit($id)
    {
        $ids = Crypt::decrypt($id);
        $cpl = CPL::findOrFail($ids);
        $kurikulums = Kurikulum::all();
        return view('admin.cpl.edit', compact('cpl', 'kurikulums'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aspek' => 'required',
            'kurikulum' => ['required', 'integer', 'digits:4'],
            'kode' => ['required'],
            'judul' => 'required',
        ]);

        $ids = Crypt::decrypt($id);
        $cpl = CPL::findOrFail($ids);
        // if($request->aspek == 'Sikap') 
        //     $kode = 'S' . $request->kode;
        // else if($request->aspek == 'Umum') 
        //     $kode = 'KU' . $request->kode;
        // else if($request->aspek == 'Pengetahuan') 
        //     $kode = 'P' . $request->kode;
        // else
        //     $kode = 'KK' . $request->kode;
        
        $cpl->update([
            'aspek' => $request->aspek,
            'kurikulum' => $request->kurikulum,
            'kode' => 'CPL-'. $request->kode,
            'judul' => $request->judul,
        ]);

        return redirect('/admin/list-cpl')->with('success', 'CPL successfully edited!');
    }

    public function delete($id)
    {
        $ids = Crypt::decrypt($id);
        CPL::where('id', $ids)->delete();
        return redirect('/admin/list-cpl');
    }
}
