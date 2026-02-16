<?php
namespace App\Models\Pmb;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilMahasiswa extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_profil_mahasiswa';

    protected $fillable = [
        'user_id',
        'nik',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_lengkap',
        'asal_sekolah',
        'nisn',
        'nama_ibu_kandung',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
