<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttDevice extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_att_device';
    protected $primaryKey = 'att_device_id';

    protected $appends = ['encrypted_att_device_id'];

    public function getRouteKeyName()
    {
        return 'att_device_id';
    }

    protected $fillable = [
        'name',
        'sn',
        'ip',
        'port',
        'is_active',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getEncryptedAttDeviceIdAttribute()
    {
        return encryptId($this->att_device_id);
    }
}
