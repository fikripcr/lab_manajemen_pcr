<?php
namespace App\Models\Hr;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengembanganDiri extends Model
{
    use HasFactory, SoftDeletes, HashidBinding;
    protected $table      = 'hr_pengembangan_diri';
    protected $primaryKey = 'pengembangandiri_id';
    protected $guarded    = ['pengembangandiri_id'];

    protected $casts = [
        'tgl_mulai'      => 'date',
        'tgl_selesai'    => 'date',
        'berlaku_hingga' => 'date',
    ];

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }
}
