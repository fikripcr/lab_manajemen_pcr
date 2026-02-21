<?php
namespace App\Services\Pmb;

use App\Models\Pmb\SyaratDokumenJalur;

class SyaratDokumenJalurService
{
    public function getSyaratByJalur($jalurId)
    {
        return SyaratDokumenJalur::with('jenisDokumen')->where('jalur_id', $jalurId)->get();
    }

    public function updateOrCreate(array $data): SyaratDokumenJalur
    {
        $syarat = SyaratDokumenJalur::updateOrCreate(
            ['jalur_id' => $data['jalur_id'], 'jenis_dokumen_id' => $data['jenis_dokumen_id']],
            ['is_required' => $data['is_required'] ?? false]
        );

        logActivity('pmb_syarat_jalur', "Update syarat dokumen untuk jalur ID: {$data['jalur_id']}", $syarat);

        return $syarat;
    }

    public function delete(SyaratDokumenJalur $syarat): bool
    {
        $syarat->delete();
        logActivity('pmb_syarat_jalur', "Menghapus syarat dokumen ID: {$syarat->syarat_id}");
        return true;
    }
}
