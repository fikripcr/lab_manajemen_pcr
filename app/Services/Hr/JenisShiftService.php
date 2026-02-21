<?php
namespace App\Services\Hr;

use App\Models\Hr\JenisShift;
use Illuminate\Support\Facades\DB;

class JenisShiftService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $jenisShift = JenisShift::create($data);
            logActivity('hr', "Menambahkan jenis shift: {$jenisShift->nama_shift}", $jenisShift);
            return $jenisShift;
        });
    }

    public function update(JenisShift $jenisShift, array $data)
    {
        return DB::transaction(function () use ($jenisShift, $data) {
            $jenisShift->update($data);
            logActivity('hr', "Memperbarui jenis shift: {$jenisShift->nama_shift}", $jenisShift);
            return $jenisShift;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jenisShift = JenisShift::findOrFail($id);
            logActivity('hr', "Menghapus jenis shift: {$jenisShift->nama_shift}", $jenisShift);
            $jenisShift->delete();
        });
    }
}
