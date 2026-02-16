<?php
namespace App\Models\Pmb;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenUpload extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_dokumen_upload';

    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen_id',
        'path_file',
        'status_verifikasi',
        'catatan_revisi',
        'verifikator_id',
        'waktu_upload',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getIsVerifiedAttribute()
    {
        return $this->status_verifikasi === 'Valid';
    }

    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
