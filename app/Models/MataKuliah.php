<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
    ];

    protected $casts = [
        'sks' => 'integer',
    ];

    /**
     * Relationship: Mata Kuliah has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relationship: Mata Kuliah has many software requests through pivot
     */
    public function requestSoftwares()
    {
        return $this->belongsToMany(RequestSoftware::class, 'request_software_mata_kuliah', 'mata_kuliah_id', 'request_software_id');
    }
}