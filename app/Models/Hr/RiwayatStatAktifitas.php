<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatStatAktifitas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_stataktifitas';
    protected $primaryKey = 'riwayatstataktifitas_id';
    protected $guarded    = ['riwayatstataktifitas_id'];

    protected $casts = [
        'tmt'       => 'date',
        'tgl_akhir' => 'date',
    ];

    public function statusAktifitas()
    {
        return $this->belongsTo(StatusAktifitas::class, 'statusaktifitas_id', 'statusaktifitas_id');
    }
}
