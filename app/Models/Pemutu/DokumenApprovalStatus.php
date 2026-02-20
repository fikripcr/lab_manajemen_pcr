<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenApprovalStatus extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_dok_approval_status';
    protected $primaryKey = 'dokstatusapproval_id';

    protected $appends = ['encrypted_dokstatusapproval_id'];

    public function getRouteKeyName()
    {
        return 'dokstatusapproval_id';
    }

    public function getEncryptedDokstatusapprovalIdAttribute()
    {
        return encryptId($this->dokstatusapproval_id);
    }

    protected $fillable = [
        'dokapproval_id',
        'status_approval',
        'komentar',
    ];

    public function approval()
    {
        return $this->belongsTo(DokumenApproval::class, 'dokapproval_id', 'dokapproval_id');
    }
}
