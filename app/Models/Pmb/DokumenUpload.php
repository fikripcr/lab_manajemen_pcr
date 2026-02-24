<?php
namespace App\Models\Pmb;

use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\Pendaftaran;
use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenUpload extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_dokumen_upload';
    protected $primaryKey = 'dokumenupload_id';
    protected $appends    = ['encrypted_dokumenupload_id', 'is_verified'];

    public function getRouteKeyName()
    {
        return 'dokumenupload_id';
    }

    public function getEncryptedDokumenuploadIdAttribute()
    {
        return encryptId($this->dokumenupload_id);
    }

    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen_id',
        'path_file',
        'status_verifikasi',
        'catatan_verifikasi',
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
