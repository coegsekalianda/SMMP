<?php

namespace App\Http\Controllers\PenjaminMutu;

use App\Http\Controllers\Controller;
use App\Models\Soal;

class DashboardController extends Controller
{
    public function list(){
        $soal = Soal::all();
        $valid = Soal::where('status','Valid')->get();
        $belum = Soal::where('status','Belum')->get();
        $tolak = Soal::where('status','Tolak')->get();
        return view('penjamin-mutu.dashboard', compact('soal', 'valid','belum','tolak'));
    }

    // public function chart()
    // {
    //     $rpss = RPS::where('pengembang', auth()->user()->name)->get();
    //     $mks = collect();
    //     $cpp = null;
    //     $warnap = null;
    //     $borderp = null;
    //     $cpk = null;
    //     $warnak = null;
    //     $borderk = null;
    //     $pengetahuan = null;
    //     $keterampilan = null;
    //     foreach ($rpss as $rps) {
    //         $id_mk = $rps->mk->kode;
    //         $mk = MK::firstWhere('kode',$id_mk);
    //         $mks->push($mk);
    //     }
    //     $cplmks = collect();
    //     foreach ($mks as $mk) {
    //         $kode_mk = $mk->kode;
    //         $temp = CPLMK::where('kode_mk', $kode_mk)->get();
    //         foreach ($temp as $cplmk) {
    //             $cplmks->push($cplmk);
    //         }
    //     }
    //     $cpls = CPL::all();
    //     $i = -1;
    //     foreach ($cpls as $cpl) {
    //         if ($cpl->aspek == 'Pengetahuan') {
    //             $pengetahuan[++$i] = $cpl->kode;
    //         }
    //     }
    //     $i = -1;
    //     foreach ($cpls as $cpl) {
    //         if ($cpl->aspek == 'Keterampilan') {
    //             $keterampilan[++$i] = $cpl->kode;
    //         }
    //     }

    //     $i = -1;
    //     foreach ($cpls as $cpl) {
    //         if ($cpl->aspek == 'Pengetahuan') {
    //             $cpl_pengetahuan[++$i] = $cpl->id;
    //         }
    //     }
    //     $i = -1;
    //     foreach ($cpls as $cpl) {
    //         if ($cpl->aspek == 'Keterampilan') {
    //             $cpl_keterampilan[++$i] = $cpl->id;
    //         }
    //     }

    //     $list_warna = [
    //         'rgba(255, 99, 132, 0.2)',
    //         'rgba(54, 162, 235, 0.2)',
    //         'rgba(255, 206, 86, 0.2)',
    //         'rgba(75, 192, 192, 0.2)',
    //         'rgba(153, 102, 255, 0.2)',
    //         'rgba(255, 159, 64, 0.2)',
    //     ];

    //     $list_border = [
    //         'rgba(255,99,132,1)',
    //         'rgba(54, 162, 235, 1)',
    //         'rgba(255, 206, 86, 1)',
    //         'rgba(75, 192, 192, 1)',
    //         'rgba(153, 102, 255, 1)',
    //         'rgba(255, 159, 64, 1)',
    //     ];

    //     if(isset($cpl_pengetahuan)){
    //         $sump = 0;
    //         foreach ($cpl_pengetahuan as $no => $cpls) {
    //             $cpp[$no] = 0;
    //             $sump += 1;
    //         }
    //         foreach ($cpl_pengetahuan as $no => $cpls) {
    //             foreach ($cplmks as $cplmk) {
    //                 if ($cplmk->id_cpl == $cpls) {
    //                     $cpp[$no] += 1;
    //                 }
    //             }
    //         }
    //         $tmp = -1;
    //         for ($i = 0; $i < $sump; $i++) {
    //             $warnap[$i] = $list_warna[++$tmp];
    //             if ($tmp == 5) {
    //                 $tmp = 0;
    //             }
    //         }

    //         $tmp = -1;
    //         for ($i = 0; $i < $sump; $i++) {
    //             $borderp[$i] = $list_border[++$tmp];
    //             if ($tmp == 5) {
    //                 $tmp = 0;
    //             }
    //         }
    //     }
    //     if(isset($cpl_keterampilan)){
    //         $sumk = 0;
    //         foreach ($cpl_keterampilan as $no => $cpls) {
    //             $cpk[$no] = 0;
    //             $sumk += 1;
    //         }
    //         foreach ($cpl_keterampilan as $no => $cpls) {
    //             foreach ($cplmks as $cplmk) {
    //                 if ($cplmk->id_cpl == $cpls) {
    //                     $cpk[$no] += 1;
    //                 }
    //             }
    //         }

    //         $tmp = -1;
    //         for ($i = 0; $i < $sumk; $i++) {
    //             $warnak[$i] = $list_warna[++$tmp];
    //             if ($tmp == 5) {
    //                 $tmp = 0;
    //             }
    //         }
    //         $tmp = -1;
    //         for ($i = 0; $i < $sumk; $i++) {
    //             $borderk[$i] = $list_border[++$tmp];
    //             if ($tmp == 5) {
    //                 $tmp = 0;
    //             }
    //         }
    //     }





    //     $dt = ([
    //         'pengetahuan' => $pengetahuan,
    //         'keterampilan' => $keterampilan,
    //         'jumlahp' => $cpp,
    //         'warnap' => $warnap,
    //         'borderp' => $borderp,
    //         'jumlahk' => $cpk,
    //         'warnak' => $warnak,
    //         'borderk' => $borderk,
    //     ]);

    //     return response()->json($dt);
    // }

}
