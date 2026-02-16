<?php
namespace App\Services\Pmb;

use App\Models\Pmb\SyaratDokumenJalur;

class SyaratDokumenJalurService
{
    public function updateOrCreate(array $data)
    {
        return SyaratDokumenJalur::updateOrCreate(
            ['jalur_id' => $data['jalur_id'], 'jenis_dokumen_id' => $data['jenis_dokumen_id']],
            ['is_required' => $data['is_required'] ?? false]
        );
    }

    public function delete(SyaratDokumenJalur $syarat)
    {
        return $syarat->delete();
    }
}
