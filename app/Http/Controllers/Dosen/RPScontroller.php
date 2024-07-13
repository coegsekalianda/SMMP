<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CPL;
use App\Models\MK;
use App\Models\User;
use App\Models\RPS;
use App\Models\Activity;
use App\Models\CPMK;
use App\Models\CPLMK;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PDF;
class RPScontroller extends Controller
{
    public function Add()
    {
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        // dd($mks);
        return view('dosen.RPS.add', compact('mks', 'users'));
    }

    public function StoreRpsStep1(Request $request){
        if (RPS::count() == 0) {
            $nomor = 1;
        } else {
            
            $lastRpss = RPS::latest()->first();
            $nomor = $lastRpss->nomor + 1;
        }
        
        $validatedData1 = $request->validate([
            'prodi' => 'required',
            'matakuliah' => 'required|unique:rpss,kode_mk',
            'semester' => ['required', 'integer', 'digits:1']
        ]);
        $validatedData1['nomor'] = $nomor;
        // dd($validatedData1);
        session()->put('step1_dataRps', $validatedData1);

        return redirect()->route('addRpsStep2');
    }

    public function AddRpsStep2()
    {
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        return view('dosen.RPS.addRpsStep2', compact('mks', 'users'));
    }

    public function StoreRpsStep2(Request $request)
    {
        $prodi = session('step1_dataRps.prodi');
        

        if($prodi == 'S1 - Ilmu Komputer'){
            $kaprodi =
            User::where('jabatan', 'Kaprodi S-1 Ilmu Komputer')->pluck('name')->first();
            
        }
        if($prodi == 'D3 - Manajemen Informatika'){
            $kaprodi =
            User::where('jabatan', 'Kaprodi S-1 Ilmu Komputer')->pluck('name')->first();
        }

        
        $validatedData2 = $request->validate([
            'pengembang' => 'required',
            'koordinator' => 'required',
            'dosen' => 'required',
        ]);
        $validatedData2['kaprodi'] = $kaprodi;
        // dd($validatedData2);
        session()->put('step2_dataRps', $validatedData2);
        
        return redirect()->route('addRpsStep3');
    }

    public function AddRpsStep3()
    {
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        return view('dosen.RPS.addRpsStep3', compact('mks', 'users'));
    }

    public function StoreRpsStep3(Request $request)
    {
        $validatedData3 = $request->validate([
            'materi_mk' => 'required',
            'kontrak' => 'required'
        ]);
        // dd($validatedData3);
        session()->put('step3_dataRps', $validatedData3);

        return redirect()->route('addRpsStep4');
    }

    public function AddRpsStep4()
    {
        return view('dosen.RPS.addRpsStep4');
    }

    public function StoreRpsStep4(Request $request)
    {
        $validatedData4 = $request->validate([
            'pustaka_utama' => 'required',
            'pustaka_pendukung' => 'nullable'
        ]);
        session()->put('step4_dataRps', $validatedData4);

        if (session()->get('step4_dataRps.pustaka_pendukung') == null) {
            $p = 'Tidak ada';
        } else {
            $p = session()->get('step4_dataRps.pustaka_pendukung');
        }


        $kode_mk = session()->get('step1_dataRps.matakuliah');
        // dd($kode_mk);
        $mk = MK::firstWhere('kode', $kode_mk);
        if ($mk->bobot_teori + $mk->bobot_praktikum == 3) {
            $w = '"Lectures: 3 x 50 = 150 minutes per week.
                Exercises and Assignments: 3 x 60 = 180 minutes per week.
                Private study: 3 x 60 = 180 minutes per week."';
            $t = 'Lecture, group discussion, task, and practicum';
        } else {
            $w = '"Lectures: 2 x 50 = 100 minutes per week.
                Exercises and Assignments: 2 x 60 = 120 minutes per week.
                Private study: 2 x 60 = 120 minutes per week."';
            $t = 'Lecture, group discussion, and task';
        }

        RPS::create([
            'nomor' => session()->get('step1_dataRps.nomor'),
            'prodi' => session()->get('step1_dataRps.prodi'),
            'kode_mk' => $kode_mk,
            'semester' => session()->get('step1_dataRps.semester'),
            'kurikulum' => $mk->kurikulum,
            'pengembang' => session()->get('step2_dataRps.pengembang'),
            'koordinator' => session()->get('step2_dataRps.koordinator'),
            'dosen' => session()->get('step2_dataRps.dosen'),
            'kaprodi' => session()->get('step2_dataRps.kaprodi'),
            'materi_mk' => session()->get('step3_dataRps.materi_mk'),
            'kontrak' => session()->get('step3_dataRps.kontrak'),
            'pustaka_utama' => session()->get('step4_dataRps.pustaka_utama'),
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
        ]);
       

        session()->forget([
            'step1_dataRps', 'step2_dataRps', 'step3_dataRps', 'step4_dataRps'
        ]);
        return redirect('/dosen/rps/list-rps')->with('success', 'New RPS successfully added!');
    }


    public function List()
    {
        $rpss = RPS::where('pengembang', Auth::user()->name)->get();
        return view('dosen.RPS.list', compact('rpss'));
    }


    public function print($id)
    {
        $ids = Crypt::decrypt($id);
        $rps = RPS::findOrFail($ids);
        $mks = MK::all();
        $activities = Activity::all();
        $cplmks = collect();
        $pengetahuans = collect();
        $keterampilans = collect();
        foreach ($mks as $mk) {
            if ($rps->kode_mk == $mk->kode) {
                $cplmkss = CPLMK::where('kode_mk', $mk->kode)->get();
            }
        }
        foreach ($cplmkss as $c) $cplmks->push($c);
        foreach ($cplmks as $cplmk) {
            $pengetahuan = CPL::where('aspek', 'Pengetahuan')->where('id', $cplmk->id_cpl)->get();
            $keterampilan = CPL::where('aspek', 'Keterampilan')->where('id', $cplmk->id_cpl)->get();
            foreach ($keterampilan as $k) $keterampilans->push($k);
            foreach ($pengetahuan as $p) $pengetahuans->push($p);
        }
        $cpls = CPL::all();
        $cpmks = $rps->mk->cpmk;
        $sikaps = CPL::where('aspek', 'Sikap')->where('kurikulum', $rps->kurikulum)->get();
        $umums = CPL::where('aspek', 'Umum')->where('kurikulum', $rps->kurikulum)->get();

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

        $pdf = PDF::loadView('admin.rps.print', $data)->setOrientation('landscape');
        $pdf->setOption('enable-local-file-access', true);
        return $pdf->stream('rps.pdf');
    }

    //STORE YG PENGEN DIUBAH
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

        $mk = MK::firstWhere('kode', $request->matakuliah);
        if ($mk->bobot_teori + $mk->bobot_praktikum == 3) {
            $w = '"Lectures: 3 x 50 = 150 minutes per week.
    Exercises and Assignments: 3 x 60 = 180 minutes per week.
    Private study: 3 x 60 = 180 minutes per week."';
            $t = 'Lecture, group discussion, task, and practicum';
        } 
        else {
            $w = '"Lectures: 2 x 50 = 100 minutes per week.
Exercises and Assignments: 2 x 60 = 120 minutes per week.
Private study: 2 x 60 = 120 minutes per week."';
            $t = 'Lecture, group discussion, and task';
        }

        RPS::create([
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

    public function Edit($id)
    {
        $rps = RPS::findOrFail($id);
        $mks = MK::all();
        $users = User::where('otoritas', 'Dosen')->get();
        return view('dosen.RPS.edit', compact('rps', 'users', 'mks'));
    }
    public function Update(Request $request, $id)
    {
        $request->validate([
            'nomor' => ['required',],
            'prodi' => 'required',
            'matakuliah' => 'required',
            'semester' => ['required', 'integer', 'digits:1'],
            'dosen' => 'required',
            'kaprodi' => ['required', 'string', 'regex:/^[a-zA-Z., ]+$/', 'max:255'],
            'pengembang' => 'required',
            // 'koordinator' => 'required',
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

        $mk = MK::firstWhere('kode',$request->matakuliah);
        if ($mk->bobot_teori + $mk->bobot_praktikum == 3) {
            $w = '"Lectures: 3 x 50 = 150 minutes per week.
Exercises and Assignments: 3 x 60 = 180 minutes per week.
Private study: 3 x 60 = 180 minutes per week."';
            $t = 'Lecture, group discussion, task, and practicum';
        } else {
            $w = '"Lectures: 2 x 50 = 100 minutes per week.
Exercises and Assignments: 2 x 60 = 120 minutes per week.
Private study: 2 x 60 = 120 minutes per week."';
            $t = 'Lecture, group discussion, and task';
        }

        $rps = RPS::findOrFail($id);
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
        return redirect('/dosen/rps/list-rps')->with('success', 'RPS successfully updated!');
    }

    public function Delete($id)
    {
        RPS::where('id', $id)->delete();
        return redirect('/dosen/rps/list-rps')->with('success', 'RPS successfully deleted!');
    }
}
