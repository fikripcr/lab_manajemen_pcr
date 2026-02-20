<?php
namespace App\Models\Event;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatPeserta extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table      = 'event_rapat_peserta';
    protected $primaryKey = 'rapatpeserta_id';

    protected $appends = ['encrypted_rapatpeserta_id'];

    public function getRouteKeyName()
    {
        return 'rapatpeserta_id';
    }

    public function getEncryptedRapatpesertaIdAttribute()
    {
        return encryptId($this->rapatpeserta_id);
    }

    protected $fillable = [
        'rapat_id',
        'user_id',
        'jabatan',
        'status',
        'waktu_hadir',
        'notes',
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
    ];

    /**
     * Get the rapat for the peserta.
     */
    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }

    /**
     * Get the user for the peserta.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
