<?php
namespace App\Services\Hr;

use App\Models\Hr\JabatanStruktural;

class JabatanStrukturalService
{
    public function create(array $data)
    {
        return JabatanStruktural::create($data);
    }

    public function update(JabatanStruktural $jabatanStruktural, array $data)
    {
        $jabatanStruktural->update($data);
        return $jabatanStruktural;
    }

    public function delete($id)
    {
        $jabatanStruktural = JabatanStruktural::findOrFail($id);
        $jabatanStruktural->delete();
    }
}
