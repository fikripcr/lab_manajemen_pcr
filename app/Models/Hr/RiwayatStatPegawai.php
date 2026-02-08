<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatStatPegawai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_statpegawai';
    protected $primaryKey = 'riwayatstatpegawai_id';
    protected $guarded    = ['riwayatstatpegawai_id'];

    protected $casts = [
        'tmt'       => 'date',
        'tgl_akhir' => 'date',
    ];

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'statuspegawai_id', 'statuspegawai_id');
    }
}
