<?php
namespace App\Models\Pmb;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatApproval extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_riwayat_approval';
    protected $primaryKey = 'riwayatapproval_id';

    protected $appends = ['encrypted_riwayatapproval_id'];

    public function getRouteKeyName()
    {
        return 'riwayatapproval_id';
    }

    public function getEncryptedRiwayatapprovalIdAttribute()
    {
        return encryptId($this->riwayatapproval_id);
    }

    protected $fillable = [
        'model',
        'model_id',
        'status', // Draft, Pending, Approved, Rejected
        'pejabat',
        'jabatan',
        'catatan',
        'lampiran_url',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the parent approvalable model.
     */
    public function subject()
    {
        return $this->morphTo('subject', 'model', 'model_id');
    }
}
