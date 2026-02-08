<?php
namespace App\Services\Hr;

use App\Models\Hr\JenisShift;

class JenisShiftService
{
    public function create(array $data)
    {
        return JenisShift::create($data);
    }

    public function update(JenisShift $jenisShift, array $data)
    {
        $jenisShift->update($data);
        return $jenisShift;
    }

    public function delete($id)
    {
        $jenisShift = JenisShift::findOrFail($id);
        $jenisShift->delete();
    }
}
