<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSoftware extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'request_software';
    protected $primaryKey = 'request_software_id';

    protected $fillable = [
        'dosen_id',
        'nama_software',
        'alasan',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship: Software request belongs to a dosen (user)
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relationship: Software request has many mata kuliah through pivot
     */
    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'request_software_mata_kuliah', 'request_software_id', 'mata_kuliah_id');
    }

    /**
     * Accessor to get encrypted request_software_id
     */
    public function getEncryptedRequestSoftwareIdAttribute()
    {
        return encryptId($this->request_software_id);
    }

    /**
     * Accessor to get encrypted dosen_id
     */
    public function getEncryptedDosenIdAttribute()
    {
        return encryptId($this->dosen_id);
    }
}
