<?php
namespace App\Services\Hr;

use App\Models\Hr\Posisi;

class PosisiService
{
    public function create(array $data)
    {
        return Posisi::create($data);
    }

    public function update(Posisi $posisi, array $data)
    {
        $posisi->update($data);
        return $posisi;
    }

    public function delete($id)
    {
        $posisi = Posisi::findOrFail($id);
        $posisi->delete();
    }
}
