<?php
namespace App\Models\Pmb;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periode extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_periode';
    protected $primaryKey = 'periode_id';
    protected $appends    = ['encrypted_periode_id'];

    public function getRouteKeyName()
    {
        return 'periode_id';
    }

    public function getEncryptedPeriodeIdAttribute()
    {
        return encryptId($this->periode_id);
    }

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
    ];

}
