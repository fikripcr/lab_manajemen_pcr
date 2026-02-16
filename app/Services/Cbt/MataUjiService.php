<?php
namespace App\Services\Cbt;

use App\Models\Cbt\MataUji;

class MataUjiService
{
    public function store(array $data)
    {
        return MataUji::create($data);
    }

    public function update(MataUji $mu, array $data)
    {
        $mu->update($data);
        return $mu;
    }

    public function delete(MataUji $mu)
    {
        return $mu->delete();
    }
}
