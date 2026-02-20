<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndisiplinerPegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_indisipliner_pegawai';
    protected $primaryKey = 'indispegawai_id';

    protected $appends = ['encrypted_indispegawai_id'];

    public function getRouteKeyName()
    {
        return 'indispegawai_id';
    }

    public function getEncryptedIndispegawaiIdAttribute()
    {
        return encryptId($this->indispegawai_id);
    }

    protected $fillable = [
        'indisipliner_id',
        'pegawai_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'indispegawai_id' => 'integer',
        'indisipliner_id' => 'integer',
        'pegawai_id'      => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    /**
     * Get the indisipliner record.
     */
    public function indisipliner()
    {
        return $this->belongsTo(Indisipliner::class, 'indisipliner_id', 'indisipliner_id');
    }

    /**
     * Get the pegawai record.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }
}
