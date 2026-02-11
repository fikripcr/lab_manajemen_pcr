<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalKuliah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'lab_jadwal_kuliah';
    protected $primaryKey = 'jadwal_kuliah_id';

    protected $fillable = [
        'semester_id',
        'mata_kuliah_id',
        'dosen_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'lab_id',
    ];

    protected $casts = [
        'jadwal_kuliah_id' => 'string',
    ];

    /**
     * Relationship: Jadwal Kuliah belongs to a semester
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    /**
     * Relationship: Jadwal Kuliah belongs to a mata kuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relationship: Jadwal Kuliah belongs to a dosen (user)
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relationship: Jadwal Kuliah belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Jadwal Kuliah has many PC assignments
     */
    public function pcAssignments()
    {
        return $this->hasMany(PcAssignment::class, 'jadwal_id');
    }

    /**
     * Relationship: Jadwal Kuliah has many PC usage logs
     */
    public function logPenggunaanPcs()
    {
        return $this->hasMany(LogPenggunaanPc::class, 'jadwal_id');
    }

    /**
     * Accessor to get encrypted jadwal_kuliah_id
     */
    public function getEncryptedJadwalKuliahIdAttribute()
    {
        return encryptId($this->jadwal_kuliah_id);
    }
}
