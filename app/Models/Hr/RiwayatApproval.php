<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'hr_riwayat_approval';
    protected $primaryKey = 'riwayatapproval_id';

    protected $fillable = [
        'model',
        'model_id',
        'status', // Draft, Pending, Approved, Rejected
        'pejabat',
        'jenis_jabatan',
        'keterangan',
        'created_by_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the parent model (polymorphic).
     * However, simpler to just have reverse relationships on the specific models
     * or use a trait if needed.
     */
}
