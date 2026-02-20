<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayananIsian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_jenis_layanan_isian';
    protected $primaryKey = 'jlisian_id';

    protected $appends = ['encrypted_jlisian_id'];

    public function getEncryptedJlisianIdAttribute()
    {
        return encryptId($this->jlisian_id);
    }
    protected $fillable = [
        'jenislayanan_id',
        'kategoriisian_id',
        'seq',
        'is_required',
        'is_show_on_validasi',
        'fill_by',
        'rule',
        'info_tambahan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'jlisian_id';
    }

    protected $casts = [
        'is_required'         => 'boolean',
        'is_show_on_validasi' => 'boolean',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    public function kategoriIsian()
    {
        return $this->belongsTo(KategoriIsian::class, 'kategoriisian_id', 'kategoriisian_id');
    }
}
