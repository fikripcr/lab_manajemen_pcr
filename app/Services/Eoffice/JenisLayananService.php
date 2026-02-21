<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananIsian;
use App\Models\Eoffice\JenisLayananPic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JenisLayananService
{
    /**
     * Get paginated data
     */
    public function getFilteredQuery(Request $request)
    {
        return JenisLayanan::query();
    }

    /**
     * Store new Jenis Layanan
     */
    public function createJenisLayanan(array $data)
    {
        if (isset($data['file_template'])) {
            $data['file_template'] = $this->uploadTemplate($data['file_template']);
        }

        // Handle JSON fields
        if (isset($data['only_show_on'])) {
            $data['only_show_on'] = json_encode($data['only_show_on']);
        }

        $jl = JenisLayanan::create($data);
        logActivity('eoffice_master', "Menambahkan jenis layanan baru: {$jl->nama_layanan}");
        return $jl;
    }

    /**
     * Update existing Jenis Layanan
     */
    public function updateJenisLayanan($id, array $data)
    {
        $jl = JenisLayanan::findOrFail($id);

        if (isset($data['file_template'])) {
            // Delete old template
            if ($jl->file_template) {
                Storage::delete($jl->file_template);
            }
            $data['file_template'] = $this->uploadTemplate($data['file_template']);
        }

        if (isset($data['only_show_on'])) {
            $data['only_show_on'] = json_encode($data['only_show_on']);
        }

        $jl->update($data);
        logActivity('eoffice_master', "Memperbarui jenis layanan: {$jl->nama_layanan}");
        return $jl;
    }

    /**
     * Upload template file
     */
    private function uploadTemplate($file)
    {
        return $file->store('eoffice/templates');
    }

    /**
     * Store PIC for Jenis Layanan
     */
    public function storePic($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $data['jenislayanan_id'] = $id;
            $pic                     = JenisLayananPic::create($data);
            logActivity('eoffice_master', "Menambahkan PIC untuk layanan: " . ($pic->jenisLayanan->nama_layanan ?? $id));
            return $pic;
        });
    }

    /**
     * Delete PIC
     */
    public function deletePic($picId)
    {
        $pic  = JenisLayananPic::findOrFail($picId);
        $nama = $pic->user->name ?? $picId;
        $jl   = $pic->jenisLayanan->nama_layanan ?? 'Unknown';
        $pic->delete();
        logActivity('eoffice_master', "Menghapus PIC '{$nama}' dari layanan: {$jl}");
        return true;
    }

    /**
     * Store Isian (Field) for Jenis Layanan
     */
    public function storeIsian($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $data['jenislayanan_id'] = $id;
            $isian                   = JenisLayananIsian::create($data);
            logActivity('eoffice_master', "Menambahkan field isian '{$isian->kategoriIsian->nama_isian}' untuk layanan: " . ($isian->jenisLayanan->nama_layanan ?? $id));
            return $isian;
        });
    }

    /**
     * Delete Isian
     */
    public function deleteIsian($isianId)
    {
        $isian = JenisLayananIsian::findOrFail($isianId);
        $nama  = $isian->kategoriIsian->nama_isian ?? $isianId;
        $jl    = $isian->jenisLayanan->nama_layanan ?? 'Unknown';
        $isian->delete();
        logActivity('eoffice_master', "Menghapus field isian '{$nama}' dari layanan: {$jl}");
        return true;
    }

    /**
     * Update Isian field
     */
    public function updateIsian($isianId, array $data)
    {
        $isian = JenisLayananIsian::findOrFail($isianId);
        $isian->update($data);
        logActivity('eoffice_master', "Memperbarui konfigurasi field '{$isian->kategoriIsian->nama_isian}' untuk layanan: {$isian->jenisLayanan->nama_layanan}");
        return $isian;
    }

    /**
     * Update Isian sequence
     */
    public function updateIsianSeq(array $sequences)
    {
        return DB::transaction(function () use ($sequences) {
            foreach ($sequences as $item) {
                JenisLayananIsian::where('jenislayananisian_id', $item['id'])->update(['seq' => $item['seq']]);
            }
            logActivity('eoffice_master', "Memperbarui urutan field isian layanan.");
            return true;
        });
    }

    public function getById($id)
    {
        return JenisLayanan::with(['pics.user', 'isians.kategori', 'disposisis', 'periodes'])->findOrFail($id);
    }
}
