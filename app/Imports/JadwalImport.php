<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Lab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Find semester by tahun_ajaran and semester
        $semester = Semester::where('tahun_ajaran', $row['tahun_ajaran'])
            ->where('semester', $row['semester'])
            ->first();
        
        // Find mata kuliah by kode_mk
        $mataKuliah = MataKuliah::where('kode_mk', $row['kode_mk'])->first();
        
        // Find dosen by name or email
        $dosen = User::where('name', $row['dosen'])->orWhere('email', $row['dosen'])->first();
        
        // Find lab by name
        $lab = Lab::where('name', $row['lab'])->first();

        if ($semester && $mataKuliah && $dosen && $lab) {
            return new Jadwal([
                'semester_id' => $semester->semester_id,
                'mata_kuliah_id' => $mataKuliah->id,
                'dosen_id' => $dosen->id,
                'hari' => $row['hari'],
                'jam_mulai' => $row['jam_mulai'],
                'jam_selesai' => $row['jam_selesai'],
                'lab_id' => $lab->lab_id,
            ]);
        }
        
        // Skip if any related data not found
        return null;
    }
}