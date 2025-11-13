<?php
namespace App\Imports;

use App\Models\Lab;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\MataKuliah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalImport implements ToCollection,WithHeadingRow
{
    protected $semesters;
    protected $mks;
    protected $labs;
    protected $dosens;

    public function __construct()
    {
        $this->semesters = Semester::all();
        $this->mks       = MataKuliah::all();
        $this->labs      = Lab::all();
        $this->dosens    = User::all();
    }

    public function collection(Collection $rows)
    {
        // Prepare all data to be inserted
        $jadwals = [];

        // ORIGINAL CODE (commented out)
        /*
        foreach ($rows as $row) {
            // Ensure all required keys exist
            if (
                ! isset($row['tahun_ajaran']) ||
                ! isset($row['semester']) ||
                ! isset($row['kode_mk']) ||
                ! isset($row['dosen']) ||
                ! isset($row['hari']) ||
                ! isset($row['jam_mulai']) ||
                ! isset($row['jam_selesai']) ||
                ! isset($row['lab'])
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
        if (! empty($jadwals)) {
            Jadwal::insert($jadwals);
        }
        */

        // UPDATED CODE: Auto-create missing data
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

            // Create semester if not found
            if (! $semester) {
                $semester = Semester::create([
                    'tahun_ajaran' => $row['tahun_ajaran'],
                    'semester' => $semesterValue,
                    'start_date' => now(), // Default start date
                    'end_date' => now()->addMonths(4), // Default end date
                    'status' => 'aktif', // Default status
                ]);
                // Refresh the cache
                $this->semesters = Semester::all();
            }

            // Find mata kuliah from cache
            $mataKuliah = $this->mks->firstWhere('kode_mk', $row['kode_mk']);

            // Create mata kuliah if not found
            if (! $mataKuliah) {
                $mataKuliah = MataKuliah::create([
                    'kode_mk' => $row['kode_mk'],
                    'nama_mk' => $row['kode_mk'], // Use kode_mk as nama if not provided in a separate column
                    'sks' => 3, // Default SKS
                    'keterangan' => 'Created from import', // Default description
                ]);
                // Refresh the cache
                $this->mks = MataKuliah::all();
            }

            // Find dosen from cache
            $dosen = $this->dosens->firstWhere('name', $row['dosen']);

            // If not found by name, try searching by email
            if (! $dosen) {
                $dosen = $this->dosens->firstWhere('email', $row['dosen']);
            }

            // Create dosen if not found
            if (! $dosen) {
                // Create a unique email if only name was provided
                $dosenName = $row['dosen'];
                $emailDomain = '@university.ac.id'; // Default domain
                $email = strtolower(str_replace(' ', '.', $dosenName)) . $emailDomain;

                // Check if email already exists and modify if needed
                $counter = 1;
                $originalEmail = $email;
                while (User::where('email', $email)->exists()) {
                    $email = strtolower(str_replace(' ', '.', $dosenName)) . $counter . $emailDomain;
                    $counter++;
                }

                $dosen = User::create([
                    'name' => $dosenName,
                    'email' => $email,
                    'password' => bcrypt('password'), // Default password
                    'role' => 'dosen', // Default role
                    'nomor_induk' => 'DOSEN' . rand(10000, 99999), // Generate random ID
                    'is_verified' => 1, // Mark as verified
                ]);
                // Refresh the cache
                $this->dosens = User::all();

                // Assign the 'dosen' role to the user
                $dosenRole = \Spatie\Permission\Models\Role::where('name', 'dosen')->first();
                if ($dosenRole) {
                    $dosen->assignRole($dosenRole);
                }
            }

            // Find lab from cache
            $lab = $this->labs->firstWhere('name', $row['lab']);

            // Create lab if not found
            if (! $lab) {
                $lab = Lab::create([
                    'name' => $row['lab'],
                    'kapasitas' => 30, // Default capacity
                    'fasilitas' => 'Standard Lab', // Default facilities
                    'lokasi' => 'Main Building', // Default location
                ]);
                // Refresh the cache
                $this->labs = Lab::all();
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

    public function headingRow(): int
    {
        return 1; // Ganti angka ini sesuai baris header di Excel Anda
    }
}
