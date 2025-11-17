<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventaris';
    protected $primaryKey = 'inventaris_id';

    protected $fillable = [
        'nama_alat',
        'jenis_alat',
        'kondisi_terakhir',
        'tanggal_pengecekan',
    ];

    protected $casts = [
        'tanggal_pengecekan' => 'date',
    ];

    /**
     * Relationship: Inventory has many lab assignments through lab_inventaris
     */
    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab_inventaris', 'inventaris_id', 'lab_id')
                    ->withPivot(['kode_inventaris', 'no_series', 'tanggal_penempatan', 'tanggal_penghapusan', 'status', 'keterangan'])
                    ->withTimestamps();
    }

    /**
     * Relationship: Inventory has many lab_inventaris entries
     */
    public function labInventaris()
    {
        return $this->hasMany(LabInventaris::class, 'inventaris_id', 'inventaris_id');
    }

    /**
     * Relationship: Inventory has many damage reports
     */
    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class, 'inventaris_id');
    }

    /**
     * Get current lab assignment for this inventory item
     */
    public function getCurrentLab()
    {
        return $this->labInventaris()
                    ->where('status', 'active')
                    ->latest('tanggal_penempatan')
                    ->first();
    }
}
