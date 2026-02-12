<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HashidBinding;

class RiwayatStatAktifitas extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;
    
    protected $table      = 'hr_riwayat_stataktifitas';
    protected $primaryKey = 'riwayatstataktifitas_id';
    protected $guarded    = ['riwayatstataktifitas_id'];

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'statusaktifitas_id',
        'tmt',
        'tgl_akhir',
        'no_sk',
        'keterangan',
        'created_by',
        'updated_by',        'deleted_by',
    
    ];

    protected $casts = [
        'tmt'       => 'date',
        'tgl_akhir' => 'date',
    ];

    public function statusAktifitas()
    {
        return $this->belongsTo(StatusAktifitas::class, 'statusaktifitas_id', 'statusaktifitas_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function before()
    {
        return $this->belongsTo(RiwayatStatAktifitas::class, 'before_id', 'riwayatstataktifitas_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatStatAktifitas::class, 'before_id', 'riwayatstataktifitas_id');
    }
}
