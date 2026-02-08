<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatJabStruktural extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_jabstruktural';
    protected $primaryKey = 'riwayatjabstruktural_id';
    protected $guarded    = ['riwayatjabstruktural_id'];

    protected $casts = [
        'tgl_awal'       => 'date',
        'tgl_akhir'      => 'date',
        'tgl_pengesahan' => 'date',
    ];

    public function jabatanStruktural()
    {
        return $this->belongsTo(JabatanStruktural::class, 'jabstruktural_id', 'jabstruktural_id');
    }
}
