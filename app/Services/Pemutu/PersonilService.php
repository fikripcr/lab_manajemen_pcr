<?php
namespace App\Services\Pemutu;

use App\Imports\Pemutu\PersonilImport;
use App\Models\Pemutu\Personil;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PersonilService
{
    public function getFilteredQuery(array $filters = [])
    {
        return Personil::with(['orgUnit', 'user']);
    }

    public function getPersonilById(int $id): ?Personil
    {
        return Personil::with(['orgUnit', 'user'])->find($id);
    }

    public function createPersonil(array $data): Personil
    {
        return DB::transaction(function () use ($data) {
            // Auto-link user by email
            if (! empty($data['email'])) {
                $user = User::where('email', $data['email'])->first();
                if ($user && empty($data['user_id'])) {
                    $data['user_id'] = $user->id;
                }
            }

            $personil = Personil::create($data);

            logActivity(
                'personil_management',
                "Membuat personil baru: {$personil->nama}"
            );

            return $personil;
        });
    }

    public function updatePersonil(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $personil = $this->findOrFail($id);
            $oldName  = $personil->nama;

            // Auto-link user by email if changed and not manually set
            // Request data might override user_id if supplied?
            // Controller logic was: if email changed/supplied and user not linked or new email matches new user..
            // "if (! empty($data['email']) && $data['email'] !== $personil->email)"
            if (! empty($data['email']) && $data['email'] !== $personil->email) {
                $user = User::where('email', $data['email'])->first();
                if ($user) {
                    $data['user_id'] = $user->id; // Override or set
                }
            }

            $personil->update($data);

            logActivity(
                'personil_management',
                "Memperbarui personil: {$oldName}" . ($oldName !== $personil->nama ? " menjadi {$personil->nama}" : "")
            );

            return true;
        });
    }

    public function deletePersonil(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $personil = $this->findOrFail($id);
            $name     = $personil->nama;

            $personil->delete();

            logActivity(
                'personil_management',
                "Menghapus personil: {$name}"
            );

            return true;
        });
    }

    public function importPersonils($file)
    {
        return DB::transaction(function () use ($file) {
            Excel::import(new PersonilImport, $file);

            logActivity(
                'personil_management',
                "Import personil dari file."
            );
            return true;
        });
    }

    protected function findOrFail(int $id): Personil
    {
        $model = Personil::find($id);
        if (! $model) {
            throw new \Exception("Personil dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
