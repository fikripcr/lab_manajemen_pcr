<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class RiwayatInpassing extends Model
{
    protected $table      = 'hr_riwayat_inpassing';
    protected $primaryKey = 'riwayatinpassing_id';
    protected $guarded    = ['riwayatinpassing_id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function golonganInpassing()
    {
        return $this->belongsTo(GolonganInpassing::class, 'gol_inpassing_id', 'gol_inpassing_id');
    }
}
