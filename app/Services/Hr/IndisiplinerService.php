<?php
namespace App\Services\Hr;

use App\Models\Hr\Indisipliner;
use App\Models\Hr\IndisiplinerPegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndisiplinerService
{
    /**
     * Store new indisipliner record
     */
    public function store(array $data, $file = null): Indisipliner
    {
        return DB::transaction(function () use ($data, $file) {
            $indisipliner = Indisipliner::create([
                'jenisindisipliner_id' => $data['jenisindisipliner_id'],
                'tgl_indisipliner'     => $data['tgl_indisipliner'],
                'keterangan'           => $data['keterangan'] ?? null,
            ]);

            if ($file) {
                $path = $file->store('hr/indisipliner', 'public');
                $indisipliner->update(['file_pendukung' => $path]);
            }

            foreach ($data['pegawai_id'] as $pegawaiId) {
                IndisiplinerPegawai::create([
                    'indisipliner_id' => $indisipliner->indisipliner_id,
                    'pegawai_id'      => $pegawaiId,
                ]);
            }

            logActivity('hr', "Mencatat indisipliner: " . ($indisipliner->jenisIndisipliner->jenis_indisipliner ?? 'N/A'), $indisipliner);

            return $indisipliner;
        });
    }

    /**
     * Update existing indisipliner record
     */
    public function update(Indisipliner $indisipliner, array $data, $file = null): Indisipliner
    {
        return DB::transaction(function () use ($indisipliner, $data, $file) {
            $indisipliner->update([
                'jenisindisipliner_id' => $data['jenisindisipliner_id'],
                'tgl_indisipliner'     => $data['tgl_indisipliner'],
                'keterangan'           => $data['keterangan'] ?? null,
            ]);

            if ($file) {
                // Delete old file if exists
                if ($indisipliner->file_pendukung) {
                    Storage::disk('public')->delete($indisipliner->file_pendukung);
                }
                $path = $file->store('hr/indisipliner', 'public');
                $indisipliner->update(['file_pendukung' => $path]);
            }

            // Sync pegawai
            IndisiplinerPegawai::where('indisipliner_id', $indisipliner->indisipliner_id)->delete();
            foreach ($data['pegawai_id'] as $pegawaiId) {
                IndisiplinerPegawai::create([
                    'indisipliner_id' => $indisipliner->indisipliner_id,
                    'pegawai_id'      => $pegawaiId,
                ]);
            }

            logActivity('hr', "Mengupdate indisipliner: " . ($indisipliner->jenisIndisipliner->jenis_indisipliner ?? 'N/A'), $indisipliner);

            return $indisipliner;
        });
    }

    /**
     * Delete indisipliner record
     */
    public function delete(Indisipliner $indisipliner): void
    {
        DB::transaction(function () use ($indisipliner) {
            // Soft delete associated pegawai records
            IndisiplinerPegawai::where('indisipliner_id', $indisipliner->indisipliner_id)->delete();

            $indisipliner->delete();

            logActivity('hr', "Menghapus data indisipliner", $indisipliner);
        });
    }
}
