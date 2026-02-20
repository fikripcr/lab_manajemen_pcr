<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriIsian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_kategori_isian';
    protected $primaryKey = 'kategoriisian_id';

    protected $appends = ['encrypted_kategoriisian_id'];

    public function getEncryptedKategoriisianIdAttribute()
    {
        return encryptId($this->kategoriisian_id);
    }
    protected $fillable = [
        'nama_isian',
        'type',
        'type_value',
        'keterangan_isian',
        'alias_on_document',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'kategoriisian_id';
    }

    protected $casts = [
        'type_value' => 'json',
    ];
}
