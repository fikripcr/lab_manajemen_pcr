<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TanggalLibur extends Model
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table      = 'hr_tanggal_libur';
    protected $primaryKey = 'tanggallibur_id';

    protected $appends = ['encrypted_tanggallibur_id'];

    public function getRouteKeyName()
    {
        return 'tanggallibur_id';
    }

    public function getEncryptedTanggalliburIdAttribute()
    {
        return encryptId($this->tanggallibur_id);
    }

    protected $fillable = [
        'tahun',
        'tgl_libur',
        'keterangan', 'created_by', 'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'tgl_libur' => 'date',
    ];
}
