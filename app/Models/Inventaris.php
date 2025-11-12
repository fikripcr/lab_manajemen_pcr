<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'lab_id',
        'nama_alat',
        'jenis_alat',
        'kondisi_terakhir',
        'tanggal_pengecekan',
    ];

    protected $casts = [
        'tanggal_pengecekan' => 'date',
    ];

    /**
     * Relationship: Inventory belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Inventory has many damage reports
     */
    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class, 'inventaris_id');
    }
}