<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_status_pegawai';
    protected $primaryKey = 'statuspegawai_id';

    protected $appends = ['encrypted_statuspegawai_id'];

    public function getRouteKeyName()
    {
        return 'statuspegawai_id';
    }

    public function getEncryptedStatuspegawaiIdAttribute()
    {
        return encryptId($this->statuspegawai_id);
    }

    protected $fillable = [
        'kode_status',
        'nama_status',
        'organisasi',
        'is_active',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
