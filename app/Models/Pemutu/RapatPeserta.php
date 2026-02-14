<?php
namespace App\Models\Pemutu;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatPeserta extends Model
{
    use HasFactory, Blameable, HashidBinding, UuidTrait;

    protected $table      = 'pemutu_rapat_peserta';
    protected $primaryKey = 'rapatpeserta_id';

    protected $fillable = [
        'rapat_id',
        'user_id',
        'jabatan',
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
