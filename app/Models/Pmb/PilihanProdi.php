<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Pendaftaran;
use App\Models\Shared\StrukturOrganisasi;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PilihanProdi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_pilihan_prodi';
    protected $primaryKey = 'pilihanprodi_id';
    protected $appends    = ['encrypted_pilihanprodi_id'];

    public function getRouteKeyName()
    {
        return 'pilihanprodi_id';
    }

    public function getEncryptedPilihanprodiIdAttribute()
    {
        return encryptId($this->pilihanprodi_id);
    }

    protected $fillable = [
        'pendaftaran_id',
        'orgunit_id',
        'urutan',
        'rekomendasi_sistem',
        'keputusan_admin',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'orgunit_id');
    }
}
