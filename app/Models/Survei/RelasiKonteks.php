<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelasiKonteks extends Model
{
    use HasFactory;

    protected $table = 'survei_relasi_konteks';

    protected $fillable = [
        'survei_id',
        'pertanyaan_id', // Nullable (if context is global for survey)
        'model_type',
        'model_id',
        'keterangan',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }

    /**
     * Get the owning model (SpmiIndikator, MataKuliah, etc).
     */
    public function model()
    {
        return $this->morphTo();
    }
}
