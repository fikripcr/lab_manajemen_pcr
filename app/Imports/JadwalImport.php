<?php

namespace App\Imports;

use App\Models\Lab;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalImport implements ToModel, WithHeadingRow
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

    public function model(array $row)
    {
        // Pastikan semua key kolom tersedia di file Excel
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

        // Cari semester berdasarkan tahun_ajaran dan semester
        $semester = Semester::where('tahun_ajaran', $row['tahun_ajaran'])
            ->where('semester', $row['semester'])
            ->first();

        if (! $semester) {
            throw new \Exception("Semester {$row['tahun_ajaran']} / {$row['semester']} tidak ditemukan di database.");
        }

        // Cari mata kuliah berdasarkan kode_mk
        $mataKuliah = MataKuliah::where('kode_mk', $row['kode_mk'])->first();

        if (! $mataKuliah) {
            throw new \Exception("Mata kuliah dengan kode {$row['kode_mk']} tidak ditemukan di database.");
        }

        // Cari dosen berdasarkan nama atau email
        $dosen = User::where('name', $row['dosen'])
            ->orWhere('email', $row['dosen'])
            ->first();

        if (! $dosen) {
            throw new \Exception("Dosen {$row['dosen']} tidak ditemukan di database.");
        }

        // Cari lab berdasarkan nama
        $lab = Lab::where('name', $row['lab'])->first();

        if (! $lab) {
            throw new \Exception("Lab {$row['lab']} tidak ditemukan di database.");
        }

        // Validasi format jam
        if (! preg_match('/^\d{2}:\d{2}$/', $row['jam_mulai'])) {
            throw new \Exception("Format jam_mulai tidak valid ({$row['jam_mulai']}). Gunakan format HH:MM.");
        }

        if (! preg_match('/^\d{2}:\d{2}$/', $row['jam_selesai'])) {
            throw new \Exception("Format jam_selesai tidak valid ({$row['jam_selesai']}). Gunakan format HH:MM.");
        }

        // Jika semua valid, buat data jadwal baru
        return new Jadwal([
            'semester_id'    => $semester->semester_id,
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id'       => $dosen->id, // ambil dari Excel, bukan user login
            'hari'           => $row['hari'],
            'jam_mulai'      => $row['jam_mulai'],
            'jam_selesai'    => $row['jam_selesai'],
            'lab_id'         => $lab->lab_id,
        ]);
    }
}
