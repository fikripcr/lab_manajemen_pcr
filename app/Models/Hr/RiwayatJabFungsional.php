<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatJabFungsional extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_jabfungsional';
    protected $primaryKey = 'riwayatjabfungsional_id';
    protected $guarded    = ['riwayatjabfungsional_id'];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function jabatanFungsional()
    {
        return $this->belongsTo(JabatanFungsional::class, 'jabfungsional_id', 'jabfungsional_id');
    }

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}
