<?php
namespace App\Models\Event;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatAgenda extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table      = 'event_rapat_agenda';
    protected $primaryKey = 'rapatagenda_id';

    protected $appends = ['encrypted_rapatagenda_id'];

    public function getRouteKeyName()
    {
        return 'rapatagenda_id';
    }

    public function getEncryptedRapatagendaIdAttribute()
    {
        return encryptId($this->rapatagenda_id);
    }

    protected $fillable = [
        'rapat_id',
        'judul_agenda',
        'isi',
        'seq',
    ];

    /**
     * Get the rapat for the agenda.
     */
    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }
}
