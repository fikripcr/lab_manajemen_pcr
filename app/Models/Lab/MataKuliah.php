<?php
namespace App\Models\Lab;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_mata_kuliahs';
    protected $primaryKey = 'mata_kuliah_id';

    protected $appends = ['encrypted_mata_kuliah_id'];

    public function getRouteKeyName()
    {
        return 'mata_kuliah_id';
    }

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks', 'created_by', 'updated_by', 'deleted_by',

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
        return $this->belongsToMany(RequestSoftware::class, 'lab_request_software_mata_kuliah', 'mata_kuliah_id', 'request_software_id');
    }

    /**
     * Accessor to get encrypted mata_kuliah_id
     */
    public function getEncryptedMataKuliahIdAttribute()
    {
        return encryptId($this->mata_kuliah_id);
    }
}
