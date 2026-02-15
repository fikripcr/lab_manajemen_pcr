<?php
namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratBebasLab extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_surat_bebas_labs';
    protected $primaryKey = 'surat_bebas_lab_id';

    protected $fillable = [
        'student_id',
        'status',
        'file_path',
        'remarks',
        'approved_by',
        'approved_at',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function latestApproval()
    {
        return $this->belongsTo(\App\Models\Lab\LabRiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    public function approvals()
    {
        return $this->morphMany(\App\Models\Lab\LabRiwayatApproval::class, 'approvalable', 'model', 'model_id')->orderBy('created_at', 'desc');
    }

    public function getEncryptedIdAttribute()
    {
        return encryptId($this->surat_bebas_lab_id);
    }
}
