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

    protected $table      = 'pmb_profil_mahasiswa';
    protected $primaryKey = 'profilmahasiswa_id';
    protected $appends    = ['encrypted_profilmahasiswa_id'];

    public function getRouteKeyName()
    {
        return 'profilmahasiswa_id';
    }

    public function getEncryptedProfilmahasiswaIdAttribute()
    {
        return encryptId($this->profilmahasiswa_id);
    }

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
