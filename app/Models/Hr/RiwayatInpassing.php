<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HashidBinding;

class RiwayatInpassing extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;
    
    protected $table      = 'hr_riwayat_inpassing';
    protected $primaryKey = 'riwayatinpassing_id';
    protected $guarded    = ['riwayatinpassing_id'];

    protected $casts = [
        'tgl_sk' => 'date',
        'tmt'    => 'date',
    ];

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'gol_inpassing_id',
        'no_sk',
        'tgl_sk',
        'tmt',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'gaji_pokok',
        'file_sk',
        'created_by',
        'updated_by',        'deleted_by',
    
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function golonganInpassing()
    {
        return $this->belongsTo(GolonganInpassing::class, 'gol_inpassing_id', 'gol_inpassing_id');
    }

    public function before()
    {
        return $this->belongsTo(RiwayatInpassing::class, 'before_id', 'riwayatinpassing_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatInpassing::class, 'before_id', 'riwayatinpassing_id');
    }
}
