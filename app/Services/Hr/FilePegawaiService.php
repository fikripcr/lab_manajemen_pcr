<?php
namespace App\Services\Hr;

use App\Models\Hr\FilePegawai;
use Illuminate\Support\Facades\DB;

class FilePegawaiService
{
    /**
     * Get query for employee files.
     */
    public function getQuery($pegawaiId)
    {
        return FilePegawai::with(['jenisfile', 'media'])
            ->where('pegawai_id', $pegawaiId);
    }

    /**
     * Store a new employee file.
     */
    public function storeFile($pegawaiId, array $data, $fileRequest)
    {
        return DB::transaction(function () use ($pegawaiId, $data, $fileRequest) {
            $filePegawai = FilePegawai::create([
                'pegawai_id'   => $pegawaiId,
                'jenisfile_id' => $data['jenisfile_id'],
                'keterangan'   => $data['keterangan'] ?? null,
            ]);

            $filePegawai->addMedia($fileRequest)
                ->toMediaCollection('file_pegawai');

            logActivity('hr', "Mengupload file baru untuk pegawai (ID: {$pegawaiId}): " . ($filePegawai->jenisfile->nama ?? 'File'), $filePegawai);

            return $filePegawai;
        });
    }

    /**
     * Delete an employee file.
     */
    public function deleteFile($id)
    {
        return DB::transaction(function () use ($id) {
            $filePegawai = FilePegawai::findOrFail($id);
            logActivity('hr', "Menghapus file pegawai (ID: {$filePegawai->pegawai_id}): " . ($filePegawai->jenisfile->nama ?? 'File'), $filePegawai);
            return $filePegawai->delete();
        });
    }
}
