<?php

namespace App\Traits;

use App\Models\Pemutu\TimMutu;

/**
 * Trait ScopesTimMutu
 *
 * Sederhana: cek apakah user bisa edit unit tertentu
 * berdasarkan assignment di Tim Mutu.
 *
 * Admin bisa edit semua unit (return true).
 * User biasa hanya bisa edit unit yang di-assign ke mereka.
 */
trait ScopesTimMutu
{
    /**
     * Cek apakah user bisa EDIT unit tertentu.
     *
     * - Admin/Admin SPMI → selalu bisa (true)
     * - User biasa → hanya unit yang di-assign di Tim Mutu
     */
    protected function canEditUnit(int $unitId): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        // Admin bisa semua
        if ($user->hasRole(['Administrator', 'Admin SPMI'])) {
            return true;
        }

        // User tanpa pegawai → tidak bisa edit
        if (! $user->pegawai) {
            return false;
        }

        $periodeId = session('siklus_spmi_tahun');
        if (! $periodeId) {
            return false;
        }

        // Cek assignment di Tim Mutu
        $hasAccess = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)
            ->where('periodespmi_id', $periodeId)
            ->where('org_unit_id', $unitId)
            ->exists();

        return $hasAccess;
    }
}
