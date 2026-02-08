<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttDevice extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table      = 'hr_att_device';
    protected $primaryKey = 'att_device_id';

    protected $fillable = [
        'name',
        'sn',
        'ip',
        'port',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
