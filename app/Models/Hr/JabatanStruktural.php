<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JabatanStruktural extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'hr_jabatan_struktural';
    protected $primaryKey = 'jabstruktural_id';

    protected $fillable = [
        'nama',
        'parent_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(JabatanStruktural::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(JabatanStruktural::class, 'parent_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
