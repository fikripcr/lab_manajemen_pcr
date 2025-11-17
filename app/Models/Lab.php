<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'labs';
    protected $primaryKey = 'lab_id';

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
        return $this->hasManyThrough(MataKuliah::class, JadwalKuliah::class, 'lab_id', 'id', 'lab_id', 'mata_kuliah_id');
    }

    /**
     * Relationship: Lab has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class, 'lab_id');
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
     * Relationship: Lab has many media entries
     */
    public function labMedia()
    {
        return $this->hasMany(LabMedia::class, 'lab_id', 'lab_id');
    }
}
