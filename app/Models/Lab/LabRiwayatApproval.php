<?php
namespace App\Models\Lab;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabRiwayatApproval extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table      = 'lab_riwayat_approval';
    protected $primaryKey = 'riwayatapproval_id';

    protected $fillable = [
        'model',
        'model_id',
        'status',
        'pejabat',
        'jenis_jabatan', // Optional
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the parent approvalable model (RequestSoftware, Kegiatan, etc).
     */
    public function approvalable()
    {
        return $this->morphTo(__FUNCTION__, 'model', 'model_id');
    }
}
