<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPendidikan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_pendidikan';
    protected $primaryKey = 'riwayatpendidikan_id';
    protected $guarded    = ['riwayatpendidikan_id'];

    protected $appends = ['encrypted_riwayatpendidikan_id'];

    public function getRouteKeyName()
    {
        return 'riwayatpendidikan_id';
    }

    public function getEncryptedRiwayatpendidikanIdAttribute()
    {
        return encryptId($this->riwayatpendidikan_id);
    }

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'tingkat_pendidikan',
        'nama_sekolah',
        'jurusan',
        'tahun_lulus',
        'no_ijazah',
        'tgl_ijazah',
        'ipk',
        'keterangan',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'tgl_ijazah' => 'date',
    ];

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
        return $this->belongsTo(RiwayatPendidikan::class, 'before_id', 'riwayatpendidikan_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatPendidikan::class, 'before_id', 'riwayatpendidikan_id');
    }
}
