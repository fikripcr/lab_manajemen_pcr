<?php
namespace App\Models\Pmb;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisDokumen extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_jenis_dokumen';
    protected $primaryKey = 'jenis_dokumen_id';
    protected $appends    = ['encrypted_jenis_dokumen_id'];

    public function getRouteKeyName()
    {
        return 'jenis_dokumen_id';
    }

    public function getEncryptedJenisDokumenIdAttribute()
    {
        return encryptId($this->jenis_dokumen_id);
    }

    protected $fillable = [
        'nama_dokumen',
        'tipe_file',
        'max_size_kb',
    ];

}
