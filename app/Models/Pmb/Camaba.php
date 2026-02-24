<?php
namespace App\Models\Pmb;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camaba extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_camaba';
    protected $primaryKey = 'camaba_id';
    protected $appends    = ['encrypted_camaba_id'];

    public function getRouteKeyName()
    {
        return 'camaba_id';
    }

    public function getEncryptedCamabaIdAttribute()
    {
        return encryptId($this->camaba_id);
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

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pendaftaran(): HasOne
    {
        return $this->hasOne(Pendaftaran::class, 'user_id', 'user_id');
    }
}
