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

    protected $table      = 'pmb_jalur';
    protected $primaryKey = 'jalur_id';
    protected $appends    = ['encrypted_jalur_id'];

    public function getRouteKeyName()
    {
        return 'jalur_id';
    }

    public function getEncryptedJalurIdAttribute()
    {
        return encryptId($this->jalur_id);
    }

    protected $fillable = [
        'nama_jalur',
        'biaya_pendaftaran',
        'is_aktif',
    ];

    public function syaratDokumen()
    {
        return $this->hasMany(SyaratDokumenJalur::class, 'jalur_id');
    }
}
