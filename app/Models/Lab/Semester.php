<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'semesters';
    protected $primaryKey = 'semester_id';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'semester_id' => 'string',
    ];

    /**
     * Relationship: Semester has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class, 'semester_id', 'semester_id');
    }

    /**
     * Accessor to get encrypted semester_id
     */
    public function getEncryptedSemesterIdAttribute()
    {
        return encryptId($this->semester_id);
    }
}
