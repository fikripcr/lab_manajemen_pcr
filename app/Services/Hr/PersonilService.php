<?php

namespace App\Services\Hr;

use App\Models\Hr\Personil;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PersonilService
{
    /**
     * Daftar unit untuk dropdown.
     */
    public function getUnits()
    {
        return StrukturOrganisasi::orderBy('name')->get();
    }

    /**
     * Query DataTable untuk index lembur.
     */
    public function getDataQuery()
    {
        return Personil::with(['orgUnit', 'user.roles']);
    }

    public function getFilteredQuery(array $filters = [])
    {
        $query = $this->getDataQuery();

        if (! empty($filters['org_unit_id']) && $filters['org_unit_id'] !== 'all') {
            $query->where('org_unit_id', $filters['org_unit_id']);
        }

        return $query;
    }

    public function createPersonil(array $data): Personil
    {
        return DB::transaction(function () use ($data) {
            $data['status_aktif'] = $data['status_aktif'] ?? true;
            $personil = Personil::create($data);

            logActivity('personil_management', "Menambah personil baru: {$personil->nama}", $personil);

            return $personil;
        });
    }

    public function updatePersonil($id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $personil = Personil::findOrFail($id);
            $oldName = $personil->nama;

            $data['status_aktif'] = $data['status_aktif'] ?? $personil->status_aktif;
            $personil->update($data);

            logActivity(
                'personil_management',
                "Memperbarui personil: {$oldName}".($oldName !== $personil->nama ? " menjadi {$personil->nama}" : ''),
                $personil
            );

            return true;
        });
    }

    public function deletePersonil($id): bool
    {
        return DB::transaction(function () use ($id) {
            $personil = Personil::findOrFail($id);
            $name = $personil->nama;

            $personil->delete();

            logActivity('personil_management', "Menghapus personil: {$name}");

            return true;
        });
    }

    /**
     * Buat akun user untuk personil yang belum punya user.
     *
     * @return array{user: User, email: string, password: string, role: string}
     */
    public function generateUserForPersonil(Personil $personil): array
    {
        $email = $personil->email ?? $this->generateEmailFromNama($personil->nama);
        $password = 'password123';

        if (User::where('email', $email)->exists()) {
            throw new \RuntimeException("Email {$email} sudah terdaftar. Silakan gunakan email lain.");
        }

        return DB::transaction(function () use ($personil, $email, $password) {
            $user = User::create([
                'name' => $personil->nama,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'created_by' => auth()->id() ?? 'system',
            ]);

            $personil->update(['user_id' => $user->id]);

            $role = $this->determineRoleFromPosisi($personil->posisi);
            $user->assignRole($role);

            logActivity('personil_management', "Generate user untuk personil: {$personil->nama} ({$email})", $personil);

            return compact('user', 'email', 'password', 'role');
        });
    }

    private function generateEmailFromNama(string $nama): string
    {
        $base = Str::slug($nama);
        $email = "{$base}@pcr.ac.id";
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = "{$base}{$counter}@pcr.ac.id";
            $counter++;
        }

        return $email;
    }

    private function determineRoleFromPosisi(?string $posisi): string
    {
        $posisi = strtolower($posisi ?? '');

        if (str_contains($posisi, 'security')) {
            return 'security';
        } elseif (str_contains($posisi, 'cleaning')) {
            return 'cleaning_service';
        } elseif (str_contains($posisi, 'driver')) {
            return 'driver';
        }

        return 'admin';
    }
}
