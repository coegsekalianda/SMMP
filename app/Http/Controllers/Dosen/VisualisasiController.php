<?php

namespace App\Http\Controllers\Dosen;

use App\Models\MK;
use App\Models\CPL;
use App\Models\Mutu;
use App\Models\Soal;
use App\Models\CPLMK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class VisualisasiController extends Controller
{
    public function index()
    {
        // Ambil nama universitas dari tabel mutus
        $universitasMutus = DB::table('mutus')
            ->select('universitas')
            ->distinct()
            ->get();
        // Ambil data universitas lengkap dari tabel universitas
        $universitasData = DB::table('universitas')
            ->select('nama', 'img') // Menggunakan kolom 'nama' untuk kecocokan
            ->get()
            ->keyBy('nama'); // Menggunakan keyBy untuk memudahkan akses berdasarkan nama universitas

        // Gabungkan data dari kedua tabel
        $universitas = $universitasMutus->map(function ($item) use ($universitasData) {
            $item->img = $universitasData->get($item->universitas)->img ?? null; // Menambahkan properti img
            return $item;
        });

        return view('dosen.visualisasi.indexVisualisasi', compact('universitas'));
    }

    public function getProdiByUniversitas(Request $request)
    {
        // return $request;
        $universitas = $request->input('universitas');

        // ambil npm berdasarkan angkatan dari ajax
        $universitasData = DB::table('mutus')
            ->select('prodi')
            ->where('universitas', $universitas)
            ->distinct()
            ->get();
        // return $universitasData;
        // balikin npm ke dropdown
        $options = '<option value="">Pilih Prodi</option>';
        foreach ($universitasData as $a) {
            $options .= '<option value="' . $a->prodi . '">' . $a->prodi . '</option>';
        }

        return $options;
    }

    public function getAngkatanByUniversitas(Request $request)
    {
        $universitas = $request->input('universitas');
        // ambil npm berdasarkan angkatan dari ajax
        $angkatanData = DB::table('mutus')
            ->select('angkatan')
            ->where('universitas', $universitas)
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->get();
        // return $angkatanData;
        // balikin npm ke dropdown
        $options = '<option value="">Pilih Npm</option>';
        foreach ($angkatanData as $a) {
            $options .= '<option value="' . $a->angkatan . '">' . $a->angkatan . '</option>';
        }

        return $options;
    }



    public function getNpmByAngkatan(Request $request)
    {

        $angkatan = $request->input('angkatan');
        $universitas = $request->input('universitas');
        // ambil npm berdasarkan angkatan dari ajax
        $npm = DB::table('mutus')
            ->select('npm', 'nama')
            ->where('angkatan', $angkatan)
            ->where('universitas', $universitas)
            ->distinct()
            ->orderBy('npm', 'asc')
            ->get();
        // return $npm;
        // balikin npm ke dropdown
        $options = '<option value="">Pilih Npm</option>';
        foreach ($npm as $npm) {
            $options .= '<option value="' . $npm->npm . '">' . $npm->npm . '-' . $npm->nama . '</option>';
        }

        return $options;
    }

    public function getPemetaanCpl(Request $request)
    {

        $idCpl = $request->input('idCpl');
       
        $cpl = DB::table('cplmks')
            // ->select()
            ->join('mks', 'cplmks.kode_mk', '=', 'mks.kode', $type = 'left')
            ->where('id_cpl', $idCpl)
            ->get(['kode_mk', 'mks.nama']);
        return $cpl;
    }


    public function hasilVisualMahasiswa(Request $request)
    {
        $npm = $request->input('npm');
        $angkatan = $request->input('angkatan');
        $universitas = $request->input('universitas');
        $imgSrc = $request->input('imgSrc');

        
        // return $imgSrc;
        // TODO:REVISI SEMHAS : KETERCAPAIAN CPL
        $subQueryMutus = DB::table('mutus')
            ->select('npm', 'Course', 'jenis', DB::raw('MAX(id) as id'))
            ->groupBy('npm', 'Course', 'jenis');

        // Gabungkan subquery dengan tabel mutus untuk menghitung total nilai berdasarkan bobot ujian dan nilai
        $nilaiMk = DB::table('mutus')
            ->joinSub($subQueryMutus, 'sub', function ($join) {
                $join->on('mutus.id', '=', 'sub.id');
            })
            ->select('mutus.Course', DB::raw('SUM(mutus.examWeight / 100 * mutus.Nilai) as total_nilai'))
            ->where('mutus.npm', $npm)
            ->groupBy('mutus.Course')
            ->get();

        // return $nilaiMk;
        $nilaiMkLulus = [];
        $nilaiMkTidakLulus = [];


        // return $nilaiMkLulus;
        // buat masukkin mk yg lulus >50
        foreach ($nilaiMk as $nilai) {
            $namaMk = DB::table('mks')
                ->where('kode', $nilai->Course)
                ->value('nama');
            if ($nilai->total_nilai >= 50) {
                $nilaiMkLulus[$nilai->Course] = [$nilai->total_nilai, $namaMk];
            } else {
                $nilaiMkTidakLulus[$nilai->Course] = [$nilai->total_nilai, $namaMk];
            }
        }
        // Ambil list key Course dari $nilaiMkLulus yg dah lulus
        // $coursesLulus = array_keys($nilaiMkLulus);
        // ambil cpl perhitungannya
        $cplNilaiMk = DB::table('mutus')
            ->select('Course', 'Cpl', DB::raw('SUM(examWeight / 100 * Nilai) as total_nilaiCek'))
            ->where('npm', $npm)
            ->whereIn('Course', array_keys($nilaiMkLulus))
            ->groupBy('Course', 'Cpl')
            ->get();

        // return $nilaiMk;
        $cplNilaiMkUnique = $cplNilaiMk->pluck('Cpl')->unique()->toArray();

        // Query ke cplmk
        $cplResults = DB::table('cplmks')
            ->whereIn('id_cpl', $cplNilaiMkUnique)
            ->get()->groupBy('id_cpl');

        //Buat dapetin semua cpl untuk pemilihan pemmetaan 
        $cplResultsAll = DB::table('cpls')
                       ->get(['id','kode']);
        // return $cplResultsAll;
        // Melihat keseluruhan total cpl perhitungan
        $persentaseTotalCplCapaian = [];

        // Membuat array hasil akhir
        foreach ($cplResults as $id_cpl => $items) {
            $kode_mk_list = $items->pluck('kode_mk')->toArray();
            $kodeMkArray = explode(',', implode(',', $kode_mk_list));
            $countKesamaan = count(array_intersect($kodeMkArray, array_keys($nilaiMkLulus)));
            $persentase = ($countKesamaan / count($items)) * 100;

            $cplCode = DB::table('cpls')
                ->where('id', $id_cpl)
                ->value('kode');

            $persentaseTotalCplCapaian[] = [
                'cpl' => $id_cpl,
                'kode_mk' => implode(',', $kode_mk_list),
                'count_mk' => count($items),
                'persentase' => round($persentase, 2),
                'kode_cpl' => $cplCode,
            ];
        }
        // buat nambahin kode_cpl
        // return $persentaseLulusCpl;
        // // Fungsi untuk ngurutin $persentaseLulusCpl berdasarkan 'cpl'
        // usort($persentaseLulusCpl, function ($a, $b) {
        //     return $a['cpl'] <=> $b['cpl']; 
        // });

        // TODO:CPL
        $cpl = DB::table('mutus as m')
            ->select(
                'm.NPM',
                'm.nama',
                'm.angkatan',
                'm.prodi',
                'm.cpl',
                DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpl')
            )
            ->where('m.NPM', $npm)
            ->where('m.universitas', $universitas)
            ->groupBy('m.cpl')
            ->get();
        // return $cpl;
        // SUMMARRY CPL
        $tempCplArray = $cpl->pluck('HasilCpl', 'cpl')->toArray();

        $minValue = min($tempCplArray);
        $minSummaryCpl = [];
        // Perulangan untuk mencari semua kunci dengan nilai terendah
        foreach ($tempCplArray as $key => $value) {
            if ($value == $minValue) {
                $minSummaryCpl[$key] = $minValue;
            }
        }

        $maxValue = max($tempCplArray);
        $maxSummaryCpl = [];

        // Perulangan untuk mencari semua kunci dengan nilai maksimum
        foreach ($tempCplArray as $key => $value) {
            if ($value == $maxValue) {
                $maxSummaryCpl[$key] = $maxValue;
            }
        }

        // return $minSummaryCpl;

        // array buat ambil data query
        $countDataCpl = [];
        $foreignCplmutu = [];

        // masukin data ke array buat proses ke chart js
        foreach ($cpl as $row) {
            $countDataCpl[] = $row->HasilCpl;
            $foreignCplmutu[] = $row->cpl;
        }

        //TODO: Profil CPL
        $profilCplInfo = DB::table('profil_cpl')
            ->join('profil_lulusan', 'profil_cpl.idProfil', '=', 'profil_lulusan.id')
            ->whereIn('profil_cpl.idCpl', $foreignCplmutu)
            ->select(
                'profil_cpl.id',
                'profil_cpl.idProfil',
                'profil_cpl.idCpl',
                'profil_cpl.bobot',
                'profil_lulusan.namaProfil',
                'profil_lulusan.deskripsi'
            )
            ->get();
        // Array kunci cpl dan value HasilCpl
        $profilCpl = $cpl->pluck('HasilCpl', 'cpl')->toArray();

        $hasilFinalProfil = [];

        foreach ($profilCpl as $cpl => $hasilCpl) {
            $profilCplData = $profilCplInfo->where('idCpl', $cpl)->all();

            // Iiterasi koleksi
            foreach ($profilCplData as $singleProfilCplData) {
                $idProfil = $singleProfilCplData->idProfil;

                // Inisialisasi array jika belum ada
                if (!isset($hasilFinalProfil[$idProfil])) {
                    $hasilFinalProfil[$idProfil] = [
                        'NamaProfil' => $singleProfilCplData->namaProfil,
                        'Deskripsi' => $singleProfilCplData->deskripsi,
                        'TotalAkhir' => 0,
                        'CPLs' => []
                    ];
                }

                // ambil data dari tabel cpls pake foreign key idCpl
                $cplInfo = DB::table('cpls')->where('id', $cpl)->first();
                $kode = $cplInfo->kode;

                // Mengalikan HasilCpl dengan bobot
                $bobot = $singleProfilCplData->bobot;
                $total = $hasilCpl * $bobot;
                // return $total;
                // Menambahkan hasil ke dalam array
                $hasilFinalProfil[$idProfil]['CPLs'][] = [
                    'CPL' => $kode,
                    'Bobot' => $bobot,
                    'HasilCPL' => $hasilCpl,
                    'Total' => $total,
                ];

                // Menambahkan total ke dalam TotalAkhir
                $hasilFinalProfil[$idProfil]['TotalAkhir'] += $total;
            }
        }
        usort($hasilFinalProfil, function ($a, $b) {
            return strcmp($a['NamaProfil'], $b['NamaProfil']);
        });
        // return $hasilFinalProfil;
        // BUAT BAR CHART DATA
        $chartDataProfil = collect($hasilFinalProfil)->map(function ($profil) {
            return [
                'label' => $profil['NamaProfil'],
                'data' => number_format($profil['TotalAkhir'], 2, '.', ''),
            ];
        })->sortBy('label')->values()->all();

        //    data
        $data   =  DB::table('mutus')
            ->select('nama', 'prodi')
            ->where('npm', $npm)
            ->where('universitas', $universitas)
            ->distinct()
            ->first();
        $nama = $data->nama;
        $prodi = $data->prodi;

        $allNpm =  DB::table('mutus')
            ->select('npm')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();
        $resultArray = [];
        foreach ($allNpm as $npmObject) {
            $npm = $npmObject->npm;

            $cpl = DB::table('mutus as m')
                ->select(
                    'm.NPM',
                    'm.nama',
                    'm.cpl',
                    DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpl')
                )
                ->where('m.NPM', $npm)
                ->where('m.universitas', $universitas)
                ->whereIn('cpl', $foreignCplmutu)
                ->groupBy('m.cpl')
                ->get();

            $resultArray[] = [
                'npm' => $npm,
                'cplData' => $cpl,
            ];
        }
        // return $persentaseLulusCpl;
        // return $resultArray;
        // AVERAGE CPL ANGKATAN
        $averageCpl = [];
        $countCpl = [];
        // return $persentaseLulusCpl;
        foreach ($resultArray as $item) {
            // return $persentaseLulusCpl;
            $cplData = $item['cplData'];
            // return $persentaseLulusCpl;
            foreach ($cplData as $cplItem) {
                $cplIndex = $cplItem->cpl;
                $hasilCpl = $cplItem->HasilCpl;

                // Inisialisasi nilai pertama jika cpl belum ada dalam $averageCpl
                if (!isset($averageCpl[$cplIndex])) {
                    $averageCpl[$cplIndex] = $hasilCpl;
                    $countCpl[$cplIndex] = 1;
                } else {
                    // Jika cpl sudah ada, tambahkan $hasilCpl dan jumlahnya
                    $averageCpl[$cplIndex] += $hasilCpl;
                    $countCpl[$cplIndex]++;
                }
            }
        }
        // TODO:Sampe sini kok berubah?
        // return $persentaseLulusCpl;
        // Menghitung rata-rata dengan membagi hasilCpl dengan jumlah nilai cpl yang sama
        foreach ($averageCpl as $cplIndex => $total) {
            $averageCpl[$cplIndex] /= $countCpl[$cplIndex];
        }

        $averageCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $averageCpl);
        // Ubah array asosiatif jdi array biasa
        ksort($averageCplFormat);
        $averageCplAngkatan = array_values($averageCplFormat);

        // SUMMARY PERBANDINGAN
        $minAvgSummaryCpl = [];

        // Perulangan untuk membandingkan kunci yang sama antara $minSummaryCpl dan $averageCplFormat
        foreach ($minSummaryCpl as $key => $value) {
            // Memeriksa apakah kunci ada di dalam $averageCplFormat
            if (array_key_exists($key, $averageCplFormat)) {
                // Menyimpan kunci dan nilainya di dalam $minAvgSummaryCpl
                $minAvgSummaryCpl[$key] = $averageCplFormat[$key];
            }
        }

        $maxAvgSummaryCpl = [];

        // Perulangan untuk membandingkan kunci yang sama antara $maxSummaryCpl dan $averageCplFormat
        foreach ($maxSummaryCpl as $key => $value) {
            // Memeriksa apakah kunci ada di dalam $averageCplFormat
            if (array_key_exists($key, $averageCplFormat)) {
                // Menyimpan kunci dan nilainya di dalam $maxAvgSummaryCpl
                $maxAvgSummaryCpl[$key] = $averageCplFormat[$key];
            }
        }
        //BAGIAN GANTI ID CPL JDI KODE
        $keysMin = array_keys($minSummaryCpl);

        // Query untuk mengambil kode dari tabel cpls berdasarkan semua kunci
        $kodeCpl = DB::table('cpls')
            ->whereIn('id', $keysMin)
            ->pluck('kode', 'id')
            ->toArray();

        // Ubah kunci-kunci dalam $minSummaryCpl menggunakan kode dari tabel cpls
        $minSummaryCplWithCode = [];
        foreach ($minSummaryCpl as $key => $value) {
            if (isset($kodeCpl[$key])) {
                $minSummaryCplWithCode[$kodeCpl[$key]] = $value;
            }
        }

        $keysMax = array_keys($maxSummaryCpl);

        // Query untuk mengambil kode dari tabel cpls berdasarkan semua kunci
        $kodeCpl = DB::table('cpls')
            ->whereIn('id', $keysMax)
            ->pluck('kode', 'id')
            ->toArray();

        // Ubah kunci-kunci dalam $maxSummaryCpl menggunakan kode dari tabel cpls
        $maxSummaryCplWithCode = [];
        foreach ($maxSummaryCpl as $key => $value) {
            if (isset($kodeCpl[$key])) {
                $maxSummaryCplWithCode[$kodeCpl[$key]] = $value;
            }
        }

        // return $maxSummaryCplWithCode;


        // MAX MIN CPL ANGKATAN
        $minCplValues = [];
        $maxCplValues = [];
        // return $persentaseLulusCpl;
        foreach ($resultArray as $item) {

            $cplData = $item['cplData'];

            foreach ($cplData as $cplItem) {
                $cplIndeks = $cplItem->cpl;
                $hasilCpl = $cplItem->HasilCpl;

                if (!isset($minCplValues[$cplIndeks]) || $hasilCpl < $minCplValues[$cplIndeks]) {
                    $minCplValues[$cplIndeks] = $hasilCpl;
                }
                if (!isset($maxCplValues[$cplIndeks]) || $hasilCpl > $maxCplValues[$cplIndeks]) {
                    $maxCplValues[$cplIndeks] = $hasilCpl;
                }
            }
        }
        // return $persentaseLulusCpl;
        $minCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $minCplValues);
        $maxCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $maxCplValues);
        $minCplAngkatan = array_map('floatval', array_values($minCplFormat));
        $maxCplAngkatan = array_map('floatval', array_values($maxCplFormat));
        //    return $minCplAngkatan;

        // return $npm;
        // Ambil kelas berdasarkan perhitungan cpl
        $querycourseCpl = DB::table('mutus')
            ->select('Course', 'namaCourse', 'cpl', 'npm')
            ->whereIn('cpl', $foreignCplmutu)
            ->where('npm', $npm)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->get();
        // return $querycourseCpl;
        $courseArray = array_unique($querycourseCpl->pluck('namaCourse', 'Course')->toArray());

        // Course untuk lihat cpmk berdasarkan matkul yg diampuh dosen kalo kaprodi bebas
        $userNameLogin = auth()->user()->name;
        $userJabatanLogin = auth()->user()->jabatan;

        if ($userJabatanLogin == "Kaprodi") {
            $courseCpmkRestrict = $courseArray;
        } else {
            $courseCpmkPj       = DB::table('rpss')
                ->select('kode_mk', 'pengembang')
                ->where('pengembang', $userNameLogin)
                ->get();
            $courseCpmkRestrict = [];

            foreach ($courseCpmkPj as $item) {
                // Periksa apakah kode_mk ada dalam $courseArray
                if (isset($courseArray[$item->kode_mk])) {
                    // Jika ada, tambahkan ke $courseCpmkRestrict dengan kunci dan nilai yang sama
                    $courseCpmkRestrict[$item->kode_mk] = $courseArray[$item->kode_mk];
                }
            }
        }
        // return $courseCpmkRestrict;


        // Ambil judul berdasarkan foreign buat dikirim keterangan
        $judulCpl = DB::table('cpls')
            ->whereIn('id', $foreignCplmutu)
            ->pluck('judul')
            ->toArray();

        // Ambil panjang array dan masukan label cpl
        $labelCpl = DB::table('cpls')
            ->whereIn('id', $foreignCplmutu)
            ->pluck('kode')
            ->toArray();
        // return $labelCpl;                 
        // $length = count($judulCpl);
        // for ($i = 1; $i <= $length; $i++) {
        //     $labelCpl[] = "CPL 0" . $i;
        // }


        // TODO:alur mencari soal yang menyebabkan cpl terendah 
        $dictionaryCpl = collect($foreignCplmutu)->combine($countDataCpl)->all();


        // TODO:Soal terendah
        $minCpl = collect($dictionaryCpl)->min();
        $minCplKey = collect($dictionaryCpl)->search($minCpl);
        // dd($minCplKey);

        // Joinkan tabel bila soal deskripsi tidak ada ambil dari idsoal di tabel soal
        $soalDesc = DB::table('mutus')
            ->select('mutus.id', 'mutus.soal', 'mutus.Jenis', 'mutus.Course', 'mutus.namaCourse', 'mutus.idSoal', 'soals.pertanyaan as soalFromId')
            ->leftJoin('soals', 'mutus.idSoal', '=', 'soals.id')
            ->where('mutus.npm', $npm)
            ->where('mutus.cpl', $minCplKey)
            ->where('mutus.universitas', $universitas)
            ->distinct()
            ->get();
        // return $soalDesc;    
        //kelompokin 1 array membuang soal null
        $uniqueData = [];
        // return $persentaseTotalCplCapaian;

        foreach ($soalDesc as $result) {
            // cek untuk isi atribut soal, buang yang kosong
            $soal = !empty($result->soal) ? $result->soal : $result->soalFromId;

            // kunci unik(namaCourse, jenis, soal)
            $uniqueKey = $result->namaCourse . '|' . $result->Jenis . '|' . $soal;

            // Tambahkan elemen ke array asosiatif jika belum ada
            if (!isset($uniqueData[$uniqueKey])) {
                $uniqueData[$uniqueKey] = [
                    'soal' => $soal,
                    'id' => $result->id,
                    'Jenis' => $result->Jenis,
                    'namaCourse' => $result->namaCourse,
                    'idSoal' => $result->idSoal,
                ];
            }
        }
        $soalTerendah = array_values($uniqueData);
        // return $minAvgSummaryCpl;
        // dd($soalTerendah);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => [
                'angkatan' => $angkatan,
                'npm' => $npm,
                'countDataCpl' => $countDataCpl,
                'judulCpl' => $judulCpl,
                'labelCpl' => $labelCpl,
                'nama' => $nama,
                'prodi' => $prodi,
                'courseArray' => $courseArray,
                'soalTerendah' => $soalTerendah,
                'minCplAngkatan' => $minCplAngkatan,
                'maxCplAngkatan' => $maxCplAngkatan,
                'averageCplAngkatan' => $averageCplAngkatan,
                'allNpm' => $allNpm,
                'hasilFinalProfil' => $hasilFinalProfil,
                'chartDataProfil' => $chartDataProfil,
                'courseCpmkRestrict' => $courseCpmkRestrict,
                'minAvgSummaryCpl' => $minAvgSummaryCpl,
                'maxAvgSummaryCpl' => $maxAvgSummaryCpl,
                'minSummaryCplWithCode' => $minSummaryCplWithCode,
                'maxSummaryCplWithCode' => $maxSummaryCplWithCode,
                'universitas' => $universitas,
                'imgSrc' => $imgSrc,
                'nilaiMkLulus' => $nilaiMkLulus,
                'nilaiMkTidakLulus' => $nilaiMkTidakLulus,
                'persentaseTotalCplCapaian' => $persentaseTotalCplCapaian,
                'cplResultsAll' => $cplResultsAll,
            ],
            'showVisualContainer' => true
        ]);
    }


    public function hasilVisualCpmkMahasiswa(Request $request)
    {
        // dd($request);
        $allNpm = json_decode($request->input('allNpm'));
        // dd($allNpm);
        $npmArray = [];

        //buat select cpmk lain
        foreach ($allNpm as $item) {
            $npmArray[] = $item->npm;
        }
        $universitas = $request->universitasCPMK;
        $universitasImg = $request->universitasImg;
        // dd($universitasImg);
        $allNamaNpmData = DB::table('mutus')
            ->whereIn('Npm', $npmArray)
            ->where('universitas', $universitas)
            ->select('Nama', 'NPM')
            ->distinct()
            ->get();
        // dd($allNamaNpmData);
        $npm = $request->npm;
        $npm = $request->npm;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $angkatan = $request->angkatan;
        $originalReqCourse = $request->course;
        list($course, $namaCourse) = explode('-', $originalReqCourse);
        // dd($npm);    

        // DB::statement("SET SESSION group_concat_max_len = 10000");

        // Kemudian jalankan kueri Anda seperti biasa
        $cpmk = DB::table('mutus as m')
            ->select(
                'm.cpmk',
                DB::raw('GROUP_CONCAT(m.idSoal) AS DaftarIdSoal'),
                'cpmks.judul',
                DB::raw('GROUP_CONCAT(m.jenis) AS DaftarJenis'),
                DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpmk'),
                DB::raw('GROUP_CONCAT(IF(m.idSoal IS NOT NULL, m.idSoal, m.soal)) AS DaftarSoal')
            )
            ->join('cpmks', 'm.cpmk', '=', 'cpmks.id')
            ->leftJoin('soals', 'm.idSoal', '=', 'soals.id')
            ->where('m.NPM', $npm)
            ->where('universitas', $universitas)
            ->where(
                'm.course',
                $course
            )
            ->groupBy('m.cpmk')
            ->get();

        // dd($cpmk);            
        // array buat ambil data query
        $countDataCpmk = [];
        $foreignCpmkmutu = [];
        $labelCpmk = [];

        foreach ($cpmk as $row) {
            $countDataCpmk[] = $row->HasilCpmk;
            $foreignCpmkmutu[] = $row->cpmk;
        }
        // Judul cpmk
        $judulCpmk = DB::table('cpmks')
            ->whereIn('id', $foreignCpmkmutu)
            ->pluck('judul')
            ->toArray();

        // dd($judulCpmk);        
        $length = count($judulCpmk);
        for ($i = 1; $i <= $length; $i++) {
            $labelCpmk[] = "CPMK 0" . $i;
        }

        // Hitung dulu per mahasiswa cpmknya
        $resultArray = [];

        foreach ($allNpm as $npmObject) {
            $npm = $npmObject->npm;

            $cpmkAngkatan = DB::table('mutus as m')
                ->select(
                    'm.NPM',
                    'm.nama',
                    'm.cpmk',
                    DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpmk')
                )
                ->where('m.NPM', $npm)
                ->where('m.course', $course)
                ->where('m.universitas', $universitas)
                ->groupBy('m.cpmk')
                ->get();

            $resultArray[] = [
                'npm' => $npm,
                'cpmkData' => $cpmkAngkatan
            ];
        }
        // dd($resultArray);
        $averageCpmk = [];
        $countCpmk = [];

        foreach ($resultArray as $item) {
            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndex = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                // Inisialisasi nilai pertama jika cpl belum ada dalam $averageCpmk
                if (!isset($averageCpmk[$cpmkIndex])) {
                    $averageCpmk[$cpmkIndex] = $hasilCpmk;
                    $countCpmk[$cpmkIndex] = 1;
                } else {
                    // Jika cpmk sudah ada, tambahkan $hasilcpmk dan jumlahnya
                    $averageCpmk[$cpmkIndex] += $hasilCpmk;
                    $countCpmk[$cpmkIndex]++;
                }
            }
        }

        // Menghitung rata-rata dengan membagi hasilCpl dengan jumlah nilai cpl yang sama
        foreach ($averageCpmk as $cpmkIndex => $total) {
            $averageCpmk[$cpmkIndex] /= $countCpmk[$cpmkIndex];
        }

        $averageCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $averageCpmk);
        // Ubah array asosiatif jdi array biasa
        // return $averageCpmkFormat;

        ksort($averageCpmkFormat);
        // gabungin cpmk angkatan ke cpmk biar bisa 1 tabel
        foreach ($cpmk as $key => $item) {
            // Ambil nilai rata-rata dari $averageCpmkFormat yang memiliki kunci yang sama dengan nilai cpmk pada $item
            $average = $averageCpmkFormat[$item->cpmk] ?? null;

            // Jika nilai rata-rata tersedia, tambahkan ke $item
            if ($average !== null) {
                $cpmk[$key]->averageCpmkFormat = $average;
            }
        }
        // dd($cpmk);
        $averageCpmkAngkatan = array_values($averageCpmkFormat);
        // $averageCpmkAngkatan = array_map('floatval', array_values($averageCpmkFormat));

        // MAX MIN CPMK ANGKATAN
        $minCpmkValues = [];
        $maxCpmkValues = [];

        foreach ($resultArray as $item) {

            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndeks = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                if (!isset($minCpmkValues[$cpmkIndeks]) || $hasilCpmk < $minCpmkValues[$cpmkIndeks]) {
                    $minCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
                if (!isset($maxCpmkValues[$cpmkIndeks]) || $hasilCpmk > $maxCpmkValues[$cpmkIndeks]) {
                    $maxCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
            }
        }

        $minCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $minCpmkValues);
        $maxCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $maxCpmkValues);
        $minCpmkAngkatan = array_map('floatval', array_values($minCpmkFormat));
        $maxCpmkAngkatan = array_map('floatval', array_values($maxCpmkFormat));
        // dd($averageCpmkAngkatan);


        // dd($cpmk);
        return view('dosen.visualisasi.hasilVisualisasiCpmkMahasiswa', [
            'countDataCpmk' => $countDataCpmk, 'judulCpmk' => $judulCpmk,
            'labelCpmk' => $labelCpmk, 'nama' => $nama, 'npm' => $npm, 'prodi' => $prodi, 'angkatan' => $angkatan,
            'completeCourseFormat' => $originalReqCourse, 'cpmk' => $cpmk, 'minCpmkAngkatan' => $minCpmkAngkatan,
            'maxCpmkAngkatan' => $maxCpmkAngkatan,
            'averageCpmkAngkatan' => $averageCpmkAngkatan,
            'allNamaNpmData' => $allNamaNpmData,
            'allNpm' => $allNpm,
            'universitas' => $universitas, 'universitasImg' => $universitasImg
        ]);
    }
    // GANTI MAHASISWA LAIN CPMK

    // TODO: SIDEBAR ANGKATAN
    public function indexAngkatan()
    {
        // Ambil nama universitas dari tabel mutus
        $universitasMutus = DB::table('mutus')
            ->select('universitas')
            ->distinct()
            ->get();
        // Ambil data universitas lengkap dari tabel universitas
        $universitasData = DB::table('universitas')
            ->select('nama', 'img')
            ->get()
            ->keyBy('nama');

        // Gabungkan data dari kedua tabel
        $universitas = $universitasMutus->map(function ($item) use ($universitasData) {
            $item->img = $universitasData->get($item->universitas)->img ?? null; // Menambahkan properti img
            return $item;
        });
        return view('dosen.visualisasi.indexVisualisasiAngkatan', compact('universitas'));
    }

    public function hasilVisualMahasiswaAngkatan(Request $request)
    {
        // return $request;
        $prodi = $request->prodi;
        $angkatan = $request->input('angkatan');
        $universitas = $request->input('universitas');
        $imgSrc = $request->input('imgSrc');

        // CPL ANGKATAN YANG INI YG BENER
        $allNpm =  DB::table('mutus')
            ->select('npm')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();
        // dd($allNpm);
        $resultArray = [];
        foreach ($allNpm as $npmObject) {
            $npm = $npmObject->npm;
            $cpl = DB::table('mutus as m')
                ->select(
                    'm.NPM',
                    'm.nama',
                    'm.cpl',
                    DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpl')
                )
                ->where('m.NPM', $npm)
                ->where('m.universitas', $universitas)
                ->groupBy('m.cpl')
                ->get();

            $resultArray[] = [
                'npm' => $npm,
                'cplData' => $cpl,
            ];
        }

        // Ambil key foreignnya
        $foreignCplMutu = [];
        foreach ($resultArray as $result) {
            $cplData = $result['cplData'];
            $cplValues = $cplData->pluck('cpl')->unique()->toArray();
            $foreignCplMutu = array_merge($foreignCplMutu, $cplValues);
        }
        $foreignCplMutu = array_unique($foreignCplMutu);

        $averageCpl = [];
        $countCpl = [];

        foreach ($resultArray as $item) {
            $cplData = $item['cplData'];

            foreach ($cplData as $cplItem) {
                $cplIndex = $cplItem->cpl;
                $hasilCpl = $cplItem->HasilCpl;

                // Inisialisasi nilai pertama jika cpl belum ada dalam $averageCpl
                if (!isset($averageCpl[$cplIndex])) {
                    $averageCpl[$cplIndex] = $hasilCpl;
                    $countCpl[$cplIndex] = 1;
                } else {
                    // Jika cpl sudah ada, tambahkan $hasilCpl dan jumlahnya
                    $averageCpl[$cplIndex] += $hasilCpl;
                    $countCpl[$cplIndex]++;
                }
            }
        }

        // Menghitung rata-rata dengan membagi hasilCpl dengan jumlah nilai cpl yang sama
        foreach ($averageCpl as $cplIndex => $total) {
            $averageCpl[$cplIndex] /= $countCpl[$cplIndex];
        }

        $averageCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $averageCpl);
        // Ubah array asosiatif jdi array biasa
        ksort($averageCplFormat);
        $averageCplAngkatan = array_values($averageCplFormat);
        // return $averageCplFormat;

        // MAX MIN CPL ANGKATAN
        $minCplValues = [];
        $maxCplValues = [];

        foreach ($resultArray as $item) {

            $cplData = $item['cplData'];

            foreach ($cplData as $cplItem) {
                $cplIndeks = $cplItem->cpl;
                $hasilCpl = $cplItem->HasilCpl;

                if (!isset($minCplValues[$cplIndeks]) || $hasilCpl < $minCplValues[$cplIndeks]) {
                    $minCplValues[$cplIndeks] = $hasilCpl;
                }
                if (!isset($maxCplValues[$cplIndeks]) || $hasilCpl > $maxCplValues[$cplIndeks]) {
                    $maxCplValues[$cplIndeks] = $hasilCpl;
                }
            }
        }
        $minCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $minCplValues);
        $maxCplFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $maxCplValues);


        // MIN MAX SUMMARY
        $min_value = min($minCplFormat);
        $min_keys = array_keys($minCplFormat, $min_value);
        $minSummary = [];
        foreach ($min_keys as $min_key) {
            $cpl = DB::table('cpls')->select('kode')->where('id', $min_key)->first();
            $min_codeAvg = $cpl->kode;
            $minSummary[$min_codeAvg] = $min_value;
        }
        // return $minSummary;
        // MAX SUMMARY
        $max_value = max($maxCplFormat);
        $max_keys = array_keys($maxCplFormat, $max_value);

        $maxSummary = [];
        foreach ($max_keys as $max_key) {
            $cpl = DB::table('cpls')->select('kode')->where('id', $max_key)->first();
            $max_code = $cpl->kode;
            $maxSummary[$max_code] = $max_value;
        }

        // MIN AVG SUMMARY
        $min_valueAvg = min($averageCplFormat);
        $min_keysAvg = array_keys($averageCplFormat, $min_valueAvg);

        $minAvgSummary = [];
        foreach ($min_keysAvg as $min_keyAvg) {
            $cpl = DB::table('cpls')->select('kode')->where('id', $min_keyAvg)->first();
            $min_codeAvg = $cpl->kode;
            $minAvgSummary[$min_codeAvg] = $min_valueAvg;
        }

        // MAX AVG SUMMARY
        $max_valueAvg = max($averageCplFormat);
        $max_keysAvg = array_keys($averageCplFormat, $max_valueAvg);

        $maxAvgSummary = [];
        foreach ($max_keysAvg as $max_keyAvg) {
            $cpl = DB::table('cpls')->select('kode')->where('id', $max_keyAvg)->first();
            $max_codeAvg = $cpl->kode;
            $maxAvgSummary[$max_codeAvg] = $max_valueAvg;
        }
        // return $maxAvgSummary;

        // return $maxSummary;

        $minCplAngkatan = array_map('floatval', array_values($minCplFormat));
        $maxCplAngkatan = array_map('floatval', array_values($maxCplFormat));
        // return $minCplAngkatan;
        // Buat labeling chart
        // Ambil kelas berdasarkan perhitungan cpl
        $querycourseCpl = DB::table('mutus')
            ->select('Course', 'namaCourse')
            ->whereIn('cpl', $foreignCplMutu)
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->get();
        $courseArray = array_unique($querycourseCpl->pluck('namaCourse', 'Course')->toArray());

        $judulCpl = DB::table('cpls')
            ->whereIn('id', $foreignCplMutu)
            ->pluck('judul')
            ->toArray();
        $labelCpl = DB::table('cpls')
            ->whereIn('id', $foreignCplMutu)
            ->pluck('kode')
            ->toArray();
        // Ambil panjang array dan masukan label cpl           
        // $length = count($judulCpl);
        // for ($i = 1; $i <= $length; $i++) {
        //     $labelCpl[] = "CPL 0" . $i;
        // }
        // dd($labelCpl);

        // Buat soal rata rata angkatan terendah 
        // return $minCplValues;
        $minCplKey = array_keys($minCplValues);
        // return $minCplKey;
        $soalDesc = DB::table('mutus')
            ->select('mutus.id', 'mutus.cpl', 'mutus.soal', 'mutus.Jenis', 'mutus.Course', 'mutus.namaCourse', 'mutus.idSoal', 'soals.pertanyaan as soalFromId')
            ->leftJoin('soals', 'mutus.idSoal', '=', 'soals.id')
            ->where('mutus.angkatan', $angkatan)
            ->where('mutus.prodi', $prodi)
            ->where('mutus.cpl', $minCplKey)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();
        // return $soalDesc;
        $uniqueData = [];

        foreach ($soalDesc as $result) {
            // cek untuk isi atribut soal, buang yang kosong
            $soal = !empty($result->soal) ? $result->soal : $result->soalFromId;
            // kunci unik(namaCourse, jenis, soal)
            $uniqueKey = $result->namaCourse . '|' . $result->Jenis . '|' . $soal;
            // Tambahkan elemen ke array asosiatif jika belum ada
            if (!isset($uniqueData[$uniqueKey])) {
                $uniqueData[$uniqueKey] = [
                    'soal' => $soal,
                    'id' => $result->id,
                    'Jenis' => $result->Jenis,
                    'namaCourse' => $result->namaCourse,
                    'idSoal' => $result->idSoal,
                ];
            }
        }

        $soalTerendah = array_values($uniqueData);
        // return $minCplAngkatan;
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => [
                'averageCplAngkatan' => $averageCplAngkatan, 'minCplAngkatan' => $minCplAngkatan, 'maxCplAngkatan' => $maxCplAngkatan,
                'judulCpl' => $judulCpl, 'labelCpl' => $labelCpl, 'courseArray' => $courseArray, 'soalTerendah' => $soalTerendah,
                'angkatan' => $angkatan, 'prodi' => $prodi, 'allNpm' => $allNpm,
                'minSummary' => $minSummary, 'maxSummary' => $maxSummary, 'minAvgSummary' => $minAvgSummary, 'maxAvgSummary' => $maxAvgSummary,
                'universitas' => $universitas, 'imgSrc' => $imgSrc
            ],
            'showVisualContainer' => true
        ]);
    }

    public function getAngkatanByProdiUniversitas(Request $request)
    {

        $prodi = $request->input('prodi');
        $universitas = $request->input('universitas');
        // ambil npm berdasarkan angkatan dari ajax
        $angkatanData = DB::table('mutus')
            ->select('angkatan')
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->orderBy('angkatan', 'desc')
            ->distinct()
            ->get();
        // return $prodiData;
        // balikin npm ke dropdown
        $options = '<option value="">Pilih Angkatan</option>';
        foreach ($angkatanData as $p) {
            $options .= '<option value="' . $p->angkatan . '">' . $p->angkatan . '</option>';
        }

        return $options;
    }

    public function hasilVisualCpmkAngkatan(Request $request)
    {

        $allAngkatan = json_decode($request->input('allAngkatan'));
        $allNpm = json_decode($request->input('allNpm'));
        $prodi = $request->prodi;
        $angkatan = $request->angkatan;
        $universitas = $request->universitasCPMK;
        $universitasImg = $request->universitasImg;
        $originalReqCourse = $request->course;
        list($course, $namaCourse) = explode('-', $originalReqCourse);
        // dd($allAngkatan);

        // Hitung cpmk angkatan
        $resultArray = [];

        foreach ($allNpm as $npmObject) {
            $npm = $npmObject->npm;

            $cpmkAngkatan = DB::table('mutus as m')
                ->select(
                    'm.NPM',
                    'm.nama',
                    'm.cpmk',
                    DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpmk')
                )
                ->where('m.NPM', $npm)
                ->where('m.course', $course)
                ->where('m.universitas', $universitas)
                ->groupBy('m.cpmk')
                ->get();

            $resultArray[] = [
                'npm' => $npm,
                'cpmkData' => $cpmkAngkatan
            ];
        }

        // Ambil key foreignnya
        $foreignCpmkMutu = [];
        foreach ($resultArray as $result) {
            $cpmkData = $result['cpmkData'];
            $cpmkValues = $cpmkData->pluck('cpmk')->unique()->toArray();
            $foreignCpmkMutu = array_merge($foreignCpmkMutu, $cpmkValues);
        }
        $foreignCpmkMutu = array_unique($foreignCpmkMutu);


        $averageCpmk = [];
        $countCpmk = [];

        foreach ($resultArray as $item) {
            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndex = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                // Inisialisasi nilai pertama jika cpl belum ada dalam $averageCpmk
                if (!isset($averageCpmk[$cpmkIndex])) {
                    $averageCpmk[$cpmkIndex] = $hasilCpmk;
                    $countCpmk[$cpmkIndex] = 1;
                } else {
                    // Jika cpmk sudah ada, tambahkan $hasilcpmk dan jumlahnya
                    $averageCpmk[$cpmkIndex] += $hasilCpmk;
                    $countCpmk[$cpmkIndex]++;
                }
            }
        }

        // Menghitung rata-rata dengan membagi hasilCpl dengan jumlah nilai cpl yang sama
        foreach ($averageCpmk as $cpmkIndex => $total) {
            $averageCpmk[$cpmkIndex] /= $countCpmk[$cpmkIndex];
        }
        // dd($averageCpmk);
        $averageCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $averageCpmk);
        // Ubah array asosiatif jdi array biasa
        // dd($averageCpmkFormat);
        ksort($averageCpmkFormat);
        // dd(($averageCpmkFormat));
        $averageCpmkAngkatan = array_values($averageCpmkFormat);
        // dd($averageCpmkAngkatan);

        // MAX MIN CPMK ANGKATAN
        $minCpmkValues = [];
        $maxCpmkValues = [];

        foreach ($resultArray as $item) {

            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndeks = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                if (!isset($minCpmkValues[$cpmkIndeks]) || $hasilCpmk < $minCpmkValues[$cpmkIndeks]) {
                    $minCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
                if (!isset($maxCpmkValues[$cpmkIndeks]) || $hasilCpmk > $maxCpmkValues[$cpmkIndeks]) {
                    $maxCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
            }
        }

        $minCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $minCpmkValues);
        $maxCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $maxCpmkValues);


        $minCpmkAngkatan = array_map('floatval', array_values($minCpmkFormat));
        $maxCpmkAngkatan = array_map('floatval', array_values($maxCpmkFormat));

        // MIN MAX SUMMARY
        $min_value = min($minCpmkFormat);
        $min_keys = array_keys($minCpmkFormat, $min_value);
        $minSummary = [];
        foreach ($min_keys as $key) {
            $minSummary[$key] = $min_value;
        }
        foreach ($minCpmkAngkatan as $index => $value) {
            if (in_array($value, $minSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $minSummary[$key] = $minSummary[array_search($value, $minSummary)];
                unset($minSummary[array_search($value, $minSummary)]);
            }
        }

        $max_value = max($maxCpmkFormat);
        $max_keys = array_keys($maxCpmkFormat, $max_value);
        $maxSummary = [];
        foreach ($max_keys as $key) {
            $maxSummary[$key] = $max_value;
        }
        foreach ($maxCpmkAngkatan as $index => $value) {
            if (in_array($value, $maxSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $maxSummary[$key] = $maxSummary[array_search($value, $maxSummary)];
                unset($maxSummary[array_search($value, $maxSummary)]);
            }
        }

        $min_avgValue = min($averageCpmkFormat);
        // dd($min_avgValue);
        $min_avgKeys = array_keys($averageCpmkFormat, $min_avgValue);
        $minAvgSummary = [];
        foreach ($min_avgKeys as $key) {
            $minAvgSummary[$key] = $min_avgValue;
        }
        foreach ($averageCpmkAngkatan as $index => $value) {
            if (in_array($value, $minAvgSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $minAvgSummary[$key] = $minAvgSummary[array_search($value, $minAvgSummary)];
                unset($minAvgSummary[array_search($value, $minAvgSummary)]);
            }
        }

        $max_avgValue = max($averageCpmkFormat);
        $max_avgKeys = array_keys($averageCpmkFormat, $max_avgValue);
        $maxAvgSummary = [];
        foreach ($max_avgKeys as $key) {
            $maxAvgSummary[$key] = $max_avgValue;
        }
        foreach ($averageCpmkAngkatan as $index => $value) {
            if (in_array($value, $maxAvgSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $maxAvgSummary[$key] = $maxAvgSummary[array_search($value, $maxAvgSummary)];
                unset($maxAvgSummary[array_search($value, $maxAvgSummary)]);
            }
        }
        // return $maxAvgSummary;
        // return $maxSummary;
        // return $minSummary;
        // return $minSummary;
        // return $minCpmkAngkatan;
        // Labelling chart
        $judulCpmk = DB::table('cpmks')
            ->whereIn('id', $foreignCpmkMutu)
            ->pluck('judul')
            ->toArray();

        $length = count($judulCpmk);
        for ($i = 1; $i <= $length; $i++) {
            $labelCpmk[] = "CPMK 0" . $i;
        }
        // Buat soal rata rata cpmk angkatan terendah 
        $minCpmkValue = min($averageCpmk);
        $minCpmkKey = array_search($minCpmkValue, $averageCpmk);

        // dd($minCpmkKey);
        $soalDesc = DB::table('mutus')
            ->select('mutus.id', 'mutus.cpmk', 'mutus.soal', 'mutus.Jenis', 'mutus.Course', 'mutus.namaCourse', 'mutus.idSoal', 'soals.pertanyaan as soalFromId')
            ->leftJoin('soals', 'mutus.idSoal', '=', 'soals.id')
            ->where('mutus.angkatan', $angkatan)
            ->where('mutus.prodi', $prodi)
            ->where('mutus.course', $course)
            ->where('mutus.universitas', $universitas)
            ->where('mutus.cpmk', $minCpmkKey)
            ->distinct()
            ->get();
        $uniqueData = [];

        foreach ($soalDesc as $result) {
            // cek untuk isi atribut soal, buang yang kosong
            $soal = !empty($result->soal) ? $result->soal : $result->soalFromId;
            // kunci unik(namaCourse, jenis, soal)
            $uniqueKey = $result->namaCourse . '|' . $result->Jenis . '|' . $soal;
            // Tambahkan elemen ke array asosiatif jika belum ada
            if (!isset($uniqueData[$uniqueKey])) {
                $uniqueData[$uniqueKey] = [
                    'soal' => $soal,
                    'id' => $result->id,
                    'idSoal' => $result->idSoal,
                    'Jenis' => $result->Jenis,
                    'namaCourse' => $result->namaCourse,
                ];
            }
        }

        $soalTerendah = array_values($uniqueData);
        // dd($soalTerendah);




        return view('dosen.visualisasi.hasilVisualisasiCpmkAngkatan', [
            'judulCpmk' => $judulCpmk,
            'labelCpmk' => $labelCpmk, 'prodi' => $prodi, 'angkatan' => $angkatan,
            'completeCourseFormat' => $originalReqCourse, 'minCpmkAngkatan' => $minCpmkAngkatan,
            'maxCpmkAngkatan' => $maxCpmkAngkatan,
            'averageCpmkAngkatan' => $averageCpmkAngkatan,
            'soalTerendah' => $soalTerendah,
            'course' => $originalReqCourse,
            'allNpm' => $allNpm,
            'allAngkatan' => $allAngkatan,
            'minSummary' => $minSummary,
            'maxSummary' => $maxSummary,
            'minAvgSummary' => $minAvgSummary,
            'maxAvgSummary' => $maxAvgSummary,
            'universitas' => $universitas, 'universitasImg' => $universitasImg
        ]);
    }

    public function getAllAngkatanCpmk(Request $request)
    {

        $courseRequest = $request->input('course');
        $prodi = $request->input('prodi');
        $universitas = $request->input('universitas');
        list($course, $namaCourse) = explode('-', $courseRequest);
        // return $course;
        $allAngkatan =  DB::table('mutus')
            ->select('angkatan')
            ->where('prodi', $prodi)
            ->where('course', $course)
            ->where('universitas', $universitas)
            ->orderBy('angkatan', 'desc')
            ->distinct()
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => ['allAngkatan' => $allAngkatan],
        ]);
    }

    public function getAllNpmByAngkatan(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $prodi = $request->input('prodi');

        $allNpm =  DB::table('mutus')
            ->select('npm')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->distinct()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => ['allNpm' => $allNpm],
        ]);
    }


    public function indexMataKuliah()
    {
        // Ambil nama universitas dari tabel mutus
        $universitasMutus = DB::table('mutus')
            ->select('universitas')
            ->distinct()
            ->get();
        // Ambil data universitas lengkap dari tabel universitas
        $universitasData = DB::table('universitas')
            ->select('nama', 'img')
            ->get()
            ->keyBy('nama');

        // Gabungkan data dari kedua tabel
        $universitas = $universitasMutus->map(function ($item) use ($universitasData) {
            $item->img = $universitasData->get($item->universitas)->img ?? null; // Menambahkan properti img
            return $item;
        });
        return view('dosen.visualisasi.indexVisualisasiMataKuliah', compact('universitas'));
    }

    public function getCourseByProdi(Request $request)
    {
        // return $request;
        $prodi = $request->input('prodi');
        $angkatan = $request->input('angkatan');
        $universitas = $request->input('universitas');
        // return $universitas;
        $courseArray = DB::table('mutus')
            ->select('course', 'namaCourse')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();
        // return $courseArray;
        // TODO: next abis makan
        $userNameLogin = auth()->user()->name;
        $userJabatanLogin = auth()->user()->jabatan;
        if ($userJabatanLogin == "Kaprodi") {
            $course = $courseArray->map(function ($item) {
                return [
                    'course' => $item->course,
                    'namaCourse' => $item->namaCourse
                ];
            })->toArray();
        } else {
            $courseCpmkPj    = DB::table('rpss')
                ->select('kode_mk', 'pengembang')
                ->where('pengembang', $userNameLogin)
                ->get();

            $course = [];

            $courseArrayAssoc = [];
            foreach ($courseArray as $courseItem) {
                $courseArrayAssoc[$courseItem->course] = $courseItem->namaCourse;
            }

            foreach ($courseCpmkPj as $item) {
                // Periksa apakah kode_mk ada dalam $courseArrayAssoc
                if (isset($courseArrayAssoc[$item->kode_mk])) {
                    // kalo ada, tambahkan ke $course 
                    $course[] = [
                        'course' => $item->kode_mk,
                        'namaCourse' => $courseArrayAssoc[$item->kode_mk],
                    ];
                }
            }
        }

        // balikin npm ke dropdown
        $options = '<option value="">Pilih Course</option>';
        foreach ($course as $c) {
            $options .= '<option value="' . $c['course'] . '-' . $c['namaCourse'] . '">' . $c['course'] . '-' . $c['namaCourse'] . '</option>';
        }

        return $options;
    }

    public function hasilVisualMahasiswaMataKuliah(Request $request)
    {
        // return $request;
        $prodi = $request->prodi;
        $angkatan = $request->input('angkatan');
        $universitas = $request->input('universitas');
        $imgSrc = $request->input('imgSrc');
        $originalReqCourse = $request->course;
        list($course, $namaCourse) = explode('-', $originalReqCourse);

        $allNpm =  DB::table('mutus')
            ->select('npm')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();


        // Hitung cpmk angkatan
        $resultArray = [];

        foreach ($allNpm as $npmObject) {
            $npm = $npmObject->npm;

            $cpmkAngkatan = DB::table('mutus as m')
                ->select(
                    'm.NPM',
                    'm.nama',
                    'm.cpmk',
                    DB::raw('ROUND(AVG((m.bobotSoal/100 * m.nilaiSoal) * m.examWeight / 100), 2) AS HasilCpmk')
                )
                ->where('m.NPM', $npm)
                ->where('m.course', $course)
                ->where('m.universitas', $universitas)
                ->groupBy('m.cpmk')
                ->get();

            $resultArray[] = [
                'npm' => $npm,
                'cpmkData' => $cpmkAngkatan
            ];
        }
        // dd($resultArray);

        // Ambil key foreignnya
        $foreignCpmkMutu = [];
        foreach ($resultArray as $result) {
            $cpmkData = $result['cpmkData'];
            $cplValues = $cpmkData->pluck('cpmk')->unique()->toArray();
            $foreignCpmkMutu = array_merge($foreignCpmkMutu, $cplValues);
        }
        $foreignCpmkMutu = array_unique($foreignCpmkMutu);

        $averageCpmk = [];
        $countCpmk = [];

        foreach ($resultArray as $item) {
            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndex = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                // Inisialisasi nilai pertama jika cpl belum ada dalam $averageCpmk
                if (!isset($averageCpmk[$cpmkIndex])) {
                    $averageCpmk[$cpmkIndex] = $hasilCpmk;
                    $countCpmk[$cpmkIndex] = 1;
                } else {
                    // Jika cpmk sudah ada, tambahkan $hasilcpmk dan jumlahnya
                    $averageCpmk[$cpmkIndex] += $hasilCpmk;
                    $countCpmk[$cpmkIndex]++;
                }
            }
        }

        // Menghitung rata-rata dengan membagi hasilCpl dengan jumlah nilai cpl yang sama
        foreach ($averageCpmk as $cpmkIndex => $total) {
            $averageCpmk[$cpmkIndex] /= $countCpmk[$cpmkIndex];
        }
        // dd($averageCpmk);
        $averageCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $averageCpmk);
        // Ubah array asosiatif jdi array biasa
        $averageCpmkAngkatan = array_map('floatval', array_values($averageCpmkFormat));
        // dd($averageCpmkAngkatan);

        // MAX MIN CPMK ANGKATAN
        $minCpmkValues = [];
        $maxCpmkValues = [];

        foreach ($resultArray as $item) {

            $cpmkData = $item['cpmkData'];

            foreach ($cpmkData as $cpmkItem) {
                $cpmkIndeks = $cpmkItem->cpmk;
                $hasilCpmk = $cpmkItem->HasilCpmk;

                if (!isset($minCpmkValues[$cpmkIndeks]) || $hasilCpmk < $minCpmkValues[$cpmkIndeks]) {
                    $minCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
                if (!isset($maxCpmkValues[$cpmkIndeks]) || $hasilCpmk > $maxCpmkValues[$cpmkIndeks]) {
                    $maxCpmkValues[$cpmkIndeks] = $hasilCpmk;
                }
            }
        }

        $minCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $minCpmkValues);
        $maxCpmkFormat = array_map(function ($value) {
            return number_format($value, 2, '.', '');
        }, $maxCpmkValues);
        $minCpmkAngkatan = array_map('floatval', array_values($minCpmkFormat));
        $maxCpmkAngkatan = array_map('floatval', array_values($maxCpmkFormat));

        // MIN MAX SUMMARY
        $min_value = min($minCpmkFormat);
        $min_keys = array_keys($minCpmkFormat, $min_value);
        $minSummary = [];
        foreach ($min_keys as $key) {
            $minSummary[$key] = $min_value;
        }
        foreach ($minCpmkAngkatan as $index => $value) {
            if (in_array($value, $minSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $minSummary[$key] = $minSummary[array_search($value, $minSummary)];
                unset($minSummary[array_search($value, $minSummary)]);
            }
        }
        // return $minSummary;
        $max_value = max($maxCpmkFormat);
        $max_keys = array_keys($maxCpmkFormat, $max_value);
        $maxSummary = [];
        foreach ($max_keys as $key) {
            $maxSummary[$key] = $max_value;
        }
        foreach ($maxCpmkAngkatan as $index => $value) {
            if (in_array($value, $maxSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $maxSummary[$key] = $maxSummary[array_search($value, $maxSummary)];
                unset($maxSummary[array_search($value, $maxSummary)]);
            }
        }

        $min_avgValue = min($averageCpmkFormat);
        // dd($min_avgValue);
        $min_avgKeys = array_keys($averageCpmkFormat, $min_avgValue);
        $minAvgSummary = [];
        foreach ($min_avgKeys as $key) {
            $minAvgSummary[$key] = $min_avgValue;
        }
        foreach ($averageCpmkAngkatan as $index => $value) {
            if (in_array($value, $minAvgSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $minAvgSummary[$key] = $minAvgSummary[array_search($value, $minAvgSummary)];
                unset($minAvgSummary[array_search($value, $minAvgSummary)]);
            }
        }

        $max_avgValue = max($averageCpmkFormat);
        $max_avgKeys = array_keys($averageCpmkFormat, $max_avgValue);
        $maxAvgSummary = [];
        foreach ($max_avgKeys as $key) {
            $maxAvgSummary[$key] = $max_avgValue;
        }
        foreach ($averageCpmkAngkatan as $index => $value) {
            if (in_array($value, $maxAvgSummary)) {
                $key = $index + 1; // tambahkan 1 ke indeks
                $maxAvgSummary[$key] = $maxAvgSummary[array_search($value, $maxAvgSummary)];
                unset($maxAvgSummary[array_search($value, $maxAvgSummary)]);
            }
        }

        // Labelling chart
        $judulCpmk = DB::table('cpmks')
            ->whereIn('id', $foreignCpmkMutu)
            ->pluck('judul')
            ->toArray();

        $length = count($judulCpmk);
        for ($i = 1; $i <= $length; $i++) {
            $labelCpmk[] = "CPMK 0" . $i;
        }
        // Buat soal rata rata cpmk angkatan terendah 
        $minCpmkValue = min($averageCpmk);
        $minCpmkKey = array_search($minCpmkValue, $averageCpmk);

        // dd($minCpmkKey);
        $soalDesc = DB::table('mutus')
            ->select('mutus.id', 'mutus.cpmk', 'mutus.soal', 'mutus.Jenis', 'mutus.Course', 'mutus.namaCourse', 'mutus.idSoal', 'soals.pertanyaan as soalFromId')
            ->leftJoin('soals', 'mutus.idSoal', '=', 'soals.id')
            ->where('mutus.angkatan', $angkatan)
            ->where('mutus.prodi', $prodi)
            ->where('mutus.course', $course)
            ->where('mutus.universitas', $universitas)
            ->where('mutus.cpmk', $minCpmkKey)
            ->distinct()
            ->get();

        $uniqueData = [];

        foreach ($soalDesc as $result) {
            // cek untuk isi atribut soal, buang yang kosong
            $soal = !empty($result->soal) ? $result->soal : $result->soalFromId;
            // kunci unik(namaCourse, jenis, soal)
            $uniqueKey = $result->namaCourse . '|' . $result->Jenis . '|' . $soal;
            // Tambahkan elemen ke array asosiatif jika belum ada
            if (!isset($uniqueData[$uniqueKey])) {
                $uniqueData[$uniqueKey] = [
                    'soal' => $soal,
                    'id' => $result->id,
                    'idSoal' => $result->idSoal,
                    'Jenis' => $result->Jenis,
                    'namaCourse' => $result->namaCourse,
                ];
            }
        }

        $soalTerendah = array_values($uniqueData);

        $npm =  DB::table('mutus')
            ->select('npm', 'nama')
            ->where('angkatan', $angkatan)
            ->where('prodi', $prodi)
            ->where('universitas', $universitas)
            ->distinct()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => [
                'judulCpmk' => $judulCpmk,
                'labelCpmk' => $labelCpmk, 'prodi' => $prodi, 'angkatan' => $angkatan,
                'completeCourseFormat' => $originalReqCourse, 'minCpmkAngkatan' => $minCpmkAngkatan,
                'maxCpmkAngkatan' => $maxCpmkAngkatan,
                'averageCpmkAngkatan' => $averageCpmkAngkatan,
                'soalTerendah' => $soalTerendah,
                'allNpm' => $allNpm,
                'npm' => $npm,
                'minSummary' => $minSummary,
                'maxSummary' => $maxSummary,
                'minAvgSummary' => $minAvgSummary,
                'maxAvgSummary' => $maxAvgSummary,
                'universitas' => $universitas,
                'imgSrc' => $imgSrc
            ],
            'showVisualContainer' => true
        ]);
    }

    public function getNamaByNpm(Request $request)
    {

        $npm = $request->input('npm');

        // ambil npm berdasarkan angkatan dari ajax
        $namaData = DB::table('mutus')
            ->where('npm', $npm)
            ->distinct()
            ->pluck('nama')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diolah',
            'result' => ['namaData' => $namaData],
        ]);
    }
}
