<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Middleware;

class Lab extends Model
{
    use HasFactory;

    protected $table = 'labs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * Relationship: Lab has many mata kuliah
     */
    public function mataKuliahs()
    {
        return $this->hasManyThrough(MataKuliah::class, Jadwal::class, 'lab_id', 'id', 'lab_id', 'mata_kuliah_id');
    }

    /**
     * Relationship: Lab has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many PC assignments
     */
    public function pcAssignments()
    {
        return $this->hasMany(PcAssignment::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many PC usage logs
     */
    public function logPenggunaanPcs()
    {
        return $this->hasMany(LogPenggunaanPc::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many lab usage logs
     */
    public function logPenggunaanLabs()
    {
        return $this->hasMany(LogPenggunaanLab::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many events
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many inventories
     */
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'lab_id');
    }
    
    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }
}