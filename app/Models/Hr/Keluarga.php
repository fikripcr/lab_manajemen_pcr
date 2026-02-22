<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarga extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_keluarga';
    protected $primaryKey = 'keluarga_id';
    protected $guarded    = ['keluarga_id'];

    protected $appends = ['encrypted_keluarga_id'];

    public function getRouteKeyName()
    {
        return 'keluarga_id';
    }

    public function getEncryptedKeluargaIdAttribute()
    {
        return encryptId($this->keluarga_id);
    }

    protected $fillable = [
        'pegawai_id',
        'hubungan',
        'nama',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'telp',
        'latest_riwayatapproval_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function approval()
    {
        return $this->morphOne(RiwayatApproval::class, 'subject', 'model', 'model_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }
}
