<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Soal;
use App\Models\MK;
use App\Models\CPMKSoal;
use App\Models\CPMK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;

class SoalController extends Controller
{
    public function list()
    {
        $mks = MK::all();
        $soals = collect();
        foreach ($mks as $mk) {
            $kuis1s = Soal::where('kode_mk', $mk->kode)->where('jenis', 'Kuis ke-1')->skip(0)->take(1)->get();
            $kuis2s = Soal::where('kode_mk', $mk->kode)->where('jenis', 'Kuis ke-2')->skip(0)->take(1)->get();
            $utss = Soal::where('kode_mk', $mk->kode)->where('jenis', 'UTS')->skip(0)->take(1)->get();
            $uass = Soal::where('kode_mk', $mk->kode)->where('jenis', 'UAS')->skip(0)->take(1)->get();
            foreach ($kuis1s as $k1) $soals->push($k1);
            foreach ($kuis2s as $k2) $soals->push($k2);
            foreach ($utss as $ut) $soals->push($ut);
            foreach ($uass as $ua) $soals->push($ua);
        }

        //table mapping cpmk soal
        $cpmks = CPMK::orderBy('id', 'asc')->get()->groupBy('kode_mk');
        // foreach($cpmks as $mk=>$cpmk){
        //     foreach($cpmk as $cp){
        //         dd($cp,$cp->soal->count());
        //     }
        // }
        return view('admin.soal.list', compact('soals', 'mks', 'cpmks'));
    }

    public function summary()
    {
        $cpmks = CPMK::orderBy('id', 'asc')->get()->groupBy('kode_mk');
        return view('admin.soal.summary', compact('cpmks'));

    }

    public function chart_soal($kode_mk)
    {
        $cpmks = CPMK::orderBy('id', 'asc')->get()->groupBy('kode_mk');
        $i = 0;
        $sum = 0;
        foreach ($cpmks as $mk => $cpmk) {
            if ($mk == $kode_mk) {
                foreach ($cpmk as $cp) {
                    $sum += 1;
                    $cpp[$i] = $cp->soal->count();
                    $kodecpmks[$i] = 'CPMK - ' . $sum;
                    $i++;
                }
            }
        }

        $list_warna = [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
        ];
        $tmp = -1;
        for ($i = 0; $i < $sum; $i++) {
            $warna[$i] = $list_warna[++$tmp];
            if ($tmp == 5) {
                $tmp = 0;
            }
        }

        $list_border = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',];
        $tmp = -1;
        for ($i = 0; $i < $sum; $i++) {
            $border[$i] = $list_border[++$tmp];
            if ($tmp == 5) {
                $tmp = 0;
            }
        }

        $dt = ([
            'kode_cpmk' => $kodecpmks,
            'jumlah' => $cpp,
            'warna' => $warna,
            'border' => $border,
        ]);

        return response()->json($dt);
    }

    public function print($id)
    {
        $ids = Crypt::decrypt($id);
        $soal = soal::findOrFail($ids);
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
        $mk = MK::findorFail($soal->kode_mk);
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
}
