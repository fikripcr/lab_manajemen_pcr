<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_perusahaan';
    protected $primaryKey = 'perusahaan_id';

    public function getRouteKeyName()
    {
        return 'perusahaan_id';
    }

    protected $fillable = [
        'kategoriperusahaan_id',
        'nama_perusahaan',
        'alamat',
        'kota',
        'telp',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriPerusahaan::class, 'kategoriperusahaan_id', 'kategoriperusahaan_id');
    }

    public function syarat()
    {
        return $this->hasMany(KpSyarat::class, 'perusahaan_id', 'perusahaan_id');
    }
}
