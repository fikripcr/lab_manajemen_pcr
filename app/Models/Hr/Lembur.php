<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lembur extends Model
{
    use SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_lembur';
    protected $primaryKey = 'lembur_id';

    protected $fillable = [
        'pengusul_id',
        'judul',
        'uraian_pekerjaan',
        'alasan',
        'tgl_pelaksanaan',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'latest_riwayatapproval_id',
    ];

    protected $casts = [
        'tgl_pelaksanaan' => 'date',
        'jam_mulai'       => 'datetime:H:i',
        'jam_selesai'     => 'datetime:H:i',
        'durasi_menit'    => 'integer',
    ];

    protected $appends = ['encrypted_lembur_id'];

    public function getRouteKeyName()
    {
        return 'lembur_id';
    }

    public function getEncryptedLemburIdAttribute()
    {
        return encryptId($this->lembur_id);
    }

    /**
     * Boot method untuk auto-calculate durasi
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($lembur) {
            if ($lembur->jam_mulai && $lembur->jam_selesai) {
                $mulai                = Carbon::parse($lembur->jam_mulai);
                $selesai              = Carbon::parse($lembur->jam_selesai);
                $lembur->durasi_menit = $selesai->diffInMinutes($mulai);
            }
        });
    }

    /**
     * Relasi ke Pegawai (Pengusul)
     */
    public function pengusul(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pengusul_id', 'pegawai_id');
    }

    /**
     * Relasi ke Pegawai yang ikut lembur (many-to-many through pivot)
     */
    public function lemburPegawais(): HasMany
    {
        return $this->hasMany(LemburPegawai::class, 'lembur_id', 'lembur_id');
    }

    /**
     * Relasi ke Pegawai yang ikut lembur (direct access)
     */
    public function pegawais()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'hr_lembur_pegawai',
            'lembur_id',
            'pegawai_id',
            'lembur_id',
            'pegawai_id'
        )
            ->withPivot(['catatan', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    /**
     * Relasi ke Riwayat Approval
     */
    public function latestApproval(): BelongsTo
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    /**
     * Relasi ke semua approval history (polymorphic)
     */
    public function approvals()
    {
        return $this->hasMany(RiwayatApproval::class, 'model_id', 'lembur_id')
            ->where('model', self::class)
            ->orderBy('created_at', 'desc');
    }

    public function riwayatApproval()
    {
        return $this->approvals();
    }

    /**
     * Accessor untuk status approval
     */
    public function getStatusApprovalAttribute(): string
    {
        if (! $this->latestApproval) {
            return 'pending';
        }
        return $this->latestApproval->status ?? 'pending';
    }



    /**
     * Scope untuk filter by status approval
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->whereHas('latestApproval', function ($q) use ($status) {
            $q->where('status', $status);
        });
    }

    /**
     * Scope untuk filter by pengusul
     */
    public function scopeByPengusul($query, int $pengusulId)
    {
        return $query->where('pengusul_id', $pengusulId);
    }

    /**
     * Scope untuk filter by tanggal range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tgl_pelaksanaan', [$startDate, $endDate]);
    }
}
