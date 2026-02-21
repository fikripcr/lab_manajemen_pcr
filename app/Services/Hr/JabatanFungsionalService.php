<?php
namespace App\Services\Hr;

use App\Models\Hr\JabatanFungsional;
use Illuminate\Support\Facades\DB;

class JabatanFungsionalService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $jabatanFungsional = JabatanFungsional::create($data);
            logActivity('hr', "Menambahkan jabatan fungsional: {$jabatanFungsional->nama_jabatan}", $jabatanFungsional);
            return $jabatanFungsional;
        });
    }

    public function update(JabatanFungsional $jabatanFungsional, array $data)
    {
        return DB::transaction(function () use ($jabatanFungsional, $data) {
            $jabatanFungsional->update($data);
            logActivity('hr', "Memperbarui jabatan fungsional: {$jabatanFungsional->nama_jabatan}", $jabatanFungsional);
            return $jabatanFungsional;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jabatanFungsional = JabatanFungsional::findOrFail($id);
            logActivity('hr', "Menghapus jabatan fungsional: {$jabatanFungsional->nama_jabatan}", $jabatanFungsional);
            $jabatanFungsional->delete();
        });
    }
}
