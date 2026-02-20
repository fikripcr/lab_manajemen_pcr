<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TanggalTidakMasuk extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_tanggal_tidak_masuk';
    protected $primaryKey = 'tidakmasuk_id';

    protected $appends = ['encrypted_tidakmasuk_id'];

    public function getRouteKeyName()
    {
        return 'tidakmasuk_id';
    }

    public function getEncryptedTidakmasukIdAttribute()
    {
        return encryptId($this->tidakmasuk_id);
    }
    protected $fillable = [
        'tanggal',
        'tahun',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
