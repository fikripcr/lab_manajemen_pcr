<?php
namespace App\Models\Hr;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPenugasan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_penugasan';
    protected $primaryKey = 'riwayatpenugasan_id';
    protected $guarded    = ['riwayatpenugasan_id'];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
        'tgl_sk'      => 'date',
        'approved_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'org_unit_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper to check if currently active
    public function getIsActiveAttribute()
    {
        return is_null($this->tgl_selesai) || $this->tgl_selesai->isFuture();
    }
}
