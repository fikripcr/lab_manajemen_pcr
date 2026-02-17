<?php
namespace App\Models\Shared;

use App\Models\Shared\StrukturOrganisasi;
use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $appends = ['encrypted_mahasiswa_id'];

    public function getRouteKeyName()
    {
        return 'mahasiswa_id';
    }

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'email',
        'orgunit_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'mahasiswa_id' => 'string',
    ];

    public function getEncryptedMahasiswaIdAttribute()
    {
        return encryptId($this->mahasiswa_id);
    }

    public function prodi()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'orgunit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
