<?php
namespace App\Services\Hr;

use App\Models\Hr\JabatanFungsional;

class JabatanFungsionalService
{
    public function create(array $data)
    {
        return JabatanFungsional::create($data);
    }

    public function update(JabatanFungsional $jabatanFungsional, array $data)
    {
        $jabatanFungsional->update($data);
        return $jabatanFungsional;
    }

    public function delete($id)
    {
        $jabatanFungsional = JabatanFungsional::findOrFail($id);
        $jabatanFungsional->delete();
    }
}
