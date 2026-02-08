<?php
namespace App\Models\Hr;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPendidikan extends Model
{
    use HasFactory, SoftDeletes, HashidBinding;
    protected $table      = 'hr_riwayat_pendidikan';
    protected $primaryKey = 'riwayatpendidikan_id';
    protected $guarded    = ['riwayatpendidikan_id'];

    protected $casts = [
        'tgl_ijazah' => 'date',
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
