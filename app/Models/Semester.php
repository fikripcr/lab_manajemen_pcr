<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Semester has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'semester_id', 'semester_id');
    }
}