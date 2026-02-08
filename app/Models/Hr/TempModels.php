<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarga extends Model
{
    use HasFactory, SoftDeletes;
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

class RiwayatStatPegawai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_statpegawai';
    protected $primaryKey = 'riwayatstatpegawai_id';
    protected $guarded    = ['riwayatstatpegawai_id'];

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'statuspegawai_id', 'statuspegawai_id');
    }

    // No latest_riwayatapproval_id column, so we rely on reverse lookup or service logic.
}

class RiwayatStatAktifitas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_stataktifitas';
    protected $primaryKey = 'riwayatstataktifitas_id';
    protected $guarded    = ['riwayatstataktifitas_id'];

    public function statusAktifitas()
    {
        return $this->belongsTo(StatusAktifitas::class, 'statusaktifitas_id', 'statusaktifitas_id');
    }
}

class RiwayatJabFungsional extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_jabfungsional';
    protected $primaryKey = 'riwayatjabfungsional_id';
    protected $guarded    = ['riwayatjabfungsional_id'];

    public function jabatanFungsional()
    {
        return $this->belongsTo(JabatanFungsional::class, 'jabfungsional_id', 'jabfungsional_id');
    }

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}

class RiwayatJabStruktural extends Model
{
    use HasFactory, SoftDeletes;
    protected $table      = 'hr_riwayat_jabstruktural';
    protected $primaryKey = 'riwayatjabstruktural_id';
    protected $guarded    = ['riwayatjabstruktural_id'];

    public function jabatanStruktural()
    {
        return $this->belongsTo(JabatanStruktural::class, 'jabstruktural_id', 'jabstruktural_id');
    }
}
