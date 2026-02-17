<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratDokumenJalur extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_syarat_dokumen_jalur';

    protected $fillable = [
        'jalur_id',
        'jenis_dokumen_id',
        'is_wajib',
        'keterangan_khusus',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class, 'jalur_id');
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }
}
