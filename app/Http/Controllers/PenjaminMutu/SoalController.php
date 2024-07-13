<?php

namespace App\Http\Controllers\PenjaminMutu;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Soal;
use App\Models\MK;
use App\Models\CPMKSoal;
use App\Models\CPMK;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;

class SoalController extends Controller
{
    public function list()
    {
        $mks = MK::all();
        $soal = collect();
        foreach ($mks as $mk) {
            $kuis1s = Soal::where('kode_mk', $mk->kode)->where('jenis', 'Kuis ke-1')->skip(0)->take(1)->get();
            $kuis2s = Soal::where('kode_mk', $mk->kode)->where('jenis', 'Kuis ke-2')->skip(0)->take(1)->get();
            $utss = Soal::where('kode_mk', $mk->kode)->where('jenis', 'UTS')->skip(0)->take(1)->get();
            $uass = Soal::where('kode_mk', $mk->kode)->where('jenis', 'UAS')->skip(0)->take(1)->get();
            foreach ($kuis1s as $k1) $soal->push($k1);
            foreach ($kuis2s as $k2) $soal->push($k2);
            foreach ($utss as $ut) $soal->push($ut);
            foreach ($uass as $ua) $soal->push($ua);
            $soals = $soal->sortBy('status');
        }
        return view('penjamin-mutu.soal.list', ['soals' => $soals], compact('soals', 'mks'));
    }

    public function print($id)
    {
        $ids = Crypt::decrypt($id);
        $soal = Soal::findOrFail($ids);
        $mks = MK::all();
        $cpmks = collect();
        $soals = collect();
        $cpmk_soals = collect();
        // $countsoals = collect();
        foreach ($mks as $mk) {
            if ($soal->kode_mk == $mk->kode) {
                $soalss = Soal::where('kode_mk', $mk->kode)->where('jenis', $soal->jenis)->orderBy('id', 'asc')->get();
            }
        }
        foreach ($soalss as $s) $soals->push($s);
        foreach ($soals as $sl) {
            $temp = DB::table('cpmk_soals')->select(DB::raw('id_cpmk, id_soal'))->groupBy('id_cpmk')->orderBy('id_cpmk', 'asc')->get();
            $cpmk_s = $temp->where('id_soal', $sl->id);
            // $count = DB::table('cpmk_soals')->select(DB::raw('id_soal,COUNT(*) as soal_count'))->where('id_soal', $sl->id)->groupBy('id_soal')->orderBy('id_cpmk', 'asc')->get();
            foreach ($cpmk_s as $cpmk) $cpmk_soals->push($cpmk);
            // foreach ($count as $cnt) $countsoals->push($cnt);
        }
        foreach ($cpmk_soals as $c_s) {
            $cpmkss = CPMK::where('id', $c_s->id_cpmk)->get();
            foreach ($cpmkss as $cp) $cpmks->push($cp);
        }
        $mk = MK::where('kode', $soal->kode_mk)->firstOrFail();
        $data = compact(
            'mk',
            'soal',
            'mks',
            'cpmks',
            'cpmk_soals'
        );
        $pdf = PDF::loadView('admin.soal.print', $data);
        $pdf->setOption('enable-local-file-access', true);
        return $pdf->stream('soal.pdf');
    }

    public function validasi($id)
    {
        // $ids = Crypt::decrypt($id);
        $soal = Soal::findOrFail($id);
        $another = Soal::where('kode_mk',$soal->kode_mk)->where('jenis',$soal->jenis)->where('minggu',$soal->minggu)->get();
        foreach($another as $s){
            $s->update([
                'status' => 3
            ]);
        }
        return redirect('/penjamin-mutu/list-soal')->with('success', 'Soal successfully validated!');
    }

    public function tolak_validasi(Request $request, $id)
    {
        $ids = Crypt::decrypt($id);
        $soal = Soal::findOrFail($ids);
        $request->validate([
            'komentar' => 'required',
        ]);
        $another = Soal::where('kode_mk', $soal->kode_mk)->where('jenis', $soal->jenis)->where('minggu', $soal->minggu)->get();
        foreach ($another as $s) {
            $s->update([
                'komentar' => $request->komentar,
                'status' => 2
            ]);
        }
        return redirect('/penjamin-mutu/list-soal')->with('success', 'Soal successfully rejected!');
    }
}
