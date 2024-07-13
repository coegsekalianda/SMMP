<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\RPS;
use App\Models\MK;
use App\Models\Activity;
use App\Models\CPLMK;
use App\Models\CPL;
use App\Models\CPMK;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RPSsImport;

class RpsController extends Controller
{
    public function create()
    {
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        return view('admin.rps.add', compact('mks', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => ['required', 'unique:rpss', 'regex:/^[0-9\/]+$/'],
            'prodi' => 'required',
            'matakuliah' => 'required',
            'semester' => ['required', 'integer', 'digits:1'],
            'dosen' => 'required',
            'kaprodi' => ['required', 'string', 'regex:/^[a-zA-Z., ]+$/', 'max:255'],
            'pengembang' => 'required',
            'koordinator' => 'required',
            'pustaka_pendukung' => 'nullable',
            'materi_mk' => 'required',
            'pustaka_utama' => 'required',
            'kontrak' => 'required'
        ]);
        if ($request->pustaka_pendukung == null) {
            $p = 'Tidak ada';
        } else {
            $p = $request->pustaka_pendukung;
        }

        $mk = MK::findOrFail($request->matakuliah);
        $bobot = $mk->bobot_teori + $mk->bobot_praktikum;
        if($bobot == 1 && $mk->bobot_teori < $mk->bobot_praktikum) {
            $t = 'Lecture, group discussion, task, and practicum';
        }
        elseif ($bobot == 3) {
            $t = 'Lecture, group discussion, task, and practicum';
        } else {
            $t = 'Lecture, group discussion, and task';
        }
        $w = '"Lectures: ' . $bobot . 'x 50 = 150 minutes per week. 
Exercises and Assignments: ' . $bobot . 'x 60 = 180 minutes per week. 
Private study: ' . $bobot . 'x 60 = 180 minutes per week."';

        RPS::create([
            'nomor' => $request->nomor,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'kurikulum' => $mk->kurikulum,
            'id_mk' => $request->matakuliah,
            'dosen' => $request->dosen,
            'pengembang' => $request->pengembang,
            'koordinator' => $request->koordinator,
            'kaprodi' => $request->kaprodi,
            'pustaka_utama' => $request->pustaka_utama,
            'materi_mk' => $request->materi_mk,
            'pustaka_pendukung' => $p,
            'tipe' => $t,
            'waktu' => $w,
            'syarat_ujian' => 'A student must have attended at least 80% of the lectures to sit in the exams.',
            'syarat_studi' => '"Trial, either midterm or semester test,
Tasks, including individual or group assignments to be completed within a certain timeframe, and team project
Quizzes, held on face-to-face, once before midterm exam and once after midterm exam, with a short answer form.
Assessment is done using benchmark assessment, with the aim of measuring the level of student understanding related to the target and class rank.
"',
            'media' => 'e-learning (virtual class), LCD, whiteboard, and websites',
            'kontrak' => $request->kontrak
        ]);
        return redirect('/admin/list-rps')->with('success', 'New RPS successfully added!');
    }

    public function list()
    {
        $rpss = RPS::orderBy('created_at', 'desc')->get();
        $mks = MK::all();
        return view('admin.rps.list', compact('rpss', 'mks'));
    }

    public function edit($kode)
    {
        $rps = RPS::where('kode_mk', $kode)->firstOrFail();
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        return view('admin.rps.edit', compact('rps', 'users', 'mks'));
    }

    public function update(Request $request, $kode)
    {
        $request->validate([
            'nomor' => ['required', 'regex:/^[0-9\/]+$/'],
            'prodi' => 'required',
            'matakuliah' => 'required',
            'semester' => ['required', 'integer', 'digits:1'],
            'dosen' => 'required',
            'kaprodi' => ['required', 'string', 'regex:/^[a-zA-Z., ]+$/', 'max:255'],
            'pengembang' => 'required',
            'koordinator' => 'nullable',
            'pustaka_pendukung' => 'nullable',
            'materi_mk' => 'required',
            'pustaka_utama' => 'required',
            'tipe' => ['required', 'regex:/^[a-zA-Z., ]+$/', 'string'],
            'waktu' => 'required',
            'syarat_ujian' => ['required', 'regex:/^[0-9a-zA-Z.,% ]+$/'],
            'syarat_studi' => 'required',
            'media' => 'required',
            'kontrak' => 'required'
        ]);
        if ($request->pustaka_pendukung == null) {
            $p = 'Tidak ada';
        } else {
            $p = $request->pustaka_pendukung;
        }

        $mk = MK::where('kode', $request->matakuliah)->firstOrFail();
        $rps = RPS::where('kode_mk', $kode)->firstOrFail();
        $rps->update([
            'nomor' => $request->nomor,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'kurikulum' => $mk->kurikulum,
            'kode_mk' => $request->matakuliah,
            'dosen' => $request->dosen,
            'pengembang' => $request->pengembang,
            'koordinator' => $request->koordinator,
            'kaprodi' => $request->kaprodi,
            'pustaka_utama' => $request->pustaka_utama,
            'materi_mk' => $request->materi_mk,
            'pustaka_pendukung' => $p,
            'tipe' => $request->tipe,
            'waktu' => $request->waktu,
            'syarat_ujian' => $request->syarat_ujian,
            'syarat_studi' => $request->syarat_studi,
            'media' => $request->media,
            'kontrak' => $request->kontrak
        ]);
        return redirect('/admin/list-rps')->with('success', 'RPS successfully updated!');
    }

    public function delete($id)
    {
        $ids = Crypt::decrypt($id);
        RPS::where('id', $ids)->delete();
        return redirect('/admin/list-rps')->with('success', 'RPS successfully deleted!');
    }

    public function print($kode)
    {
        $mks = MK::all();
        $rps = RPS::where('kode_mk', $kode)->firstOrFail();
        $activities = Activity::all();
        $pengetahuans = collect();
        $keterampilans = collect();
        $umums = collect();
        $cplmks = CPLMK::where('kode_mk', $rps->kode_mk)->get();
        foreach ($cplmks as $cplmk) {
            $pengetahuan = CPL::where('aspek', 'Pengetahuan')->where('id', $cplmk->id_cpl)->get();
            $keterampilan = CPL::where('aspek', 'Keterampilan')->where('id', $cplmk->id_cpl)->get();
            $umum = CPL::where('aspek', 'Umum')->where('id', $cplmk->id_cpl)->get();
            foreach ($keterampilan as $k) $keterampilans->push($k);
            foreach ($pengetahuan as $p) $pengetahuans->push($p);
            foreach ($umum as $u) $umums->push($u);
        }
        $cpls = CPL::all();
        $cpmks = CPMK::where('kode_mk', $rps->kode_mk)->get();
        $sikaps = CPL::where('aspek', 'Sikap')->where('kurikulum', $rps->kurikulum)->get();

        $data = compact(
            'rps',
            'activities',
            'mks',
            'cplmks',
            'cpls',
            'cpmks',
            'sikaps',
            'umums',
            'pengetahuans',
            'keterampilans'
        );

        // $pdf = PDF::loadView('admin.rps.print', $data)->setOrientation('landscape');
        // $pdf->setOption('enable-local-file-access', true);
        // return $pdf->stream('rps.pdf');
        return view('admin.rps.print', $data);
    }


    public function create_wfile(Request $request)
    {
        try {
            $excel = $request->file('excel');
            $excelPath = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $excel->getClientOriginalName());
            $excel->move(public_path('../public/assets/excel/'), $excelPath);
            Excel::import(new RPSsImport, public_path('../public/assets/excel/' . $excelPath));
            $file = new Filesystem;
            $file->cleanDirectory('../public/assets/excel/');
            return redirect('/admin/list-rps')->with('success', 'RPS successfully added!');
        } catch (\Exception $e) {
            return redirect('/admin/add-rps')->with('error', "Terjadi kesalahan, silahkan periksa kembali data dalam excel anda!, " . $e);
        }
    }
}
