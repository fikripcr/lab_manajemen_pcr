<?php

namespace App\Imports;

use App\Models\Lab;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JadwalImport implements WithHeadingRow
{
    protected $semesters;
    protected $mks;
    protected $labs;
    protected $dosens;

    public function __construct()
    {
        $this->semesters = Semester::all();
        $this->mks = MataKuliah::all();
        $this->labs = Lab::all();
        $this->dosens = User::all();
    }

    public function collection(Collection $rows)
    {
        // Prepare all data to be inserted
        $jadwals = [];

        foreach ($rows as $row) {
            // Ensure all required keys exist
            if (
                !isset($row['tahun_ajaran']) ||
                !isset($row['semester']) ||
                !isset($row['kode_mk']) ||
                !isset($row['dosen']) ||
                !isset($row['hari']) ||
                !isset($row['jam_mulai']) ||
                !isset($row['jam_selesai']) ||
                !isset($row['lab'])
            ) {
                throw new \Exception("Kolom Excel tidak sesuai template. Pastikan semua kolom sudah benar.");
            }

            // Determine semester based on value
            $semesterValue = $row['semester'];
            // If the incoming value is 'Ganjil'/'Genap', use as-is, otherwise assume it's a numeric value
            if (is_numeric($row['semester']) || in_array($row['semester'], ['1', '2'])) {
                $semesterValue = $row['semester'] == '1' ? 'Ganjil' : 'Genap';
            }

            // Find semester from cache
            $semester = $this->semesters->firstWhere(function ($s) use ($row, $semesterValue) {
                return $s->tahun_ajaran === $row['tahun_ajaran'] && $s->semester === $semesterValue;
            });

            if (! $semester) {
                throw new \Exception("Semester {$row['tahun_ajaran']} / {$row['semester']} tidak ditemukan di database.");
            }

            // Find mata kuliah from cache
            $mataKuliah = $this->mks->firstWhere('kode_mk', $row['kode_mk']);

            if (! $mataKuliah) {
                throw new \Exception("Mata kuliah dengan kode {$row['kode_mk']} tidak ditemukan di database.");
            }

            // Find dosen from cache
            $dosen = $this->dosens->firstWhere('name', $row['dosen']);

            // If not found by name, try searching by email
            if (! $dosen) {
                $dosen = $this->dosens->firstWhere('email', $row['dosen']);
            }

            if (! $dosen) {
                throw new \Exception("Dosen {$row['dosen']} tidak ditemukan di database.");
            }

            // Find lab from cache
            $lab = $this->labs->firstWhere('name', $row['lab']);

            if (! $lab) {
                throw new \Exception("Lab {$row['lab']} tidak ditemukan di database.");
            }

            // Validate time format
            if (! preg_match('/^\d{2}:\d{2}$/', $row['jam_mulai'])) {
                throw new \Exception("Format jam_mulai tidak valid ({$row['jam_mulai']}). Gunakan format HH:MM.");
            }

            if (! preg_match('/^\d{2}:\d{2}$/', $row['jam_selesai'])) {
                throw new \Exception("Format jam_selesai tidak valid ({$row['jam_selesai']}). Gunakan format HH:MM.");
            }

            // Add data to collection
            $jadwals[] = [
                'semester_id'    => $semester->semester_id,
                'mata_kuliah_id' => $mataKuliah->id,
                'dosen_id'       => $dosen->id,
                'hari'           => $row['hari'],
                'jam_mulai'      => $row['jam_mulai'],
                'jam_selesai'    => $row['jam_selesai'],
                'lab_id'         => $lab->lab_id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        // Perform bulk insert
        if (!empty($jadwals)) {
            Jadwal::insert($jadwals);
        }
    }
}