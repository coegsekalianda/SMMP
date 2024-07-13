<?php

namespace App\Imports;

use App\Models\Mutu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ImportTanpaSoal implements  WithStartRow,ToCollection,WithValidation, WithCalculatedFormulas
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if ($rows->count() > 0) {
                $headers = $rows->first();
                $columnCount = count($headers);
            }
            for ($i=11; $i < $columnCount ; $i++) { 
                $substring = explode('/', $headers[$i]);
                dd($rows);
                if ($row['3'] != 'NPM') {
                    Mutu::create([
                        'universitas' => ucwords($row[0]),
                        'tahun' => ucwords($row[1]),
                        'angkatan' => $row[2],
                        'NPM' => $row[3],
                        'Nama' => ucwords($row[4]),
                        'prodi' => ucwords($row[5]),
                        'Course' => strtoupper($row[6]),
                        'namaCourse' => strtoupper($row[7]),
                        'Jenis' => strtolower($row[8]),
                        'examWeight' => $row[9],
                        'Nilai' => $row[10],
                        'soal' => $substring[0],
                        'BobotSoal' => $substring[1],
                        'nilaiSoal' => $row[$i],
                    ]);
                }
            }
        }
    }
    public function startRow(): int
    {
            return 1;
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '3' => 'required',
            '4' => 'required',
            '5' => 'required',
            '6' => 'required',
            '7' => 'required',
            '8' => 'required',
            '9' => 'required',
            '10' => 'required',
            '11' => 'required',
        ];
    }
}
