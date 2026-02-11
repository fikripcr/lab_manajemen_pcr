<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_layanan';
    protected $primaryKey = 'layanan_id';
    
    protected $appends = ['encrypted_layanan_id'];
    protected $fillable   = [
        'no_layanan',
        'jenislayanan_id',
        'pengusul_nama',
        'pengusul_nim',
        'pengusul_prodi',
        'pengusul_email',
        'pengusul_inisial',
        'pengusul_jabstruktural',
        'pic_awal',
        'pic_pengganti',
        'keterangan',
        'disposisi_info',
        'disposisi_list',
        'latest_layananstatus_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'disposisi_info' => 'json',
        'disposisi_list' => 'json',
    ];

    public function getRouteKeyName()
    {
        return 'layanan_id';
    }

    public function getEncryptedLayananIdAttribute()
    {
        return encryptId($this->layanan_id);
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    public function statuses()
    {
        return $this->hasMany(LayananStatus::class, 'layanan_id', 'layanan_id');
    }

    public function latestStatus()
    {
        return $this->belongsTo(LayananStatus::class, 'latest_layananstatus_id', 'layananstatus_id');
    }

    public function isians()
    {
        return $this->hasMany(LayananIsian::class, 'layanan_id', 'layanan_id');
    }

    public function diskusi()
    {
        return $this->hasMany(LayananDiskusi::class, 'layanan_id', 'layanan_id');
    }

    public function keterlibatan()
    {
        return $this->hasMany(LayananKeterlibatan::class, 'layanan_id', 'layanan_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'layanan_id', 'layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function periode()
    {
        return $this->hasOne(LayananPeriode::class, 'layanan_id', 'layanan_id');
    }
}
