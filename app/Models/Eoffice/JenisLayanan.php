<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayanan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_jenis_layanan';
    protected $primaryKey = 'jenislayanan_id';
    
    protected $appends = ['encrypted_jenislayanan_id'];
    protected $fillable   = [
        'nama_layanan',
        'kategori',
        'bidang_terkait',
        'batas_pengerjaan',
        'is_diskusi',
        'is_fitur_keterlibatan',
        'jenis_khusus',
        'only_show_on',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'jenislayanan_id';
    }

    public function getEncryptedJenislayananIdAttribute()
    {
        return encryptId($this->jenislayanan_id);
    }

    protected $casts = [
        'only_show_on'          => 'json',
        'is_diskusi'            => 'boolean',
        'is_fitur_keterlibatan' => 'boolean',
        'is_active'             => 'boolean',
    ];

    public function pics()
    {
        return $this->hasMany(JenisLayananPic::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    public function isians()
    {
        return $this->hasMany(JenisLayananIsian::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    public function disposisis()
    {
        return $this->hasMany(JenisLayananDisposisi::class, 'jenislayanan_id', 'jenislayanan_id')->orderBy('seq');
    }

    public function periodes()
    {
        return $this->hasMany(JenisLayananPeriode::class, 'jenislayanan_id', 'jenislayanan_id');
    }
}
