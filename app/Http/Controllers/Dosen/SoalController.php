<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RPS;
use App\Models\CPLMK;
use App\Models\CPMK;
use App\Models\Kurikulum;
use App\Models\MK;
use App\Models\Soal;
use App\Models\CPMKSoal;
use App\Models\CPL;
use App\Models\Mutu;
use App\Models\Komponen;
use App\Models\Universitas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Log;
use App\Exports\MutuExport;
use App\Imports\MutuImport;
use App\Imports\ImportTanpaSoal;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SoalController extends Controller
{
    public function Add()
    {
        $cpls = CPL::orderBy('judul', 'asc')->get();
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');
        $kurikulum = Kurikulum::all();
        $komponen = Komponen::groupBy('jenis')->get();
        $prodi = Prodi::all();
        $latestData = Soal::latest()->first();
        // $cplmk = DB::table('cplmks')
        //                 ->select('id_cpl')
        //                 ->where('kode_mk', $latestData->kode_mk)
        //                 ->get();
        // $cpl = DB::table('cpls')
        //                 ->select('judul')
        //                 ->where('id', $cplmk)
        //                 ->get();
        return view('dosen.soal.add', compact('rpss','kurikulum', 'cpls', 'komponen', 'latestData', 'prodi'));
    }

    public function addRaw()
    {
        $cpls = CPL::orderBy('judul', 'asc')->get();
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');
        $kurikulum = Kurikulum::all();
        $komponen = Komponen::groupBy('jenis')->get();
        $prodi = Prodi::all();
        $latestData = Soal::latest()->first();
        return view('dosen.soal.addRaw', compact('rpss','kurikulum', 'cpls', 'komponen', 'latestData', 'prodi'));
    }

    public function getCPLBykode_mk(Request $request)
    {   
        $kode_mk = $request->input('kode_mk');

        // Query to get ids from cplmks table
        $soalData = DB::table('cplmks')
                    ->where('kode_mk', $kode_mk)
                    ->pluck('id_cpl'); // Use pluck() to get an array of id_cpl values

        // Query to get judul and id from cpls table based on the ids from cplmks
        $soalData1 = DB::table('cpls')
                    ->select('judul', 'id')
                    ->whereIn('id', $soalData)
                    ->get();

        $options = '<option value="">Pilih CPL</option>';
        foreach ($soalData1 as $pertanyaan) {
            $options .= '<option value="' . $pertanyaan->id . '">' . $pertanyaan->judul . '</option>';
        }

        return $options;    
    }

    public function getCPMKBykode_mk(Request $request)
    {   

        $kode_mk = $request->input('kode_mk');

        $soalData = DB::table('cpmks')
                    ->select('judul', 'id')
                    ->where('kode_mk', $kode_mk)
                    ->get();

        $options = '<option value=""></option>';
        foreach ($soalData as $pertanyaan) {
            $options .= '<option value="' . $pertanyaan->id . '">' . $pertanyaan->judul . '</option>';
        }

        return $options;
    }

    public function cetakSoal($id)
    {
        $soals = Soal::findOrFail($id);
        return view('dosen.soal.cetakSoal', compact('soals'));
    }

    public function New(Request $request)
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');
        $soals = Soal::all();
        $cpls = CPLMK::all();
        $mks = MK::all();
        $course = MK::all();
        foreach ($rpss as $rps) {
            $id_mk = $rps->kode_mk;
            $mk = MK::firstWhere('kode',$id_mk);
            $mks->push($mk);
        }
        $cpls = CPL::orderBy('aspek', 'desc')->get();
        $komponen = Komponen::groupBy('jenis')->get();
        $universitas = Universitas::all();
        $prodi = Prodi::all();

        return view('dosen.mutu.addMutu', compact('soals','rpss','cpls','mks','course', 'komponen', 'universitas', 'prodi'));
    }

    public function getSoalBykode_mk(Request $request)
    {   
        $kode_mk = $request->input('kode_mk');
        $jenis = $request->input('jenis');

        $soalData = DB::table('soals')
                    ->select('pertanyaan')
                    ->where('kode_mk', $kode_mk)
                    ->where('jenis', $jenis)
                    ->get();

        $options = '<option value="">Pilih Soal</option>';
        foreach ($soalData as $pertanyaan) {
            $options .= '<option value="' . $pertanyaan->pertanyaan . '">' . $pertanyaan->pertanyaan . '</option>';
        }

        return $options;
    }

    public function TanpaSoal(Request $request)
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');
        $soals = Soal::all();
        $cpls = CPLMK::all();
        $mks = MK::all();
        $course = MK::all();
        foreach ($rpss as $rps) {
            $id_mk = $rps->kode_mk;
            $mk = MK::firstWhere('kode',$id_mk);
            $mks->push($mk);
        }
        $cpls = CPL::orderBy('aspek', 'desc')->get();
        $komponen = Komponen::groupBy('jenis')->get();
        $universitas = Universitas::all();
        $prodi = Prodi::all();

        return view('dosen.mutu.mutuTanpaSoal', compact('soals','rpss','cpls','mks','course','komponen','universitas', 'prodi'));
    }
    
    public function Excel(Request $request){
        // dd($request);
        $currentYear = date('Y');
        $jenis = $request->input('jenis');
        $kode_mk = $request->input('kode_mk');
        $prodi = $request->input('prodi');
        $univ = $request->input('univ');
        $semester = $request->input('semester');
        $mk =  DB::table('mks')
        ->select('nama')
        ->where('kode', $kode_mk)
        ->get();
        
        foreach ($mk as $mk) {
            $mks = $mk->nama;
        }

        $bobot = $request->input('bobot');
        $soal = $request->input('soal');
        $pertanyaan =  DB::table('soals')
                        ->select('id', 'cpl', 'cpmk')
                        ->whereIn('id', $soal)
                        ->orderBy('id', 'asc')
                        ->get();
        // dd($pertanyaan);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getProtection()->setSheet(true);
        $activeWorksheet->setCellValue('A1', "Universitas");
        $sheet->getColumnDimension('A')->setWidth(18);
        $activeWorksheet->setCellValue('A2', $univ);
        $activeWorksheet->setCellValue('B1', "Tahun Akademik");
        $sheet->getColumnDimension('B')->setWidth(15);
        $activeWorksheet->setCellValue('B2', $semester . ' ' . date('Y'));
        $activeWorksheet->setCellValue('C1', "Angkatan");
        $activeWorksheet->setCellValue('D1', "NPM");
        $sheet->getColumnDimension('D')->setWidth(11);
        $activeWorksheet->setCellValue('E1', "Nama");
        $sheet->getColumnDimension('E')->setWidth(25);
        $activeWorksheet->setCellValue('F1', "Prodi");
        $sheet->getColumnDimension('F')->setWidth(25);
        $activeWorksheet->setCellValue('F2', $prodi);
        $activeWorksheet->setCellValue('G1', "Kode MK");
        $sheet->getColumnDimension('G')->setWidth(11);
        $activeWorksheet->setCellValue('G2', $kode_mk);
        $activeWorksheet->setCellValue('H1', "Nama MK");
        $activeWorksheet->setCellValue('H2', $mks);
        $sheet->getColumnDimension('H')->setWidth(30);
        $activeWorksheet->setCellValue('I1', "Jenis");
        $activeWorksheet->setCellValue('I2', $jenis);
        $activeWorksheet->setCellValue('J1', "Bobot Jenis (%)");
        $sheet->getColumnDimension('J')->setWidth(13);
        $activeWorksheet->setCellValue('K1', "Nilai Total");
        
        $startRow = 'L';
        $columnIndex = 1;
        $columnSoal = 1; 
        foreach ($pertanyaan as $key => $value) {
            $activeWorksheet->getStyle('A:' . $startRow . '')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            $activeWorksheet->getStyle('A1:' . $startRow . '1')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            $activeWorksheet->setCellValue($startRow . '1', "Q{$columnSoal}/{$soal[$key]}/{$bobot[$key]}/{$value->cpl}/{$value->cpmk}");
            $columnSoal++;
            // $sheet->getCell($startRow . $columnIndex)->getHyperlink()->setUrl('http://127.0.0.1:8000/dosen/soal/cetakSoal/'.$soalItem->id); // URL to link to
            $sheet->getColumnDimension($startRow)->setWidth(15);
            $validation = $sheet->getCell($startRow . '2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setFormula1(0); // Minimum character length
            $validation->setFormula2(100); // Maximum character length
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Hanya boleh input angka 0-100');
            $validationRange = $startRow . ':' . $startRow . '';
            $sheet->setDataValidation($validationRange, $validation);

            $validation5 = $sheet->getCell($startRow . '1')->getDataValidation();
            $validation5->setAllowBlank(false);
            $validation5->setShowInputMessage(true);
            $validation5->setShowErrorMessage(true);
            $validation5->setShowDropDown(true);
            $validation5->setPrompt('soal/id_soal/bobot_soal/id_cpl/id_cpmk');
            $validationRange5 = $startRow . '1:' . $startRow . '1';
            $sheet->setDataValidation($validationRange5, $validation5);
            $startRow++;
            
            $numericValue = ord($startRow) - ord('A'); 
            $newColumn = chr($numericValue - 1 + ord('A'));
            $activeWorksheet->setCellValue('K2', '=SUM(L2:'.$newColumn . '2)/' . $columnSoal-1);
        }
        
        $validation1 = $sheet->getCell('D2')->getDataValidation();
            $validation1->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation1->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation1->setAllowBlank(false);
            $validation1->setShowInputMessage(true);
            $validation1->setShowErrorMessage(true);
            $validation1->setShowDropDown(true);
            $validation1->setErrorTitle('Input error');
            $validation1->setError('Only integers are allowed!');
            $validationRange1 = 'D:D';
            $sheet->setDataValidation($validationRange1, $validation1);
        $validation2 = $sheet->getCell('C2')->getDataValidation();
            $validation2->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation2->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation2->setAllowBlank(false);
            $validation2->setShowInputMessage(true);
            $validation2->setShowErrorMessage(true);
            $validation2->setShowDropDown(true);
            $validation2->setErrorTitle('Input error');
            $validation2->setError('Only integers are allowed!');
            $validationRange2 = 'C:C';
            $sheet->setDataValidation($validationRange2, $validation2);
        $validation3 = $sheet->getCell('J2')->getDataValidation();
            $validation3->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation3->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation3->setAllowBlank(false);
            $validation3->setShowInputMessage(true);
            $validation3->setShowErrorMessage(true);
            $validation3->setShowDropDown(true);
            $validation3->setErrorTitle('Input error');
            $validation3->setError('Only integers are allowed!');
            $validationRange3 = 'J:J';
            $sheet->setDataValidation($validationRange3, $validation3);
        $validation4 = $sheet->getCell('K2')->getDataValidation();
            $validation4->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation4->setAllowBlank(false);
            $validation4->setShowInputMessage(true);
            $validation4->setShowErrorMessage(true);
            $validation4->setShowDropDown(true);
            $validation4->setErrorTitle('Input error');
            $validation4->setError('Only integers are allowed!');
            $validationRange4 = 'K:K';
            $sheet->setDataValidation($validationRange4, $validation4);

        $startColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('K');
        $highestColumnIndex = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumnIndex);
        $numColumns = $highestColumnIndex - $startColumnIndex + 1;
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template Penilaian.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function ExcelTanpaSoal(Request $request){
        $soal = $request->input('soal');
        $bobot = $request->input('bobot');
        $jenis = $request->input('jenis');
        $kode_mk = $request->input('kode_mk');
        $prodi = $request->input('prodi');
        $semester = $request->input('semester');
        $univ = $request->input('univ');

        // Validasi total bobot
        $totalBobot = array_sum($bobot);
        if ($totalBobot != 100) {
            return redirect()->back()->with('error', 'Total bobot harus sama dengan 100%.');
        }

        //mengambil nama mk
        $mk =  DB::table('mks')
                        ->select('nama')
                        ->where('kode', $kode_mk)
                        ->get();
            
        foreach ($mk as $mk) {
            $mks = $mk->nama;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getProtection()->setSheet(true);
        $activeWorksheet->setCellValue('A1', "Universitas");
        $sheet->getColumnDimension('A')->setWidth(18);
        $activeWorksheet->setCellValue('A2', $univ);
        $activeWorksheet->setCellValue('B1', "Tahun Akademik");
        $sheet->getColumnDimension('B')->setWidth(15);
        $activeWorksheet->setCellValue('B2', $semester . ' ' . date('Y'));
        $activeWorksheet->setCellValue('C1', "Angkatan");
        $activeWorksheet->setCellValue('D1', "NPM");
        $sheet->getColumnDimension('D')->setWidth(11);
        $activeWorksheet->setCellValue('E1', "Nama");
        $sheet->getColumnDimension('E')->setWidth(25);
        $activeWorksheet->setCellValue('F1', "Prodi");
        $sheet->getColumnDimension('F')->setWidth(25);
        $activeWorksheet->setCellValue('F2', $prodi);
        $activeWorksheet->setCellValue('G1', "Kode MK");
        $sheet->getColumnDimension('G')->setWidth(11);
        $activeWorksheet->setCellValue('G2', $kode_mk);
        $activeWorksheet->setCellValue('H1', "Nama MK");
        $activeWorksheet->setCellValue('H2', $mks);
        $sheet->getColumnDimension('H')->setWidth(30);
        $activeWorksheet->setCellValue('I1', "Jenis");
        $activeWorksheet->setCellValue('I2', $jenis);
        $activeWorksheet->setCellValue('J1', "Bobot Jenis (%)");
        $sheet->getColumnDimension('J')->setWidth(13);
        $activeWorksheet->setCellValue('K1', "Nilai Total");
        
        $startRow = 'L';
        $columnIndex = 1;
        $columnSoal = 1;
        
        foreach ($soal as $key => $value) {
            $activeWorksheet->getStyle('A:' . $startRow . '')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            $activeWorksheet->getStyle('A1:' . $startRow . '1')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            $activeWorksheet->setCellValue($startRow . '1', "$soal[$key]/$bobot[$key]");
            $sheet->getColumnDimension($startRow)->setWidth(15);
            $columnSoal++;
            $validation = $sheet->getCell($startRow . '2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setFormula1(0); // Minimum character length
            $validation->setFormula2(100); // Maximum character length
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Hanya boleh input angka 0-100');
            $validationRange = $startRow . ':' . $startRow . '';
            $sheet->setDataValidation($validationRange, $validation);

            $validation5 = $sheet->getCell($startRow . '1')->getDataValidation();
            $validation5->setAllowBlank(false);
            $validation5->setShowInputMessage(true);
            $validation5->setShowErrorMessage(true);
            $validation5->setShowDropDown(true);
            $validation5->setPrompt('soal/bobot_soal');
            $validationRange5 = $startRow . '1:' . $startRow . '1';
            $sheet->setDataValidation($validationRange5, $validation5);
            $startRow++;
            
            $numericValue = ord($startRow) - ord('A'); 
            $newColumn = chr($numericValue - 1 + ord('A'));
            $activeWorksheet->setCellValue('K2', '=SUM(L2:'.$newColumn . '2)/' . $columnSoal-1);
        }
        $validation1 = $sheet->getCell('D2')->getDataValidation();
            $validation1->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation1->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation1->setAllowBlank(false);
            $validation1->setShowInputMessage(true);
            $validation1->setShowErrorMessage(true);
            $validation1->setShowDropDown(true);
            $validation1->setErrorTitle('Input error');
            $validation1->setError('Only integers are allowed!');
            $validationRange1 = 'D:D';
            $sheet->setDataValidation($validationRange1, $validation1);
        $validation2 = $sheet->getCell('C2')->getDataValidation();
            $validation2->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation2->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation2->setAllowBlank(false);
            $validation2->setShowInputMessage(true);
            $validation2->setShowErrorMessage(true);
            $validation2->setShowDropDown(true);
            $validation2->setErrorTitle('Input error');
            $validation2->setError('Only integers are allowed!');
            $validationRange2 = 'C:C';
            $sheet->setDataValidation($validationRange2, $validation2);
        $validation3 = $sheet->getCell('J2')->getDataValidation();
            $validation3->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
            $validation3->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation3->setAllowBlank(false);
            $validation3->setShowInputMessage(true);
            $validation3->setShowErrorMessage(true);
            $validation3->setShowDropDown(true);
            $validation3->setErrorTitle('Input error');
            $validation3->setError('Only integers are allowed!');
            $validationRange3 = 'J:J';
            $sheet->setDataValidation($validationRange3, $validation3);
        $validation4 = $sheet->getCell('K2')->getDataValidation();
            $validation4->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation4->setAllowBlank(false);
            $validation4->setShowInputMessage(true);
            $validation4->setShowErrorMessage(true);
            $validation4->setShowDropDown(true);
            $validation4->setErrorTitle('Input error');
            $validation4->setError('Only integers are allowed!');
            $validationRange4 = 'K:K';
            $sheet->setDataValidation($validationRange4, $validation4);
  
        $startColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('K');
        $highestColumnIndex = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumnIndex);
        $numColumns = $highestColumnIndex - $startColumnIndex + 1;
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template Penilaian Tanpa Soal.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
    
    public function mutuexport(){
        
        return Excel::download(new MutuExport, 'mutu.xlsx');
    }
    
    public function import(){
        $mutus = Mutu::all();
        $mutus = Mutu::paginate(10);
        return view('dosen.mutu.importMutu', compact('mutus'));
    }

    public function import1(){
        $mutus = Mutu::all();
        $mutus = Mutu::paginate(10);
        return view('dosen.mutu.importTanpaSoal', compact('mutus'));
    }

    public function filter(Request $request){
        $mutus = Mutu::query();
        $mutus->when($request->course, function ($query) use ($request){
            return $query->where('Nama', 'like', '%'.$request->course.'%');
        });
        return view('dosen.mutu.importMutu', ['mutus' => $mutus->paginate(10)]);
    }

    public function filterSoal(Request $request)
    {
        $soals = Soal::query();

        // Filter berdasarkan course (jika ada)
        $soals->when($request->course, function ($query) use ($request) {
            return $query->whereHas('mk', function ($query) use ($request) {
                $query->where('nama', 'like', '%'.$request->course.'%');
            });
        });

        // Paginate dan kirimkan data ke view
        return view('dosen.soal.list', ['soals' => $soals->paginate(25)]);
    }

    public function mutuimport(Request $request){
        try{
            // dd($request->all());
            $validator = Validator::make($request->all(),[
                'file' => 'required|file|mimes:xlsx',
            ]);

            if ($validator->fails()){
                return redirect()->back()->with('error', "File harus format 'xslx'");
            }

            Excel::import(new MutuImport, $request->file('file'));
        
            return redirect()->back()->with('success', "File imported successfully");
        }catch (\Exception $e) {
            return redirect()->back()->with('error', "Ada kolom yang kosong. Silahkan cek kembali");
        }
    }

    public function importTanpaSoal(Request $request){
        try{
            // dd($request->all());
            $validator = Validator::make($request->all(),[
                'file' => 'required|file|mimes:xlsx',
            ]);

            if ($validator->fails()){
                return redirect()->back()->with('error', "File harus format 'xslx'");
            }

            Excel::import(new ImportTanpaSoal, $request->file('file'));
        
            return redirect()->back()->with('success', "File imported successfully");
        }catch (\Exception $e) {
            return redirect()->back()->with('error', "Ada kolom yang kosong. Silahkan cek kembali");
        }
    }

    public function list()
    {
        $soals = Soal::where('dosen', auth()->user()->name)
                    ->orderBy('kode_mk', 'asc')
                    ->groupBy('pertanyaan')
                    ->paginate(10);
        $firstPageItems = $soals->items();
        $secondPageItems = Soal::where('dosen', auth()->user()->name)
                      ->orderBy('kode_mk', 'asc')
                      ->groupBy('pertanyaan')
                      ->paginate(10, ['*'], 'page', 2)
                      ->items();

        return view('dosen.soal.list', compact('soals'));
    }

    public function Store(Request $request)
    {
        $data = $request->all();
        $data['dosen'] = auth()->user()->name;
        $kurikulum = $request->input('kurikulum');
        $prodi = $request->input('prodi');
        $kode_mk = $request->input('kode_mk');
        $minggu = $request->input('minggu');
        $jenis = $request->input('jenis');
        $pertanyaan = $request->input('pertanyaan');
        $bobotSoal = $request->input('bobotSoal');
        $cpl = $request->input('cpl');
        $cpmk = $request->input('cpmk');

        $mk =  DB::table('mks')
                        ->select('nama')
                        ->where('kode', $kode_mk)
                        ->get();
            
        foreach ($mk as $mk) {
            $mks = $mk->nama;
        }
        
        $hitungBobot = DB::table('soals')
        ->select(DB::raw('SUM(bobotSoal) as total'))
        ->from(DB::raw("
            (SELECT DISTINCT bobotSoal, pertanyaan
            FROM soals
            WHERE kode_mk = '$kode_mk' AND jenis = '$jenis') as subquery"))
        ->get();
        
        $totalBobot = 0;
        foreach ($hitungBobot as $bobot) {
            $totalBobot += $bobot->total;
        }

        $totalBobot = $totalBobot + array_sum($bobotSoal);

        if ($totalBobot < 100) {
            foreach ($cpmk as $index => $value) {
                    $pertanyaanValue = isset($pertanyaan->pertanyaan) ? $pertanyaan->pertanyaan : '';
                Soal::create([
                    "kurikulum" => $kurikulum,
                    "prodi" => $prodi,
                    "kode_mk" => $kode_mk,
                    "minggu" => $minggu,
                    "jenis" => $jenis,
                    "pertanyaan" => $pertanyaan[0],
                    "bobotSoal" => $bobotSoal[0],
                    "cpl" => $cpl[0],
                    'cpmk' => $value,
                    "dosen" => $data['dosen']
                ]);
            }
            return redirect(route('soal-add'))->with('success', "Bobot $jenis pada $mks saat ini adalah ($totalBobot)");
        } elseif($totalBobot == 100) {
            foreach ($cpmk as $index => $value) {
                Soal::create([
                    "kurikulum" => $kurikulum,
                    "prodi" => $prodi,
                    "kode_mk" => $kode_mk,
                    "minggu" => $minggu,
                    "jenis" => $jenis,
                    "pertanyaan" => $pertanyaan[0],
                    "bobotSoal" => $bobotSoal[0],
                    "cpl" => $cpl[0],
                    'cpmk' => $value,
                    "dosen" => $data['dosen']
                ]);
            }
            return redirect(route('soal-list'))->with('success', "Berhasil membuat soal");
        }else{
            return redirect(route('soal-add'))->with('error', "Bobot saai ini ($totalBobot) melebihi 100%");
        }
    }

    public function Edit($id)
    {
        $soal = Soal::findOrFail($id);
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = $rpss->pluck('id');
        $kurikulum = Kurikulum::all();
        $komponen = Komponen::groupBy('jenis')->get();
        return view('dosen.soal.edit', compact('rpss', 'soal', 'komponen', 'kurikulum'));
    }

    public function Update(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);
        $data = $request->all();
        $data['dosen'] = auth()->user()->name;
        $data['status'] = 1;
        $soal->update($data);
        return redirect(route('soal-list'))->with('success', 'Soal successfully updated!');
    }

    public function Delete($id)
    {
        $soal = Soal::findOrFail($id);
        $soal->cpmk()->sync([]);
        $soal->delete();
        return redirect(route('soal-list'))->with('success', 'Soal successfully removed!');
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
        $mk = MK::where('kode',$soal->kode_mk)->first();
        $data = compact(
            'mk',
            'soal',
            'mks',
            'cpmks',
            'cpmk_soals'
        );
        // dd($data);
        $pdf = PDF::loadView('dosen.soal.print', $data);
        $pdf->setOption('enable-local-file-access', true);
        return $pdf->stream('soal.pdf');
    }
}
