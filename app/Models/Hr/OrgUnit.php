<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgUnit extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_org_unit';
    protected $primaryKey = 'org_unit_id';
    
    protected $appends = ['encrypted_org_unit_id'];

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'type', // departemen, prodi, unit, jabatan_struktural, posisi
        'level',
        'sort_order',
        'is_active',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level'     => 'integer',
    ];

    public function getEncryptedOrgUnitIdAttribute()
    {
        return encryptId($this->org_unit_id);
    }

    public function parent()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id');
    }

    // Helper to get only active units
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Boot for auth tracking
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1; // Fallback to 1 if system seed
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id() ?? 1;
            $model->save();
        });
    }
}
