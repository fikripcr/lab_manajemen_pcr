<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisIzin extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_jenis_izin';
    protected $primaryKey = 'jenisizin_id';

    protected $fillable = [
        'nama',
        'kategori',
        'max_hari',
        'pemilihan_waktu',
        'urutan_approval',
        'is_active',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all perizinan using this jenis izin.
     */
    public function perizinan()
    {
        return $this->hasMany(Perizinan::class, 'jenisizin_id', 'jenisizin_id');
    }

    /**
     * Scope for active jenis izin.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
