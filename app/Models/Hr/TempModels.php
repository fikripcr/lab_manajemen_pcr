<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Keluarga extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;
    protected $table      = 'hr_keluarga';
    protected $primaryKey = 'keluarga_id';
    protected $guarded    = ['keluarga_id'];

    public function approval()
    {
        return $this->morphOne(RiwayatApproval::class, 'model', 'model', 'model_id', 'keluarga_id');
        // Note: RiwayatApproval stores full class name in 'model', so we can use morphOne if we set it up right,
        // or just manual relation. For simplicity, let's use manual lookup often if morph is tricky with non-standard keys.
        // Actually, let's try standard manual relation for safety first.
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}

class RiwayatPendidikan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_pendidikan';
    protected $primaryKey = 'riwayatpendidikan_id';
    protected $guarded    = ['riwayatpendidikan_id'];

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}

class PengembanganDiri extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_pengembangan_diri';
    protected $primaryKey = 'pengembangandiri_id';
    protected $guarded    = ['pengembangandiri_id'];

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}
