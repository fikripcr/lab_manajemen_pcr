<?php
namespace App\Services\Hr;

use App\Models\Hr\Departemen;

class DepartemenService
{
    public function create(array $data)
    {
        return Departemen::create($data);
    }

    public function update(Departemen $departemen, array $data)
    {
        $departemen->update($data);
        return $departemen;
    }

    public function delete($id)
    {
        $departemen = Departemen::findOrFail($id);
        $departemen->delete();
    }
}
