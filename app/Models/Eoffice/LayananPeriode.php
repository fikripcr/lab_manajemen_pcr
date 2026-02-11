<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananPeriode extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_layanan_periode';
    protected $primaryKey = 'layananperiode_id';

    public function getRouteKeyName()
    {
        return 'layananperiode_id';
    }

    protected $fillable = [
        'layanan_id',
        'jlperiode_id',
        'tgl_mulai',
        'tgl_selesai',
        'tahun_ajaran',
        'semester',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function jenisLayananPeriode()
    {
        return $this->belongsTo(JenisLayananPeriode::class, 'jlperiode_id', 'jlperiode_id');
    }
}
