<?php
namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSoftware extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_request_software';
    protected $primaryKey = 'request_software_id';

    protected $fillable = [
        'dosen_id',
        'periodsoftreq_id',
        'nama_software',
        'versi',
        'url_download',
        'deskripsi',
        'status',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship: Software request belongs to a period
     */
    public function period()
    {
        return $this->belongsTo(PeriodSoftRequest::class, 'periodsoftreq_id', 'periodsoftreq_id');
    }

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
        return $this->belongsToMany(MataKuliah::class, 'lab_request_software_mata_kuliah', 'request_software_id', 'mata_kuliah_id');
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
    /**
     * Relationship: Latest approval record
     */
    public function latestApproval()
    {
        return $this->belongsTo(LabRiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    /**
     * Relationship: All approval history
     */
    public function approvals()
    {
        return $this->morphMany(LabRiwayatApproval::class, 'approvalable', 'model', 'model_id')->orderBy('created_at', 'desc');
    }
}
