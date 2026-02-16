<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatStatPegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_statpegawai';
    protected $primaryKey = 'riwayatstatpegawai_id';
    protected $guarded    = ['riwayatstatpegawai_id'];

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'statuspegawai_id',
        'tmt',
        'tgl_akhir',
        'no_sk',
        'keterangan',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'tmt'       => 'date',
        'tgl_akhir' => 'date',
    ];

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'statuspegawai_id', 'statuspegawai_id');
    }

    public function approval()
    {
        return $this->morphOne(RiwayatApproval::class, 'subject', 'model', 'model_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function before()
    {
        return $this->belongsTo(RiwayatStatPegawai::class, 'before_id', 'riwayatstatpegawai_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatStatPegawai::class, 'before_id', 'riwayatstatpegawai_id');
    }
}
