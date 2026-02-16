<?php
namespace App\Models\Pmb;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jalur extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_jalur';

    protected $fillable = [
        'nama_jalur',
        'biaya_pendaftaran',
        'is_aktif',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function syaratDokumen()
    {
        return $this->hasMany(SyaratDokumenJalur::class, 'jalur_id');
    }
}
