<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPerusahaan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_kategori_perusahaan';
    protected $primaryKey = 'kategoriperusahaan_id';

    protected $appends = ['encrypted_kategoriperusahaan_id'];

    public function getEncryptedKategoriperusahaanIdAttribute()
    {
        return encryptId($this->kategoriperusahaan_id);
    }

    public function getRouteKeyName()
    {
        return 'kategoriperusahaan_id';
    }

    protected $fillable = [
        'nama_kategori',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function perusahaan()
    {
        return $this->hasMany(Perusahaan::class, 'kategoriperusahaan_id', 'kategoriperusahaan_id');
    }
}
