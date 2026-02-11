<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatJabFungsional extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;
    
    protected $table      = 'hr_riwayat_jabfungsional';
    protected $primaryKey = 'riwayatjabfungsional_id';
    protected $guarded    = ['riwayatjabfungsional_id'];

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'jabfungsional_id',
        'tmt',
        'no_sk',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function jabatanFungsional()
    {
        return $this->belongsTo(JabatanFungsional::class, 'jabfungsional_id', 'jabfungsional_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    public function before()
    {
        return $this->belongsTo(RiwayatJabFungsional::class, 'before_id', 'riwayatjabfungsional_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatJabFungsional::class, 'before_id', 'riwayatjabfungsional_id');
    }
}
