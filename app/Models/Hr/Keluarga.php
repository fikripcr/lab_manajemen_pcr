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

    protected $fillable = [
        'pegawai_id',
        'hubungan',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'pendidikan',
        'pekerjaan',
        'keterangan',
        'created_by',
        'updated_by',        'deleted_by',
    
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
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
