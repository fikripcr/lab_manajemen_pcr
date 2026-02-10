<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presensi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_presensi';
    protected $primaryKey = 'presensi_id';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'presensi_id';
    }

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'check_in_time',
        'check_out_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_address',
        'check_in_photo',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_address',
        'check_out_photo',
        'check_in_distance',
        'check_out_distance',
        'check_in_face_verified',
        'check_out_face_verified',
        'status',
        'duration_minutes',
        'overtime_minutes',
        'late_minutes',
        'shift_id',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'check_in_distance' => 'decimal:2',
        'check_out_distance' => 'decimal:2',
        'check_in_face_verified' => 'boolean',
        'check_out_face_verified' => 'boolean',
        'duration_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'late_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function shift()
    {
        return $this->belongsTo(JenisShift::class, 'shift_id', 'jenis_shift_id');
    }

    // Accessors
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->presensi_id);
    }

    public function getFormattedCheckInTimeAttribute()
    {
        return $this->check_in_time ? $this->check_in_time->format('H:i:s') : '-';
    }

    public function getFormattedCheckOutTimeAttribute()
    {
        return $this->check_out_time ? $this->check_out_time->format('H:i:s') : '-';
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '-';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours} jam {$minutes} menit";
        }

        return "{$minutes} menit";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'on_time' => '<span class="badge bg-success">Tepat Waktu</span>',
            'late' => '<span class="badge bg-warning">Terlambat</span>',
            'absent' => '<span class="badge bg-danger">Tidak Hadir</span>',
            'early_checkout' => '<span class="badge bg-info">Pulang Awal</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">-</span>';
    }

    // Scopes
    public function scopeByPegawai($query, $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeWithCheckIn($query)
    {
        return $query->whereNotNull('check_in_time');
    }

    public function scopeWithCheckOut($query)
    {
        return $query->whereNotNull('check_out_time');
    }

    public function scopeComplete($query)
    {
        return $query->whereNotNull('check_in_time')
                    ->whereNotNull('check_out_time');
    }

    // Methods
    public function isCheckedIn()
    {
        return !is_null($this->check_in_time);
    }

    public function isCheckedOut()
    {
        return !is_null($this->check_out_time);
    }

    public function isComplete()
    {
        return $this->isCheckedIn() && $this->isCheckedOut();
    }

    public function calculateDuration()
    {
        if ($this->check_in_time && $this->check_out_time) {
            $duration = $this->check_in_time->diffInMinutes($this->check_out_time);
            $this->duration_minutes = $duration;
            $this->save();
        }
    }

    public function getStatusText()
    {
        $statuses = [
            'on_time' => 'Tepat Waktu',
            'late' => 'Terlambat',
            'absent' => 'Tidak Hadir',
            'early_checkout' => 'Pulang Awal',
        ];

        return $statuses[$this->status] ?? 'Tidak Diketahui';
    }
}
