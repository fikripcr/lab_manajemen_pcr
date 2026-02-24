<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indisipliner extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_indisipliner';
    protected $primaryKey = 'indisipliner_id';

    protected $appends = ['encrypted_indisipliner_id'];

    protected $fillable = [
        'jenisindisipliner_id',
        'keterangan',
        'tgl_indisipliner',
        'file_pendukung',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'indisipliner_id'      => 'integer',
        'jenisindisipliner_id' => 'integer',
        'tgl_indisipliner'     => 'date',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'indisipliner_id';
    }

    public function getEncryptedIndisiplinerIdAttribute()
    {
        return encryptId($this->indisipliner_id);
    }

    /**
     * Get the jenis indisipliner (type).
     */
    public function jenisIndisipliner()
    {
        return $this->belongsTo(JenisIndisipliner::class, 'jenisindisipliner_id', 'jenisindisipliner_id');
    }

    /**
     * Get indisipliner pegawai pivot records.
     */
    public function indisiplinerPegawai()
    {
        return $this->hasMany(IndisiplinerPegawai::class, 'indisipliner_id', 'indisipliner_id');
    }

    /**
     * Get the pegawai associated with this indisipliner (many-to-many through pivot).
     */
    public function pegawai()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'hr_indisipliner_pegawai',
            'indisipliner_id',
            'pegawai_id',
            'indisipliner_id',
            'pegawai_id'
        )->withTimestamps();
    }

    /**
     * Scope for filtering by year.
     */
    public function scopeFilterByYear($query, $year)
    {
        if ($year && $year !== 'all') {
            return $query->whereYear('tgl_indisipliner', $year);
        }
        return $query;
    }
}
