<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayananPeriode extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_jenis_layanan_periode';
    protected $primaryKey = 'jlperiode_id';

    protected $appends = ['encrypted_jlperiode_id'];

    public function getEncryptedJlperiodeIdAttribute()
    {
        return encryptId($this->jlperiode_id);
    }

    public function getRouteKeyName()
    {
        return 'jlperiode_id';
    }

    protected $fillable = [
        'jenislayanan_id',
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

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    /**
     * Check if the given period overlaps with existing periods for the same service.
     */
    public static function hasOverlap($jenislayananId, $tglMulai, $tglSelesai, $excludeId = null)
    {
        $query = static::where('jenislayanan_id', $jenislayananId)
            ->where('tgl_selesai', '>=', $tglMulai)
            ->where('tgl_mulai', '<=', $tglSelesai);

        if ($excludeId) {
            $query->where('jlperiode_id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get active period for a given service type.
     */
    public static function getAktif($jenislayananId)
    {
        return static::where('jenislayanan_id', $jenislayananId)
            ->where('tgl_mulai', '<=', now()->toDateString())
            ->where('tgl_selesai', '>=', now()->toDateString())
            ->get();
    }
}
