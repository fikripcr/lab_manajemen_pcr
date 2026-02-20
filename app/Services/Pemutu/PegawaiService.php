<?php
namespace App\Services\Pemutu;

use App\Imports\Pemutu\PegawaiImport;
use App\Models\Shared\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiService
{
    public function getFilteredQuery(array $filters = [])
    {
        return Pegawai::with(['orgUnit', 'user']);
    }

    public function getPegawaiById(int $id): ?Pegawai
    {
        return Pegawai::with(['orgUnit', 'user'])->find($id);
    }

    public function createPegawai(array $data): Pegawai
    {
        return DB::transaction(function () use ($data) {
            // Auto-link user by email
            if (! empty($data['email'])) {
                $user = User::where('email', $data['email'])->first();
                if ($user && empty($data['user_id'])) {
                    $data['user_id'] = $user->id;
                }
            }

            $pegawai = Pegawai::create($data);

            logActivity(
                'pegawai_management',
                "Membuat pegawai baru: {$pegawai->nama}"
            );

            return $pegawai;
        });
    }

    public function updatePegawai(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $pegawai = $this->findOrFail($id);
            $oldName = $pegawai->nama;

            // Auto-link user by email if changed and not manually set
            if (! empty($data['email']) && $data['email'] !== $pegawai->email) {
                $user = User::where('email', $data['email'])->first();
                if ($user) {
                    $data['user_id'] = $user->id;
                }
            }

            $pegawai->update($data);

            logActivity(
                'pegawai_management',
                "Memperbarui pegawai: {$oldName}" . ($oldName !== $pegawai->nama ? " menjadi {$pegawai->nama}" : "")
            );

            return true;
        });
    }

    public function deletePegawai(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $pegawai = $this->findOrFail($id);
            $name    = $pegawai->nama;

            $pegawai->delete();

            logActivity(
                'pegawai_management',
                "Menghapus pegawai: {$name}"
            );

            return true;
        });
    }

    public function importPegawai($file)
    {
        return DB::transaction(function () use ($file) {
            Excel::import(new PegawaiImport, $file);

            logActivity(
                'pegawai_management',
                "Import pegawai dari file."
            );
            return true;
        });
    }

    protected function findOrFail(int $id): Pegawai
    {
        $model = Pegawai::find($id);
        if (! $model) {
            throw new \Exception("Pegawai dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
